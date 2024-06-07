<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
Use Exception;
use Illuminate\Support\Arr;
use App\Models\Batch;
use App\Models\Student;
use App\Models\User;
use App\Models\AttachmentCategory;
use App\Models\Attachment;
use App\Models\Category;
use App\Models\Record;
use App\Models\RecordAttachment;

use ZanySoft\Zip\Zip;

// use \ZipArchive;


class CsvUpload extends Controller
{
    public function studentCsvUpload(Request $request)
    {
    
        $request->validate([
            'studentCsv' => 'required|mimes:csv,txt'
        ]);

        //get file from upload
        $path = $request->file('studentCsv')->getRealPath();
        

        //turn into array
        $file = file($path);

        //to store the header name in array
        $firstLine = array_map('str_getcsv', $file);
        
        $firstLineArray = array();
        $queryArray = array();
        
        foreach($firstLine[0] as $line){
            array_push($firstLineArray, $line);
        }
        // dd($firstLineArray);
        $name = (array_search('name', $firstLineArray));
        
        if($name===false){
            
            session()->flash('danger', 'Please enter the data headers correctly.');
            return redirect('student/list');
        }
        $stream = (array_search('stream', $firstLineArray));
        $year = (array_search('year', $firstLineArray));
        $email = (array_search('email', $firstLineArray));
        $roll = (array_search('roll', $firstLineArray));
        $image = (array_search('image', $firstLineArray));
        
        // dd($year==0);
        if( $stream ==0 || $year ==0 || $email ==0 || $roll ==0 || $image==0 ){
            session()->flash('danger', 'Please enter the data headers correctly.');
            return redirect('student/list');
        }
        
        
        //remove first line
        $data = array_slice($file, 1);

        //loop through file and split every 1000 lines
        $parts = (array_chunk($data, 1000));
        $i = 1;
        foreach($parts as $line) {   

            $parseddata = array_map('str_getcsv', $line);

            foreach($parseddata as $row) {

                //check if the batch was given correctly

                $batchCheck = Batch::select('id')->where([
                    ['stream', $row[$stream]],
                    ['year',$row[$year]]
                    ])->first();
                    // dd($batchCheck->id);

                if($batchCheck){
                    try{
                        //insert the record or update if it already exists
                        $user = User::Create([
                            'name' => $row[$name],
                            'email' => $row[$email],
                            'password' => Hash::make("deerwalk", [12]),
                            'role' => 'student'
                        ]);

                        if($user){
                            
                            Student::Create([ 
                                'user_id'=> $user->id,
                                'roll' => $row[$roll],
                                'image' => $row[$image],
                                'batch_id' => $batchCheck->id,
            
                            ]);
                        }

                        session()->flash('success', 'Import completed successfully!');
                    }
                    catch(Exception $ex){
                        session()->flash('danger', $ex->getMessage());
                    }   
                }
                
            }
            $i++;
        }

        return redirect('student/list');  

    }

    public function batchCsvUpload(Request $request)
    {
        $request->validate([
            'batchCsv' => 'required|mimes:csv,txt'
        ]);

        //get file from upload
        $path = $request->file('batchCsv')->getRealPath();
        
        //turn into array
        $file = file($path);

        //to store the header name in array
        $firstLine = array_map('str_getcsv', $file);
        
        $firstLineArray = array();
        $queryArray = array();

        foreach($firstLine[0] as $line){
            array_push($firstLineArray, $line);
        }

        //remove first line
        $data = array_slice($file, 1);
            
        //loop through file and split every 1000 lines
        $parts = (array_chunk($data, 1000));
        $i = 1;
        foreach($parts as $line) {   
            
            $parseddata = array_map('str_getcsv', $line);
           
            foreach($parseddata as $row) {

                if($row[4] == NULL) 
                    $row[4] = NULL;
                $queryArray = array_combine($firstLineArray, $row);
                
                DB::table('batches')->insert($queryArray);
            }
            $i++;
        }

        return redirect('batch/list')->with('success','Import completed successfully!');
    }


