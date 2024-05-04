<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Site;
use App\Models\Enterprise;
use App\Models\Holliday;
use App\Models\HollidaySubstitution;
use App\Models\Pme;
use App\Models\Pne;
use App\Models\Processes;
use App\Models\PublicHolliday;
use App\Models\Service;
use App\Models\Status;
use App\Models\TypePme;
use App\Models\TypePne;
use App\Models\Users;
use App\Models\Validation;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin/adashboard');
    }

    public function enterprise()
    {
        $data = Enterprise::all();
        return view('admin/enterprise', compact('data'));
    }
        public function processes()
    {
        $data = Processes::all();
        return view('admin/processus', compact('data'));
    }
    public function department()
    {
        $data = Department::all();
        $ents = Enterprise::all();
        return view('admin/department', compact("data", "ents"));
    }
        public function site()
    {
        $data = Site::all();
        $ents = Enterprise::all();
        return view('admin/site', compact("data", "ents"));
    }
    public function service()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        $services = Service::all();
        $manager = Users::where('role', 2)->get();
        return view('admin/service', compact('ents', 'deps', 'services', 'manager'));
    }
    public function employee()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        $services = Service::all();
        $data = Users::where('role', 2)->get();
        return view('admin/employee', compact('ents', 'deps', 'services', 'data'));
    }
    public function pme()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        $services = Service::all();
        $typepme = TypePme::all();
        $status = Status::all();
        $data = Pme::where('status', 4)->orderBy('created_at', 'desc')->get();
        $ferier = PublicHolliday::whereIn('pme', $data->pluck('id'))->get();
        return view('admin/pme', compact('ents', 'deps', 'services', 'typepme', 'status', 'data', 'ferier'));
    }
    public function pne()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        $services = Service::all();
        $typepne = TypePne::all();
        $status = Status::all();
        $data = Pne::where('status', 4)->orderBy('created_at', 'desc')->get();
        $ferier = PublicHolliday::whereIn('pne', $data->pluck('id'))->get();
        return view('admin/pne', compact('ents', 'deps', 'services', 'typepne', 'status', 'data', 'ferier'));
    }
    public function holliday()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        $services = Service::all();
        $typepne = TypePne::all();
        $substitution = HollidaySubstitution::all();
        $status = Status::all();
        $data = Holliday::where('status', 4)->orderBy('created_at', 'desc')->get();
        $ferier = PublicHolliday::whereIn('pne', $data->pluck('id'))->get();
        return view('admin/holliday', compact('ents', 'deps', 'services', 'typepne', 'substitution', 'status', 'data', 'ferier'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Users $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Users $users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Users $users)
    {
        //
    }
}
