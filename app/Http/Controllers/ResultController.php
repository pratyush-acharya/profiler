<?php

namespace App\Http\Controllers;

use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Result;
use App\Models\ReBoardResult;
use App\Models\Student;

use PDF;


class ResultController extends Controller
{
    public function getResult($resultId, $examType)
    {
       $getResult = Result::select('id','mid_attachment', 'final_attachment', 'board_attachment')->where('id', $resultId)->first();
       
       if(!empty($getResult))
        {
            $getReBoardResults = ReBoardResult::select('id','board_attachment')->where('result_id',$getResult->id)->get()->toArray();

            if($examType == 1)
            {
                if($getResult->mid_attachment != NULL)
                {
                    return \Storage::download($getResult->mid_attachment);
                }
            }

            if($examType == 2)
            {
                if($getResult->final_attachment != NULL)
                {
                    return \Storage::download($getResult->final_attachment);
                }
            }

            if($examType == 3)
            {
                if($getResult->board_attachment != NULL)
                {
                    Storage::copy($getResult->board_attachment,$getResult->id.'/main'.substr($getResult->board_attachment,26)); //26 11

                    if($getReBoardResults != NULL){ 
                        foreach($getReBoardResults as $result=>$file){
                            Storage::copy($file['board_attachment'],$getResult->id.'/back'.substr($file['board_attachment'],28)); //28 13
                        }
                        $zip = new ZipArchive;
                        $fileName = 'boardResults.zip';
                        $zip->open($fileName, (ZipArchive::CREATE | ZipArchive::OVERWRITE));

                        $path = storage_path('app/'.$getResult->id);
                        // $files = new \RecursiveDirectoryIterator($path);
                    
                        $iterator = new \RecursiveIteratorIterator(
                                        new \RecursiveDirectoryIterator(
                                            $path,
                                            \FilesystemIterator::FOLLOW_SYMLINKS
                                        ),
                                        \RecursiveIteratorIterator::SELF_FIRST
                                    );

                        while ($iterator->valid()) {
                            if (!$iterator->isDot()) {
                                $filePath = $iterator->getPathName();
                                $relativePath = substr($filePath, strlen($path) + 1);
                    
                                if (!$iterator->isDir()) {
                                    $zip->addFile($filePath, $relativePath);
                                } else {
                                    if ($relativePath !== false) {
                                        $zip->addEmptyDir($relativePath);
                                    }
                                }
                            }
                            $iterator->next();
                        }

                        // foreach ($files as $name => $file)
                        // {
                        //     if($file->isDir()){
                        //         foreach($file as $actualFile){
                        //             dd($actualFile);
                        //         }
                        //     }
                        //         dd("here");
                        //     // We're skipping all subfolders
                        //     if (!$file->isDir()) {
                        //         $filePath     = $file->getRealPath();

                        //         // extracting filename with substr/strlen
                        //         $relativePath = 'boardResults/' . substr($filePath, strlen($path) + 1);

                        //         $zip->addFile($filePath, $relativePath);
                        //         dd($zip);
                        //     }
                        // }
                        $zip->close();
                        Storage::deleteDirectory($getResult->id);
                        return response()->download($fileName); 
                    }
                    else
                    {
                        Storage::deleteDirectory($getResult->id);
                        return \Storage::download($getResult->board_attachment);
                    }
                }
            }
        }
    }
    
    public function makeResult(Request $request)
    {
        // dd("Here");
        $examDate = strtoupper($request->examDate);
        $examType = strtoupper($request->examType);
        $semester = $request->examSem;
        // dd($semester);
        $publishDate = strtoupper($request->publishDate);
        $grade = strtoupper($request->grade);

        $failedJobId = [];

        //get file from upload
        $path = $request->file('resultCsv')->getRealPath();
        
        //turn into array
        $file = file($path);
     
        $titleArray = array_map('str_getcsv', array(array_shift($file)));
        $titleArray = array_shift($titleArray);

        // dd($titleArray);
        $subjectCount = count($titleArray) - 1;
        // dd($subjectCount);

        $fullMarks = array_map('str_getcsv', array(array_shift($file)));
        $fullMarks = array_shift($fullMarks);
        // dd($fullMarks);

        $passMarks = array_map('str_getcsv', array(array_shift($file)));
        $passMarks = array_shift($passMarks);
        // dd($passMarks);
      
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
                // dd($parseddata[0]);
                $userId = User::select('id')->where('email', $parseddata[0])->first()->id;
                $name = User::select('name')->where('id', $userId)->first()->name;
                $roll = Student::select('roll')->where('user_id', $userId)->first()->roll;
                
                // dd($getUser);
                //make and save result
                $total = 0;
                $maxTotal = 0;
                $totalStatus = 'PASS';
                for($j = 1; $j <= $subjectCount; $j++)
                {
                    $maxTotal += (float)$fullMarks[$j];
                    $total += (float)$parseddata[$j];
                    if($parseddata[$j] < $passMarks[$j])
                        $totalStatus = 'FAIL';
                }
                $percentage = round(($total / $maxTotal) * 100, 2);

                view()->share('examType', $examType);
                view()->share('name', $name); 
                view()->share('roll',$roll); 
                view()->share('examDate', $examDate); 
                view()->share('semester',$semester); 
                view()->share('publishDate', $publishDate); 
                view()->share('grade', $grade); 
                view()->share('title', $titleArray); 
                view()->share('subjectCount', $subjectCount); 
                view()->share('fullMarks', $fullMarks); 
                view()->share('passMarks', $passMarks); 
                view()->share('myMarks', $parseddata); 
                view()->share('status', $totalStatus); 
                view()->share('percentage', $percentage);

                $pdf = PDF::loadView('livewire.result.result-template');
                $filename = 'result/'.date('ymdHis').$userId.$roll.'.pdf';
                // return $pdf->stream();
                // return $pdf->setPaper('a4')->download('invoice.pdf');

                // $pdf->render();
                // $pdf->output();
                $status = Storage::put($filename, $pdf->output());
                // dd($status);

                //dd($examType);
                if(strtolower($examType) == 'mid term')
                {
                    $col = 'mid';
                }else{
                    $col = 'final';
                }
                

                $isResultExist = Result::where('user_id', $userId)
                ->where('semester' , $semester)
                ->count();

                if($isResultExist == 0)
                {
                    Result::create([
                        'user_id' => $userId,
                        'semester' => $semester,
                        'mid_status' => 'pass',
                        'mid_percentage' => '0.00',
                        'final_status' => 'pass',
                        'final_percentage' => '0.00',
                        'board_status' => 'pass',
                        'board_percentage' => '0.00',
                    ]);
                }

                $oldResult = Result::where('user_id',$userId)->where('semester',$semester)->first();
                $oldResult->update([
                    $col.'_status' => strtolower($totalStatus),
                    $col.'_percentage' => $percentage,
                    $col.'_attachment' => $filename
                ]);

                Result::updateOrInsert(
                    [
                        'user_id' => $userId,
                        'semester' => $semester
                    ],
                    [
                        'user_id' => $userId,
                        'semester' => $semester,
                        $col.'_status' => strtolower($totalStatus),
                        $col.'_percentage' => $percentage,
                        $col.'_attachment' => $filename
                    ]
                );
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

        return redirect('result/create')->with('success', $msg);
    }
}