    public function attachmentCsvUpload(Request $request)
    {
        $request->validate([
            'attachmentCsv' => 'required|mimes:csv,txt',
            'collectionZip' => 'required|mimes:zip'
        ]);

        $failedJobId = [];

        //get file from upload
        $path = $request->file('attachmentCsv')->getRealPath();
        
        //turn into array
        $file = file($path);
     
        $titleArray = array_map('str_getcsv', array(array_shift($file)));
        $titleArray = array_shift($titleArray);
      
        //loop through file and split every 1000 lines
        $parts = (array_chunk($file, 1000));
        $i = 1;

        $folder = 'attachment/'.date('ymdhis').'collection';
        $CollectionUrl = $request->collectionZip->store($folder);

        $isFileExtracted = $this->extractZip($CollectionUrl, $folder);
        if($isFileExtracted === False)
            return false;    

        // dd($parts);
        foreach($parts[0] as $line) {
            $parseddata = array_map('str_getcsv', array($line));
            $parseddata = array_shift($parseddata);
            
            $userExists = User::where('email', $parseddata[0])->get()->count();
            if($userExists > 0)
            {
                $userId = User::select('id')->where('email', $parseddata[0])->first()->id;
                $categoryExists = AttachmentCategory::where('category_name', $parseddata[1])->count();
                $uploadFileName = $parseddata[2];
                if($categoryExists > 0)
                {
                    $categoryId = AttachmentCategory::where('category_name', $parseddata[1])->first()->id;
                    $myUrl = $folder.'/'.$uploadFileName;
                    if(\Storage::exists($myUrl))
                    {
                        Attachment::create([
                            'user_id' => $userId,
                            'attachment_category_id' => $categoryId,
                            'url' => $myUrl
                        ]);    
                    }else{
                        dd("Here 1");
                        array_push($failedJobId, $i);
                    }
                }else{
                    dd("Here 12");
                    // dd("Here");
                    array_push($failedJobId, $i);
                }
            }else{
                dd("Here 13");
                array_push($failedJobId, $i);
            }
            $i++;
        }

        // dd("here");

        if(count($failedJobId) > 0){
            $msg = "Import Completed with some failure. Failed ID :: ";
            foreach($failedJobId as $fid)
                $msg .= $fid.', ';

            $msg = rtrim($msg, ", ");
        }else{
            $msg = "Import Completed Successfully!!!";
        }

        return redirect('attachment/create')->with('success', $msg);
    }

    public function getAttachmentURL($collectionUrl, $folder, $page)
    {
        $pdf = new \setasign\Fpdi\Fpdi();
        $pageCount = $pdf->setSourceFile(\storage_path('app/'.$collectionUrl));
        
        if($page <= $pageCount)
            $pageId = $pdf->importPage($page);
        else
            return false;

        $pdf->addPage();
        $pdf->useImportedPage($pageId);
        $url = $folder.'/'.date('ymdhis').$page.'.pdf';
        $pdf->Output('F', \storage_path('app/'.$url));

        return $url;
    }

