<?php

namespace App\Http\Controllers;

use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Gravity;
use App\Models\Processes;
use App\Models\Site;
use App\Models\Status;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Throwable;

class RQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }
    public function dysfonction()
    {
        $ents = Enterprise::all();
        $site = Site::all();
        return view('rq/dysfonction', compact('ents', 'site'));
    }
    public function n1dysfonction()
    {
        return view('rq/n1dysfonction');
    }
    public function listeSignalement()
    {
        $data = Dysfunction::all();
        $status = Status::all();
        return view('rq/listesignalement', compact('data', 'status'));
    }
    public function planif()
    {
        $dys = Dysfunction::all();
        $users = Users::all();
        return view('rq/planifs', compact('dys','users'));
    }
    public function empty()
    {
        return view('employees/empty');
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
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("Nous ne trouvons pas la ressource auquel vous essayez d'accéder.", 1);
            }
            $status = Status::all();
            $processes = Processes::all();
            $ents = Enterprise::all();
            $site = Site::all();
            $gravity = Gravity::all();
            $data = $dys;
            return view('rq/infos', compact('data',
                'status',
                'processes',
                'ents',
                'site',
                'gravity'));
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Users $users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Users $users)
    {
        //
    }
}
