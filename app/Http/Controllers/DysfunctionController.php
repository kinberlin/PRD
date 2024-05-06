<?php

namespace App\Http\Controllers;

use App\Models\Dysfunction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
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
        //try {
        DB::beginTransaction();
        $dys = new Dysfunction();
        $dys->occur_date = $request->input('occur_date');
        $dys->enterprise = $request->input('enterprise');
        $dys->description = $request->input('description');
        $dys->emp_signaling = 'Test First'; //Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $dys->emp_matricule = 'YU14AS'; //Auth::user()->matricule;
        $dys->emp_email = 't@t.t'; //Auth::user()->email;
        if ($request->hasFile('group-a')) {
            $totalSize = 0;
            throw new Exception("Error Processing Request", 1);
            foreach ($request->file('group-a') as $index => $fileData) {
                $file = $fileData['pj'];
                $totalSize += $file->getSize();
            }
            if ($totalSize > 5 * 1024 * 1024) { // Convert 5MB to bytes
                throw new Exception("La taille des fichiers soumis sont supérieures a 5mb. Vous avez soumis en tous ."($totalSize / (1024 * 1024)) . ' mb', 1);
            }
            $filePaths = [];
            foreach ($request->file('group-a') as $index => $fileData) {
                $file = $fileData['pj'];
                $fileName = time() . '_' .$file->getClientOriginalName();
                $file->storeAs('uploads/dysfunctions', $fileName);
                
                $file->move(public_path('uploads/dysfunctions'), $fileName);
                $path= asset('uploads/dysfunctions/' . $fileName);
                $filePaths[] = $path;
            }
            $dys->pj = json_encode($filePaths);
        }
        //$dys->save();
        DB::commit();
        return redirect()->back()->with('error', "Merci d'avoir fait ce signalement. Nous le traiterons dans les plus bref délais.");
        /*} catch (Throwable $th) {

    return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
    }*/
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $dys = new Dysfunction();
            $dys->occur_date = $request->input('occur_date');
            $dys->enterprise = $request->input('enterprise');
            $dys->description = $request->input('description');
            $dys->emp_signaling = Auth::user()->firstname . ' ' . Auth::user()->lastname;
            $dys->emp_matricule = Auth::user()->matricule;
            $dys->emp_email = Auth::user()->email;
            $dys->save();
            DB::commit();
            return redirect()->back()->with('error', "Insertions terminées avec succes");
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
