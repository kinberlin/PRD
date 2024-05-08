<?php

namespace App\Http\Controllers;

use App\Models\Dysfunction;
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
            $dys->concern_processes = $request->input('occur_date');
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
