<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Invites;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class InvitationController extends Controller
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
        // try {
        DB::beginTransaction();
        $data = new Invitation();
        $data->object = $request->input('object');
        $data->dysfonction = $request->input('dysfonction');
        $data->motif = $request->input('motif');
        $data->dates = $request->input('dates');
        $data->place = $request->input('place');
        $data->link = $request->input('link');
        $data->description  = $request->input('description');
        $i_v = $request->input('internal_invites', []);
        $internal_invites = [];
        foreach ($i_v as $option) {
            $internal_invites[] = new Invites( Users::where('email', $option)->get()->first());
        }
        $data->internal_invites = json_encode($internal_invites);
            $ext_u = [];
        for ($i = 0; $i < count($request->extuser); $i++) {
            // Create a new Person object for each row and add it to the array
            $ext_u[] =  $request->extuser[$i];
        }
        $data->external_invites = json_encode($ext_u);
        dd($data);
        //$data->save();
        DB::commit();
        return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
        /* } catch (Throwable $th) {
    return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
    }*/
    }

    /**
     * Display the specified resource.
     */
    public function show(Invitation $invitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        //
    }
}
