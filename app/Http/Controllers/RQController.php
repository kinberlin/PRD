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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Throwable;

class RQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('isRq', Auth::user());
    }
    public function department()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Department::all();
        $ents = Enterprise::all();
        return view('rq/department', compact("data", "ents"));
    }
    public function site()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Site::all();
        $ents = Enterprise::all();
        return view('rq/site', compact("data", "ents"));
    }
    public function dysfonction()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $site = Site::all();
        return view('rq/dysfonction', compact('ents', 'site'));
    }
    public function n1dysfonction()
    {
        Gate::authorize('isRq', Auth::user());
        return view('rq/n1dysfonction');
    }
    public function listeSignalement()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Dysfunction::where('emp_matricule', Auth::user()->matricule)->get();;
        $status = Status::all();
        return view('rq/listesignalement', compact('data', 'status'));
    }
    public function allSignalement()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Dysfunction::all();
        $status = Status::all();
        return view('rq/signalements', compact('data', 'status'));
    }
    public function planif()
    {
        Gate::authorize('isRq', Auth::user());
        $dys = Dysfunction::all();
        $users = Users::all();
        return view('rq/planifs', compact('dys', 'users'));
    }
    public function empty()
    {
        return view('employees/empty');
    }
    public function profile()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        return view('employees/profile', compact('ents', 'deps'));
    }
    public function employee()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        $data = Users::where('role', 2)->get();
        return view('rq/employee', compact('ents', 'deps', 'data'));
    }
    public function rqemployee()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        $data = AuthorisationRq::all();
        $users = Users::whereIn('id', $data->pluck('user'))->get();
        return view('rq/rqemployee', compact('ents', 'deps', 'data', 'users'));
    }
    public function pltemployee()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $processes = Processes::all();
        $deps = Department::all();
        $data = AuthorisationPilote::all();
        $users = Users::whereIn('id', $data->pluck('user'))->get();
        return view('rq/pltemployee', compact('ents', 'processes', 'deps', 'data'));
    }
    public function invitation()
    {
        Gate::authorize('isRq', Auth::user());
        // Query to get all invitations where internal_invites contains an invite with the user's email
        $matricule = 'PZN0131';
        $data = Invitation::whereRaw('JSON_CONTAINS(internal_invites, \'{"matricule": "' . $matricule . '"}\', \'$\')')->get(); //Waiting for auth
        $dys = Dysfunction::whereIn('id', $data->pluck('dysfunction'))->get();
        return view('rq/invitation', compact('data', 'dys'));
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
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Gate::authorize('isRq', Auth::user());
        try {
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("Nous ne trouvons pas la ressource auquel vous essayez d'accéder.", 1);
            }
            $ents = Enterprise::where('name', $dys->enterprise)->get()->first();
            if (Gate::allows('isEnterpriseRQ', [$ents != null ? $ents : null]) || Gate::allows('isAdmin', Auth::user())) {
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
            } else {
                throw new Exception("« Il est impossible d'afficher cette page. Il se peut que vous n'ayez pas les autorisations nécessaires pour manipuler ces données ou que certaines informations aient été mises à jour, rendant cette page accessible uniquement au Directeur Qualité. »", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
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
