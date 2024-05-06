<?php

namespace App\Http\Controllers;

use App\Models\Dysfunction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $totalSize = 0;
        $filePaths = [];
        $files = $request->files->all();
        dd($files);
        //dd($files);
        foreach ($files as $inputName => $fileArray) {
            // Check if files were uploaded for the current input field
            if (is_array($fileArray)) {
                foreach ($fileArray as $file) {
                    if (is_array($file)) {
                        foreach ($file as $f) {
                            // Check if $file is an UploadedFile instance
                            if ($f instanceof UploadedFile) {
                                $totalSize += $f->getSize(); // Get the size of each file in bytes
                           }
                        }
                    }
                }
            }
        }
        $totalSize = ($totalSize / (1024 * 1024));
        dd($totalSize);
        /*foreach ($request->file('group-a') as $index => $fileData) {

        dd($request->file('group-a'));
        if ($fileData->isValid()) {
        $file = $fileData;
        $totalSize += $file->getSize();
        }
        }
        if ($totalSize > 5 * 1024 * 1024) { // Convert 5MB to bytes
        throw new Exception("La taille des fichiers soumis sont supérieures a 5mb. Vous avez soumis en tous ."($totalSize / (1024 * 1024)) . ' mb', 1);
        }
        foreach ($request->file('group-a') as $index => $file) {
        if ($file->isValid()) {
        $filename = 'file_' . $index . '_' . time() . '.' . $file->getClientOriginalName();

        // Upload the file to the server
        $file->storeAs('uploads/dysfunctions', $filename);

        // Store the file route in an array
        $filePaths[] = 'uploads/dysfunctions/' . $filename;
        }
        }

        /*foreach ($request->file('group-a') as $index => $fileData) {
        $file = $fileData['pj'];
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('uploads/dysfunctions', $fileName);

        $file->move(public_path('uploads/dysfunctions'), $fileName);
        $path = asset('uploads/dysfunctions/' . $fileName);
        $filePaths[] = $path;
        }*/
        $dys->pj = json_encode($filePaths);
        dd($dys);
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
