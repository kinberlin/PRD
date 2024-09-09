<?php

namespace App\Http\Controllers;

use App\Imports\DepartmentImport;
use App\Models\Department;
use App\Models\Enterprise;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Throwable;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

            DB::beginTransaction();
            $data = $request->input('data');
            if (!is_array($data)) {
                throw new Exception("Vous n'avez pas soumis de données a sauvegarder", 1);
            }
            foreach ($data as $row) {
                if (Gate::allows('isAdmin', Auth::user()) || Gate::allows('isEnterpriseRQ', [Enterprise::find($row[1])])) {
                    $name = $row[2];
                    $enterprise = $row[1];
                    if (Department::where('name', $name)->where('enterprise', $enterprise)->get()->first() != null) {
                        throw new Exception("DUPLICATA!!!! Il existe déja un departement avec le nom : " . $name . " dans l'entreprise dont l'ID est : " . $enterprise, 1);
                    }
                    $department = new Department();
                    $department->name = $name;
                    $department->enterprise = $enterprise;
                    $department->save();
                } else {
                    throw new Exception("Arrêt inattendu du processus suite a une tentative d'insertion/de manipulation de donnée sans detention des privileges requis pour l'operation.", 501);
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
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            $d = Department::find($id);
            if (Gate::allows('isEnterpriseRQ', [Enterprise::find($request->input('enterprise'))]) || Gate::allows('isAdmin', Auth::user())) {
                DB::beginTransaction();

                $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
                if ($d->enterprise != $request->input('enterprise')) {
                    $number = count(Users::where('department', $d->id)->get());
                    if ($number > 0) {
                        throw new Exception("Il n'est pas possible de changer l'entreprise au quel appartient ce département. Car, nous avons trouvés " . $number . "
                employé(s). Pour pouvoir modifier ce département, affecter ou vider les employés de ce département.", 1);
                    }
                }
                $d->enterprise = $request->input('enterprise');
                $d->save();
                DB::commit();
            } else {
                throw new Exception("Arrêt inattendu du processus suite a une tentative de mise a jour/de manipulation de donnée sans detention des privileges requis pour l'operation.", 501);
            }
            return redirect()->back()->with('error', "Mis a Jour effectuer avec succes.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rec = Department::find($id);
        try {
            if (Gate::allows('canDepDelete', $rec)) {
                if (Gate::allows('isEnterpriseRQ', [Enterprise::find($rec->enterprise)]) || Gate::allows('isAdmin', Auth::user())) {
                    DB::beginTransaction();
                    $rec->forceDelete();
                    DB::commit();
                    return redirect()->back()->with('error', "Ce site a été supprimé avec succès.");
                } else {
                    throw new Exception("Arrêt inattendu du processus suite a une tentative de suppression/de manipulation de donnée sans detention des privileges requis pour l'operation.", 501);
                }
            } else {
                throw new Exception("Présence d'une dépendance fonctionnelle. Cette ressource ne peut être supprimée.", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Echec lors de la surpression. L'erreur indique : " . $th->getMessage());
        }
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
            Excel::import(new DepartmentImport, $request->file('file'));
            return redirect()->back()->with('error', 'Insertions terminées avec succes!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            session()->flash('file', $failures);
            return redirect()->back()->with('error', "Une erreur s'est produite.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Une erreur s'est produite. L'erreur indique : " . $th->getMessage());
        }

    }
}
