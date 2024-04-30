<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\SitemapGenerator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('login');
});
Route::get('/admin', function () {
    return redirect('/admin/dashboard');
});
Route::get('/employee', function () {
    return redirect('/employee/dashboard');
});
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'auth'])->name('auth');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/notfound', [AuthController::class, 'NotFound404'])->name('404');
Route::post('/notfound', [AuthController::class, 'NotFound404P'])->name('404');


Route::group(['middleware' => ['web'], 'namespace' => 'App\Http\Controllers',], function () {
    Route::get('/rq/dysfonctionnement', 'RQController@dysfonction')->name('rq.dysfonction');
    Route::get('/rq/n1/dysfonctionnement', 'RQController@n1dysfonction')->name('rq.n1dysfonction');
    Route::get('/rq/messignalements', 'RQController@listeSignalement')->name('rq.signalement');
        Route::get('/rq/plans', 'RQController@planif')->name('rq.planif');
    Route::get('notification/{id}', 'NotificationController@destroy')->name('admin.notification.destroy');
    Route::get('/employee/dashboard', 'RQController@index')->name('rq.index');
    Route::get('/employee/profile', 'RQController@profile')->name('rq.profile');

    

    Route::get('/employee/empty', 'EmployeeController@empty')->name('emp.empty');
});
Route::group(['middleware' => ['web', 'auth', 'role:1'], 'namespace' => 'App\Http\Controllers',], function () {
    //Admins

    Route::get('/admin/dashboard', 'AdminController@index')->name('admin.index');
    Route::get('/admin/dashboard/ep1', 'AdminController@indexEndPoint')->name('admin.indexep1');
    Route::get('/admin/dashboard/ep2', 'AdminController@indexEndPoint2')->name('admin.indexep2');
    Route::get('/admin/enterprise', 'AdminController@enterprise')->name('admin.enterprise');
    Route::get('/admin/enterprise/{id}', 'EnterpriseController@destroy')->name('admin.enterprise.destroy');
    Route::post('/admin/enterprise', 'EnterpriseController@store')->name('admin.enterprise.store');
    Route::post('/admin/enterprise/{id}', 'EnterpriseController@update')->name('admin.enterprise.update');

    Route::get('/admin/department', 'AdminController@department')->name('admin.department');
    Route::get('/admin/department/{id}', 'DepartmentController@destroy')->name('admin.department.destroy');
    Route::post('/admin/department', 'DepartmentController@store')->name('admin.department.store');
    Route::post('/admin/department/{id}', 'DepartmentController@update')->name('admin.department.update');

    Route::get('/admin/service', 'AdminController@service')->name('admin.service');
    Route::get('/admin/service/{id}', 'ServiceController@destroy')->name('admin.service.destroy');
    Route::post('/admin/service', 'ServiceController@store')->name('admin.service.store');
    Route::post('/admin/service/{id}', 'ServiceController@update')->name('admin.service.update');

    Route::get('/admin/employee', 'AdminController@employee')->name('admin.employee');
    Route::post('/admin/employee', 'EmployeeController@store')->name('admin.employee.store');
    Route::get('/admin/pme', 'AdminController@pme')->name('admin.pme');
    Route::get('/admin/pne', 'AdminController@pne')->name('admin.pne');
    Route::get('/admin/holliday', 'AdminController@holliday')->name('admin.holliday');
});
Route::any('{any}', function () {
    return redirect('/notfound');
})->where('any', '.*');
