<?php

namespace App\Http\Controllers;

use App\Models\AuthorisationRq;
use App\Models\Enterprise;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthorisationRqController extends Controller
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
            $data = new AuthorisationRq();
            $allents = Enterprise::all();
            $user = Users::find($request->input('user'));
            $ents = $allents->where('id', $request->input('enterprise'))->first();
            if ($user == null || $ents == null) {
                throw new Exception("Nous ne trouvons pas la ressource utilisateur/entreprise correspondant.", 404);
            }
            if ($request->input('interim') == 0) {
                $isPrincipal = AuthorisationRq::where('user', $user->id)->where('interim', 0)->get()->first();
                if (!is_null($isPrincipal)) {
                    throw new Exception($user->firstname . " ne peut pas être RQ principal dans la filliale/entreprise : "  . $ents->name. ". Car il est déja RQ principal dans la filiale : ". $allents->where('id', $isPrincipal->enterprise)->first()->name , 501);
                }
                $currentPrincipal = AuthorisationRq::where('enterprise', $ents->id)->where('interim', 0)->get()->first();
                if (!is_null($currentPrincipal)) {
                    throw new Exception($user->firstname . " ne peut pas être RQ principal dans la filliale/entreprise : " . $ents->name.". Car la filiale : " . $ents->name.' a déja un RQ pincipal.'. $allents->where('id', $currentPrincipal->enterprise)->first()->name , 501);
                }
            }
            $exist = AuthorisationRq::where('user', $user->id)->where('enterprise', $ents->id)->get()->first();
            if (!is_null($exist)) {
                $exist->interim = $request->input('interim');
                $exist->save();
            } else {
                $data->user = $user->id;
                $data->enterprise = $ents->id;
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
    public function show(AuthorisationRq $authorisationRq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthorisationRq $authorisationRq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuthorisationRq $authorisationRq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $data = AuthorisationRq::withTrashed()->find($id);
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
