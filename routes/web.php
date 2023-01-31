<?php

use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;


Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');


Route::post('/loginsubmit', function(Request $request){

    $response = array('status'=>0,'message'=>'','data'=>[]);
    $email = $request->input('uemail');
    $password = $request->input('upassword');

    if (Auth::attempt(['email' => $email, 'password' => $password])){
        return redirect('dashboard');
    }else{
        return redirect('login');
    }

})->name('rtloginsubmit');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('logout', function(Request $request){

    Auth::logout();
    return redirect('login');
});


Route::get('/manageemployee', [EmployeeController::class, 'index'])->name('manageemployee')->middleware('auth');
Route::post('/getEmployeeList', [EmployeeController::class, 'getEmployeeList'])->name('getemployeelist');
