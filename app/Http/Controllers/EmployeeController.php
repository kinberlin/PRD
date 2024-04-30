<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Enterprise;
use App\Models\Holliday;
use App\Models\HollidaySubstitution;
use App\Models\Pme;
use App\Models\Pne;
use App\Models\PublicHolliday;
use App\Models\Service;
use App\Models\Status;
use App\Models\TypePme;
use App\Models\TypePne;
use App\Models\Users;
use App\Models\Validation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ents = Enterprise::all();
        $deps = Department::all();
        $services = Service::all();
        $typepme = TypePme::all();
        $typepne = TypePne::all();
        $substitution = HollidaySubstitution::all();
        $dg = Users::where('id', Department::where('id', Auth::user()->department)->value('manager'))->first();
        $managers = null;

        $temp = Auth::user()->service != null ? Service::where('id', Auth::user()->service)->get()->first() : null;
        $directM = $temp != null ? Users::where('id', $temp->manager)->get()->first() : null;
        $permissionInfo = $this->getRemainingPermissionInfo(Auth::user()->matricule);

        if (Auth::user()->id != Department::where('id', Auth::user()->department)->value('manager') && Auth::user()->id != Enterprise::where('id', Auth::user()->enterprise)->value('manager')) {
            $managers = Users::join('service as s', 'users.id', '=', 's.manager')
                ->where('s.level', '<', Service::where('id', Auth::user()->service)->value('level'))
                ->select('users.*')
                ->get();
        }
        $pme = Pme::where('matricule', Auth::user()->matricule)->get();
        $pne = Pne::where('matricule', Auth::user()->matricule)->get();
        $holiday = Holliday::where('matricule', Auth::user()->matricule)->get();
        $mergedCollection = $pme->merge($pne)->merge($holiday);
        $sortedCollection = $mergedCollection->sortByDesc('created_at');
        $status = Status::all();
        $ferier = PublicHolliday::whereIn('pme', $pme->pluck('id'))->get();
        $vpme = Validation::whereIn('pme', $pme->pluck('id'))->get();
        $vpne = Validation::whereIn('pne', $pne->pluck('id'))->get();
        $vholliday = Validation::whereIn('holliday', $holiday->pluck('id'))->get();
        $validations = $vpme->merge($vpne)->merge($vholliday);
        // Retrieve the nearest end date and difference in hours from the result
        $nearestEndDate = $permissionInfo['nearestEndDate'];
        $differenceInHours = $permissionInfo['differenceInHours'];

        return view('employees/edashboard', compact(
            'directM',
            'ents',
            'deps',
            'services',
            'pme',
            'pne',
            'holiday',
            'managers',
            'dg',
            'sortedCollection',
            'status',
            'permissionInfo',
            'typepme',
            'typepne',
            'substitution',
            'ferier',
            'validations'
        ));
    }
    public function dysfonction()
    {
        return view('employees/dysfonction');
    }
    public function n1dysfonction()
    {
        return view('employees/n1dysfonction');
    }
        public function listeSignalement()
    {
        return view('employees/listesignalement');
    }
        public function planif()
    {
        return view('employees/planifs');
    }
    public function pme()
    {
        $type = TypePme::all();
        return view('employees/pme', compact('type'));
    }
    public function opme()
    {
        $type = TypePme::all();
        $ousers = null;
        $isdepartment = Department::where('manager', Auth::user()->id)
            ->orWhere('vice_manager', Auth::user()->id)
            ->get()->first();
        if (Auth::user()->service != null) {
        }
        $isservice = Service::where('manager', Auth::user()->id)->get()->first();
        if ($isdepartment != null) {
            $ser_ = Service::where('department', Auth::user()->department)
                ->where('level', 0)
                ->get()->first();
            $services = DB::select("
            WITH RECURSIVE ServiceHierarchy AS (
                SELECT id, parent
                FROM service
                WHERE id = :child_id

                UNION ALL

                SELECT p.id, p.parent
                FROM service p
                INNER JOIN ServiceHierarchy ch ON p.parent = ch.id
            )
            SELECT *
            FROM ServiceHierarchy", ['child_id' => $ser_->id]);
            $childIds = array_map(function ($child) {
                return $child->id;
            }, $services);

            // Use the extracted IDs in the subsequent query
            $ousers = Users::whereIn('service', $childIds)->get();
        } elseif ($isservice != null) {
            $services = DB::select("
            WITH RECURSIVE ServiceHierarchy AS (
                SELECT id, parent
                FROM service
                WHERE id = :child_id

                UNION ALL

                SELECT p.id, p.parent
                FROM service p
                INNER JOIN ServiceHierarchy ch ON p.parent = ch.id
            )
            SELECT *
            FROM ServiceHierarchy
        ", ['child_id' => Auth::user()->service]);
            $childIds = array_map(function ($child) {
                return $child->id;
            }, $services);

            // Use the extracted IDs in the subsequent query
            $ousers = Users::whereIn('service', $childIds)->get();
        }
        if ($ousers == null) {
            $ousers = [];
        }
        return view('employees/opme', compact('type', 'ousers'));
    }
    public function vpme()
    {
        $data = Validation::where('validator', Auth::user()->id)->where('status', 1)->get();
        //$data = [];
        $type = TypePme::all();
        $vid = Validation::where('validator', Auth::user()->id)->where('status', 1)->get()->pluck('pme');
        $pme = Pme::whereIn('id', $vid)->get();
        $pmeid = Pme::whereIn('id', $vid)->get()->pluck('id');
        $ferier = PublicHolliday::whereIn('pme', $pmeid)->get();
        return view('employees/vpme', compact('data', 'pme', 'type', 'ferier'));
    }
    public function pne()
    {
        $type = TypePne::all();
        return view('employees/pne', compact("type"));
    }
    public function opne()
    {
        $type = TypePne::all();
        $ousers = null;
        $isdepartment = Department::where('manager', Auth::user()->id)
            ->orWhere('vice_manager', Auth::user()->id)
            ->get();
        if (Auth::user()->service != null) {
        }
        $isservice = Service::where('manager', Auth::user()->id);
        if ($isdepartment != null) {
            $ser_ = Service::where('department', Auth::user()->department)
                ->where('level', 0)
                ->get()->first();
            $services = DB::select("
            WITH RECURSIVE ServiceHierarchy AS (
                SELECT id, parent
                FROM service
                WHERE id = :child_id

                UNION ALL

                SELECT p.id, p.parent
                FROM service p
                INNER JOIN ServiceHierarchy ch ON p.parent = ch.id
            )
            SELECT *
            FROM ServiceHierarchy", ['child_id' => $ser_->id]);
            $childIds = array_map(function ($child) {
                return $child->id;
            }, $services);

            // Use the extracted IDs in the subsequent query
            $ousers = Users::whereIn('service', $childIds)->get();
        } elseif ($isservice != null) {
            $services = DB::select("
            WITH RECURSIVE ServiceHierarchy AS (
                SELECT id, parent
                FROM service
                WHERE id = :child_id

                UNION ALL

                SELECT p.id, p.parent
                FROM service p
                INNER JOIN ServiceHierarchy ch ON p.parent = ch.id
            )
            SELECT *
            FROM ServiceHierarchy
        ", ['child_id' => Auth::user()->service]);
            $childIds = array_map(function ($child) {
                return $child->id;
            }, $services);

            // Use the extracted IDs in the subsequent query
            $ousers = Users::whereIn('service', $childIds)->get();
        }
        return view('employees/opne', compact('type', 'ousers'));
    }
    public function vpne()
    {
        $data = Validation::where('validator', Auth::user()->id)->where('status', 1)->get();
        //$data = [];
        $type = TypePne::all();
        $vid = Validation::where('validator', Auth::user()->id)->where('status', 1)->get()->pluck('pne');
        $pne = Pne::whereIn('id', $vid)->get();
        $pneid = Pne::whereIn('id', $vid)->get()->pluck('id');
        $ferier = PublicHolliday::whereIn('pne', $pneid)->get();
        return view('employees/vpne', compact('data', 'pne', 'type', 'ferier'));
    }
    public function vholliday()
    {
        $data = Validation::where('validator', Auth::user()->id)->where('status', 1)->get();
        //$data = [];
        $type = HollidaySubstitution::all();
        $vid = Validation::where('validator', Auth::user()->id)->where('status', 1)->get()->pluck('holliday');
        $holliday = Holliday::whereIn('id', $vid)->get();
        $hollidayid = Holliday::whereIn('id', $vid)->get()->pluck('id');
        $ferier = PublicHolliday::whereIn('holliday', $hollidayid)->get();
        return view('employees/vholliday', compact('data', 'holliday', 'type', 'ferier'));
    }
    public function holliday()
    {
        $type = TypePne::all();
        return view('employees/holliday', compact("type"));
    }
    public function oholliday()
    {
        $type = TypePne::all();
        $ousers = null;
        $isdepartment = Department::where('manager', Auth::user()->id)
            ->orWhere('vice_manager', Auth::user()->id)
            ->get();
        if (Auth::user()->service != null) {
        }
        $isservice = Service::where('manager', Auth::user()->id);
        if ($isdepartment != null) {
            $ser_ = Service::where('department', Auth::user()->department)
                ->where('level', 0)
                ->get()->first();
            $services = DB::select("
            WITH RECURSIVE ServiceHierarchy AS (
                SELECT id, parent
                FROM service
                WHERE id = :child_id

                UNION ALL

                SELECT p.id, p.parent
                FROM service p
                INNER JOIN ServiceHierarchy ch ON p.parent = ch.id
            )
            SELECT *
            FROM ServiceHierarchy", ['child_id' => $ser_->id]);
            $childIds = array_map(function ($child) {
                return $child->id;
            }, $services);

            // Use the extracted IDs in the subsequent query
            $ousers = Users::whereIn('service', $childIds)->get();
        } elseif ($isservice != null) {
            $services = DB::select("
            WITH RECURSIVE ServiceHierarchy AS (
                SELECT id, parent
                FROM service
                WHERE id = :child_id

                UNION ALL

                SELECT p.id, p.parent
                FROM service p
                INNER JOIN ServiceHierarchy ch ON p.parent = ch.id
            )
            SELECT *
            FROM ServiceHierarchy
        ", ['child_id' => Auth::user()->service]);
            $childIds = array_map(function ($child) {
                return $child->id;
            }, $services);

            // Use the extracted IDs in the subsequent query
            $ousers = Users::whereIn('service', $childIds)->get();
        }
        return view('employees/oholliday', compact("type", "ousers"));
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
