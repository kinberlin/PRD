<?php

namespace App\Http\Controllers;

use App\Models\Processes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class ProcessesController extends Controller
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
            Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $data = $request->input('data');
            if (!is_array($data)) {
                throw new Exception("Vous n'avez pas soumis de données a sauvegarder", 1);
            }
            foreach ($data as $row) {
                Processes::create([
                    //'id' => $id,
                    'name' => $row[1],
                    'surfix' => $row[2],
                ]);
            }
            DB::commit();
            return redirect()->back()->with('error', "Insertion(s) terminée(s) avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Processes $processes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Processes $processes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $d = Processes::find($id);
            $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
            $d->surfix = empty($request->input('surfix')) ? $d->surfix : $request->input('surfix');
            $d->save();
            DB::commit();
            return redirect()->back()->with('error', "Mis a Jour effectuer avec succes. ");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rec = Processes::find($id);
        Gate::authorize('isAdmin', Auth::user());
        try {
            if (Gate::allows('canSiteDelete', $rec)) {
                if (Gate::allows('isAdmin', Auth::user())) {
                    DB::beginTransaction();
                    $rec->forceDelete();
                    DB::commit();
                    return redirect()->back()->with('error', "Ce processus a été supprimé avec succès.");
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
}
