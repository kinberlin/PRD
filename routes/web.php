<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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
    return redirect('/login');
});
Route::get('/admin', function () {
    return redirect('/admin/dashboard');
});
Route::get('/rq', function () {
    return redirect('/rq/dysfonctionnement');
});
Route::get('/employee', function () {
    return redirect('/employee/dysfonctionnement');
});
Route::get('/appmail', function () {
    return view('employees.invitation_appMail', ['invitation' => App\Models\Invitation::find(16)]);
});
Route::get('/dysmail', function () {
    $currentTimePlusOneHour = \Carbon\Carbon::now()->addHour()->toDateTimeString();
    $invitations = App\Models\Invitation::all();
    dd($invitations);
    return view('employees.dysfunction_reminder', ['user' => App\Models\Users::find(53), 'dysfunction' => App\Models\Dysfunction::find(18)]);
});
Route::get('/excmail', function () {
    return view('employees.invitation_excludeMail');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'auth'])->name('auth');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/notfound', [AuthController::class, 'NotFound404'])->name('404');
Route::post('/notfound', [AuthController::class, 'NotFound404P'])->name('404.post');

Route::group(['middleware' => ['web', 'auth', 'role:2'], 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/dys/data', 'GanttController@get');

    Route::get('/rq/dysfunction/report', 'RQController@report')->name('rq.dysfunction.report');
    Route::post('/rq/dysfunction/report', 'RQController@report')->name('rq.dysfunction.report.post');
    Route::get('/rq/dashboard/{id}', 'RQController@index')->name('rq.index');
    Route::get('/rq/js/{id}/dashboard.js', 'DynamicJsController@rq')->name('rq.dashboardjs');
    Route::get('/employee/dysfonctionnement', 'EmployeeController@dysfunction')->name('employees.dysfunction');
    Route::get('/employee/messignalements', 'EmployeeController@listeSignalement')->name('employees.signalement');
    Route::get('/employee/mestaches', 'EmployeeController@mytasks')->name('employees.mytask');
    Route::get('/employee/invitations', 'EmployeeController@invitation')->name('emp.invitation');
    Route::get('/employee/dashboard/{id}', 'EmployeeController@index')->name('employee.index');
    Route::get('/employee/js/{id}/dashboard.js', 'DynamicJsController@pilote')->name('employee.dashboardjs');

    Route::get('/rq/dysfonctionnement', 'RQController@dysfonction')->name('rq.dysfonction');
    Route::get('/rq/detail/dysfonctionnement/{id}', 'RQController@show')->name('rq.n1dysfonction');
    Route::get('/rq/messignalements', 'RQController@listeSignalement')->name('rq.signalement');
    Route::get('/rq/signalements', 'RQController@allSignalement')->name('rq.allsignalement');
    Route::get('/rq/plans', 'RQController@planif')->name('rq.planif');
    Route::get('/rq/invitations', 'RQController@invitation')->name('rq.invitation');

    Route::get('/rq/department', 'RQController@department')->name('rq.department');
    Route::get('/rq/department/{id}', 'DepartmentController@destroy')->name('rq.department.destroy');
    Route::post('/rq/department', 'DepartmentController@store')->name('rq.department.store');
    Route::post('/rq/department/{id}', 'DepartmentController@update')->name('rq.department.update');

    Route::get('/rq/site', 'RQController@site')->name('rq.site');

    Route::get('/rq/employee', 'RQController@employee')->name('rq.employees');
    Route::get('/rq/responsable', 'RQController@rqemployee')->name('rq.responsables');
    Route::get('/rq/pilote', 'RQController@pltemployee')->name('rq.pilotes');

    Route::get('rq/planner/{id}', 'GanttController@rqplanner')->name('rq.planner');
    Route::get('notification/{id}', 'NotificationController@destroy')->name('admin.notification.destroy');
    //Route::get('/employee/dashboard', 'RQController@index')->name('rq.index');
    Route::get('/rq/profile', 'RQController@profile')->name('rq.profile');
    Route::get('/employee/profile', 'EmployeeController@profile')->name('emp.profile');

    Route::get('/rq/meetings/inprocess', 'RQController@meetingProcess')->name('rq.meeting.inprocess');
    Route::get('/rq/meetings/closed', 'RQController@meetingClosed')->name('rq.meeting.closed');

    Route::get('/employee/empty', 'EmployeeController@empty')->name('emp.empty');
});
Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers'], function () {
    //site
    Route::get('/site/{id}', 'SiteController@destroy')->name('site.destroy');
    Route::post('/site', 'SiteController@store')->name('site.store');
    Route::post('/site/{id}', 'SiteController@update')->name('site.update');

    Route::post('/visible/enterprise/{id}', 'EnterpriseController@visible')->name('enterprise.visible');
    Route::post('/visible/site/{id}', 'SiteController@visible')->name('site.visible');
    Route::post('/visible/probability/{id}', 'ProbabilityController@visible')->name('probability.visible');
    Route::post('/visible/gravity/{id}', 'GravityController@visible')->name('gravity.visible');
    Route::post('/visible/origin/{id}', 'OriginController@visible')->name('origin.visible');
    //users
    Route::post('/password/update', 'AuthController@updatePassword')->name('auth.passwordupdate');
    Route::get('/dysfunctions/report', 'EmployeeController@report')->name('emp.dysfunction.report');
    Route::post('/dysfunctions/report', 'EmployeeController@report')->name('emp.dysfunction.report.post');
    Route::get('/invitations/index', 'InvitationController@index')->name('invitation.index');
    Route::get('/invitations/show/{id}', 'InvitationController@show')->name('invitation.show');
    Route::get('/invitations/delete/{id}', 'InvitationController@destroy')->name('invitation.destroy');
    Route::post('/invitation', 'InvitationController@store')->name('invitation.store');
    Route::post('/invitation/update/{id}', 'InvitationController@update')->name('inv                                       itation.update');
    Route::post('/invitation/invite', 'InvitationController@inviteConfirmation')->name('invitation.invite.confirmation');

    Route::post('/dysfunction/new', 'DysfunctionController@init')->name('dysfunction.init');
    Route::post('/dysfunction/cost/{id}', 'DysfunctionController@cost')->name('dysfunction.cost');
    Route::post('/dysfunction/store/{id}', 'DysfunctionController@store')->name('dysfunction.store');
    Route::post('/dysfunction/action/{id}', 'DysfunctionController@action')->name('dysfunction.action');
    Route::post('/dysfunction/evaluate/{id}', 'DysfunctionController@evaluation')->name('dysfunction.evaluation');
    Route::post('/dysfunction/close/{id}', 'DysfunctionController@close')->name('dysfunction.close');
    Route::get('/dysfunction/cancel/{id}', 'DysfunctionController@cancel')->name('dysfunction.cancel');
    Route::get('/dysfunction/evaluation/launch/{id}', 'DysfunctionController@launchEvaluation')->name('dysfunction.evaluation.launch');
    Route::get('/dysfunction/evaluation/cancel/{id}', 'DysfunctionController@cancelEvaluation')->name('dysfunction.evaluation.cancel');

    Route::get('/meeting/{id}/close', 'InvitationController@close')->name('invitation.close');
    Route::post('/meeting/{id}/participation', 'InvitationController@participation')->name('invitation.participation');
    Route::get('/actif/year/{year}', 'AuthController@setyear')->name('auth.year');
});
Route::group(['middleware' => ['web', 'auth', 'role:1'], 'namespace' => 'App\Http\Controllers'], function () {
    //admins

    Route::post('/admin/user/import', 'EmployeeController@import')->name('admin.user.import');

    Route::get('/admin/dysfunction/report', 'DysfunctionController@report')->name('admin.dysfunction.report');
    Route::post('/admin/dysfunction/report', 'DysfunctionController@report')->name('admin.dysfunction.report.post');
    Route::get('/admin/profile', 'AdminController@profile')->name('admin.profile');
    Route::get('/admin/planner/{id}', 'GanttController@adminplanner')->name('admin.planner');
    Route::get('/admin/js/dashboard.js', 'DynamicJsController@admin')->name('admin.dashboardjs');
    Route::get('/admin/dashboard', 'AdminController@index')->name('admin.index');
    Route::get('/admin/messignalements', 'AdminController@listeSignalement')->name('admin.signalement');
    Route::get('/admin/invitations', 'AdminController@invitation')->name('admin.invitation');
    Route::get('/admin/dysfonctionnement', 'AdminController@dysfonction')->name('admin.dysfonction');

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

    Route::get('/admin/probability', 'AdminController@probability')->name('admin.probability');
    Route::get('/admin/probability/{id}', 'ProbabilityController@destroy')->name('admin.probability.destroy');
    Route::post('/admin/probability', 'ProbabilityController@store')->name('admin.probability.store');
    Route::post('/admin/probability/{id}', 'ProbabilityController@update')->name('admin.probability.update');

    Route::get('/admin/origin', 'AdminController@origin')->name('admin.origin');
    Route::get('/admin/origin/{id}', 'OriginController@destroy')->name('admin.origin.destroy');
    Route::post('/admin/origin', 'OriginController@store')->name('admin.origin.store');
    Route::post('/admin/origin/{id}', 'OriginController@update')->name('admin.origin.update');

    Route::get('/admin/processes', 'AdminController@processes')->name('admin.processes');
    Route::get('/admin/processes/{id}', 'ProcessesController@destroy')->name('admin.processes.destroy');
    Route::post('/admin/processes', 'ProcessesController@store')->name('admin.processes.store');
    Route::post('/admin/processes/{id}', 'ProcessesController@update')->name('admin.processes.update');

    Route::get('/admin/signal', 'AdminController@signals')->name('admin.signals'); /*to be done */
    Route::get('/admin/department/{id}', 'DepartmentController@destroy')->name('admin.department.destroy');
    Route::post('/admin/department', 'DepartmentController@store')->name('admin.department.store');
    Route::post('/admin/department/{id}', 'DepartmentController@update')->name('admin.department.update');

    /*Route::get('/admin/signal/{id}', 'SignalController@destroy')->name('admin.signal.destroy');
    Route::post('/admin/signal', 'SignalController@store')->name('admin.signal.store');
    Route::post('/admin/signal/{id}', 'SignalController@update')->name('admin.signal.update');*/

    Route::get('/admin/department', 'AdminController@department')->name('admin.department');

    Route::get('/admin/site', 'AdminController@site')->name('admin.site');

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

    Route::get('/admin/meetings/inprocess', 'AdminController@meetingProcess')->name('admin.meeting.inprocess');
    Route::get('/admin/meetings/closed', 'AdminController@meetingClosed')->name('admin.meeting.closed');

    //Trash
    Route::get('/admin/trash/enterprise', 'TrashController@enterprise')->name('admin.trash.enterprise');
    Route::post('/admin/trash/enterprise/{id}', 'EnterpriseController@restore')->name('admin.trash.enterprise.restore');

    Route::post('/user/{id}/update-password', 'AdminController@updatePassword')->name('admin.user.updatePassword');
    Route::post('/user/{id}/update-profile', 'AdminController@updateProfile')->name('admin.user.updateProfile');
});
Route::any('{any}', function () {
return redirect('/notfound');
})->where('any', '.*');