    public function recordCsvUpload(Request $request)
    {
        $request->validate([
            'recordCsv' => 'required|mimes:csv,txt',
            'attachmentCollection' => 'mimes:zip,pdf',
            'attType' => 'required',
        ]);

        // $isFileExtracted = False;

        // dd($request->fileZip);

        $folder = 'recordAttachment/'.date('ymdHis').rand(11110111,99999999);

        $attType = $request->attType;

        if($request->attachmentCollection != NULL)
        {
            $link = $request->attachmentCollection->store($folder);
            $fileType = \File::extension($link);
            
            if(strtolower($fileType) == strtolower($attType))
                $fileType == strtolower($fileType);
            else
                return false;

            // dd($fileType);

            if($fileType == 'pdf')
            {
                //do nothing
            }elseif($fileType == 'zip')
            {
                $isFileExtracted = $this->extractZip($link, $folder);
                if($isFileExtracted === False)
                    return false;    
                // dd('Failed Unzipping');
            }else{
                //invalid file type
            }
        }
        

        // dd("Missed");

        // if($request->fileZip != NULL){
        //     $link = $request->fileZip->store($folder);
        //     $isFileExtracted = $this->extractZip($link, $folder);
        //     if($isFileExtracted === False)
        //         dd('Failed Unzipping');
        // }

        $failedJobId = [];

        //get file from upload
        $path = $request->file('recordCsv')->getRealPath();
        
        //turn into array
        $file = file($path);
     
        $titleArray = array_map('str_getcsv', array(array_shift($file)));
        $titleArray = array_shift($titleArray);
      
        //loop through file and split every 1000 lines
        $parts = (array_chunk($file, 1000));
        $i = 1;

        // dd($parts);
        foreach($parts[0] as $line) {
            $parseddata = array_map('str_getcsv', array($line));
            $parseddata = array_shift($parseddata);
            
            $userExists = User::where('email', $parseddata[0])->get()->count();
            if($userExists > 0)
            {
                $userId = User::select('id')->where('email', $parseddata[0])->first()->id;
                $categoryExists = Category::where('name', $parseddata[2])->count();
                if($categoryExists > 0)
                {
                    $categoryId = Category::where('name', $parseddata[2])->first()->id;

                    $semster = $parseddata[1];
                    $comment = $parseddata[3];
                    $start_date = $parseddata[4] == '' ? NULL : $parseddata[4];
                    $end_date = $parseddata[5] == '' ? NULL : $parseddata[5];

                    if($fileType == 'zip')
                    {
                        if($parseddata[6] == '')
                        {
                            $uploadFileName = NULL;
                        }else{
                            $uploadFileName = array_map('str_getcsv', array($parseddata[6]));
                            $uploadFileName = array_shift($uploadFileName);
                        }
                        // $uploadFileName = $parseddata[6] == '' ? NULL : array_map('str_getcsv', array($parseddata[6]));
                    }


                    // dd($uploadFileName);
                    
                    \DB::beginTransaction();

                    try{
                        $newRecord = new Record();
                        $newRecord->user_id = $userId;
                        $newRecord->semester = $semster;
                        $newRecord->category_id = $categoryId;
                        $newRecord->comment = $comment;
                        $newRecord->start_date = ($start_date == NULL) ? NULL : date('Y-m-d',strtotime($start_date));
                        $newRecord->end_date = ($end_date == NULL) ? NULL : date('Y-m-d',strtotime($end_date));
                        $newRecord->save();

                        if($request->attachmentCollection != NULL)
                        {
                            if($fileType == 'pdf')
                            {
                                RecordAttachment::create([
                                    'user_id' => $userId,
                                    'category_id' => $categoryId,
                                    'record_id' => $newRecord->id,
                                    'url' => $this->getAttachmentURL($link, $folder, $i) 
                                ]);
                            }elseif($fileType == 'zip')
                            {
                                if($isFileExtracted === True && $uploadFileName != NULL)
                                {
                                    foreach($uploadFileName as $f)
                                    {
                                        $myUrl = $folder.'/'.ltrim($f);
                                        //echo("<br>For ".$myUrl."exists = ".\Storage::exists($myUrl));
                                        //echo("<br>");
                                        if(\Storage::exists($myUrl))
                                        {
                                          //  echo($myUrl."Exists");
                                            RecordAttachment::create([
                                                'user_id' => $userId,
                                                'category_id' => $categoryId,
                                                'record_id' => $newRecord->id,
                                                'url' => $myUrl
                                            ]);
                                            // echo("Stored File".$myUrl);
                                        }else{
                                            //echo($myUrl."Doesnt exists");
                                        }

                                        // echo($myUrl);
                                    }
                                }
//                                dd("Here");
                            }
                        }
                        
                        \DB::commit();
                    }catch(Exception $e)
                    {
                        // //dd($start_date);
                        // dd(date('Y-m-d',strtotime($start_date)));
                        // dd($e);
                        DB::rollback();
                        array_push($failedJobId, $i);
                    }
                }else{
                    array_push($failedJobId, $i);
                }
            }else{
                array_push($failedJobId, $i);
            }
            $i++;
        }

        if(count($failedJobId) > 0){
            $msg = "Import Completed with some failure. Failed ID :: ";
            foreach($failedJobId as $fid)
                $msg .= $fid.', ';

            $msg = rtrim($msg, ", ");
        }else{
            $msg = "Import Completed Successfully!!!";
        }

        return redirect('record/create')->with('success', $msg);
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
}
