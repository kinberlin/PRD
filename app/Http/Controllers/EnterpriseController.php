<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Enterprise;
use App\Models\Service;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class EnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function trashenterprise()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Enterprise::onlyTrashed()->get();
        return view('admin.trash.enterprise', compact('data'));
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
            $data = $request->input('data');
            if (!is_array($data)) {
                throw new Exception("Vous n'avez pas soumis de données a sauvegarder", 1);
            }
            foreach ($data as $row) {
                Enterprise::create([
                    //'id' => $id,
                    'name' => $row[1],
                    'surfix' => $row[2],
                ]);
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
    public function show(Enterprise $enterprise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enterprise $enterprise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $d = Enterprise::find($id);
            $d->name = empty($request->input('name')) ? $d->name : $request->input('name');
            $d->surfix = empty($request->input('surfix')) ? $d->surfix : $request->input('surfix');
            $d->save();
            DB::commit();
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
       // try {
            Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $rec = Enterprise::find($id);
            // Soft delete all products associated with the supplier
            if (!$rec) {
                dd('Rec not found');
            }

            
            dd( $rec->departments); // Confirm the collection of departments

            $rec->departments->each(function ($department) {
                dd($department);
                // Soft delete related departments
                $department->users()->each(function ($user) {
                    // Soft delete related users
                    $user->dysfunctions()->each(function ($dysfunction) {
                        // Soft delete related dysfunctions
                        $dysfunction->tasks()->each(function ($task) {
                            // Soft delete related tasks
                            $task->tasks()->each(function ($evaluations) {
                                // Soft delete related evaluations
                                $evaluations->delete();
                            });
                            $task->delete();
                        });
                        $dysfunction->invitations()->each(function ($invitation) {
                            // Soft delete related invitations
                            $invitation->delete();
                        });
                        $dysfunction->delete();
                    });
                    $user->delete();
                });
                // Soft delete the product itself
                $department->delete();
            });
            //$rec->delete();
            DB::commit();
            return redirect()->back()->with('error', "Cette entreprise a été ajouté dans la corbeille.");
        /*} catch (Throwable $th) {
            return redirect()->back()->with('error', "Echec lors de la surpression. L'erreur indique : " . $th->getMessage());
        }*/
    }
    // Restore a single soft-deleted enterprise by ID
    public function restore($id)
    {
        $ent = Enterprise::onlyTrashed()->find($id);

        if ($ent) {
            $ent->restore();
            return redirect()->back()->with('error', "L'entreprise et toutes les données qui en dépendent ont bien été restaurées.");
        }

        return redirect()->back()->with('error', "L'élément à restaurer n'a peut-être pas pu être restauré.");
    }
}
