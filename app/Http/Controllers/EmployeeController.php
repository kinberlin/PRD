<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\AuthorisationPilote;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
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
            $employee->password = bcrypt('123456789');
            $enterp = Enterprise::find($request->input('enterprise'));
            if ($enterp == null) {
                throw new Exception("Nous ne parvenons pas a trouver l'entreprise dont l'ID est égal
                a : " . $request->input('enterprise') . ' dans notre système. Veuillez consulter la liste des entreprises et entrer un Identifiant valide.', 404);
            }
            if (Department::where('id', $request->input('department'))->where('enterprise', $request->input('enterprise')) == null) {
                throw new Exception("Nous ne parvenons pas a trouver le Département dont l'ID est égal
                a : " . $request->input('department') . ' dans notre système. Veuillez consulter la liste des Départements dans l\'entreprise dont l\'ID est : ' . $request->input('enterprise') . ' et entrer un Identifiant valide.', 404);
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
                $corrections = Task::where('dysfunction', $id)->whereNotIn('id', $parentTasks->pluck('id')->unique())->get();
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
                return view('employees/dys_report', compact(
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
                return view('employees/dys_report', compact(
                    'data',
                ));
            }
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
                a : " . $row[1] . ' dans notre système. Veuillez consulter la liste des entreprises et entrer un Identifiant valide.', 404);
                }
                if (Department::where('id', $row[2])->where('enterprise', $row[1]) == null) {
                    throw new Exception("Nous ne parvenons pas a trouver le Département dont l'ID est égal
                a : " . $row[2] . ' dans notre système. Veuillez consulter la liste des Départements dans l\'entreprise dont l\'ID est : ' . $row[1] . ' et entrer un Identifiant valide.', 404);
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
    /**
     * Import datas from the excel file, checks them and save to database
     */
    public function import(Request $request)
    {
        Gate::authorize('isAdmin', Auth::user());
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
        // Load the file using Excel and read the first row
                /*$path = $request->file('file')->getRealPath();
        $headings = Excel::toArray([], $path, null, \Maatwebsite\Excel\Excel::XLSX)[0][0];
            dd($headings);*/
            Excel::import(new UserImport, $request->file('file'));
            return redirect()->back()->with('error', 'Insertions terminées avec succes!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            session()->flash('file', $failures);
            return redirect()->back()->with('error', "Une erreur s'est produite.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Une erreur s'est produite.L'erreur indique : " . $th->getMessage());
        }

    }
}
