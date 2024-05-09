<?php

namespace App\Http\Controllers;

use App\Models\Correction;
use App\Models\Dysfunction;
use App\Models\Status;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class DysfunctionController extends Controller
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

    public function init(Request $request)
    {
        try {
            DB::beginTransaction();
            $dys = new Dysfunction();
            $dys->occur_date = $request->input('occur_date');
            $dys->enterprise = $request->input('enterprise');
            $dys->site = $request->input('site');
            $dys->description = $request->input('description');
            $dys->emp_signaling = 'Test First'; //Auth::user()->firstname . ' ' . Auth::user()->lastname;
            $dys->emp_matricule = 'YU14AS'; //Auth::user()->matricule;
            $dys->emp_email = 't@t.t'; //Auth::user()->email;
            $urls = [];
            foreach ($request->file('group-a') as $key => $fileData) {
                if (isset($fileData['pj']) && $fileData['pj']->isValid()) {
                    $pj = $fileData['pj'];
                    $filename = time() . '_' . $pj->getClientOriginalName();
                    $pj->move(public_path('/uploads/dysfonction'), $filename);
                    $url = asset('/uploads/dysfonction/' . $filename);
                    $urls[] = $url;
                }
            }
            $dys->pj = json_encode($urls);
            $dys->save();
            DB::commit();
            return redirect()->back()->with('error', "Merci d'avoir fait ce signalement. Nous le traiterons dans les plus bref délais.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function store(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("Impossible de trouver la ressource demandée.", 404);
            }
            $dys->impact_processes = json_encode($request->input('impact_processes'));
            $dys->concern_processes = json_encode($request->input('concern_processes'));
            $dys->gravity = $request->input('gravity');
            $dys->probability = $request->input('probability');
            $dys->cause = empty($request->input('cause')) ? null : $request->input('cause');
            if ($dys->status == 1) {$dys->status = 2;}
            $dys->save();
            DB::commit();
            return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function cancel($id)
    {
        try {
            DB::beginTransaction();
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("Error Processing Request", 1);
            }
            $dys->status = 3;

            if ($dys->status == 1) {$dys->status = 2;}
            $dys->save();
            DB::commit();
            return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function action(Request $request, $id)
    {
       // try {
            DB::beginTransaction();
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("Impossible de trouver la ressource demandée.", 404);
            }
            $corrections = [];
            for ($i = 0; $i < count($request->user); $i++) {
                // Create a new Person object for each row and add it to the array
                $corrections[] = new Correction(
                    $request->action[$i],
                    $request->department[$i],
                    $request->user[$i],
                    $request->delay[$i]
                );
            }
            $corrective_acts = json_encode($corrections);
            $dys->corrective_acts = $corrective_acts;
            //dd($corrective_acts);
            if ($dys->status == 1) {$dys->status = 2;}
            $dys->save();
            DB::commit();
            return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
       /* } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }*/
    }
    /**
     * Display the specified resource.
     */
    public function show(Dysfunction $dysfunction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dysfunction $dysfunction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dysfunction $dysfunction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dysfunction $dysfunction)
    {
        //
    }
}
