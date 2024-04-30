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

class EnterpriseController extends Controller
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

                $name = $row[1];
                Enterprise::create([
                    //'id' => $id,
                    'name' => $name,
                ]);
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
    public function show(Enterprise $enterprise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enterprise $enterprise)
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
            $d = Enterprise::find($id);
            $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
            $d->manager = empty($request->input('manager')) ? $d->manager : $request->input('manager');
            $u = Users::find($request->input('manager'));
            $de = Department::find($u->department);
            $message = '';
            if ($de->manager == $u->id) {
                $de->manager = null;
                $message .= $u->firstname . ' ' . $u->lastname . " n'est plus manager du département " . $d->name;
            }
            if ($u->service != null) {
                $s = Service::find($u->service);
                if ($s->manager == $u->id) {
                    $s->manager = null;
                    $message .= $u->firstname . ' ' . $u->lastname . " n'est plus manager du service " . $s->name;
                }
                $s->save();
            }
            $u->service = null;
            $u->save();
            $de->save();
            $d->save();
            DB::commit();
            return redirect()->back()->with('error', "Mis a Jour effectuer avec succes. " . $message);
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
            $rec = Enterprise::find($id);
            $rec->delete();
            DB::commit();
            return redirect()->back()->with('error', "Cette entreprise a été ajouté dans la corbeille.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Echec lors de la surpression. L'erreur indique : " . $th->getMessage());
        }
    }
}
