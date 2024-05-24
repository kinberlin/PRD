<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Site;
use App\Models\Status;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class EmployeeController extends Controller
{
    public function dysfunction()
    {
        $ents = Enterprise::all();
        $site = Site::all();
        return view('employees/dysfunction', compact('ents', 'site'));
    }
    public function listeSignalement()
    {
        $data = Dysfunction::all();
        $status = Status::all();
        return view('employees/listesignalement', compact('data', 'status'));
    }
        public function store(Request $request)
    {
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
                if (Enterprise::find($row[1]) == null) {
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
                $employee->matricule = $row[10];
                $employee->save();
                DB::commit();
            }
            DB::commit();
            return redirect()->back()->with('error', "Insertions terminées avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
}
