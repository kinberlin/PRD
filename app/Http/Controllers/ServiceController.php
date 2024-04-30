<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Enterprise;
use App\Models\Service;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class ServiceController extends Controller
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
            $service = new Service();
            $service->name = $request->input('name');
            $service->enterprise = $request->input('enterprise');
            $service->department = $request->input('department');
            $service->level = $request->input('level');
            if($request->input('level') > 0 && !$request->has('parent')){
                throw new Exception("Le service que vous voulez ajouter n'a pas de parent.
                 Désigner un parent pour ce service s'il y en a un ou alors, 
                 ajouter le service parent puis ajouter ce service ensuite.", 501);
                
            }
            $service->parent = $request->has('parent') ? $request->input('parent') : null;
            $service->vice_manager = null;
            $service->manager = null;
            $service->save();
            DB::commit();
            return redirect()->back()->with('error', "Insertions terminées avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $service = Service::find($id);
            $service->name = $request->has('name') ? $request->input('name') : $service->name;
            //$service->vice_manager = $request->has('vice_manager') ? $request->input('vice_manager') : $service->vice_manager;
            $service->manager = $request->has('manager') ? $request->input('manager') : $service->manager;
            $u = Users::find($request->input('manager'));
            $d = Department::find($service->department);
            $de = Enterprise::find($service->enterprise);
            $message = '';
            if ($de->manager == $u->id) {
                $de->manager = null;
                $message .= $u->firstname . ' ' . $u->lastname . " n'est plus manager de l'entreprise " . $de->name;
            }
            if ($u->service != null) {
                $s = Service::find($u->service);
                if ($s->manager == $u->id) {
                    $s->manager = null;
                    $message .= $u->firstname . ' ' . $u->lastname . " n'est plus manager du service " . $s->name;
                }
                $s->save();
            }
            if ($d->manager == $u->manager ) {
                    $d->manager = null;
                    $message .= $u->firstname . ' ' . $u->lastname . " n'est plus manager du service " . $s->name;
            }
            $u->service = $service->id;
            $u->save();
            $de->save();
            $d->save();
            $service->save();
            DB::commit();
            return redirect()->back()->with('error', "Service mis a jour avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
