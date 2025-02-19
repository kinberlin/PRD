<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class SiteController extends Controller
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
                if (Gate::allows('isAdmin', Auth::user()) || Gate::allows('isEnterpriseRQ', [Enterprise::find($row[1])])) {
                    $name = $row[2];
                    $enterprise = $row[1];
                    $location = $row[3];
                    $site = new Site();
                    $site->name = $name;
                    $site->enterprise = $enterprise;
                    $site->location = $location;
                    $site->save();
                } else {
                    throw new Exception("Arrêt inattendu du processus suite a une tentative d'insertion/de manipulation de donnée sans detention des privileges requis pour l'operation.", 501);
                }
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
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $d = Site::find($id);
            if (Gate::allows('isEnterpriseRQ', [Enterprise::find($request->input('enterprise'))]) || Gate::allows('isAdmin', Auth::user())) {
                DB::beginTransaction();
                $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
                $d->location = empty($request->input('location')) ? $d->location : $request->input('location');
                $d->enterprise = empty($request->input('enterprise')) ? $d->enterprise : $request->input('enterprise');
                $d->save();
                DB::commit();
                return redirect()->back()->with('error', "Mis a Jour effectuer avec succes. ");
            } else {
                throw new Exception("Arrêt inattendu du processus suite a une tentative de mise a jour/de manipulation de donnée sans detention des privileges requis pour l'operation.", 501);
            }
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
            //Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $d = Site::find($id);
            if (Gate::allows('isEnterpriseRQ', [Enterprise::find($d->enterprise)]) || Gate::allows('isAdmin', Auth::user())) {
                $d->visible = $request->boolean('visibility');
                $d->save();
                DB::commit();
            } else {
                throw new Exception("Arrêt inattendu du processus suite a une tentative de mise a jour/de manipulation de donnée sans detention des privileges requis pour l'operation.", 501);
            }
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
        $rec = Site::find($id);
        //Gate::authorize('isAdmin', Auth::user());
        try {
            if (Gate::allows('canSiteDelete', $rec)) {
                if (Gate::allows('isEnterpriseRQ', [Enterprise::find($rec->enterprise)]) || Gate::allows('isAdmin', Auth::user())) {
                    DB::beginTransaction();
                    $rec->forceDelete();
                    DB::commit();
                    return redirect()->back()->with('error', "Ce site a été supprimé avec succès.");
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
