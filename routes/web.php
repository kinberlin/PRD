<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

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
Route::get('/rq', function () {
    return redirect('/rq/dashboard');
});
Route::get('/employee', function () {
    return redirect('/employee/dysfonctionnement');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'auth'])->name('auth');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/notfound', [AuthController::class, 'NotFound404'])->name('404');
Route::post('/notfound', [AuthController::class, 'NotFound404P'])->name('404.post');

Route::group(['middleware' => ['web'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/dys/data', 'GanttController@get');

    Route::get('/employee/dysfonctionnement', 'EmployeeController@dysfunction')->name('employees.dysfunction');
    Route::get('/employee/messignalements', 'EmployeeController@listeSignalement')->name('employees.signalement');

    Route::post('/dysfunction/new', 'DysfunctionController@init')->name('dysfunction.init');
    Route::post('/dysfunction/store/{id}', 'DysfunctionController@store')->name('dysfunction.store');
    Route::post('/dysfunction/action/{id}', 'DysfunctionController@action')->name('dysfunction.action');
    Route::get('/dysfunction/cancel/{id}', 'DysfunctionController@cancel')->name('dysfunction.cancel');
    Route::get('/rq/dysfonctionnement', 'RQController@dysfonction')->name('rq.dysfonction');
    Route::get('/rq/detail/dysfonctionnement/{id}', 'RQController@show')->name('rq.n1dysfonction');
    Route::get('/rq/messignalements', 'RQController@listeSignalement')->name('rq.signalement');
    Route::get('/rq/signalements', 'RQController@allSignalement')->name('rq.allsignalement');
    Route::get('/rq/plans', 'RQController@planif')->name('rq.planif');
    Route::get('/rq/invitations', 'RQController@invitation')->name('rq.invitation');
    Route::get('/invitations/index', 'InvitationController@index')->name('invitation.index');
    Route::get('/invitations/show/{id}', 'InvitationController@show')->name('invitation.show');

    Route::get('/rq/department', 'RQController@department')->name('rq.department');
    Route::get('/rq/department/{id}', 'DepartmentController@destroy')->name('rq.department.destroy');
    Route::post('/rq/department', 'DepartmentController@store')->name('rq.department.store');
    Route::post('/rq/department/{id}', 'DepartmentController@update')->name('rq.department.update');

    Route::get('/rq/site', 'RQController@site')->name('rq.site');
    Route::get('/rq/site/{id}', 'SiteController@destroy')->name('rq.site.destroy');
    Route::post('/rq/site', 'SiteController@store')->name('rq.site.store');
    Route::post('/rq/site/{id}', 'SiteController@update')->name('rq.site.update');

    Route::get('/rq/employee', 'RQController@employee')->name('rq.employees');
    Route::get('/rq/responsable', 'RQController@rqemployee')->name('rq.responsables');
    Route::get('/rq/pilote', 'RQController@pltemployee')->name('rq.pilotes');

    Route::post('/invitation', 'InvitationController@store')->name('invitation.store');
    Route::post('/invitation/update/{id}', 'InvitationController@update')->name('invitation.update');
    Route::get('/planner/{id}', 'GanttController@planner')->name('rq.planner');
    Route::get('notification/{id}', 'NotificationController@destroy')->name('admin.notification.destroy');
    Route::get('/employee/dashboard', 'RQController@index')->name('rq.index');
    Route::get('/employee/profile', 'RQController@profile')->name('rq.profile');

    Route::get('/admin/enterprise', 'AdminController@enterprise')->name('admin.enterprise');
    Route::get('/admin/enterprise/{id}', 'EnterpriseController@destroy')->name('admin.enterprise.destroy');
    Route::post('/admin/enterprise', 'EnterpriseController@store')->name('admin.enterprise.store');
    Route::post('/admin/enterprise/{id}', 'EnterpriseController@update')->name('admin.enterprise.update');

    Route::get('/admin/detail/dysfonctionnement/{id}', 'AdminController@showDysfunction')->name('admin.dysfunction.show');
    Route::get('/admin/plans', 'AdminController@planif')->name('admin.planif');

    Route::get('/admin/gravity', 'AdminController@gravity')->name('admin.gravity');
    Route::get('/admin/gravity/{id}', 'GravityController@destroy')->name('admin.gravity.destroy');
    Route::post('/admin/gravity', 'GravityController@store')->name('admin.gravity.store');
    Route::post('/admin/gravity/{id}', 'GravityController@update')->name('admin.gravity.update');

    Route::get('/admin/processes', 'AdminController@processes')->name('admin.processes');
    Route::get('/admin/processes/{id}', 'ProcessesController@destroy')->name('admin.processes.destroy');
    Route::post('/admin/processes', 'ProcessesController@store')->name('admin.processes.store');
    Route::post('/admin/processes/{id}', 'ProcessesController@update')->name('admin.processes.update');

    Route::get('/admin/signal', 'AdminController@signals')->name('admin.signals'); /*to be done */
    Route::get('/admin/signal/{id}', 'SignalController@destroy')->name('admin.signal.destroy');
    Route::post('/admin/signal', 'SignalController@store')->name('admin.signal.store');
    Route::post('/admin/signal/{id}', 'SignalController@update')->name('admin.signal.update');

    Route::get('/admin/department', 'AdminController@department')->name('admin.department');
    Route::get('/admin/department/{id}', 'DepartmentController@destroy')->name('admin.department.destroy');
    Route::post('/admin/department', 'DepartmentController@store')->name('admin.department.store');
    Route::post('/admin/department/{id}', 'DepartmentController@update')->name('admin.department.update');

    Route::get('/admin/site', 'AdminController@site')->name('admin.site');
    Route::get('/admin/site/{id}', 'SiteController@destroy')->name('admin.site.destroy');
    Route::post('/admin/site', 'SiteController@store')->name('admin.site.store');
    Route::post('/admin/site/{id}', 'SiteController@update')->name('admin.site.update');

    Route::get('/admin/authorisation/rq', 'AdminController@rqemployee')->name('admin.rqemployee');
    Route::get('/admin/authorisation/rq/{id}', 'AuthorisationRqController@destroy')->name('admin.authrq.destroy');
    Route::post('/admin/authorisation/rq', 'AuthorisationRqController@store')->name('admin.authrq.store');
    Route::post('/admin/authorisation/rq/{id}', 'AuthorisationRqController@update')->name('admin.authrq.update');

    Route::get('/admin/authorisation/pilote', 'AdminController@pltemployee')->name('admin.pltemployee');
    Route::get('/admin/authorisation/pilote/{id}', 'AuthorisationPiloteController@destroy')->name('admin.authplt.destroy');
    Route::post('/admin/authorisation/pilote', 'AuthorisationPiloteController@store')->name('admin.authplt.store');
    Route::post('/admin/authorisation/pilote/{id}', 'AuthorisationPiloteController@update')->name('admin.authplt.update');

    Route::get('/admin/employee', 'AdminController@employee')->name('admin.employee');
    Route::delete('/admin/employee/{id}', 'EmployeeController@destroy')->name('admin.employee.destroy');
    Route::post('/admin/employee', 'EmployeeController@store')->name('admin.employee.store');
    Route::post('/admin/oneemployee/store', 'EmployeeController@onestore')->name('admin.employee.onestore');



    /*   Route::get('/admin/plans', 'AdminController@plans')->name('admin.service');
    Route::get('/admin/plans/{id}', 'PlanController@destroy')->name('admin.service.destroy');
    Route::post('/admin/plans', 'PlanController@store')->name('admin.service.store');
    Route::post('/admin/plans/{id}', 'PlanController@update')->name('admin.service.update');
     */
    Route::get('/employee/empty', 'EmployeeController@empty')->name('emp.empty');
});
Route::group(['middleware' => ['web', 'auth', 'role:1'], 'namespace' => 'App\Http\Controllers'], function () {
    //Admins

    Route::get('/admin/dashboard', 'AdminController@index')->name('admin.index');
    Route::get('/admin/dashboard/ep1', 'AdminController@indexEndPoint')->name('admin.indexep1');
    Route::get('/admin/dashboard/ep2', 'AdminController@indexEndPoint2')->name('admin.indexep2');
    /*
Route::get('/admin/service', 'AdminController@service')->name('admin.service');
Route::get('/admin/service/{id}', 'ServiceController@destroy')->name('admin.service.destroy');
Route::post('/admin/service', 'ServiceController@store')->name('admin.service.store');
Route::post('/admin/service/{id}', 'ServiceController@update')->name('admin.service.update');
 */

    Route::get('/admin/pme', 'AdminController@pme')->name('admin.pme');
    Route::get('/admin/pne', 'AdminController@pne')->name('admin.pne');
    Route::get('/admin/holliday', 'AdminController@holliday')->name('admin.holliday');
});
Route::any('{any}', function () {
    return redirect('/notfound');
})->where('any', '.*');
