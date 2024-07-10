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
use App\Models\Processes;
use App\Models\Status;
use App\Models\Task;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
     * Store a newly created resource in storage.
     */

    public function init(Request $request)
    {
        try {
            DB::beginTransaction();
            $dys = new Dysfunction();
            $ents = Enterprise::where('name', $request->input('enterprise'))->get()->first();
            if (empty($ents)) {
                throw new Exception("Nous ne trouvons pas la ressource demandée.", 401);
            }
            $dys->occur_date = $request->input('occur_date');
            $dys->enterprise = $request->input('enterprise');
            $dys->enterprise_id = $ents->id;
            $dys->site = $request->input('site');
            $dys->description = $request->input('description');
            $dys->emp_signaling = Auth::user()->firstname . ' ' . Auth::user()->lastname;
            $dys->emp_matricule = Auth::user()->matricule;
            $dys->emp_email = Auth::user()->email;
            $urls = [];
            if ($request->hasFile('group-a')) {
                foreach ($request->file('group-a') as $key => $fileData) {
                    if (isset($fileData['pj']) && $fileData['pj']->isValid()) {
                        $pj = $fileData['pj'];
                        $filename = time() . '_' . $pj->getClientOriginalName();
                        $pj->move(public_path('/uploads/dysfonction'), $filename);
                        $url = asset('/uploads/dysfonction/' . $filename);
                        $urls[] = $url;
                    }
                }
            }
            $dys->pj = json_encode($urls);
            $dys->save();
            DB::commit();
            $dys->code = 'D' . Carbon::now()->year . date('m') . Enterprise::where('name', $request->input('enterprise'))->get()->first()->surfix . $dys->id;
            $dys->save();
            $rqU = AuthorisationRq::where('enterprise', $ents->id)->get();
            $rq = Users::whereIn('id', $rqU->pluck('user'))->where('role', '<>', 1)->get();
            foreach ($rq as $user) {
                $newmessage = new ApiSms(array_fill(0, 1, $user->phone), 'Cadyst PRD App', "Nous tenons à vous informer qu'un incident a été signalé par un employé via notre plateforme de résolution des incidents. Il s'agit du No." . $dys->code);
                $newmessage->send();
                $content = view('employees.dysfunction_appMail', ['user' => $user, 'dysfunction' => Dysfunction::find($dys->id)])->render();
                $newmail = new ApiMail(null, array_fill(0, 1, $user->email), 'Cadyst PRD App', "Notification d'Incident - Code de l'incident : [" . $dys->code . "]", $content, []);

                $result = $newmail->send();
            }

            return redirect()->back()->with('error', "Merci d'avoir fait ce signalement. Nous le traiterons dans les plus bref délais. (" . $result->getData()->code . ')');
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
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
                $dys->impact_processes = json_encode($request->input('impact_processes'));
                $dys->concern_processes = json_encode($request->input('concern_processes'));
                $dys->gravity = $request->input('gravity');
                $dys->origin = $request->input('origin');
                $dys->probability = $request->input('probability');
                $dys->type = $request->input('type');
                $dys->cause = empty($request->input('cause')) ? null : $request->input('cause');
                //alert pilotes
                foreach ($dys->getCProcesses() as $nd) {
                    if (is_null($old_dys->getCprocesses()->where('id', $nd->id)->first())) {
                        $ap = AuthorisationPilote::where('process', $nd->id)->get();
                        $a_pilotes = Users::whereIn('id', $ap->pluck('user')->unique());
                        $newmessage = new ApiSms(
                            $a_pilotes->pluck('phone')->unique()->toArray(),
                            'Cadyst PRD App',
                            "Nous tenons à vous informer que votre processus(" . $nd->name . ") est concerné(comme cause) par un incident. Le numéro de référence de cet incident est le suivant : " . $dys->code
                        );
                        $newmessage->send();
                        $emails = $a_pilotes->pluck('email')->unique()->toArray();
                        $content = view('employees.dysfunctionCpilote_appMail', ['dysfunction' => $dys, 'name' => $nd->name])->render();
                        $newmail = new ApiMail(null, $emails, 'Cadyst PRD App', "Notification d'Incident - Code de l'incident : [" . $dys->code . "]", $content, []);
                        $response = $newmail->send();
                    }
                }
                foreach ($dys->getIProcesses() as $nd) {
                    if (is_null($old_dys->getIProcesses()->where('id', $nd->id)->first())) {
                        $ap = AuthorisationPilote::where('process', $nd->id)->get();
                        $a_pilotes = Users::whereIn('id', $ap->pluck('user')->unique());
                        $emails = $a_pilotes->pluck('email')->unique()->toArray();
                        $content = view('employees.dysfunctionIpilote_appMail', ['dysfunction' => $dys, 'name' => $nd->name])->render();
                        $newmail = new ApiMail(null, $emails, 'Cadyst PRD App', "Notification d'Incident - Code de l'incident : [" . $dys->code . "]", $content, []);
                        $response = $newmail->send();
                        $newmessage = new ApiSms(
                            $a_pilotes->pluck('phone')->unique()->toArray(),
                            'Cadyst PRD App',
                            "Nous tenons à vous informer que votre processus(" . $nd->name . ") est impacté par un incident. Le numéro de référence de cet incident est le suivant : " . $dys->code
                        );
                        $newmessage->send();
                    }
                }
                $dys->save();
                dd($dys);
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
                    $task->created_by = 'Demo User';
                    $dys->save();
                    $task->save();
                }

                return redirect()->back()->with('error', "Le signalement a été mis a Jour.");
            } else {
                throw new Exception("« Il est impossible vu le statut actuel de ce dysfonctionnement, de le re-identifier de nouveau. »", 401);
            }
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }

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
    public function report()
    {

        return view('admin/dys_report');
    }
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
    public function launchEvaluation($id)
    {
        $dys = Dysfunction::find($id);
        try {
            if (Gate::authorize('isEnterpriseRQ', Enterprise::where('name', $dys->enterprise)->get()->first()) || Gate::allows('isAdmin', Auth::user())) {
                if (Gate::allows('DysCanEvaluate', [$dys != null ? $dys : null])) {
                    DB::beginTransaction();
                    if ($dys == null) {
                        throw new Exception("La ressource spécifié est introuvable.", 404);
                    }
                    if ($dys->status == 3) {
                        throw new Exception("Erreur de traitement.Ce dysfonctionnement est déja annulé.", 404);
                    }
                    $dys->status = 5;
                    $parentTasks = Task::select('tasks.id', 'tasks.text')
                        ->distinct()
                        ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
                        ->where('t2.dysfunction', $id)
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
                //dd($corrective_acts);
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
