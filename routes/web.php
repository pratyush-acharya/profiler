<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CsvUpload;
use App\Http\Livewire\User\CreateUser;
use App\Http\Livewire\User\ListUser;
use App\Http\Livewire\Student\ListStudent;
use App\Http\Livewire\Student\UploadCsv;
use App\Http\Livewire\Category\CreateCategory;
use App\Http\Livewire\Category\ListCategory;
use App\Http\Livewire\Category\CatComment;
use App\Http\Livewire\Batch\CreateBatch;
use App\Http\Livewire\Batch\ListBatch;
use App\Http\Livewire\Request\CreateRequest;
use App\Http\Livewire\Request\ListRequest;
use App\Http\Livewire\Record\CreateRecord;
use App\Http\Livewire\Record\EditRecord;
use App\Http\Livewire\Record\ListRecord;

use App\Http\Livewire\Result\CreateResult;

// integrated files
use App\Http\Livewire\UserLogin;

use App\Http\Livewire\Attachment\UploadFile;
use App\Http\Livewire\Attachment\ListFile;

use App\Http\Controllers\RecordAttachmentController;
use App\Http\Controllers\RecordCollectionController;
use App\Http\Controllers\ResultController;
use App\Http\Livewire\Admin\Dashboard;
use App\Http\Livewire\Log\LogList;

use App\Http\Livewire\Admin\AddNew;
use App\Http\Livewire\Student\StudentDashboard;
use App\Http\Livewire\Student\ViewProfile;
use App\Http\Livewire\Student\ExtraAttachment;
use App\Http\Livewire\Student\MyAttachment;
use App\Http\Livewire\Result\BoardResult;
use App\Http\Livewire\Student\BoardResultAttachment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['adminCheck'])->group(function () {
    Route::get('/upload', UploadCsv::class);
    Route::post('/studentCsv', [CsvUpload::class, 'studentCsvUpload'])->name('upload.studentCsv');
    Route::post('/batchCsv', [CsvUpload::class, 'batchCsvUpload'])->name('upload.batchCsv');
    Route::post('/attachmentCsv', [CsvUpload::class, 'attachmentCsvUpload'])->name('upload.attachmentCsv');
    Route::post('/recordCsv', [CsvUpload::class, 'recordCsvUpload'])->name('upload.recordCsv');
    Route::get('/user/create', CreateUser::class);
    Route::get('/user/edit/{id}', CreateUser::class);
    Route::get('/user/list', ListUser::class);
    Route::get('/student/list', ListStudent::class);
    Route::get('/category/create', CreateCategory::class);
    Route::get('/category/list', ListCategory::class);
    Route::get('/category/edit/{id}', CreateCategory::class);
    Route::get('/category/comment/{id}', CatComment::class);
//    Route::get('/batch/create', CreateBatch::class);
    Route::get('/batch/create/{stream?}', CreateBatch::class);
    Route::get('/batch/list', ListBatch::class);
    Route::get('/batch/edit/{id}', CreateBatch::class);
    Route::get('/request/list', ListRequest::class);
    Route::get('/request/edit/{id}', CreateRequest::class);
    Route::get('/record/create/{reqId?}', CreateRecord::class);
    Route::get('/record/edit/{reqId?}', EditRecord::class);
    Route::get('/result/create/{reqId?}', CreateResult::class);
    Route::get('/record/user/{userId}/semester/{reqSem?}', ListRecord::class);    
    Route::get('/record/attachment/{id}', [RecordAttachmentController::class, 'viewFile']);
    Route::get('/admin', Dashboard::class);
    Route::get('/log/list', LogList::class);
    Route::get('admin/new', AddNew::class);
    Route::get('/student/profile/{id}', ViewProfile::class);
    Route::get('/student/attachment/{userId}', ExtraAttachment::class);
    Route::get('/student/boardResult/{userId}', BoardResultAttachment::class);
    Route::get('/student/recordCollection/{id}', [RecordCollectionController::class, 'makeRecordPdf']);
    Route::post('/resultCsv', [ResultController::class, 'makeResult'])->name('upload.resultCsv');
    Route::get('/admin/result/{resultId}/{examType}', [ResultController::class, 'getResult']);
    Route::get('/attachment/create', UploadFile::class);
    Route::get('/attachment/edit/{id}', UploadFile::class);
    Route::get('/attachment/list', ListFile::class);
    Route::get('/result/bulkBoard', BoardResult::class);
});

Route::middleware(['studentCheck'])->group(function () {
    Route::get('/request/create', CreateRequest::class);
    Route::get('/student', StudentDashboard::class);
    Route::get('/student/myattachments', MyAttachment::class);
});

Route::post('/logout', function(){
    Auth::logout();
    return response('200', 200);
})->middleware('auth')->name('logout');

// integrated routes
Route::get('/login', UserLogin::class)->name('login');
Route::get('/auth/google',  [UserLogin::class,'redirectToProvider'])->name('redirectToGoogle');
Route::get('/auth/google/callback', [UserLogin::class,'handleProviderCallback']);

  

Route::get('/pdf/get-reboard-pdf/{id}', function(){
    $reBoardResultAttachment = App\Models\ReBoardResult::select('board_attachment')->where('id',request()->id)->first();
    $file = File::get(storage_path('app/'.$reBoardResultAttachment->board_attachment));
    $response = Response::make($file, 200);
    $response->header('Content-Type', 'application/pdf');
    return $response;
})->name('get-reboard-pdf');


Route::get('/pdf/get-board-pdf/{id}', function(){
    $boardResultAttachment = App\Models\Result::select('board_attachment')->where('id',request()->id)->first();
    $file = File::get(storage_path('app/'.$boardResultAttachment->board_attachment));
    $response = Response::make($file, 200);
    $response->header('Content-Type', 'application/pdf');
    return $response;
})->name('get-board-pdf');