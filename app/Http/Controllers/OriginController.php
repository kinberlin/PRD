<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class OriginController extends Controller
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
                Origin::create([
                    //'id' => $id,
                    'name' => $row[1],
                    'description' => $row[2],
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
    public function show(Origin $origin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Origin $origin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'description' => 'required',
            'name' => 'required',
        ]);
        try {
            Gate::authorize('isAdmin', Auth::user());

            DB::beginTransaction();
            $d = Origin::find($id);
            $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
            $d->description = empty($request->input('description')) ? $d->description : $request->input('description');
            $d->save();
            DB::commit();
            return redirect()->back()->with('error', "Mis a Jour effectuer avec succes. ");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    /**
     * Update the visibility attribute of this resource in storage.
     */
    public function visible(Request $request, $id)
    {
        try {
            Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $d = Origin::find($id);
            $d->visible = $request->boolean('visibility');
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
        $rec = Origin::find($id);
        Gate::authorize('isAdmin', Auth::user());
        try {
            if (Gate::allows('canOriginDelete', $rec)) {
                DB::beginTransaction();
                $rec->forcedelete();
                DB::commit();
                return redirect()->back()->with('error', "Element envoyé dans la corbeille.");
            } else {
                throw new Exception("Présence d'une dépendance fonctionnelle. Cette ressource ne peut être supprimée.", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Echec lors de la surpression. L'erreur indique : " . $th->getMessage());
        }
    }
}
