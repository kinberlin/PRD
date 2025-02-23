<?php

namespace App\Http\Controllers;

use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use App\Models\Department;
use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Evaluation;
use App\Models\Gravity;
use App\Models\Invitation;
use App\Models\Origin;
use App\Models\Probability;
use App\Models\Processes;
use App\Models\Site;
use App\Models\Status;
use App\Models\Task;
use App\Models\Users;
use App\Scopes\YearScope;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Throwable;

class RQController extends Controller
{
    /**
     * Display rq stats board for a particulare enterprise.
     */
    public function index($id)
    {
        Gate::authorize('isEnterpriseRQ', Enterprise::find($id));
        return view('rq.dashboard', compact('id'));
    }
    /**
     * Display a listing of department resource.
     */
    public function department()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Department::all();
        $ents = Enterprise::all();
        return view('rq/department', compact("data", "ents"));
    }
    /**
     * Display a listing of site resource.
     */
    public function site()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Site::all();
        $ents = Enterprise::all();
        return view('rq/site', compact("data", "ents"));
    }
    /**
     * Display a listing of dysfunction resource.
     */
    public function dysfonction()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $site = Site::all();
        return view('rq/dysfonction', compact('ents', 'site'));
    }
    /**
     * Display a listing of incidents signaled
     * by the authenticated RQ.
     */
    public function listeSignalement()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Dysfunction::where('emp_matricule', Auth::user()->matricule)->get();
        $status = Status::all();
        return view('rq/listesignalement', compact('data', 'status'));
    }
    /**
     * Display a listing of all signaled incidents in the RQ enterprise.
     */
    public function allSignalement()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Dysfunction::whereNotIn('status', [3, 6])->get()->sortByDesc('created_at');
        $status = Status::all();
        return view('rq/signalements', compact('data', 'status'));
    }
    /**
     * Display rq meeting planner view.
     */
    public function planif()
    {
        Gate::authorize('isRq', Auth::user());
        $rqU = AuthorisationRq::where('user', Auth::user()->id)->get();

        $dys = Dysfunction::whereIn('enterprise_id', $rqU->pluck('enterprise')->unique())
            ->whereNotIn('status', [3, 6])->get();
        $users = Users::all();
        return view('rq/planifs', compact('dys', 'users'));
    }
    /**
     * Display RQ account infos.
     */
    public function profile()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        return view('rq/profile', compact('ents', 'deps'));
    }
    /**
     * Display a listing of registered employees.
     */
    public function employee()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        $data = Users::where('role', 2)->get();
        return view('rq/employee', compact('ents', 'deps', 'data'));
    }
    /**
     * Display a listing of rq employees.
     */
    public function rqemployee()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $deps = Department::all();
        $data = AuthorisationRq::all();
        $users = Users::whereIn('id', $data->pluck('user')->unique())->get();
        return view('rq/rqemployee', compact('ents', 'deps', 'data', 'users'));
    }
    /**
     * Display a listing of pilote employees.
     */
    public function pltemployee()
    {
        Gate::authorize('isRq', Auth::user());
        $ents = Enterprise::all();
        $processes = Processes::all();
        $deps = Department::all();
        $data = AuthorisationPilote::all();
        $users = Users::whereIn('id', $data->pluck('user')->unique())->get();
        return view('rq/pltemployee', compact('ents', 'processes', 'deps', 'data', 'users'));
    }
    /**
     * Display a listing invitations concerned by the RQ.
     */
    public function invitation()
    {
        Gate::authorize('isRq', Auth::user());
        // Query to get all invitations where internal_invites contains an invite with the user's email
        $data = Invitation::whereRaw('JSON_CONTAINS(internal_invites, \'{"matricule": "' . Auth::user()->matricule . '"}\', \'$\')')->get()->sortByDesc('created_at');
        $dys = Dysfunction::whereIn('id', $data->pluck('dysfonction')->unique())->get();
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
            $ents = Enterprise::where('id', $dys->enterprise_id)->get()->first();
            if (Gate::allows('isEnterpriseRQ', [$ents != null ? $ents : null]) || Gate::allows('isAdmin', Auth::user())) {
                $status = Status::all();
                $processes = Processes::all();
                $ents = Enterprise::all();
                $site = Site::all();
                $gravity = Gravity::all();
                $origin = Origin::all();
                $probability = Probability::all();
                $data = $dys;
                $parentTasks = Task::withoutGlobalScope(YearScope::class)
                    ->select('tasks.id', 'tasks.text')
                    ->distinct()
                    ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
                    ->where('t2.dysfunction', $id)
                    ->whereYear('tasks.created_at', session('currentYear'))
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
                    'origin',
                    'probability',
                    'corrections',
                    'evaluations'
                ));
            } else {
                throw new Exception("« Il est impossible d'afficher cette page. Il se peut que vous n'ayez pas les autorisations nécessaires pour manipuler ces données ou que certaines informations aient été mises à jour, rendant cette page accessible uniquement au Directeur Qualité. »", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Display a listing of meetings in the RQ
     * enterprise not yet closed but in process.
     */
    public function meetingProcess()
    {
        Gate::authorize('isRq', Auth::user());
        $rqU = AuthorisationRq::where('user', Auth::user()->id)->get();
        $dys = Dysfunction::whereIn('enterprise_id', $rqU->pluck('enterprise'))->get();
        $data = Invitation::whereNUll('closed_at')->whereIn('dysfonction', $dys->pluck('id'))->get()->sortByDesc('created_at');
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
    /**
     * Display a detailed report about a dysfunction.
     */
    public function report(Request $request)
    {
        $code = $request->input('code');
        try {
            $dys = is_null(Dysfunction::withoutGlobalScope(YearScope::class)->find($code)) ? Dysfunction::withoutGlobalScope(YearScope::class)->where('code', $code)->get()->first() : Dysfunction::withoutGlobalScope(YearScope::class)->find($code);
            $data = $dys;
            if (!is_null($dys)) {
                Session::put('currentYear', Carbon::parse($dys->created_at)->year);
                $id = $dys->id;
                $status = Status::all();
                $processes = Processes::all();
                $ents = Enterprise::all();
                $site = Site::all();
                $gravity = Gravity::all();
                $origin = Origin::all();
                $probability = Probability::all();
                $parentTasks = Task::withoutGlobalScope(YearScope::class)
                    ->select('tasks.id', 'tasks.text')
                    ->distinct()
                    ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
                    ->where('t2.dysfunction', $id)
                    ->get();
                $invitations = Invitation::where('dysfonction', $id)->get()->sortByDesc('id');
                $corrections = Task::where('dysfunction', $id)->whereNotIn('id', $parentTasks->pluck('id')->unique())->get()->sortByDesc('id');
                $evaluations = Evaluation::whereIn('task', $corrections->pluck('id')->unique())->get();
                $matricules = collect();
                // Iterate over each invitation and their invites
                foreach ($invitations as $d) {
                    if ($d->internal_invites) {
                        foreach ($d->getInternalInvites() as $i) {
                            $matricules->push($i->matricule);
                        }
                    }
                }
                // Get unique user matricules
                $distinctMatricules = $matricules->unique();
                $users = Users::whereIn('matricule', $distinctMatricules)->get();
                return view('rq/dys_report', compact(
                    'data',
                    'status',
                    'processes',
                    'ents',
                    'site',
                    'gravity',
                    'origin',
                    'probability',
                    'corrections',
                    'evaluations',
                    'invitations',
                    'users'
                ));
            } else {
                return view('rq/dys_report', compact(
                    'data',
                ));
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Display a listing of meeting in the
     * RQ enterprise that are closed.
     */
    public function meetingClosed()
    {
        Gate::authorize('isRq', Auth::user());
        $data = Invitation::whereNotNUll('closed_at')->get()->sortByDesc('created_at');
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
