<?php

namespace App\Http\Controllers;

use App\Models\ApiMail;
use App\Models\ApiSms;
use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Invitation;
use App\Models\Invites;
use App\Models\Participation;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Use json_encode with JSON_UNESCAPED_UNICODE to prevent casting special characters
        $data = json_encode(Invitation::all(), JSON_UNESCAPED_UNICODE);
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
            if (Gate::allows('isRq', Auth::user()) || Gate::allows('isAdmin', Auth::user())) {
                DB::beginTransaction();
                $dys = Dysfunction::find($request->input('dysfunction'));
                if (empty($dys)) {
                    throw new Exception("Nous ne retrouvons pas la ressource.", 404);
                }
                $data = new Invitation();
                $data->rq = Auth::user()->firstname . ' ' . Auth::user()->lastname . '(Matricule : ' . Auth::user()->matricule . ')';
                $data->object = $request->input('object');
                $data->dysfonction = $request->input('dysfunction');
                $data->motif = $request->input('motif');
                $data->odates = $request->input('dates');
                $data->place = $request->input('place');
                $data->link = isEmpty($request->input('link')) ? null : $request->input('link');
                $data->description = $request->input('description');
                $data->begin = $request->input('begin');
                $data->end = $request->input('end');
                $i_v = $request->input('internal_invites', []);
                $newinvites = Users::whereIn('id', $i_v)->get();
                $internal_invites = [];
                if (!empty($i_v)) {
                    foreach ($i_v as $option) {
                        $internal_invites[] = new Invites($newinvites->where('id', $option)->first());
                    }
                }
                // Use json_encode with JSON_UNESCAPED_UNICODE to prevent casting special characters
                $data->internal_invites = json_encode($internal_invites, JSON_UNESCAPED_UNICODE);
                $ext_u = [];
                if ($request->has('extuser') && !empty($request->extuser)) {
                    for ($i = 0; $i < count($request->extuser); $i++) {
                        // Create a new Person object for each row and add it to the array
                        $ext_u[] = $request->extuser[$i];
                    }
                }
                $data->external_invites = json_encode($ext_u, JSON_UNESCAPED_UNICODE);
                $data->save();
                DB::commit();
                //$emails = array_merge($newinvites->pluck('email')->unique()->toArray(), $ext_u);
                $emails = $ext_u;
                //Mails for PRD Users with accounts
                foreach ($newinvites as $value) {
                    // Data to encode (user ID and meeting ID)
                    $confirm_infos = [
                        'matricule' => $value->matricule,
                        'invitation' => $data->id,
                        'decision' => 1,
                    ];
                    $cancel_infos = [
                        'matricule' => $value->matricule,
                        'invitation' => $data->id,
                        'decision' => 0,
                    ];

                    // Generate the confirmation URL
                    $cancelUrl = route('confirm.attendance', ['encodedData' => Crypt::encryptString(json_encode($cancel_infos))]);
                    $confirmationUrl = route('confirm.attendance', ['encodedData' => Crypt::encryptString(json_encode($confirm_infos))]);
                    $icontent = view('employees.invitation_appMail', ['invitation' => $data, 'confirm' => $confirmationUrl, 'cancel' => $cancelUrl])->render();
                    $inewmail = new ApiMail(null, [$value->email], 'Cadyst PRD App', "Invitation à la Réunion No #" . $data->id . " du : " . $data->odates, $icontent, []);
                    $inewmail->send();
                }
//Mails for Non PRD Users(those with no accounts)
                $content = view('employees.invitation_appMail', ['invitation' => $data])->render();
                $newmail = new ApiMail(null, $emails, 'Cadyst PRD App', "Invitation à la Réunion No #" . $data->id . " du : " . $data->odates, $content, []);
                $response = $newmail->send();
                $newmessage = new ApiSms(
                    $newinvites->pluck('phone')->unique()->toArray(),
                    'Cadyst PRD App',
                    'Réunion résolution ' . $dys->code . ' | Date : ' . formatDateInFrench($data->odates, 'complete') . ' | Heure : ' . $data->begin . ' - ' . $data->end . ' | Lieu : ' . (is_null($data->place) ? 'Aucun lieu Fourni. Consulter vos mails' : $data->place) . ' Merci de confirmer avant le ' . formatDateInFrench($data->odates, 'short')
                );
                $newmessage->send();
                $jsonResponse = json_decode($response->getContent(), true);
                if ($jsonResponse['code'] != 200) {
                    throw new Exception("Une erreur est survenue lors de l'envoi des mails : (" . $jsonResponse['error'] . ")", 500);
                }
                return redirect()->back()->with('error', "La réunion a été créer avec succes.");
            } else {
                throw new Exception("Malheureusement, vous ne disposez pas des acreditations necessaires pour programmer une réunion.", 401);
            }
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
            if (Gate::allows('isRq', Auth::user()) || Gate::allows('isAdmin', Auth::user())) {
                $data = Invitation::where('id', $id)->get();
                if ($data->isEmpty()) {
                    throw new Exception('Nous ne trouvons pas cette invitation: ' . $id, 404);
                }
                return response()->json([
                    // Use json_encode with JSON_UNESCAPED_UNICODE to prevent casting special characters
                    "data" => json_encode($data, JSON_UNESCAPED_UNICODE),
                ]);
            } else {
                // The user is neither an rq nor a super admin
                abort(403, 'Unauthorized action.');
            }
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
        try {
            $data = Invitation::find($id);
            if (Gate::allows('isInvitationOpen', $data)) {
                if (Gate::allows('isRq', Auth::user()) || Gate::allows('isAdmin', Auth::user())) {
                    DB::beginTransaction();
                    //old invite collection
                    $olddatas = $data;
                    $old_invits = collect($data->getInternalInvites());
                    if ($data == null) {
                        throw new Exception("Impossible de trouver l'element a Mettre à jour", 404);
                    }
                    if (Carbon::now() > $data->odates) {
                        throw new Exception("Il n'est plus possible de modifier cette réunion car la date est dépassée.", 401);
                    }
                    $dys = Dysfunction::find($request->input('dysfunction'));
                    if (empty($dys)) {
                        throw new Exception("Nous ne retrouvons pas la ressource.", 404);
                    }
                    $data->rq = Auth::user()->firstname . ' ' . Auth::user()->lastname . '(Matricule : ' . Auth::user()->matricule . ')';
                    $data->object = $request->has('object') ? $request->input('object') : $data->object;
                    $data->dysfonction = $request->has('dysfunction') ? $request->input('dysfunction') : $data->dysfonction;
                    $data->motif = $request->has('motif') ? $request->input('motif') : $data->motif;
                    $data->odates = $request->has('dates') ? $request->input('dates') : $data->odates;
                    $data->place = $request->has('place') ? $request->input('place') : $data->place;
                    $data->link = isEmpty($request->has('link')) ? null : $request->input('link');
                    $data->description = $request->has('description') ? $request->input('description') : $data->description;
                    $data->begin = $request->has('begin') ? $request->input('begin') : $data->begin;
                    $data->end = $request->has('end') ? $request->input('end') : $data->end;
                    $i_v = $request->input('internal_invites', []);
                    $newinvites = Users::whereIn('id', $i_v)->get();
                    $internal_invites = [];
                    //if date or begin or end properties are updated, reinitialise participation array to make participants confirm thier participation back.
                    if ($data->end != $olddatas->end || $data->start != $olddatas->start || $data->odates != $olddatas->odates) {
                        $data->participation = null;
                    }
                    //conserving all invite old datas
                    if (!empty($i_v)) {
                        foreach ($i_v as $option) {
                            $old = $old_invits->where('matricule', Users::find($option)->matricule)->first();
                            if (is_null($old)) {
                                $internal_invites[] = new Invites($newinvites->where('id', $option)->first());
                            } else {
                                $internal_invites[] = new Invites(null, $old);
                            }
                        }
                    }
                    //new invite collection
                    $new_invits = collect($internal_invites);
                    $data->internal_invites = json_encode($internal_invites, JSON_UNESCAPED_UNICODE);
                    $ext_u = [];
                    if ($request->has('extuser') && !empty($request->extuser)) {
                        for ($i = 0; $i < count($request->extuser); $i++) {
                            // Create a new Person object for each row and add it to the array
                            $ext_u[] = $request->extuser[$i];
                        }
                    }
                    $data->external_invites = json_encode($ext_u, JSON_UNESCAPED_UNICODE);

                    $is_updated = $data->isInvitationUpdated();
                    //inform internal and external invites about modification
                    if ($is_updated) {
                        $message = $data->getUpdateMessage();
                        $emails = array_merge($newinvites->pluck('email')->unique()->toArray(), $ext_u);
                        $content = view('employees.invitation_updateMail', ['invitation' => $data, 'message' => $message])->render();
                        $newmail = new ApiMail(null, $emails, 'Cadyst PRD App', "Invitation à la Réunion No #" . $data->id . " du : " . $data->odates, $content, []);
                        $response = $newmail->send();
                        $newmessage = new ApiSms(
                            $newinvites->pluck('phone')->unique()->toArray(),
                            'Cadyst PRD App',
                            'Réunion pour ' . $dys->code . ' MAJ : Nouvelle date : ' . formatDateInFrench($data->odates, 'short') . ' Horaire : ' . $data->begin . ' - ' . $data->end . ' Lieu : ' . (is_null($data->place) ? 'Aucun lieu Fourni. Consulter vos mails' : $data->place) . '. Merci de confirmer.'
                        );
                        $newmessage->send();
                        $jsonResponse = json_decode($response->getContent(), true);
                        if ($jsonResponse['code'] != 200) {
                            throw new Exception("Une erreur est survenue lors de l'envoi des mails : (" . $jsonResponse['error'] . ")", 500);
                        }
                    }
                    //mails for removed users
                    foreach ($old_invits as $oi) {
                        if (is_null($new_invits->where('matricule', $oi->matricule)->first()) && !in_array($oi->email, $ext_u)) {
                            $content = view('employees.invitation_excludeMail')->render();
                            $newmail = new ApiMail(null, array_fill(0, 1, $oi->email), 'Cadyst PRD App', "Mise à jour de la Réunion No #" . $data->id . " du : " . $data->odates, $content, []);
                            $response = $newmail->send();
                            $newmessage = new ApiSms(
                                array_fill(0, 1, $newinvites->pluck('phone')->unique()->toArray()),
                                'Cadyst PRD App',
                                "Bonjour ; Votre présence n'est plus requise pour la réunion concernant l'incident No. " . $dys->code . " du " . formatDateInFrench($data->odates, 'short') . " à " . $data->end . ". Merci de votre compréhension. "
                            );
                            $newmessage->send();
                        }
                    }
                    $data->save();
                    DB::commit();

                    return redirect()->back()->with('error', "La réunion a été Mise a Jour avec succes.");
                } else {
                    // The user is neither an (rq or  a super admin) or the inviation is not edistabled any more
                    abort(403, 'Unauthorized action.');
                }
            } else {
                // The user is neither an (rq or  a super admin) or the inviation is not edistabled any more
                abort(403, 'Unauthorized action. Invitation is closed');
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Registers a user availability to a particular meeting.
     */
    public function inviteConfirmation(Request $request)
    {
        try {
            $data = Invitation::find($request->input('invitation'));
            if (Gate::allows('isInvitationOpen', $data)) {
                DB::beginTransaction();
                if ($data == null) {
                    throw new Exception("Impossible de trouver l'element a Mettre à jour", 404);
                }
                $invites = $data->findInviteByMatricule(Auth::user()->matricule);
                if ($invites) {
                    if ($request->input('decision') == 'Accept') {
                        $invites = $invites->confirm();
                        $data->updateInviteByMatricule($invites);
                        //$data->internal_invites = $data->updateInviteByMatricule($invites) != null ? $data->updateInviteByMatricule($invites)->internal_invites : $data->internal_invites;
                        $data->save();
                    } elseif ($request->input('decision') == 'Reject') {
                        $invites = $invites->cancel();
                        $data->updateInviteByMatricule($invites);
                        //$data->internal_invites = $data->updateInviteByMatricule($invites) != null ? $data->updateInviteByMatricule($invites)->internal_invites : $data->internal_invites;
                        $data->save();
                    }
                }
                DB::commit();
                return redirect()->back()->with('error', 'Mise à jour de la disponibilité terminée.');
            } else {
                throw new Exception("cette réunion est déja terminée. Il n'est plus possible de l'éditer, confirmer ou désister.", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Registers a user availability to a particular meeting from encoded url
     * in mail
     */
    public function confirmAttendance($encodedData)
    {
        try {
            // Decode the encoded data
            $decodedData = json_decode(Crypt::decryptString($encodedData), true);
            // Retrieve the user and meeting
            $user = Users::where('matricule', $decodedData['matricule'])->get()->first();
            $data = Invitation::find($decodedData['invitation']);
            $decision = $decodedData['decision'];

            if (is_null($data->closed_at) &&  !Carbon::parse($data->odates)->lessThanOrEqualTo(Carbon::now())) {
                DB::beginTransaction();
                if ($data == null) {
                    throw new Exception("Impossible de trouver l'element a Mettre à jour", 404);
                }
                $invites = $data->findInviteByMatricule($user->matricule);
                if ($invites) {
                    if ($decision == 1) // 1 for accept
                    {
                        $invites = $invites->confirm();
                        $data->updateInviteByMatricule($invites);
                        //$data->internal_invites = $data->updateInviteByMatricule($invites) != null ? $data->updateInviteByMatricule($invites)->internal_invites : $data->internal_invites;
                        $data->save();
                    } elseif ($decision == 0) //0 for rejected
                    {
                        $invites = $invites->cancel();
                        $data->updateInviteByMatricule($invites);
                        //$data->internal_invites = $data->updateInviteByMatricule($invites) != null ? $data->updateInviteByMatricule($invites)->internal_invites : $data->internal_invites;
                        $data->save();
                    }
                }
                DB::commit();
                return view('inviteConfirm', ['invitation'=>$data, 'decision'=>$decision]);
            } else {
                throw new Exception("cette réunion est déja terminée. Il n'est plus possible de l'éditer, confirmer ou désister.", 401);
            }
        } catch (Throwable $th) {
            return redirect('/login')->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Closes up the meeting by giving current datetime to closeat property
     */
    public function close($id)
    {
        try {
            $data = Invitation::find($id);
            DB::beginTransaction();
            if ($data == null) {
                throw new Exception("Impossible de trouver l'élément à Mettre à jour", 404);
            }
            $data->closed_at = Carbon::now();
            $data->save();
            DB::commit();
            return redirect()->back()->with('error', 'Réunion No. #' . $data->id . ' a été clôturée.');
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Updates list of attendnace to a meeting.
     */
    public function participation(Request $request, $id)
    {

        try {
            $data = Invitation::find($id);
            if (Gate::allows('isInvitationOpen', $data) || Gate::allows('isAdmin', Auth::user())) {
                DB::beginTransaction();
                if ($data == null) {
                    throw new Exception("Impossible de trouver l'élément à Mettre à jour", 404);
                }
                $_p = [];
                $participant = $request->input('participant', []);
                $participantext = $request->input('participantext', []);
                if (!empty($participant)) {
                    $data->participation = [];
                }
                foreach ($participantext as $p) {
                    $p = new Participation([
                        'matricule' => $p,
                        'names' => 'Invites externe.',
                        'marked_by' => Auth::user()->firstname . ' ' . Auth::user()->lastname,
                        'marked_matricule' => Auth::user()->matricule,
                    ]);

                    $_p[] = $p;
                }
                foreach ($participant as $p) {
                    $p = new Participation([
                        'matricule' => $p,
                        'names' => Users::where('matricule', $p)->get()->first()->firstname,
                        'marked_by' => Auth::user()->firstname . ' ' . Auth::user()->lastname,
                        'marked_matricule' => Auth::user()->matricule,
                    ]);

                    $_p[] = $p;
                }
                // Use json_encode with JSON_UNESCAPED_UNICODE to prevent casting special characters
                $data->participation = json_encode($_p, JSON_UNESCAPED_UNICODE);
                $data->save();
                DB::commit();
                return redirect()->back()->with('error', 'Participation pour la Réunion No. #' . $data->id . ' a été mise à jour.');
            } else {
                throw new Exception("Cette réunion est déja terminée. Il n'est plus possible de l'éditer, confirmer ou désister.", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rec = Invitation::find($id);
        Gate::authorize('isInvitationPast', $rec);
        try {
            $dys = Dysfunction::find($rec->dysfonction);
            if (is_null($dys)) {
                throw new Exception("Nous ne retrouvons pas la ressource.", 404);
            }
            if (Gate::allows('isAdmin', Auth::user()) || Gate::allows('isEnterpriseRQ', [Enterprise::find($dys->enterprise_id)])) {
                DB::beginTransaction();
                $invites = collect($rec->getInternalInvites());
                $content = view('employees.invitation_cancelMail', ['invitation' => $rec, 'dysfunction' => $dys])->render();
                $_externali = json_decode($rec->external_invites, true);
                //Mails of internal and external users.
                $allmails = array_merge($_externali, $invites->pluck('email')->unique()->toArray());
                $newmail = new ApiMail(null, $allmails, 'Cadyst PRD App', "Annulation de la réunion de résolution du dysfonctionnement No." . $dys->code, $content, []);
                $response = $newmail->send();
                $internalusers = Users::whereIn('matricule', $invites->pluck('matricule'))->get();
                $newmessage = new ApiSms(
                    $internalusers->pluck('phone')->unique()->toArray(),
                    'Cadyst PRD App',
                    "Réunion " . $dys->code . " le " . formatDateInFrench($rec->odates) . " à " . $rec->begin . " est annulée. Merci pour votre compréhension. "
                );
                $newmessage->send();
                $rec->forceDelete();
                DB::commit();
                return redirect()->back()->with('error', "Suppression Effectuée.");
            } else {
                throw new Exception("Arrêt inattendu du processus suite a une tentative de suppression/de manipulation de donnée sans detention des privileges requis pour l'operation.", 501);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Echec lors de la surpression. L'erreur indique : " . $th->getMessage());
        }
    }
}
