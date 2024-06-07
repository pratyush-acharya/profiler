<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Record;
use App\Models\Result;

use PDF;

class RecordCollectionController extends Controller
{
    public function makeRecordPdf(Request $request)
    {
        $reqUserId = $request->id;
        $isUserExists = User::where('id',$reqUserId)->where('role','student')->count();

        if($isUserExists > 0)
        {
            $user = User::where('id',$reqUserId)->where('role','student')->first();
            
            $studentDetail = User::with(array('student'=>function($query){
                $query->with(array('batch'=>function($q){ $q->select('id', 'year', 'stream', 'start_date', 'end_date'); }))->select('user_id', 'batch_id');
            }))
            ->select('id', 'name', 'email')
            ->where('id', $user->id)
            ->first();

            $recordList = Record::with(array('category'=>function($query){
                $query->select('id','name');
            }))
            ->select('id', 'semester', 'category_id', 'comment', 'start_date', 'end_date')
            ->where('user_id', $user->id)
            ->orderBy('semester')
            ->get();

            $resultList = Result::select('id', 'semester', 'mid_status', 'mid_percentage', 'final_status', 'final_percentage', 'board_status', 'board_percentage')
            ->where('user_id', $user->id)
            ->orderBy('semester')
            ->get();

            //return view('recordtemplate', ['recordList' => $recordList, 'resultList' => $resultList, 'studentDetail' => $studentDetail]);

            // share data to view
            view()->share('recordList', $recordList);
            view()->share('resultList', $resultList);
            view()->share('studentDetail', $studentDetail);
            $pdf = PDF::loadView('recordtemplate');
            // download PDF file with download method
            return $pdf->download('pdf_file.pdf');
        }
    }
}
