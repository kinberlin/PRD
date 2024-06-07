<?php

namespace App\Http\Controllers;

use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use App\Models\Department;
use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Gravity;
use App\Models\Invitation;
use App\Models\Processes;
use App\Models\Site;
use App\Models\Status;
use App\Models\Users;
use App\Policies\UserPolicy;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('isAdmin', Auth::user());
        return view('admin/adashboard');
    }

    public function enterprise()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Enterprise::all();
        return view('admin/enterprise', compact('data'));
    }
    public function gravity()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Gravity::all();
        return view('admin/gravity', compact('data'));
    }
    public function processes()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Processes::all();
        return view('admin/processus', compact('data'));
    }
    public function department()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Department::all();
        $ents = Enterprise::all();
        return view('admin/department', compact("data", "ents"));
    }
    public function site()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Site::all();
        $ents = Enterprise::all();
        return view('admin/site', compact("data", "ents"));
    }
    public function signals()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Dysfunction::all();
        $complainant = Dysfunction::distinct('emp_matricule')->count('matricule');
        return view('admin/signal', compact('data', 'complainant'));
    }
    public function showDysfunction($id)
    {
        Gate::authorize('isAdmin', Auth::user());
        try {
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("Nous ne trouvons pas la ressource auquel vous essayez d'accéder.", 1);
            }
            $status = Status::all();
            $processes = Processes::all();
            $ents = Enterprise::all();
            $site = Site::all();
            $gravity = Gravity::all();
            $data = $dys;
            return view('rq/infos', compact(
                'data',
                'status',
                'processes',
                'ents',
                'site',
                'gravity'
            ));
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function planif()
    {
        Gate::authorize('isAdmin', Auth::user());
        $dys = Dysfunction::all();
        $users = Users::all();
        return view('admin/planifs', compact('dys', 'users'));
    }
    public function employee()
    {
        Gate::authorize('isAdmin', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        $data = Users::where('role', 2)->get();
        return view('admin/employee', compact('ents', 'deps', 'data'));
    }
    public function rqemployee()
    {
        Gate::authorize('isAdmin', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        $data = AuthorisationRq::all();
        $users = Users::whereIn('id', $data->pluck('user'))->get();
        return view('admin/rqemployee', compact('ents', 'deps', 'data', 'users'));
    }
    public function pltemployee()
    {
        Gate::authorize('isAdmin', Auth::user());
        $ents = Enterprise::all();
        $processes = Processes::all();
        $deps = Department::all();
        $data = AuthorisationPilote::all();
        $users = Users::whereIn('id', $data->pluck('user'))->get();
        return view('admin/pltemployee', compact('ents', 'processes', 'deps', 'data', 'users'));
    }

    public function invitation()
    {
        Gate::authorize('isAdmin', Auth::user());
        // Query to get all invitations where internal_invites contains an invite with the user's email
        $data = Invitation::whereRaw('JSON_CONTAINS(internal_invites, \'{"matricule": "' . Auth::user()->matricule . '"}\', \'$\')')->get(); //Waiting for auth
        $dys = Dysfunction::whereIn('id', $data->pluck('dysfunction'))->get();
        return view('admin/invitation', compact('data', 'dys'));
    }
    public function listeSignalement()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Dysfunction::where('emp_matricule', Auth::user()->matricule)->get();
        $status = Status::all();
        return view('admin/listesignalement', compact('data', 'status'));
    }
    public function dysfonction()
    {
        Gate::authorize('isAdmin', Auth::user());
        $ents = Enterprise::all();
        $site = Site::all();
        return view('admin/dysfonction', compact('ents', 'site'));
    }
    public function profile()
    {
        Gate::authorize('isAdmin', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        return view('admin/profile', compact('ents', 'deps'));
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
