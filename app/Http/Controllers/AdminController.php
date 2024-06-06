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

        return view('admin/adashboard');
    }

    public function enterprise()
    {
        $data = Enterprise::all();
        return view('admin/enterprise', compact('data'));
    }
    public function gravity()
    {
        $data = Gravity::all();
        return view('admin/gravity', compact('data'));
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
    public function signals()
    {
        $data = Dysfunction::all();
        $complainant = Dysfunction::distinct('emp_matricule')->count('matricule');
        return view('admin/signal', compact('data', 'complainant'));
    }
    public function showDysfunction($id)
    {
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
            return view('rq/infos', compact('data',
                'status',
                'processes',
                'ents',
                'site',
                'gravity'));
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }

    }
    public function planif()
    {
        $dys = Dysfunction::all();
        $users = Users::all();
        return view('admin/planifs', compact('dys', 'users'));
    }
    public function employee()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        $data = Users::where('role', 2)->get();
        return view('admin/employee', compact('ents', 'deps', 'data'));
    }
        public function rqemployee()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        $data = AuthorisationRq::all();
        $users = Users::whereIn('id', $data->pluck('user'))->get();
        return view('admin/rqemployee', compact('ents', 'deps', 'data', 'users'));
    }
    public function pltemployee()
    {
        $ents = Enterprise::all();
        $processes = Processes::all();
        $deps = Department::all();
        $data = AuthorisationPilote::all();
        $users = Users::whereIn('id', $data->pluck('user'))->get();
        return view('admin/pltemployee', compact('ents', 'processes', 'deps', 'data', 'users'));
    }

    public function invitation()
    {
        // Query to get all invitations where internal_invites contains an invite with the user's email
        $data = Invitation::whereRaw('JSON_CONTAINS(internal_invites, \'{"matricule": "' . Auth::user()->matricule . '"}\', \'$\')')->get();//Waiting for auth
        $dys = Dysfunction::whereIn('id',$data->pluck('dysfunction'))->get();
        return view('admin/invitation', compact('data', 'dys'));
    }
    public function listeSignalement()
    {
        $data = Dysfunction::where('emp_matricule', Auth::user()->matricule);
        $status = Status::all();
        return view('admin/listesignalement', compact('data', 'status'));
    }
    public function dysfonction()
    {
        $ents = Enterprise::all();
        $site = Site::all();
        return view('admin/dysfonction', compact('ents', 'site'));
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
