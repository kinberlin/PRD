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
            $user = Users::find($request->input('user')); 
            $ents = Enterprise::find($request->input('enterprise'));
            if($user == null || $ents == null){
                throw new Exception("Nous ne trouvons pas la ressource utilisateur/entreprise correspondant.", 404);
            }
            $exist = AuthorisationRq::where('user', $user->id)->where('enterprise', $ents->id)->get()->first();
            if(!is_null($exist)){
                $exist->interim = $request->input('interim');
                $exist->save();
            }
            $data->user = $user->id;
            $data->enterprise = $ents->id;
            $data->interim = $request->input('interim');
            $data->save();
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
    public function destroy(AuthorisationRq $authorisationRq)
    {
        //
    }
}
