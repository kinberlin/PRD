<?php

namespace App\Http\Controllers;

use App\Models\ApiMail;
use App\Models\ApiSms;
use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use App\Models\Correction;
use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Evaluation;
use App\Models\Gravity;
use App\Models\Invitation;
use App\Models\Origin;
use App\Models\Probability;
use App\Models\Processes;
use App\Models\Site;
use App\Models\Status;
use App\Models\Task;
use App\Models\Users;
use App\Scopes\YearScope;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Throwable;

class DysfunctionController extends Controller
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
     * Initialises a dysfunction in system.
     */

    public function init(Request $request)
    {
        try {
            DB::beginTransaction();
            $dys = new Dysfunction();
            $ents = Enterprise::where('name', $request->input('enterprise'))->get()->first();
            if (is_null($ents)) {
                throw new Exception("Nous ne trouvons pas la ressource demandée.", 401);
            }
            $site = Site::find($request->input('site'));
            if (is_null($site)) {
                throw new Exception("Nous ne trouvons pas la ressource demandée.", 401);
            }
            if (!$ents->visible) {
                throw new Exception("Erreur sur la ressource 'Entreprise'.", 501);
            }
            if (!$site->visible) {
                throw new Exception("Erreur sur la ressource 'Site'.", 401);
            }

            $dys->occur_date = $request->input('occur_date');
            $dys->enterprise = $request->input('enterprise');
            $dys->enterprise_id = $ents->id;
            $dys->site = $site->name . ' ,' . $site->location;
            $dys->site_id = $site->id;
            $dys->description = $request->input('description');
            $dys->emp_signaling = Auth::user()->firstname . ' ' . Auth::user()->lastname;
            $dys->emp_matricule = Auth::user()->matricule;
            $dys->emp_email = Auth::user()->email;
            $urls = [];
            if (!is_null($request->file('group-a'))) {
                foreach ($request->file('group-a') as $key => $fileData) {
                    if (isset($fileData['pj']) && $fileData['pj']->isValid()) {
                        $pj = $fileData['pj'];
                        $filename = time() . '_' . $pj->getClientOriginalName();
                        // Store the file and get the path
                        $path = $pj->storeAs('public/uploads/dysfunction', $filename);
                        $urls[] = $path;
                    }
                }
            }
            $dys->pj = json_encode($urls);
            $dys->save();
            DB::commit();
            $dys->code = 'D' . Carbon::now()->year . date('m') . Enterprise::where('name', $request->input('enterprise'))->get()->first()->surfix . $dys->id;
            $dys->save();
            //alert rq on dysfunction alert
            /*$rqU = AuthorisationRq::where('enterprise', $ents->id)->get();
            $rq = Users::whereIn('id', $rqU->pluck('user'))->where('role', '<>', 1)->get();
            foreach ($rq as $user) {
                $newmessage = new ApiSms(array_fill(0, 1, $user->phone), 'Cadyst PRD App', "Nous tenons à vous informer qu'un incident a été signalé par un employé via notre plateforme de résolution des incidents. Il s'agit du No." . $dys->code);
                $newmessage->send();
                $content = view('employees.dysfunction_appMail', ['user' => $user, 'dysfunction' => Dysfunction::find($dys->id)])->render();
                $newmail = new ApiMail(null, array_fill(0, 1, $user->email), 'Cadyst PRD App', "Notification d'Incident - Code de l'incident : [" . $dys->code . "]", $content, []);

                $result = $newmail->send();
            }*/
            return redirect()->back()->with('error', "Merci d'avoir fait ce signalement. Nous le traiterons dans les plus bref délais.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Identifies or update identification infos
     * about a dysfunction signaled in the system.
     */
    public function store(Request $request, $id)
    {
        try {
            $dys = Dysfunction::find($id);
            if (Gate::allows('DysCanIdentify', [$dys != null ? $dys : null])) {
                $old_dys = Dysfunction::find($id);
                DB::beginTransaction();
                if ($dys == null) {
                    throw new Exception("Impossible de trouver la ressource demandée.", 404);
                }
                $gra = Gravity::find($request->input('gravity'));
                if (is_null($gra)) {
                    throw new Exception("La ressource 'Gravité' est introuvable.", 401);
                }
                $probability = Probability::find($request->input('probability'));
                if (is_null($probability)) {
                    throw new Exception("La ressource 'Probabilité' est introuvable.", 401);
                }
                $origin = Origin::find($request->input('origin'));
                if (is_null($origin)) {
                    throw new Exception("La ressource 'Origine' est introuvable.", 401);
                }
                if (Gate::allows('isGravityVisible', $gra) && Gate::allows('isProbabilityVisible', $probability) && Gate::allows('isOriginVisible', $origin)) {
                    $dys->impact_processes = json_encode($request->input('impact_processes'));
                    $dys->concern_processes = json_encode($request->input('concern_processes'));
                    $dys->gravity = $gra->name;
                    $dys->gravity_id = $gra->id;
                    $dys->origin = $request->input('origin');
                    $dys->probability = $request->input('probability');
                    $dys->cause = empty($request->input('cause')) ? null : $request->input('cause');
                    //alert pilotes
                    foreach ($dys->getCProcesses() as $nd) {
                        if (is_null($old_dys->getCprocesses()->where('id', $nd->id)->first())) {
                            $ap = AuthorisationPilote::where('process', $nd->id)->get();
                            $a_pilotes = Users::whereIn('id', $ap->pluck('user')->unique());
                            $newmessage = new ApiSms(
                                $a_pilotes->pluck('phone')->unique()->toArray(),
                                'Cadyst PRD App',
                                "Bonjour cher(e) pilote ;Dysfonctionnement " . $dys->code . " : Votre processus(" . $nd->name . ") a été identifié comme origine. Préparez-vous pour intervention. Merci."
                            );
                            $newmessage->send();
                            $emails = $a_pilotes->pluck('email')->unique()->toArray();
                            $content = view('employees.dysfunctionCpilote_appMail', ['dysfunction' => $dys, 'name' => $nd->name])->render();
                            $newmail = new ApiMail(null, $emails, 'Cadyst PRD App', "Signalement de dysfonctionnement No. " . $dys->code . " - Processus concerné identifié", $content, []);
                            $response = $newmail->send();
                        }
                    }
                    foreach ($dys->getIProcesses() as $nd) {
                        if (is_null($old_dys->getIProcesses()->where('id', $nd->id)->first())) {
                            $ap = AuthorisationPilote::where('process', $nd->id)->get();
                            $a_pilotes = Users::whereIn('id', $ap->pluck('user')->unique());
                            $emails = $a_pilotes->pluck('email')->unique()->toArray();
                            $content = view('employees.dysfunctionIpilote_appMail', ['dysfunction' => $dys, 'name' => $nd->name])->render();
                            $newmail = new ApiMail(null, $emails, 'Cadyst PRD App', "Signalement de dysfonctionnement No. " . $dys->code . " - Processus impacté identifié", $content, []);
                            $response = $newmail->send();
                            $newmessage = new ApiSms(
                                $a_pilotes->pluck('phone')->unique()->toArray(),
                                'Cadyst PRD App',
                                "Dysfonctionnement " . $dys->code . " : Votre processus(" . $nd->name . ") a été identifié comme impacté. Préparez-vous pour intervention. Merci. "
                            );
                            $newmessage->send();
                        }
                    }
                    $dys->save();
                    DB::commit();

                    if ($dys->status == 1) {
                        $dys->status = 2;
                        $task = new Task();
                        $task->dysfunction = $dys->id;
                        $task->text = 'Dysfonctionnement ' . $dys->code;
                        $task->duration = 1;
                        $task->progress = 0.01;
                        $task->start_date = Carbon::now();
                        $task->parent = 0;
                        $task->unscheduled = 0;
                        $task->process = Processes::where('name', $request->input('concern_processes'))->get()->first()->id;
                        $task->created_by = Auth::user()->firstname . ' ' . Auth::user()->lastname;
                        $dys->save();
                        $task->save();
                    }

                    return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
                } else {
                    throw new Exception("« Certaines ressources ne sont pas disponibles actuellement. »", 401);
                }
            } else {
                throw new Exception("« Il est impossible vu le statut actuel de ce dysfonctionnement, de le re-identifier de nouveau. »", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Closes up a dysfunction by rejecting it.
     */
    public function cancel($id)
    {
        try {
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("La ressource spécifié est introuvable.", 404);
            }
            if (Gate::authorize('isEnterpriseRQ', Enterprise::where('name', $dys->enterprise)->get()->first()) || Gate::allows('isAdmin', Auth::user())) {
                DB::beginTransaction();

                if ($dys->status == 3) {
                    throw new Exception("Erreur de traitement.Ce dysfonctionnement est déja annulé.", 404);
                }
                $dys->status = 3;
                $dys->closed_by = Auth::user()->firstname . ' (' . Auth::user()->matricule . ')';
                if ($dys->status == 1) {
                    $dys->status = 2;
                }
                $dys->save();
                DB::commit();
            } else {
                // The user is neither an rq nor a super admin
                abort(403, 'Unauthorized action.');
            }
            return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Display a detailed report about a dysfunction.
     */
    public function report(Request $request)
    {
        $code = $request->input('code');
        try {
            $dys = is_null(Dysfunction::withoutGlobalScope(YearScope::class)->find($code)) ? Dysfunction::withoutGlobalScope(YearScope::class)->where('code', $code)->get()->first() : Dysfunction::withoutGlobalScope(YearScope::class)->find($code);
            $data = $dys;
            if (!is_null($dys)) {
                Session::put('currentYear', Carbon::parse($dys->created_at)->year);
                $id = $dys->id;
                $status = Status::all();
                $processes = Processes::all();
                $ents = Enterprise::all();
                $site = Site::all();
                $gravity = Gravity::all();
                $origin = Origin::all();
                $probability = Probability::all();
                $parentTasks = Task::withoutGlobalScope(YearScope::class)
                    ->select('tasks.id', 'tasks.text')
                    ->distinct()
                    ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
                    ->where('t2.dysfunction', $id)
                    ->get();
                $invitations = Invitation::where('dysfonction', $id)->get()->sortByDesc('id');
                $corrections = Task::where('dysfunction', $id)->whereNotIn('id', $parentTasks->pluck('id')->unique())->get()->sortByDesc('id');
                $evaluations = Evaluation::whereIn('task', $corrections->pluck('id')->unique())->get();
                $matricules = collect();
                // Iterate over each invitation and their invites
                foreach ($invitations as $d) {
                    if ($d->internal_invites) {
                        foreach ($d->getInternalInvites() as $i) {
                            $matricules->push($i->matricule);
                        }
                    }
                }
                // Get unique user matricules
                $distinctMatricules = $matricules->unique();
                $users = Users::whereIn('matricule', $distinctMatricules)->get();
                return view('admin/dys_report', compact(
                    'data',
                    'status',
                    'processes',
                    'ents',
                    'site',
                    'gravity',
                    'origin',
                    'probability',
                    'corrections',
                    'evaluations',
                    'invitations',
                    'users'
                ));
            } else {
                return view('admin/dys_report', compact(
                    'data',
                ));
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Updates a dysfunction by setting it cost attribute.
     */
    public function cost(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validate([
                'cost' => 'required|numeric|min:1',
            ]);
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("La ressource spécifié est introuvable.", 404);
            }
            if (Gate::allows('isEnterpriseRQ', Enterprise::where('name', $dys->enterprise)->get()->first()) || Gate::allows('isAdmin', Auth::user())) {
                Gate::authorize('DysRunning', $dys);
                $dys->cost = $request->input('cost');
                $dys->save();
                DB::commit();
            } else {
                // The user is neither an rq nor a super admin
                abort(403, 'Unauthorized action.');
            }
            return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Sets a dysfunction in evaluation state.
     */
    public function launchEvaluation($id)
    {
        $dys = Dysfunction::find($id);
        try {

            if (Gate::allows('isEnterpriseRQ', Enterprise::find($dys->enterprise_id)->get()->first()) || Gate::allows('isAdmin', Auth::user())) {
                if (Gate::allows('DysCanEvaluate', [$dys != null ? $dys : null])) {
                    DB::beginTransaction();
                    if ($dys == null) {
                        throw new Exception("La ressource spécifié est introuvable.", 404);
                    }
                    if ($dys->status == 3) {
                        throw new Exception("Erreur de traitement.Ce dysfonctionnement est déja annulé.", 404);
                    }
                    $dys->status = 5;
                    $parentTasks = Task::withoutGlobalScope(YearScope::class)
                        ->select('tasks.id', 'tasks.text')
                        ->distinct()
                        ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
                        ->where('t2.dysfunction', $id)
                        ->whereYear('tasks.created_at', session('currentYear'))
                        ->get();
                    $corrections = Task::where('dysfunction', $id)->whereNotIn('id', $parentTasks->pluck('id')->unique())->get();
                    if (!empty($corrections)) {
                        Evaluation::whereIn('task', $corrections->pluck('id')->unique())->delete();
                    }
                    if ($dys->status == 1) {
                        $dys->status = 2;
                    }
                    $dys->save();
                    DB::commit();
                    return redirect()->back()->with('error', "Lancement de l'Evaluation.");
                } else {
                    throw new Exception("« Le statut actuel de ce dysfonctionnement ne le permet pas de passer en évaluation. »", 401);
                }
            } else {
                // The user is neither an rq nor a super admin
                abort(403, 'Unauthorized action.');
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Cancel up valuation state of a dysfunction
     * and sets it back to planication state.
     */
    public function cancelEvaluation($id)
    {
        try {
            DB::beginTransaction();
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("La ressource spécifié est introuvable.", 404);
            }
            if ($dys->status == 3) {
                throw new Exception("Erreur de traitement.Ce dysfonctionnement est déja annulé.", 404);
            }
            if ($dys->status == 5) {
                $dys->status = 4;
            } else {
                throw new Exception("Ce dysfonctionnement n'était pas en évaluation.", 1);
            }

            if ($dys->status == 1) {
                $dys->status = 2;
            }
            $dys->save();
            DB::commit();
            return redirect()->back()->with('error', "Evaluation terminée.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * probably a useless function
     */
    public function action(Request $request, $id)
    {
        try {
            $dys = Dysfunction::find($id);
            $ents = Enterprise::where('name', $dys->enterprise)->get()->first();
            if (Gate::allows('isEnterpriseRQ', [$ents != null ? $ents : null]) || Gate::allows('isAdmin', Auth::user())) {
                DB::beginTransaction();

                $dys = Dysfunction::find($id);
                if ($dys == null) {
                    throw new Exception("Impossible de trouver la ressource demandée.", 404);
                }
                $corrections = [];
                for ($i = 0; $i < count($request->user); $i++) {
                    // Create a new Person object for each row and add it to the array
                    $corrections[] = new Correction(
                        $request->action[$i],
                        $request->department[$i],
                        $request->user[$i],
                        $request->delay[$i],
                        'Test User'
                    );
                }
                $corrective_acts = json_encode($corrections);
                $dys->corrective_acts = $corrective_acts;
                if ($dys->status == 1) {
                    $dys->status = 2;
                }
                $dys->save();
                DB::commit();
                return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
            } else {
                throw new Exception("« Vous ne disposez pas des accréditations nécessaires pour effectuer l'action que vous avez tenté de réaliser sur les données concernées par cette action. »", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Saves up task evaluation for each prescribed task.
     */
    public function evaluation(Request $request, $id)
    {
        try {
            $dys = Dysfunction::find($id);
            Gate::authorize('DysInEvaluation', $dys);
            $ents = Enterprise::where('name', $dys->enterprise)->get()->first();
            if (Gate::allows('isEnterpriseRQ', [$ents != null ? $ents : null]) || Gate::allows('isAdmin', Auth::user())) {
                $ids = $request->input('id');
                $satisfactions = $request->input('satisfaction');
                $criterias = $request->input('criteria');
                $completions = $request->input('completion');

                DB::beginTransaction();
                Evaluation::whereIn('task', $ids)->delete();
                $dys->status = 7;
                foreach ($ids as $index => $id) {
                    Evaluation::create([
                        'task' => $id,
                        'completion' => $completions[$index],
                        'satisfaction' => $satisfactions[$index],
                        'evaluation_criteria' => $criterias[$index],
                    ]);
                }
                if (count(Evaluation::whereIn('task', $ids)->get()) != count($completions)) {
                    throw new Exception("Certaines actions de ce dysfonctionnement n'ont pas été évaluer.", 401);
                }
                $dys->save();
                DB::commit();
                return redirect()->back()->with('error', "Évaluations enregistrées avec succès.");
            } else {
                throw new Exception("« Vous ne disposez pas des accréditations nécessaires pour effectuer l'action que vous avez tenté de réaliser sur les données concernées par cette action. »", 401);
            }
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Closes up a dysfunction after saving the dysfunction evaluation.
     */
    public function close(Request $request, $id)
    {
        try {
            $dys = Dysfunction::find($id);
            Gate::authorize('DysEvaluation', $dys);
            $ents = Enterprise::where('name', $dys->enterprise)->get()->first();
            if (Gate::allows('isEnterpriseRQ', [$ents != null ? $ents : null]) || Gate::allows('isAdmin', Auth::user())) {
                DB::beginTransaction();
                $dys->satisfaction_description = $request->input('satisfaction_description');
                $dys->solved = $request->has('solved') ? 1 : 0;
                $dys->status = 6;
                $dys->closed_at = Carbon::now();
                $dys->closed_by = Auth::user()->firstname . '(' . Auth::user()->matricule . ')';
                if (is_null($dys->cost)) {
                    throw new Exception("Vous n'avez pas encore renseigné de coût de non-qualité lié à ce dysfonctionnement. Pour cette raison, l'opération de clôturation a été interrompue.", 401);
                }
                $dys->save();
                DB::commit();
                return redirect()->back()->with('error', "Évaluations enregistrées avec succès.");
            } else {
                throw new Exception("« Vous ne disposez pas des accréditations nécessaires pour effectuer l'action que vous avez tenté de réaliser sur les données concernées par cette action. »", 401);
            }
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Dysfunction $dysfunction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dysfunction $dysfunction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dysfunction $dysfunction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dysfunction $dysfunction)
    {
        //
    }
}
