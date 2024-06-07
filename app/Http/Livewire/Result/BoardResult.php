<?php

namespace App\Http\Livewire\Result;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\Batch;
use App\Models\User;
use App\Models\Result;
use App\Models\ReBoardResult;

use ZanySoft\Zip\Zip;



class BoardResult extends Component
{    
    use WithFileUploads;

    public $batch = "";
    public $semester = "";
    public $zipFile = "";
    public $csvFile = "";

    public function UpdatedZipFile()
    {
        $this->validate([
            'zipFile' => 'mimes:zip',
        ]);
    }
    
    public function UpdatedCsvFile()
    {
        $this->validate([
            'csvFile' => 'mimes:csv',
        ]);
    }

    public function mount()
    {
        $this->batch = Batch::orderBy('created_at', 'desc')->first()->id;
        $this->semester = 1;
    }

    public function store()
    {   
        $file = file($this->csvFile->getRealPath());
        $firstLine = array_map('str_getcsv', $file);
        $firstLineArray = array();
        $queryArray = array();
        
        foreach($firstLine[0] as $line){
            array_push($firstLineArray, $line);
        }

        $email = (array_search('email', $firstLineArray));
        $status = (array_search('status', $firstLineArray));
        $percentage = (array_search('percentage', $firstLineArray));
        $filename = (array_search('filename', $firstLineArray));
        $backPaperStatus = (array_search('backpaperStatus', $firstLineArray));
        
        // extract zip
        if(!$backPaperStatus)
            $folder = 'boardResult/'.date('ymdhis').$this->batch.$this->semester;
        else 
            $folder = 'reBoardResult/'.date('ymdhis').$this->batch.$this->semester;

        $fileCollection = $this->zipFile->store($folder);
        $isFileExtracted = $this->extractZip($fileCollection, $folder);

        if($isFileExtracted === False)
            return false;

        //uncomment to upload attachment also
        //remove first line
        $data = array_slice($file, 1);
        $parts = (array_chunk($data, 1000));
        $i = 1;
        $failedJobId = array();

        foreach($parts as $line) {
            $parseddata = array_map('str_getcsv', $line);

            foreach($parseddata as $row) {
                $userEmail =  $row[$email];
                $userStatus = $row[$status];
                $userPercentage = $row[$percentage];
                $userMarksheet = $row[$filename];
    
                $userExists = User::where('email', $userEmail)->get()->count();

                if($userExists)
                {
                    $userId = User::select('id')->where('email', $userEmail)->first()->id;
                    
                    if($backPaperStatus)
                        $result = Result::select('id')->where('user_id',$userId)->where('semester',$this->semester)->first();

                    if($userMarksheet != "")
                    {
                        $fileUrl = $folder.'/'.$userMarksheet;
                    }else{
                        $fileUrl = NULL;
                    }

                    if(\Storage::exists($fileUrl) || $fileUrl != NULL)
                        {                        
                            if(!$backPaperStatus){
                                Result::updateOrInsert(
                                    [
                                        'user_id' => $userId,
                                        'semester' => $this->semester
                                    ],
                                    [
                                        'user_id' => $userId,
                                        'semester' => $this->semester,
                                        'board_status' => strtolower($userStatus),
                                        'board_percentage' => $userPercentage,
                                        'board_attachment' => $fileUrl
                                    ]
                                );    
                            }
                            else{
                                ReBoardResult::create(
                                    [
                                        'result_id' => $result->id,
                                        'board_attachment' => $fileUrl
                                    ]
                                ); 
                                $result->update([
                                    'board_percentage' => $userPercentage
                                ]); 
                            }
                        }else{
                            echo("failed File upload");
                            array_push($failedJobId, $i);
                        }
                }else{
                    echo("No user found");
                    array_push($failedJobId, $i);
                }
                $i++;
            }
        }

        if(count($failedJobId) > 0){
            $msg = "Import Completed with some failure. Failed ID :: ";
            foreach($failedJobId as $fid)
                $msg .= $fid.', ';

            $msg = rtrim($msg, ", ");
        }else{
            $msg = "Import Completed Successfully!!!";
        }

        session()->flash('success', $msg);
    }

    public function extractZip($zipFileLocation, $extractTo)
    {
        try{
            $zip = Zip::open(\storage_path('app/'.$zipFileLocation));
            $zip->extract(\storage_path('app/'.$extractTo));
            $zip->close();
            unlink(\storage_path('app/'.$zipFileLocation));
            return true;
        }catch(Exception $e)
        {
            return false;
        }
    }

    public function render()
    {
        $batchList = Batch::select('id', 'year', 'stream')->get();
        return view('livewire.result.board-result', ['batchList' => $batchList])->layout('layouts.admin');
    }
}
