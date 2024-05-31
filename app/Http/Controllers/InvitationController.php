<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Invites;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = json_encode(Invitation::all());
        //dd($data);
        return response()->json([
            "events" => $data,
        ]);
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
            $data = new Invitation();
            $data->rq = 'Test RQ Matricule 35258'; //waiting Auth
            $data->object = $request->input('object');
            $data->dysfonction = $request->input('dysfunction');
            $data->motif = $request->input('motif');
            $data->dates = $request->input('dates');
            $data->place = $request->input('place');
            $data->link = isEmpty($request->input('link')) ? null : $request->input('link');
            $data->description = $request->input('description');
            $data->begin = $request->input('begin');
            $data->end = $request->input('end');
            $i_v = $request->input('internal_invites', []);
            $internal_invites = [];
            if (!empty($i_v)) {foreach ($i_v as $option) {
                $internal_invites[] = new Invites(Users::find($option));
            }}
            $data->internal_invites = json_encode($internal_invites);
            $ext_u = [];
            if ($request->has('extuser') && !empty($request->extuser)) {
                for ($i = 0; $i < count($request->extuser); $i++) {
                    // Create a new Person object for each row and add it to the array
                    $ext_u[] = $request->extuser[$i];
                }
            }
            $data->external_invites = json_encode($ext_u);
            $data->save();
            DB::commit();
            return redirect()->back()->with('error', "La réunion a été créer avec succes.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = Invitation::where('id', $id)->get();
            if ($data->isEmpty()) {
                throw new Exception('Nous ne trouvons pas cette invitation: ' . $id, 404);
            }
            return response()->json([
                "data" => json_encode($data),
            ]);
        } catch (\Exception $e) {
            // Return a failure response indicating an error occurred
            return response()->json(['error' => $e->getMessage()], 404);
        }
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
    public function update(Request $request, $id)
    {
        //try {
            DB::beginTransaction();
            $data = Invitation::find($id);
            if ($data == null) {
                throw new Exception("Impossible de trouver l'element a mettre a jour", 404);

            }
            if(Carbon::now() > $data->dates ){
                throw new Exception("Il n'est plus possible de modifier cette réunion. Elle a déja eu lieu.", 1);   
            }
            $data->rq = 'Test RQ Matricule 35258'; //waiting Auth
            $data->object = $request->has('object') ? $request->input('object') : $data->object;
            $data->dysfonction = $request->has('dysfunction') ? $request->input('dysfunction') : $data->dysfonction;
            $data->motif = $request->has('motif') ? $request->input('motif') : $data->motif;
            $data->dates = $request->has('dates') ? $request->input('dates') : $data->dates;
            $data->place = $request->has('place') ? $request->input('place') : $data->place;
            $data->link = isEmpty($request->has('link')) ? null : $request->input('link');
            $data->description = $request->has('description') ? $request->input('description') : $data->description;
            $data->begin = $request->has('begin') ? $request->input('begin') : $data->begin;
            $data->end = $request->has('end') ? $request->input('end') : $data->end;
            $i_v = $request->input('internal_invites', []);
            $internal_invites = [];
            if (!empty($i_v)) {foreach ($i_v as $option) {
                $internal_invites[] = new Invites(Users::find($option));
            }}
            $data->internal_invites = json_encode($internal_invites);
            $ext_u = [];
            if ($request->has('extuser') && !empty($request->extuser)) {
                for ($i = 0; $i < count($request->extuser); $i++) {
                    // Create a new Person object for each row and add it to the array
                    $ext_u[] = $request->extuser[$i];
                }
            }
            $data->external_invites = json_encode($ext_u);
            $data->save();
            DB::commit();
            return redirect()->back()->with('error', "La réunion a été Mise a Jour avec succes.");
        /*} catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }*/
    }
    public function inviteConfirmation(Request $request)
    {
        //try {
            DB::beginTransaction();
            $data = Invitation::find($request->input('invitation'));
            if ($data == null) {
                throw new Exception("Impossible de trouver l'element a mettre a jour", 404);
            }
            if($request->input('decision') == 'Accept'){

            }elseif($request->input('decision') == 'Reject'){

            }
            if(Carbon::now() > $data->dates ){
                throw new Exception("Il n'est plus possible de modifier cette réunion. Elle a déja eu lieu.", 1);   
            }
            $data->rq = 'Test RQ Matricule 35258'; //waiting Auth
            $data->object = $request->has('object') ? $request->input('object') : $data->object;
            $data->dysfonction = $request->has('dysfunction') ? $request->input('dysfunction') : $data->dysfonction;
            $data->motif = $request->has('motif') ? $request->input('motif') : $data->motif;
            $data->dates = $request->has('dates') ? $request->input('dates') : $data->dates;
            $data->place = $request->has('place') ? $request->input('place') : $data->place;
            $data->link = isEmpty($request->has('link')) ? null : $request->input('link');
            $data->description = $request->has('description') ? $request->input('description') : $data->description;
            $data->begin = $request->has('begin') ? $request->input('begin') : $data->begin;
            $data->end = $request->has('end') ? $request->input('end') : $data->end;
            $i_v = $request->input('internal_invites', []);
            $internal_invites = [];
            if (!empty($i_v)) {foreach ($i_v as $option) {
                $internal_invites[] = new Invites(Users::find($option));
            }}
            $data->internal_invites = json_encode($internal_invites);
            $ext_u = [];
            if ($request->has('extuser') && !empty($request->extuser)) {
                for ($i = 0; $i < count($request->extuser); $i++) {
                    // Create a new Person object for each row and add it to the array
                    $ext_u[] = $request->extuser[$i];
                }
            }
            $data->external_invites = json_encode($ext_u);
            $data->save();
            DB::commit();
            return redirect()->back()->with('error', "La réunion a été Mise a Jour avec succes.");
        /*} catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }*/
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        //
    }
}
