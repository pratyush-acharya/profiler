<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Result;
use \Auth;
// use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Record;
use App\Models\Category;
use App\Models\ReBoardResult;
use App\Models\RecordAttachment;
use ZipArchive;
use Symfony\Component\Finder\Finder;

class ViewProfile extends Component
{
    public $debug;
    public $authUser;
    public $presentSem;
    public $categoryList;
    public $categoryId = 0;
    public $myBatch;

    public $mMin = 0;
    public $mMax = 0;
    public $mAvg = 0;

    public $fMin = 0;
    public $fMax = 0;
    public $fAvg = 0;

    public $bMin = 0;
    public $bMax = 0;
    public $bAvg = 0;

    public function mount($id)
    {
        $userRole = User::find($id)->role;
        if($userRole != 'student')
            return redirect('/login');

        $this->authUser = User::find($id);
        $this->debug = $this->authUser->student;
        if($this->authUser->student->batch->present_sem > 8)
        {
            $this->presentSem = 1;
        }else{
            $this->presentSem = $this->authUser->student->batch->present_sem;
        }
        $this->myBatch = $this->authUser->student->batch->id;
        // dd($this->myBatch);
        $this->categoryList = Category::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    public function getRecord()
    {
        $this->recordList = Record::select('id', 'comment', 'start_date', 'end_date', 'category_id')
        ->where('semester', $this->presentSem)
        ->where('user_id', $this->authUser->id)
        ->get();
    }

    public function changeSemTo($semester)
    {
        if($semester >= 1 && $semester <= 8)
        {
            $this->presentSem = $semester;
            $this->categoryId = 0;
        }

        return $this->emit("showChart");
    }

    public function changeCategoryTo($catId)
    {
        if($catId >= 0)
        {
            $this->categoryId = $catId;
        }
        return $this->emit("showChart");
    }

    public function downloadRecordAttachment($reqId)
    {
        $fileAray = RecordAttachment::select('url')->where('id', $reqId)->first();
        return \Storage::download($fileAray->url);
    }

    public function deleteRecord($recordId)
    {
        $record = Record::find($recordId);
        $attachments = RecordAttachment::where('record_id', $record->id)->get();
        foreach($attachments as $attachment)
        {
            if(\Storage::exists($attachment->url))
                \Storage::delete($attachment->url);
            $attachment->delete();
        }
        $record->delete();
    }

    public function getResult($resultId, $examType)
    {
        $getResult = Result::select('id','mid_attachment', 'final_attachment', 'board_attachment')->where('id', $resultId)->first();
        $this->debug = $getResult;

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
                    Storage::copy($getResult->board_attachment,$getResult->id.'/'.substr($getResult->board_attachment,11));

                    if($getReBoardResults != NULL){ 

                        foreach($getReBoardResults as $result=>$file){
                            Storage::copy($file['board_attachment'],$getResult->id.'/'.substr($file['board_attachment'],13));
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

        $this->emit('showChart');
    }

    public function resultData()
    {
        $uId = $this->authUser->id;
        
        $this->mMin = Result::select('mid_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.mid_percentage', '>', '0')
        ->min('mid_percentage')
        ;

        $this->fMin = Result::select('final_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.final_percentage', '>', '0')
        ->min('final_percentage')
        ;

        $this->bMin = Result::select('board_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.board_percentage', '>', '0')
        ->min('board_percentage')
        ;
        
        // max
        $this->mMax = Result::select('mid_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.mid_percentage', '>', '0')
        ->max('mid_percentage')
        ;

        $this->fMax = Result::select('final_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.final_percentage', '>', '0')
        ->max('final_percentage')
        ;

        $this->bMax = Result::select('board_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.board_percentage', '>', '0')
        ->max('board_percentage')
        ;

        // avg
        $this->mAvg = Result::select('mid_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.mid_percentage', '>', '0')
        ->avg('mid_percentage')
        ;

        $this->fAvg = Result::select('final_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.final_percentage', '>', '0')
        ->avg('final_percentage')
        ;

        $this->bAvg = Result::select('board_percentage')
        ->join('users', 'results.user_id', '=', 'users.id')
        ->join('students', 'users.id', '=', 'students.user_id')
        ->join('batches', 'students.batch_id', '=', 'batches.id')
        ->where('results.semester', $this->presentSem)
        ->where('batches.id', $this->myBatch)
        ->where('results.board_percentage', '>', '0')
        ->avg('board_percentage')
        ;
    }

    public function render()
    {
        $this->resultData();
        $result = Result::where('user_id',$this->authUser->id)->where('semester', $this->presentSem)->first();
        $chart = (new LarapexChart)->setType('line')
                    ->setTitle('Total Users Monthly')
                    ->setSubtitle('From January to March')
                    ->setXAxis([
                        'Semester I', 'Semester II', 'Semester III'
                    ])
                    ->setDataset([
                        [
                            'name'  =>  'Active Users',
                            'data'  =>  [250, 700, 1200]
                        ],
                        [
                            'name' => 'Blocked Users',
                            'data' => [100,250,350]
                        ]
                    ]);
                    $bar = (new LarapexChart)->setTitle('Result Comparision')
                    // ->setSubtitle('From January To March')
                    ->setType('bar')
                    ->setXAxis(['Mid Term', 'Final Term', 'Board'])
                    ->setGrid(true)
                    ->setDataset([
                        [
                            'name'  => 'Lowest',
                            'data'  =>  [round($this->mMin, 2), round($this->fMin, 2), round($this->bMin, 2)]
                        ],
                        [
                            'name'  => 'Average',
                            'data'  => [round($this->mAvg, 2), round($this->fAvg, 2), round($this->bAvg, 2)]
                        ],
                        [
                            'name'  => 'Highest',
                            'data'  => [round($this->mMax, 2), round($this->fMax, 2), round($this->bMax, 2)]
                        ],
                        [
                            'name'  => 'Obtained',
                            'data'  => $result == NULL ? [0, 0, 0] : [$result->mid_percentage, $result->final_percentage, $result->board_percentage]
                        ]
                    ])
                    ->setStroke(1)
                ;

        if($this->categoryId != 0)
        {
            $record = Record::select('id', 'comment', 'start_date', 'end_date', 'category_id')
            ->where('semester', $this->presentSem)
            ->where('user_id', $this->authUser->id)
            ->where('category_id', $this->categoryId)
            ->get();
        }else{
            $record = Record::select('id', 'comment', 'start_date', 'end_date', 'category_id')
            ->where('semester', $this->presentSem)
            ->where('user_id', $this->authUser->id)
            ->get();
        }

        $this->emit("showChart");

        return view('livewire.student.view-profile', ['result' => $result, 'records' => $record],compact('chart','bar'))->layout('layouts.admin');
    }
}
