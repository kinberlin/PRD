<?php

namespace App\Http\Controllers;

use App\Imports\EnterpriseImport;
use App\Models\Department;
use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Service;
use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
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
     * Update the visibility attribute of this resource in storage.
     */
    public function visible(Request $request, $id)
    {
        try {
            Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $d = Enterprise::find($id);
            $d->visible = $request->boolean('visibility');
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
        try {
            Gate::authorize('isAdmin', Auth::user());
            DB::beginTransaction();
            $rec = Enterprise::find($id);
            $rec->dysfunctions->each(function ($dysfunction) {
                // Soft delete related dysfunctions
                $dysfunction->tasks->each(function ($task) {
                    // Soft delete related tasks
                    $task->evaluations->each(function ($evaluations) {
                        // Soft delete related evaluations
                        $evaluations->delete();
                    });
                    $task->delete();
                });
                $dysfunction->invitations->each(function ($invitation) {
                    // Soft delete related invitations
                    $invitation->delete();
                });
                $dysfunction->delete();
            });
            $rec->sites->each(function ($si) {
                // Soft delete related sites
                $si->delete();
            });
            $rec->authorisationRqs->each(function ($ar) {
                // Soft delete related authorisations
                $ar->delete();
            });
            $rec->departments->each(function ($department) {
                // Soft delete related departments
                $department->users->each(function ($user) {
                    // Soft delete related users
                    $user->dysfunctions->each(function ($dysfunction) {
                        // Soft delete related dysfunctions
                        $dysfunction->tasks()->each(function ($task) {
                            // Soft delete related tasks
                            $task->evaluations->each(function ($evaluations) {
                                // Soft delete related evaluations
                                $evaluations->delete();
                            });
                            $task->delete();
                        });
                        $dysfunction->invitations->each(function ($invitation) {
                            // Soft delete related invitations
                            $invitation->delete();
                        });
                        $dysfunction->delete();
                    });
                    $user->authorisationRqs->each(function ($arq) {
                        // Soft delete related authorisations
                        $arq->delete();
                    });
                    $user->authorisationPilotes->each(function ($ap) {
                        // Soft delete related authorisations
                        $ap->delete();
                    });
                    $user->delete();
                });
                // Soft delete the product itself
                $department->delete();
            });
            $rec->delete();
            DB::commit();
            return redirect()->back()->with('error', "Cette entreprise a été ajouté dans la corbeille.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Echec lors de la surpression. L'erreur indique : " . $th->getMessage());
        }
    }
    // Restore a single soft-deleted enterprise by ID
    public function restore(Request $request, $id)
    {
        Gate::authorize('isAdmin', Auth::user());
        DB::beginTransaction();
        $rec = Enterprise::onlyTrashed()->find($id);

        if ($rec) {
            // Restore related departments and users
            $rec->departments()->withTrashed()->get()->each(function ($department) {
                $department->restore();
                $department->users()->withTrashed()->get()->each(function ($user) {
                    $user->restore();
                    // Restore related dysfunctions
                    $user->dysfunctions()->withTrashed()->get()->each(function ($dysfunction) {
                        $dysfunction->restore();
                        // Restore related tasks
                        $dysfunction->tasks()->withTrashed()->get()->each(function ($task) {
                            $task->restore();
                            // Restore related evaluations
                            $task->evaluations()->withTrashed()->get()->each(function ($evaluation) {
                                $evaluation->restore();
                            });
                        });
                        // Restore related invitations
                        $dysfunction->invitations()->withTrashed()->get()->each(function ($invitation) {
                            $invitation->restore();
                        });
                    });
                    // Restore related authorisations
                    $user->authorisationRqs()->withTrashed()->get()->each(function ($arq) {
                        $arq->restore();
                    });
                    $user->authorisationPilotes()->withTrashed()->get()->each(function ($ap) {
                        $ap->restore();
                    });
                });
            });

            // Restore related dysfunctions and their tasks, evaluations, and invitations directly under $rec
            $rec->dysfunctions()->withTrashed()->get()->each(function ($dysfunction) {
                $dysfunction->restore();

                // Restore related tasks for each dysfunction
                $dysfunction->tasks()->withTrashed()->get()->each(function ($task) {
                    $task->restore();

                    // Restore related evaluations for each task
                    $task->evaluations()->withTrashed()->get()->each(function ($evaluation) {
                        $evaluation->restore();
                    });
                });

                // Restore related invitations for each dysfunction
                $dysfunction->invitations()->withTrashed()->get()->each(function ($invitation) {
                    $invitation->restore();
                });
            });
            $rec->authorisationRqs()->withTrashed()->get()->each(function ($ar) {
                // Soft restore related authorisations
                $ar->restore();
            });
            $rec->sites()->withTrashed()->get()->each(function ($si) {
                // restore related sites
                $si->restore();
            });
            // Restore the main record itself first
            $rec->restore();
            DB::commit();
            return redirect()->back()->with('error', "L'entreprise et toutes les données qui en dépendent ont bien été restaurées.");
        }

        return redirect()->back()->with('error', "L'élément à restaurer n'a peut-être pas pu être restauré.");
    }

        public function import(Request $request)
    {
        Gate::authorize('isAdmin', Auth::user());
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            Excel::import(new EnterpriseImport, $request->file('file'));
            return redirect()->back()->with('error', 'Insertions terminées avec succes!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            session()->flash('file', $failures);
            return redirect()->back()->with('error', "Une erreur s'est produite.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Une erreur s'est produite.L'erreur indique : " . $th->getMessage());
        }

    }
}
