<?php

namespace App\Http\Controllers;

use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use App\Models\Department;
use App\Models\Dysfunction;
use App\Models\DysfunctionType;
use App\Models\Enterprise;
use App\Models\Evaluation;
use App\Models\Gravity;
use App\Models\Invitation;
use App\Models\Probability;
use App\Models\Processes;
use App\Models\Site;
use App\Models\Status;
use App\Models\Task;
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
        $data = Dysfunction::whereNotIn('status',[3,7])->get()->sortByDesc('created_at');
        $status = Status::all();
        return view('rq/signalements', compact('data', 'status'));
    }
    public function planif()
    {
        Gate::authorize('isRq', Auth::user());
        $rqU = AuthorisationRq::where('user', Auth::user()->id)->get();

        $dys = Dysfunction::whereNotIn('status',[3,7])->get();
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
        return view('rq/profile', compact('ents', 'deps'));
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
        $users = Users::whereIn('id', $data->pluck('user')->unique())->get();
        return view('rq/rqemployee', compact('ents', 'deps', 'data', 'users'));
    }
    public function pltemployee()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $processes = Processes::all();
        $deps = Department::all();
        $data = AuthorisationPilote::all();
        $users = Users::whereIn('id', $data->pluck('user')->unique())->get();
        return view('rq/pltemployee', compact('ents', 'processes', 'deps', 'data'));
    }
    public function invitation()
    {
        Gate::authorize('isRq', Auth::user());
        // Query to get all invitations where internal_invites contains an invite with the user's email
        $data = Invitation::whereRaw('JSON_CONTAINS(internal_invites, \'{"matricule": "' . Auth::user()->matricule . '"}\', \'$\')')->get();
        $dys = Dysfunction::whereIn('id', $data->pluck('dysfunction')->unique())->get();
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
        //try {
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
            $probability = Probability::all();
            $dystype = DysfunctionType::all();
            $data = $dys;
            $parentTasks = Task::select('tasks.id', 'tasks.text')
                ->distinct()
                ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
                ->where('t2.dysfunction', $id)
                ->get();
            $corrections = Task::where('dysfunction', $id)->whereNotIn('id', $parentTasks->pluck('id')->unique())->get();
            $evaluations = Evaluation::whereIn('task', $corrections->pluck('id')->unique())->get();
            return view('rq/infos', compact(
                'data',
                'status',
                'processes',
                'ents',
                'site',
                'gravity',
                'probability',
                'corrections',
                'evaluations',
                'dystype'
            ));
        } else {
            throw new Exception("« Il est impossible d'afficher cette page. Il se peut que vous n'ayez pas les autorisations nécessaires pour manipuler ces données ou que certaines informations aient été mises à jour, rendant cette page accessible uniquement au Directeur Qualité. »", 401);
        }
        /*} catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }*/
    }
    public function meetingProcess()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Invitation::whereNUll('closed_at')->get();
        // Initialize an empty collection to store user matricules
        $matricules = collect();
        // Iterate over each invitation and their invites
        foreach ($data as $d) {
            if ($d->internal_invites) {
                foreach ($d->getInternalInvites() as $i) {
                    $matricules->push($i->matricule);
                }
            }
        }
        // Get unique user matricules
        $distinctMatricules = $matricules->unique();
        $users = Users::whereIn('matricule', $distinctMatricules)->get();
        $dys = Dysfunction::whereIn('id', $data->pluck('dysfonction')->unique())->get();

        return view('rq/meetingProcess', compact('data', 'dys', 'users'));
    }
    public function meetingClosed()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Invitation::whereNotNUll('closed_at')->get();
        // Initialize an empty collection to store user matricules
        $matricules = collect();
        // Iterate over each invitation and their invites
        foreach ($data as $d) {
            if ($d->internal_invites) {
                foreach ($d->getInternalInvites() as $i) {
                    $matricules->push($i->matricule);
                }
            }
        }
        // Get unique user matricules
        $distinctMatricules = $matricules->unique();
        $users = Users::whereIn('matricule', $distinctMatricules)->get();
        $dys = Dysfunction::whereIn('id', $data->pluck('dysfonction')->unique())->get();

        return view('rq/meetingClosed', compact('data', 'dys', 'users'));
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
