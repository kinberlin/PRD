<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Enterprise;
use App\Models\Service;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
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

                $name = $row[2];
                $enterprise = $row[1];
                if(Department::where('name', $name)->where('enterprise', $enterprise)->get()->first() != null){
                    throw new Exception("DUPLICATA!!!! Il existe déja un departement avec le nom : ".$name ." dans l'entreprise dont l'ID est : ".$enterprise, 1);    
                }
                $department = new Department();
                $department->name = $name;
                $department->enterprise = $enterprise;
                $department->save();
            }
            DB::commit();
            return redirect()->back()->with('error', "Insertions terminées avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    public function rqstore(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->input('data');
            if (!is_array($data)) {
                throw new Exception("Vous n'avez pas soumis de données a sauvegarder", 1);
            }
            foreach ($data as $row) {

                $name = $row[2];
                $enterprise = $row[1];
                if(Department::where('name', $name)->where('enterprise', $enterprise)->get()->first() != null){
                    throw new Exception("DUPLICATA!!!! Il existe déja un departement avec le nom : ".$name ." dans l'entreprise dont l'ID est : ".$enterprise, 1);    
                }
                $department = new Department();
                $department->name = $name;
                $department->enterprise = $enterprise;
                $department->save();
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
            DB::beginTransaction();
            $d = Department::find($id);
            $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
            if($d->enterprise != $request->input('enterprise')){
                $number = count(Users::where('department', $d->id)->get());
            if($number > 0){
                throw new Exception("Il n'est pas possible de changer l'entreprise au quel appartient ce département. Car, nous avons trouvés ".$number ." 
                employé(s). Pour pouvoir modifier ce département, affecter ou vider les employés de ce département.", 1);
                
            }}
            $d->enterprise =  $request->input('enterprise');
            $d->save();
            DB::commit();
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
        try {
            DB::beginTransaction();
            $rec = Department::find($id);
            $rec->delete();
            DB::commit();
            return redirect()->back()->with('error', "Cette entreprise a été ajouté dans la corbeille.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Echec lors de la surpression. L'erreur indique : ".$th->getMessage());
        }
    }
}
