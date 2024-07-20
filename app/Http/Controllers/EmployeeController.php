<?php

namespace App\Http\Controllers;

use App\Models\AuthorisationPilote;
use App\Models\Department;
use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Invitation;
use App\Models\Processes;
use App\Models\Site;
use App\Models\Status;
use App\Models\Task;
use App\Models\Users;
use App\Scopes\YearScope;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class EmployeeController extends Controller
{
    public function index($id)
    {
        Gate::authorize('isProcessusPilote', Processes::find($id));
        return view('employees.dashboard', compact('id'));
    }
    public function dysfunction()
    {
        $ents = Enterprise::all();
        $site = Site::all();
        return view('employees/dysfonction', compact('ents', 'site'));
    }
    public function listeSignalement()
    {
        $data = Dysfunction::where('emp_matricule', Auth::user()->matricule)->get()->sortByDesc('created_at');
        $status = Status::all();
        return view('employees/listesignalement', compact('data', 'status'));
    }
    public function mytasks()
    {
        Gate::authorize('isPilote', Auth::user());
        $processes = Processes::all();
        $pltU = AuthorisationPilote::where('user', Auth::user()->id)->get();
        $dys = Dysfunction::whereIn('status', [2, 4, 5])->whereHas('tasks')->get()->sortByDesc('created_at');
        $parentTasks = Task::withoutGlobalScope(YearScope::class)
            ->select('tasks.id', 'tasks.text')
            ->distinct()
            ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
            ->whereYear('tasks.created_at', session('currentYear'))
            ->get();
        $data = Task::whereIn('process', $pltU->pluck('process'))->whereNotIn('id', $parentTasks->pluck('id')->unique())->whereIn('dysfunction', $dys->pluck('id'))->get();
        return view('employees/mytasks', compact('data', 'dys', 'pltU', 'processes'));
    }
    public function profile()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        return view('employees/profile', compact('ents', 'deps'));
    }
    public function onestore(Request $request)
    {
        Gate::authorize('isAdmin', Auth::user());
        try {
            DB::beginTransaction();
            $employee = new Users();
            $employee->enterprise = $request->input('enterprise');
            if ($request->input('department') == "null") {
                $employee->department = null;
            } else {
                $employee->department = $request->input('department');
            }
            $employee->firstname = $request->input('firstname');
            $employee->lastname = $request->input('lastname');
            $employee->email = $request->input('email');
            $employee->password = bcrypt($request->input('password'));
            $enterp = Enterprise::find($request->input('enterprise'));
            if ($enterp == null) {
                throw new Exception("Nous ne parvenons pas a trouver l'entreprise dont l'ID est égal
                a : " . $request->input('enterprise') . ' dans notre systeme. Veuillez consulter la liste des entreprises et entrer un Identifiant valide.', 404);
            }
            if (Department::where('id', $request->input('department'))->where('enterprise', $request->input('enterprise')) == null) {
                throw new Exception("Nous ne parvenons pas a trouver le Département dont l'ID est égal
                a : " . $request->input('department') . ' dans notre systeme. Veuillez consulter la liste des Départements dans l\'entreprise dont l\'ID est : ' . $request->input('enterprise') . ' et entrer un Identifiant valide.', 404);
            }
            if (strtolower($request->input('phone')) == "null") {
                $employee->phone = null;
            } else {
                $employee->phone = $request->input('phone');
            }
            if (strtolower($request->input('poste')) == "null") {
                $employee->poste = null;
            } else {
                $employee->poste = $request->input('poste');
            }
            $employee->matricule = $enterp->surfix . $request->input('matricule');
            $employee->save();
            DB::commit();
            return redirect()->back()->with('error', "Insertions terminées avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function store(Request $request)
    {
        Gate::authorize('isAdmin', Auth::user());
        try {
            DB::beginTransaction();
            $data = $request->input('data');
            if (!is_array($data)) {
                throw new Exception("Vous n'avez pas soumis de données a sauvegarder", 1);
            }
            foreach ($data as $row) {
                DB::beginTransaction();
                $employee = new Users();
                $employee->enterprise = $row[1];
                if ($row[2] == "null") {
                    $employee->department = null;
                } else {
                    $employee->department = $row[2];
                }
                $employee->firstname = $row[3];
                $employee->lastname = $row[4];
                $employee->email = $row[5];
                $employee->password = bcrypt($row[6]);
                $enterp = Enterprise::find($row[1]);
                if ($enterp == null) {
                    throw new Exception("Nous ne parvenons pas a trouver l'entreprise dont l'ID est égal
                a : " . $row[1] . ' dans notre systeme. Veuillez consulter la liste des entreprises et entrer un Identifiant valide.', 404);
                }
                if (Department::where('id', $row[2])->where('enterprise', $row[1]) == null) {
                    throw new Exception("Nous ne parvenons pas a trouver le Département dont l'ID est égal
                a : " . $row[2] . ' dans notre systeme. Veuillez consulter la liste des Départements dans l\'entreprise dont l\'ID est : ' . $row[1] . ' et entrer un Identifiant valide.', 404);
                }
                if (strtolower($row[7]) == "null") {
                    $employee->phone = null;
                } else {
                    $employee->phone = $row[7];
                }
                if (strtolower($row[8]) == "null") {
                    $employee->poste = null;
                } else {
                    $employee->poste = $row[8];
                }
                $employee->matricule = $enterp->surfix . $row[9];
                $employee->save();
                DB::commit();
            }
            DB::commit();
            return redirect()->back()->with('error', "Insertions terminées avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function invitation()
    {
        // Query to get all invitations where internal_invites contains an invite with the user's email
        $data = Invitation::whereRaw('JSON_CONTAINS(internal_invites, \'{"matricule": "' . Auth::user()->matricule . '"}\', \'$\')')->get()->sortByDesc('created_at');
        $dys = Dysfunction::whereIn('id', $data->pluck('dysfonction')->unique())->get();
        return view('employees/invitation', compact('data', 'dys'));
    }
}
