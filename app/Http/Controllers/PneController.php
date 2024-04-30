<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Enterprise;
use App\Models\Notification;
use App\Models\Pne;
use App\Models\PublicHolliday;
use App\Models\Service;
use App\Models\TypePne;
use App\Models\Users;
use App\Models\Validation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PneController extends Controller
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
            $PMC = new PmeController();
            $validator = $PMC->getMyManager(Auth::user()->id);
            if ($validator == null) {
                throw new Exception("Impossible d'initier une demande de Permission. Probleme d'hierachisation. Impossible de trouver votre responsable hierachique. Il se pourrait qu'il n'a pas été renseigner dans le systeme", 1);
            }
            DB::beginTransaction();
            $obj = new Pne();
            $obj->enterprise = Auth::user()->enterprise;
            $obj->department = Auth::user()->department;
            $obj->user = Auth::user()->id;
            $obj->type = $request->input('type');
            $obj->duration = $request->input('duration');
            $obj->begin = $request->input('begin');
            $obj->end = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('end'))->format('Y-m-d H:i:s');
            $obj->description = $request->input('description');
            $obj->email = Auth::user()->email;
            $obj->matricule = Auth::user()->matricule;
            $obj->name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
            if ($request->hasFile('pj')) {
                $pj = $request->file('pj');
                $filename = time() . '_' . $pj->getClientOriginalName();
                $pj->move(public_path('/uploads/pne/pj'), $filename);
                $obj->pj = asset('/uploads/pne/pj/' . $filename);
            }
            $obj->save();
            DB::commit();
            if ($request->has('ferier')) {
                if ($request->input('ferier') && is_array($request->input('ferier'))) {
                    $ferier = $request->input('ferier');
                    foreach ($ferier as $value) {
                        $fer = new PublicHolliday();
                        $fer->pne = $obj->id;
                        $fer->dates = $value;
                        $fer->save();
                    }
                }
            }
            //Create validation request
            $val = new Validation();
            $val->pne = $obj->id;
            $val->validator = $validator;
            $val->save();
            //Create Notification request
            $notif = new Notification();
            $notif->title = "Nouvelle demande de PNE";
            $notif->message = "Besoin d'un PNE dont le sole a débité est : " . TypePne::where('id', $obj->type)->get()->first()->name;
            $notif->receiver = $validator;
            $notif->sender = $obj->user;
            $notif->save();
            return redirect()->back()->with('error', "Votre demande a été soumis avec succes.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

    public function ostore(Request $request)
    {
        try {
            DB::beginTransaction();
            $PMC = new PmeController();
            $validator = $PMC->getMyManager(Auth::user()->id);
            $obj = new Pne();
            $obj->enterprise = Auth::user()->enterprise;
            $obj->department = Auth::user()->department;
            $obj->user = Auth::user()->id;
            $obj->type = $request->input('type');
            $obj->duration = $request->input('duration');
            $obj->begin = $request->input('begin');
            $obj->end = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('end'))->format('Y-m-d H:i:s');
            $obj->description = $request->input('description');
            $obj->email = $request->input('email');
            $obj->matricule = $request->input('matricule');
            $obj->name = $request->input('fullname');
            if ($request->hasFile('pj')) {
                $pj = $request->file('pj');
                $filename = time() . '_' . $pj->getClientOriginalName();
                $pj->move(public_path('/uploads/pne/pj'), $filename);
                $obj->pj = asset('/uploads/pne/pj/' . $filename);
            }
            $obj->save();
            DB::commit();
            if ($request->has('ferier')) {
                if ($request->input('ferier') && is_array($request->input('ferier'))) {
                    $ferier = $request->input('ferier');
                    foreach ($ferier as $value) {
                        $fer = new PublicHolliday();
                        $fer->pne = $obj->id;
                        $fer->dates = $value;
                        $fer->save();
                    }
                }
            }
            //Create validation request
            $val = new Validation();
            $val->pne = $obj->id;
            $val->validator = $validator;
            $val->save();
            //Create Notification request
            $notif = new Notification();
            $notif->title = "Nouvelle demande de PNE";
            $notif->message = "Besoin d'un PNE dont le sole a débité est : " . TypePne::where('id', $obj->type)->get()->first()->name;
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
        $PMC = new PmeController();
        $val = Validation::find($id);
        try {
            if ($val == null) {
                throw new Exception("Nous n'arrivons pas a traiter votre demande de validation ❌.", 1);
            }
            if ($val->pne == null) {
                throw new Exception("Nous n'arrivons pas a traiter votre demande de validation ❌.", 1);
            }
            $pne = Pne::find($val->pne);
            if ($pne == null) {
                throw new Exception("Nous ne retrouvons pas la Permission concerner. Ceci peut être dû a une modification externe du fournisseur de donnée de WorkWave.", 1);
            }
            if ($dec == 1) {
                throw new Exception("Cette requête est erroné et impossible a traiter ❌.", 1);
            }
            if ($dec == 3) {
                $notif1 = new Notification();
                $notif1->title = "PNE Rejeté";
                $notif1->message = "J'ai Rejeté le PNE dont le solde a debité est : " . TypePne::where('id', Pne::where('id', $val->pne)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pne;
                $notif1->receiver = $pne->user;
                $notif1->sender = Auth::user()->id;
                $notif1->save();
                $pne->status = $dec;
                $val->status = $dec;
                $pne->save();
                $val->save();
            } elseif ($dec == 2) {
                $val = Validation::find($id);
                //Create validation request
                if (Auth::user()->id == Department::where('id', Users::where('id', $pne->user)->get()->first()->department)->get()->first()->manager) {
                    //User is department manager
                    if (Service::where('department', Auth::user()->department)->where('manager', $pne->user)->get()->first() != null) {
                        //Check if request was emitted by a service Manager
                        $validator = Enterprise::where('id', Auth::user()->enterprise)->get()->first()->manager;
                        if ($validator == null) {
                            throw new Exception("Impossible d'initier une demande de Permission. Probleme d'hierachisation. Impossible de trouver votre responsable hierachique. Il se pourrait qu'il n'a pas été renseigner dans le systeme ", 1);
                        }
                        $vals = new Validation();
                        $vals->pne = $val->pne;
                        $vals->validator = $validator;
                        $vals->save();
                        //Create Notification request
                        $notif = new Notification();
                        $notif->title = "Nouvelle demande de PNE à valider";
                        $notif->message = "J'ai validé le PNE dont le solde a debité est : " . TypePne::where('id', Pne::where('id', $val->pne)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pne;
                        $notif->receiver = $validator;
                        $notif->sender = Auth::user()->id;
                        $notif->save();
                        $val->status = $dec;
                        $val->save();
                    } else {

                        $val->status = $dec;
                        $val->save();
                        $pne->status = 4;
                        if ($pne->type == 2) {
                            $usr_ = Users::where('matricule', $pne->matricule)->get()->first();
                            if ($usr_ != null) {
                                if ($usr_->holiday < $pne->duration) {
                                    throw new Exception("Malheuresement, M/Mmme " . $usr_->firstname . '(Matricule : ' . $usr_->matricule . ')' . 'a un solde congé inférieur a ' . $pne->duration . ' Jours. Cette demande sera donc annulé automatiquement sur WorkWave...', 600);
                                }
                                $usr_->holiday = ($usr_->holiday - $pne->duration);
                                $usr_->save();
                            }
                        }
                        $pne->save();
                        if (Users::find($pne->user) != null) {
                            $notif1 = new Notification();
                            $notif1->title = "PNE Validé";
                            $notif1->message = "J'ai validé le PNE dont le solde a debité est : " . TypePne::where('id', Pne::where('id', $val->pne)->get()->first()->type)->get()->first()->value('name') . '. Dont l\'ID est ' . $val->pne;
                            $notif1->receiver = $pne->user;
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
                            $pne->status = 4;
                            if ($pne->type == 2) {
                                $usr_ = Users::where('matricule', $pne->matricule)->get()->first();
                                if ($usr_ != null) {
                                    if ($usr_->holiday < $pne->duration) {
                                        throw new Exception("Malheuresement, M/Mmme " . $usr_->firstname . '(Matricule : ' . $usr_->matricule . ')' . 'a un solde congé inférieur a ' . $pne->duration . ' Jours. Cette demande sera donc annulé automatiquement sur WorkWave...', 600);
                                    }
                                    $usr_->holiday = ($usr_->holiday - $pne->duration);
                                    $usr_->save();
                                }
                            }
                        }
                        $pne->save();
                        if (Users::find($pne->user) != null) {
                            $notif1 = new Notification();
                            $notif1->title = "PNE Validé";
                            $notif1->message = "J'ai validé votre PNE dont le solde a debité est : " . TypePne::where('id', Pne::where('id', $val->pne)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pne;
                            $notif1->receiver = $pne->user;
                            $notif1->sender = Auth::user()->id;
                            $notif1->save();
                        }
                    } else {
                        throw new Exception("WorkWave n'arrive pas à valider cette demande 😞😞..", 1);
                    }
                } elseif (Service::where('manager', Auth::user()->id)->get()->first() != null) {
                    //Create Notification request
                    if ($dec == 2 || $dec == 3 || $dec == 4) {
                        $validator = $PMC->getMyManager(Auth::user()->id);;
                        if ($validator == null) {
                            throw new Exception("Impossible d'initier une demande de Permission. Probleme d'hierachisation. Impossible de trouver votre responsable hierachique. Il se pourrait qu'il n'a pas été renseigner dans le systeme ", 1);
                        }
                        $val->status = $dec;
                        $val->save();

                        $vals = new Validation();
                        $vals->pne = $val->pne;
                        $vals->validator = $validator;
                        $vals->save();
                        //Create Notification request
                        $notif = new Notification();
                        $notif->title = "Nouvelle demande de PNE à valider";
                        $notif->message = "J'ai validé le PNE dont le solde a debité est : " . TypePne::where('id', Pne::where('id', $val->pne)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pne;
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
            if ($th->getCode() == 600) {
                $pne = Pne::find($val->pne);
                $notif1 = new Notification();
                $notif1->title = "PNE Rejeté";
                $notif1->message = "Workwave a Rejeté le PNE dont le solde a debité est : " . TypePne::where('id', Pne::where('id', $val->pne)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pne .' car votre solde congé était inferieure a '.$pne->duration .' jours au moment de la validation.';
                $notif1->receiver = $pne->user;
                $notif1->sender = Auth::user()->id;
                $notif1->save();
                $pne->status = 3;
                $val->status = 3;
                $pne->save();
                $val->save();
            }
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
            if ($val->pne == null) {
                throw new Exception("Nous n'arrivons pas a traiter votre demande de validation ❌.", 1);
            }
            $pne = Pne::find($val->pne);
            if ($pne == null) {
                throw new Exception("Nous ne retrouvons pas la Permission concerner. Ceci peut être dû a une modification externe du fournisseur de donnée de WorkWave.", 1);
            }

                $notif1 = new Notification();
                $notif1->title = "PNE Rejeté";
                $notif1->message = "J'ai Rejeté le PNE dont le solde a debité est : " . TypePne::where('id', Pne::where('id', $val->pne)->get()->first()->type)->get()->first()->name . '. Dont l\'ID est ' . $val->pne;
                $notif1->receiver = $pne->user;
                $notif1->sender = Auth::user()->id;
                $notif1->save();
                $pne->status = 3;
                $val->status = 3;
                $val->reasons = $request->has('reasons') ? $request->input('reasons') : null;
                $pne->save();
                $val->save();
            return redirect()->back()->with('error', "Opération terminé avec succes.✅");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Pne $pne)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pne $pne)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pne $pne)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pne $pne)
    {
        //
    }
}
