<?php

namespace App\Http\Controllers;

use App\Annex;
use App\Document;
use App\MajorProcess;
use App\Policy;
use App\Process;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
         $major_processes = Process::with([
            'policies.annexes'
        ])->get()->sortBy(fn ($mp) => $mp->department_id);
        $policies = Policy::get();
        $processes = Process::where('status', null)->get();

        $usedPolicyDocIds = Policy::pluck('policy_id')->toArray();
        $usedAnnexDocIds  = Annex::pluck('document_id')->toArray();

        $allUsedDocIds = array_unique(array_merge(
            $usedPolicyDocIds,
            $usedAnnexDocIds
        ));
        $allDocuments = Document::whereIn('category', [
            'POLICY',
            'PROCEDURE',
            'DEPARTMENT MANUAL',
            'ANNEX'
        ])
        ->whereNull('status')
        ->get();

        $major_processes->each(function ($mp) use ($allDocuments, $allUsedDocIds) {

            $currentPolicyDocIds = $mp->policies->pluck('policy_id')->toArray();

            $currentAnnexDocIds = $mp->policies
                ->flatMap->annexes
                ->pluck('document_id')
                ->toArray();

            $currentDocIds = array_merge($currentPolicyDocIds, $currentAnnexDocIds);

            $mp->available_documents = $allDocuments->filter(function ($doc) use ($allUsedDocIds, $currentDocIds) {
                return !in_array($doc->id, $allUsedDocIds)
                    || in_array($doc->id, $currentDocIds);
            });
        });


        return view('policies.index',
        array(
            'major_processes' => $major_processes,
            'policies' => $policies,
            'processes' => $processes,
        ));
    }

    // public function new_policy(Request $request)
    // {
    //     $processId = $request->process_id[0];

    //     foreach ($request->policy_id as $index => $documentPolicyId) {

    //         if (!$documentPolicyId) continue; 

    //         // if (MajorProcess::where('process_id', $processId)->exists()) {
    //         //     return back()->with('error', 'Process ID already exists.');
    //         // }

    //         $major_process = new MajorProcess();
    //         $major_process->process_id = $processId;
    //         $major_process->save();


    //         foreach ($request->policy_id as $index => $documentPolicyId) {

    //             if (!$documentPolicyId) continue; 

    //             $policy = new Policy;
    //             $policy->process_id = $major_process->id;
    //             $policy->policy_id  = $documentPolicyId;
    //             $policy->save();

    //             if (isset($request->sub_policy_id[$index])) {
    //                 foreach ($request->sub_policy_id[$index] as $subDocumentId) {
    //                     if (!$subDocumentId) continue;

    //                     $sub = new Annex;
    //                     $sub->policy_id   = $policy->id;   
    //                     $sub->document_id = $subDocumentId;
    //                     $sub->save();
    //                 }
    //             }
    //         }

    //     }

    //     return back()->with('success', 'Saved successfully!');
    // }

    public function new_policy(Request $request)
    {
        $processId = $request->process_id;

        foreach ($request->policy_id as $index => $documentPolicyId) {

            if (!$documentPolicyId) continue;

            $policy = new Policy();
            $policy->process_id = $processId; 
            $policy->policy_id  = $documentPolicyId;
            $policy->save();

            if (!empty($request->sub_policy_id[$index])) {
                foreach ($request->sub_policy_id[$index] as $subDocumentId) {

                    if (!$subDocumentId) continue;

                    $sub = new Annex();
                    $sub->policy_id   = $policy->id;
                    $sub->document_id = $subDocumentId;
                    $sub->save();
                }
            }
        }

        return back()->with('success', 'Saved successfully!');
    }



    public function new_policy_edit(Request $request)
    {
        foreach ($request->major_process_id as $majorProcessId => $value) {

            $processId = $request->process_id[$majorProcessId] ?? null;

            $existingPolicyIds = Policy::where('process_id', $processId)
                ->pluck('id')
                ->toArray();

            $submittedPolicyIds = [];

            if (!empty($request->policy_id[$majorProcessId])) {

                foreach ($request->policy_id[$majorProcessId] as $index => $policyDocId) {

                    if (!$policyDocId) continue;

                    $policyRowId = $request->policy_row_id[$majorProcessId][$index] ?? null;

                    if ($policyRowId && in_array($policyRowId, $existingPolicyIds)) {
                        $policy = Policy::find($policyRowId);
                    } else {
                        $policy = new Policy();
                        $policy->process_id = $processId;
                    }

                    $policy->policy_id = $policyDocId;
                    $policy->save();

                    $submittedPolicyIds[] = $policy->id;

                    $existingSubIds = $policy->annexes()
                        ->pluck('id')
                        ->toArray();

                    $submittedSubIds = [];

                    if (!empty($request->sub_policy_id[$policy->id])) {

                        foreach ($request->sub_policy_id[$policy->id] as $subIndex => $subDocId) {

                            if (!$subDocId) continue;

                            $subRowId = $request->sub_policy_row_id[$policy->id][$subIndex] ?? null;

                            if ($subRowId && in_array($subRowId, $existingSubIds)) {
                                $sub = Annex::find($subRowId);
                            } else {
                                $sub = new Annex();
                                $sub->policy_id = $policy->id;
                            }

                            $sub->document_id = $subDocId;
                            $sub->save();

                            $submittedSubIds[] = $sub->id;
                        }
                    }

                    $deleteSubIds = array_diff($existingSubIds, $submittedSubIds);
                    if (!empty($deleteSubIds)) {
                        Annex::whereIn('id', $deleteSubIds)->delete();
                    }
                }
            }

            $deletePolicies = array_diff($existingPolicyIds, $submittedPolicyIds);

            if (!empty($deletePolicies)) {
                Annex::whereIn('policy_id', $deletePolicies)->delete();
                Policy::whereIn('id', $deletePolicies)->delete();
            }
        }

        return back()->with('success', 'Major process updated successfully');
    }




    public function deactivate(Request $request)
    {
        $major_process = MajorProcess::where('id', $request->id)->first();
        $major_process->status = "deactivated";
        $major_process->save();

        return "success";
    }
    public function activate(Request $request)
    {
        $major_process = MajorProcess::where('id', $request->id)->first();
        $major_process->status = null;
        $major_process->save();

        return "success";
    }

    public function getPoliciesByProcess($processId)
    {
        $process = Process::findOrFail($processId);

        $usedPolicyDocIds = Policy::pluck('policy_id')->toArray();
        $usedAnnexDocIds = Annex::pluck('document_id')->toArray();

        $allUsedDocIds = array_unique(array_merge(
            $usedPolicyDocIds,
            $usedAnnexDocIds
        ));
        
        $documents = Document::where('department_id', $process->department_id)
            ->whereIn('category', [
                'POLICY',
                'PROCEDURE',
                'DEPARTMENT MANUAL',
                'ANNEX'
            ])
            ->whereNull('status')
            ->whereNotIn('id', $allUsedDocIds)
            ->orderBy('control_code')
            ->get();

        return response()->json($documents);
    }
}
