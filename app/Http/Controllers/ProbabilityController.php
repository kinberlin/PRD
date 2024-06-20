<?php

namespace App\Http\Controllers;

use App\Models\Probability;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class ProbabilityController extends Controller
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
                Probability::create([
                    //'id' => $id,
                    'name' => $row[1],
                    'note' =>  $row[2],
                    'description' => $row[3],
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
    public function show(Probability $probability)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Probability $probability)
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
            $d = Probability::find($id);
            $validatedData = $request->validate([
                'note' => 'required|numeric|min:1',
            ]);
            $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
            $d->description = empty($request->input('minloss')) ? $d->least_price : $request->input('minloss');
            $d->note = empty($request->input('note')) ? $d->note : $request->input('note');
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
    public function destroy(Probability $probability)
    {
        //
    }
}
