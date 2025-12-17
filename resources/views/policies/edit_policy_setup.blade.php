<div class="modal fade" id="editMajorProcessModal{{ $major_process->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">New Setup</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form method="post" action="{{ url('/new-policy-edit') }}" onsubmit="show();" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="border p-3 rounded mb-3">
                        <div class="form-group mb-2">
                            <label>Process Name :</label>
                            <select name="process_id[{{ $major_process->id }}]" class="form-control form-control-sm cat">
                                <option value=""></option>
                                @foreach($processes as $process)
                                    <option value="{{ $process->id }}" 
                                        {{ $major_process->process_id == $process->id ? 'selected' : '' }}>
                                        {{ $process->process_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="major_process_id[{{ $major_process->id }}]" value="{{ $major_process->id }}">

                    <div id="policyContainerEdit{{ $major_process->id }}">
                        @foreach($major_process->policies as $policy)
                        <div class="policyRow border p-3 rounded mb-3" data-policy-id="{{ $policy->id }}">
                            <input type="hidden" name="policy_row_id[{{ $major_process->id }}][]" value="{{ $policy->id }}">
                            <div class="form-group mb-2">
                                <label>Policy :</label>
                                <select name="policy_id[{{ $major_process->id }}][]" class="form-control form-control-sm cat">
                                    <option value=""></option>
                                    @foreach($document_policies as $document)
                                        <option value="{{ $document->id }}" 
                                            {{ $policy->policy_id == $document->id ? 'selected' : '' }}>
                                            {{ $document->control_code }} - {{ $document->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="subPolicyWrapperEdit mb-2">
                                <button type="button" class="btn btn-secondary btn-sm addSubPolicyBtn mb-2">+ Add Sub Policy</button>

                                <div class="subpolicyContainerEdit mt-2">
                                    @foreach($policy->annexes as $subPolicy)
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:6px;">
                                        <input type="hidden" name="sub_policy_row_id[{{ $policy->id }}][]" value="{{ $subPolicy->id }}">
                                        <tr>
                                            <td style="width:95%;">
                                                <select name="sub_policy_id[{{ $policy->id }}][]" class="form-control form-control-sm cat">
                                                    <option value=""></option>
                                                    @foreach($document_policies as $document)
                                                        <option value="{{ $document->id }}"
                                                            {{ $subPolicy->document_id == $document->id ? 'selected' : '' }}>
                                                            {{ $document->control_code }} - {{ $document->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="width:5%; text-align:center;">
                                                <button type="button" class="btn btn-danger btn-sm removeSubPolicyBtn" data-subpolicy-id="{{ $subPolicy->id }}">×</button>
                                            </td>
                                        </tr>
                                    </table>
                                    @endforeach
                                </div>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm removePolicyEditButton mt-2">Remove Policy</button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-primary btn-sm mb-2 addPolicyBtnEdit" data-modal-id="{{ $major_process->id }}">+ Add Policy</button>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>

                <div id="deletedSubPoliciesContainer">
                    <input type="hidden" name="deleted_sub_policies[]" id="deleted_sub_policies_input">
                </div>
            </form>
        </div>
    </div>
</div>