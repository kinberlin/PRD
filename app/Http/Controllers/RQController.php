<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Site;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class RQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }
    public function dysfonction()
    {
        $ents = Enterprise::all();
        $site = Site::all();
        return view('rq/dysfonction', compact('ents', 'site'));
    }
    public function n1dysfonction()
    {
        return view('rq/n1dysfonction');
    }
        public function listeSignalement()
    {
        return view('rq/listesignalement');
    }
        public function planif()
    {
        return view('rq/planifs');
    }
    public function empty()
    {
        return view('employees/empty');
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
        try {
            $checks = Users::where('service', '=', ' ')->update(['service' => null]);
            DB::beginTransaction();
            $data = $request->input('data');
            if (!is_array($data)) {
                throw new Exception("Vous n'avez pas soumis de données a sauvegarder", 1);
            }
            foreach ($data as $row) {
                DB::beginTransaction();
                $employee = new Users();
                $employee->enterprise = $row[1];
                if ($row[2] == " ") {
                    $employee->department = null;
                } else {
                    $employee->department = $row[2];
                }
                $employee->firstname = $row[3];
                $employee->lastname = $row[4];
                $employee->email = $row[5];
                $employee->password = bcrypt($row[6]);
                if (Enterprise::find($row[1]) == null) {
                    throw new Exception("Nous ne parvenons pas a trouver l'entreprise dont l'ID est égal
                a : " . $row[1] . ' dans notre systeme. Veuillez consulter la liste des entreprises et entrer un Identifiant valide.', 404);
                }
                if (Department::where('id', $row[2])->where('enterprise', $row[1]) == null) {
                    throw new Exception("Nous ne parvenons pas a trouver le Département dont l'ID est égal
                a : " . $row[2] . ' dans notre systeme. Veuillez consulter la liste des Départements dans l\'entreprise dont l\'ID est : ' . $row[1] . ' et entrer un Identifiant valide.', 404);
                }
                if ($row[7] == "null" || $row[7] == " ") {
                    $department = Department::find($row[2]);
                    $serv_ = Service::find($row[7]);
                    if ($serv_ == null) {
                        throw new Exception("Aucune donnée n'a été trouver sur un service dont l'ID est : " . $row[7], 1);
                    }
                    if ($department->manager != null) {
                        throw new Exception("L'employé dont le matricule est " . $row[9] . '
                        ne peut pas être manager dans son département.
                        Car un manager a déja été assigné a son département.
                        Veuillez attribuer un service a cet employé ou placer le dans un autre département.', 1);
                    }
                    $employee->service = null;
                } else {
                    $employee->service = $row[7];
                }
                $employee->phone = $row[8];
                $employee->poste = $row[9];
                $employee->matricule = $row[10];
                $employee->holiday = $row[11];
                $employee->save();
                DB::commit();
                if ($row[7] == "null") {
                    $department = Department::find($row[2]);
                    if ($department->manager == null) {
                        $department->manager = $employee->id;
                        $department->save();
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('error', "Insertions terminées avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
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

    public function getRemainingPermissionInfo($employeeId)
    {
        $today = Carbon::now()->format('Y-m-d H:i:s');
        $todays = Carbon::now();
        $nearestEndDate = null;
        $pnePermissions = Pne::where('matricule', $employeeId)
            ->where('status', 4)
            ->where('begin', '<=', $today)
            ->where('end', '>=', $today)
            ->get();

        foreach ($pnePermissions as $pne) {
            if ($nearestEndDate === null || $pne->end > $nearestEndDate) {
                $nearestEndDate = $pne->end;
            }
        }

        $pmePermissions = Pme::where('matricule', $employeeId)
            ->where('status', 4)
            ->where('begin', '<=', $today)
            ->where('end', '>=', $today)
            ->get();
        foreach ($pmePermissions as $pme) {

            if ($nearestEndDate === null || $pme->end > $nearestEndDate) {
                $nearestEndDate = $pme->end;
            }
        }

        $holidayPermissions = Holliday::where('matricule', $employeeId)
            ->where('status', 4)
            ->where('begin', '<=', $today)
            ->where('end', '>=', $today)
            ->get();

        foreach ($holidayPermissions as $holiday) {
            if ($nearestEndDate === null || $holiday->end > $nearestEndDate) {
                $nearestEndDate = $holiday->end;
            }
        }
        if ($nearestEndDate === null) {
            return ['nearestEndDate' => null, 'differenceInHours' => 0];
        }
        $nearestEndDate = Carbon::createFromTimestamp($nearestEndDate)->format('Y-m-d H:i:s');
        $differenceInHours = $todays->diffInHours($nearestEndDate);

        return ['nearestEndDate' => $nearestEndDate, 'differenceInHours' => $differenceInHours];
    }
}
