<?php

namespace App\Http\Controllers;

use App\Models\AuthorisationPilote;
use App\Models\process;
use App\Models\Processes;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AuthorisationPiloteController extends Controller
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
            $data = new AuthorisationPilote();
            $allprocs = Processes::all();
            $user = Users::find($request->input('user'));
            $procs = $allprocs->where('id', $request->input('process'))->first();
            if ($user == null || $procs == null) {
                throw new Exception("Nous ne trouvons pas la ressource utilisateur/processus correspondante.", 404);
            }
            if ($request->input('interim') == 0) {
                $isPrincipal = AuthorisationPilote::where('user', $user->id)->where('interim', 0)->get()->first();
                if (!is_null($isPrincipal)) {
                    throw new Exception($user->firstname . " ne peut pas être Pilote principal dans le Processus : " . $procs->name . ". Car il est déja le pilote principal du processus : " . $allprocs->where('id', $isPrincipal->process)->first()->name, 501);
                }
                $currentPrincipal = AuthorisationPilote::where('process', $procs->id)->where('interim', 0)->get()->first();
                if (!is_null($currentPrincipal)) {
                    throw new Exception($user->firstname . " ne peut pas être le Pilote principal dans le processus : "  . $procs->name. ". Car le processus : " . $allprocs->where('id', $currentPrincipal->process)->first()->name .' a déja un Pilote pincipal.', 501);
                }
            }
            $exist = AuthorisationPilote::where('user', $user->id)->where('process', $procs->id)->get()->first();
            if (!is_null($exist)) {
                $exist->interim = $request->input('interim');
                $exist->save();
            } else {
                $data->user = $user->id;
                $data->process = $procs->id;
                $data->interim = $request->input('interim');
                $data->save();
            }
            DB::commit();
            return redirect()->back()->with('error', "Authorisation ajouté avec succès");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AuthorisationPilote $authorisationPilote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthorisationPilote $authorisationPilote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuthorisationPilote $authorisationPilote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $data = AuthorisationPilote::withTrashed()->find($id);
            if ($data == null) {
                throw new Exception("Nous ne trouvons pas la ressource demandé.", 404);
            }
            $data->forceDelete();
            DB::commit();
            return redirect()->back()->with('error', "Authorisation supprimé avec succès");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
}

