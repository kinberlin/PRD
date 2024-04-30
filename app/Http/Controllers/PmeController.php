<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Enterprise;
use App\Models\Notification;
use App\Models\Pme;
use App\Models\PublicHolliday;
use App\Models\Service;
use App\Models\TypePme;
use App\Models\Users;
use App\Models\Validation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PmeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $obj = null;
        try {
            //Create PME and accessory infos
            $validator = $this->getMyManager(Auth::user()->id);
            if ($validator == null) {
                throw new Exception("Impossible d'initier une demande de Permission. Probleme d'hierachisation. Impossible de trouver votre responsable hierachique. Il se pourrait qu'il n'a pas été renseigner dans le systeme", 1);
            }
            DB::beginTransaction();
            $obj = new Pme();
            $obj->enterprise = Auth::user()->enterprise;
            $obj->department = Auth::user()->department;
            $obj->user = Auth::user()->id;
            $obj->type = $request->input('type');
            $obj->duration = $request->input('duration');
            $obj->begin = $request->input('begin');
            $obj->end = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('end'))->format('Y-m-d H:i:s');
            $obj->description = $request->input('description');
            $obj->email = Auth::user()->email;
            $td = TypePme::where('id', $request->input('type'))->get()->first();
            $rest =   intval($td->duration) - intval($request->input('duration'));

            if ($rest < 0) {
                throw new Exception("La durée que vous avez entrée ne peut être considérer pour cette permission. " . $rest, 1);
            }
            $obj->rest = $rest;
            $obj->matricule = Auth::user()->matricule;
            $obj->name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
            if ($request->hasFile('pj')) {
                $pj = $request->file('pj');
                $filename = time() . '_' . $pj->getClientOriginalName();
                $pj->move(public_path('/uploads/pme/pj'), $filename);
                $obj->pj = asset('/uploads/pme/pj/' . $filename);
            }
            $obj->save();
            DB::commit();
            if ($request->has('ferier')) {

                if ($request->input('ferier') && is_array($request->input('ferier'))) {
                    $ferier = $request->input('ferier');
                    foreach ($ferier as $value) {
                        $fer = new PublicHolliday();
                        $fer->pme = $obj->id;
                        $fer->dates = $value;
                        $fer->save();
                    }
                }
            }

            //Create validation request
            $val = new Validation();
            $val->pme = $obj->id;
            $val->validator = $validator;
            $val->save();
            //Create Notification request
            $notif = new Notification();
            $notif->title = "Nouvelle demande de PE";
            $notif->message = "Besoin d'une PE pour motif : " . TypePme::where('id', $obj->type)->get()->first()->name;
            $notif->receiver = $validator;
            $notif->sender = $obj->user;
            $notif->save();

            return redirect()->back()->with('error', "Votre demande a été soumis avec succes.");
        } catch (Throwable $th) {
            if ($obj != null) {
                $pme = Pme::find($obj->id);
                if ($pme != null) {
                    $notif = Notification::where('pme', $pme->id)->get()->first();
                    $val = Validation::where('pme', $pme->id)->get();
                    $ferier = PublicHolliday::where('pme', $pme->id)->get();
                    if ($notif != null) {
                        $notif->delete();
                    }
                    if ($val != null) {
                        foreach ($val as $v) {
                            $v->delete();
                        }
                    }
                    if ($ferier != null) {
                        foreach ($ferier as $f) {
                            $f->delete();
                        }
                    }
                    $pme->delete();
                }
            }
            return redirect()->back()->with('error', "Le processus ne s'est pas bien terminer. La demande de validation n'a peut être pas été inité. L'erreur indique : " . $th->getMessage());
        }
    }
    public function ostore(Request $request)
    {
        try {
            $validator = $this->getMyManager(Auth::user()->id);
            DB::beginTransaction();
            $obj = new Pme();
            $obj->enterprise = Auth::user()->enterprise;
            $obj->department = Auth::user()->department;
            $obj->type = $request->input('type');
            $obj->duration = $request->input('duration');
            $obj->begin = $request->input('begin');
            $obj->end = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('end'))->format('Y-m-d H:i:s');
            $obj->description = $request->input('description');
            $obj->email = $request->input('email');
            $rest =  TypePme::find($request->input('type'))->value('duration') - $request->input('duration');
            if ($rest < 0) {
                throw new Exception("La durée que vous avez entrée ne peut être considérer pour cette permission.", 1);
            }
            $obj->rest = $rest;
            $obj->matricule = $request->input('matricule');
            $obj->name = $request->input('fullname');
            if ($request->hasFile('pj')) {
                $pj = $request->file('pj');
                $filename = time() . '_' . $pj->getClientOriginalName();
                $pj->move(public_path('/uploads/pme/pj'), $filename);
                $obj->pj = asset('/uploads/pme/pj/' . $filename);
            }
            $obj->user = Auth::user()->id;
            $obj->save();
            DB::commit();
            if ($request->has('ferier')) {
                if ($request->input('ferier') && is_array($request->input('ferier'))) {
                    $ferier = $request->input('ferier');
                    foreach ($ferier as $value) {
                        $fer = new PublicHolliday();
                        $fer->pme = $obj->id;
                        $fer->dates = $value;
                        $fer->save();
                    }
                }
            }
            //Create validation request
            $val = new Validation();
            $val->pme = $obj->id;
            $val->validator = $validator;
            $val->save();
            //Create Notification request
            $notif = new Notification();
            $notif->title = "Nouvelle demande de PE";
            $notif->message = "Besoin d'une PE pour motif : " . TypePme::where('id', $obj->type)->get()->first()->name;
            $notif->receiver = $validator;
            $notif->sender = $obj->user;
            $notif->save();
            return redirect()->back()->with('error', "Votre demande a été soumis avec succes.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function vstore($id, $dec)
    {
        try {
            $val = Validation::find($id);
            if ($val == null) {
                throw new Exception("Nous n'arrivons pas a traiter votre demande de validation ❌.", 1);
            }
            if ($val->pme == null) {
                throw new Exception("Nous n'arrivons pas a traiter votre demande de validation ❌.", 1);
            }
            $pme = Pme::find($val->pme);
            if ($pme == null) {
                throw new Exception("Nous ne retrouvons pas la Permission concerner. Ceci peut être dû a une modification externe du fournisseur de donnée de WorkWave.", 1);
            }
            if ($dec == 1) {
                throw new Exception("Cette requête est erroné et impossible a traiter ❌.", 1);
            }
            if ($dec == 3) {
                $notif1 = new Notification();
                $notif1->title = "PE Rejeté";
                $notif1->message = "J'ai Rejeté le PE dont le motif était : " . TypePme::where('id', Pme::where('id', $val->pme)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pme;
                $notif1->receiver = $pme->user;
                $notif1->sender = Auth::user()->id;
                $notif1->save();
                $pme->status = $dec;
                $val->status = $dec;
                $pme->save();
                $val->save();
            } elseif ($dec == 2) {
                $val = Validation::find($id);
                //Create validation request
                if (Auth::user()->id == Department::where('id', Users::where('id', $pme->user)->get()->first()->department)->get()->first()->manager) {
                    //User is department manager
                    if (Service::where('department', Auth::user()->department)->where('manager', $pme->user)->get()->first() != null) {
                        //Check if request was emitted by a service Manager
                        $validator = Enterprise::where('id', Auth::user()->enterprise)->get()->first()->manager;
                        if ($validator == null) {
                            throw new Exception("Impossible d'initier une demande de Permission. Probleme d'hierachisation. Impossible de trouver votre responsable hierachique. Il se pourrait qu'il n'a pas été renseigner dans le systeme ", 1);
                        }
                        $vals = new Validation();
                        $vals->pme = $val->pme;
                        $vals->validator = $validator;
                        $vals->save();
                        //Create Notification request
                        $notif = new Notification();
                        $notif->title = "Nouvelle demande de PE à valider";
                        $notif->message = "J'ai validé le PE dont le motif était : " . TypePme::where('id', Pme::where('id', $val->pme)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pme;
                        $notif->receiver = $validator;
                        $notif->sender = Auth::user()->id;
                        $notif->save();
                        $val->status = $dec;
                        $val->save();
                    } else {

                        $val->status = $dec;
                        $val->save();
                        $pme->status = 4;
                        $pme->save();
                        if (Users::find($pme->user) != null) {
                            $notif1 = new Notification();
                            $notif1->title = "PE Validé";
                            $notif1->message = "J'ai validé le PE dont le motif était : " . TypePme::where('id', Pme::where('id', $val->pme)->get()->first()->type)->get()->first()->value('name') . '. Dont l\'ID est ' . $val->pme;
                            $notif1->receiver = $pme->user;
                            $notif1->sender = Auth::user()->id;
                            $notif1->save();
                        }
                    }
                } elseif (Auth::user()->id == Enterprise::where('id', Auth::user()->enterprise)->get()->first()->manager) {
                    //Create Notification request
                    //User is a company manager
                    if ($dec == 2 || $dec == 3 || $dec == 4) {
                        $val->status = $dec;
                        $val->save();
                        if ($dec == 2) {
                            $pme->status = 4;
                        }
                        $pme->save();
                        if (Users::find($pme->user) != null) {
                            $notif1 = new Notification();
                            $notif1->title = "PE Validé";
                            $notif1->message = "J'ai validé votre PE dont le motif était : " . TypePme::where('id', Pme::where('id', $val->pme)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pme;
                            $notif1->receiver = $pme->user;
                            $notif1->sender = Auth::user()->id;
                            $notif1->save();
                        }
                    } else {
                        throw new Exception("WorkWave n'arrive pas à valider cette demande 😞😞..", 1);
                    }
                } elseif (Service::where('manager', Auth::user()->id)->get()->first() != null) {
                    //Create Notification request
                    if ($dec == 2 || $dec == 3 || $dec == 4) {
                        $validator = $this->getMyManager(Auth::user()->id);
                        if ($validator == null) {
                            throw new Exception("Impossible d'initier une demande de Permission. Probleme d'hierachisation. Impossible de trouver votre responsable hierachique. Il se pourrait qu'il n'a pas été renseigner dans le systeme ", 1);
                        }
                        $val->status = $dec;
                        $val->save();

                        $vals = new Validation();
                        $vals->pme = $val->pme;
                        $vals->validator = $validator;
                        $vals->save();
                        //Create Notification request
                        $notif = new Notification();
                        $notif->title = "Nouvelle demande de PE à valider";
                        $notif->message = "J'ai validé le PE dont le motif était : " . TypePme::where('id', Pme::where('id', $val->pme)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pme;
                        $notif->receiver = $validator;
                        $notif->sender = Auth::user()->id;
                        $notif->save();
                    } else {
                        throw new Exception("WorkWave n'arrive pas à valider cette demande 😞😞..", 1);
                    }
                } else {
                    throw new Exception("WorkWave n'arrive pas à valider cette demande 😞😞..", 1);
                }
            } else {
                throw new Exception("Cette requête est erroné et impossible a traiter ❌❌.", 1);
            }

            return redirect()->back()->with('error', "Opération terminé avec succes.✅");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    public function rejstore(Request $request, $id)
    {
        try {
            $val = Validation::find($id);
            if ($val == null) {
                throw new Exception("Nous n'arrivons pas a traiter votre demande de validation ❌.", 1);
            }
            if ($val->pme == null) {
                throw new Exception("Nous n'arrivons pas a traiter votre demande de validation ❌.", 1);
            }
            $pme = Pme::find($val->pme);
            if ($pme == null) {
                throw new Exception("Nous ne retrouvons pas la Permission concerner. Ceci peut être dû a une modification externe du fournisseur de donnée de WorkWave.", 1);
            }

            $notif1 = new Notification();
            $notif1->title = "PE Rejeté";
            $notif1->message = "J'ai Rejeté le PE dont le motif était : " . TypePme::where('id', Pme::where('id', $val->pme)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pme;
            $notif1->receiver = $pme->user;
            $notif1->sender = Auth::user()->id;
            $notif1->save();
            $pme->status = 3;
            $val->status = 3;
            $val->reasons = $request->has('reasons') ? $request->input('reasons') : null;
            $pme->save();
            $val->save();
            return redirect()->back()->with('error', "Opération terminé avec succes.✅");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Pme $pme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pme $pme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pme $pme)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pme $pme)
    {
        //
    }

    public function getMyManager($id)
    {
        $user = Users::find($id);
        $depM = Department::where('manager', $id)->get()->first();
        $manager = null;

        //check if he is an enterprise manager
        $ent = Enterprise::where('id', $user->enterprise)->where('manager', $user->id)->get()->first();
        if ($ent != null) {
            $manager = $ent->manager;
        }
        //Check if he is a department manager
        elseif ($depM != null) {
            //User is probably a department manager
            //$ent = Enterprise::where('id', $user->enterprise)->get()->first();
            $manager = $depM->manager;
        } elseif ($user->service != null) {
            $serM = Service::where('department', $user->department)
                ->where('manager', $user->id)->get()->first();
            $ser_ = Service::find($user->service);
            if ($serM != null) {
                //User is service manager
                if ($serM->level == 0) {
                    $manager = Department::where('id', $serM->department)->get()->first()->manager;
                } else {
                    $pser = Service::find($ser_->parent);
                    $manager = $pser->manager;
                }
            } elseif ($serM == null) {
                //User is not  service manager.
                $manager = $ser_->manager;
            }
        }
        return $manager;
    }
}
