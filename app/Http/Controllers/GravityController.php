<?php

namespace App\Http\Controllers;

use App\Models\Gravity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class GravityController extends Controller
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
                Gravity::create([
                    //'id' => $id,
                    'name' => $row[1],
                    'least_price' => $row[2],
                    'max_price' => $row[3],
                    'note' => $row[4],
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
    public function show(Gravity $enterprise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gravity $enterprise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'minloss' => 'required|numeric|min:0.01',
            'maxloss' => 'required|numeric|min:0.01',
            'note' => 'required|numeric|min:1',
        ]);
        try {
            Gate::authorize('isAdmin', Auth::user());

            DB::beginTransaction();
            $d = Gravity::find($id);

            $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
            $d->least_price = empty($request->input('minloss')) ? $d->least_price : $request->input('minloss');
            $d->max_price = empty($request->input('maxloss')) ? $d->name : $request->input('maxloss');
            $d->note = empty($request->input('note')) ? $d->note : $request->input('note');

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
            $d = Gravity::find($id);
            $d->visible = $d->visible == 1 ? 0 : 1;
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
        $rec = Gravity::find($id);
        Gate::authorize('isAdmin', Auth::user());
        try {
            if (Gate::allows('canGravityDelete', $rec)) {
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
