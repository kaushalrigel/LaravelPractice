<?php

use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;



//  Login page routes
Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/loginsubmit', function(Request $request){

    $response = array('status'=>0,'message'=>'','data'=>[]);
    $email = $request->input('uemail');
    $password = $request->input('upassword');

    if (Auth::attempt(['email' => $email, 'password' => $password])){

        session()->put('msgsuc','Logged In successfully!... ');
        return redirect('dashboard');
    }else{
        return redirect('login');
    }

})->name('rtloginsubmit');


// Main Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Auth Logout
Route::get('logout', function(Request $request){

    Auth::logout();
    return redirect('login');
});


//  Employee Module routes
Route::get('/manageemployee', [EmployeeController::class, 'index'])->name('manageemployee')->middleware('auth');
Route::post('/getEmployeeList', [EmployeeController::class, 'getEmployeeList'])->name('getemployeelist');
Route::post('/createEmployee', [EmployeeController::class, 'createNewEmployee'])->name('createemployee');
Route::post('/getEmployee', [EmployeeController::class, 'getEmployeeInfo'])->name('getemployee');
Route::post('/removeemployee', [EmployeeController::class, 'deleteEmployee'])->name('removeemployee');


//  Employee Module routes
Route::get('/manageetask', [TaskController::class, 'index'])->name('managetask')->middleware('auth');
Route::post('/getTaskList', [TaskController::class, 'getTaskList'])->name('gettasklist');
Route::post('/createTask', [TaskController::class, 'createNewTask'])->name('createtask');
Route::post('/getTask', [TaskController::class, 'getTaskInfo'])->name('gettask');
Route::post('/removetask', [TaskController::class, 'deleteTask'])->name('removetask');
Route::post('/assigntask', [TaskController::class, 'assignTaskToEmp'])->name('assigntask');


Route::get('testlaravelemail', function(){
  
    
});


Route::get('email-notify-employee', function(){
      
    dispatch(new App\Jobs\SendEmailJob());  
    dd('done');
});

