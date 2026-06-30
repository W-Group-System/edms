
    <div class="modal" id="updateRequest{{$request->pre_assessment_id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Update Request</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @php
                $submit = 0; 
            @endphp
            <form method='post' action='{{url('change-request-update/'.$request->pre_assessment_id)}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
       
                {{ csrf_field() }}
                <div class="modal-body">
                        <input type="hidden" class="form-control-sm form-control " name="request_type" value="{{ $request->request_type }}"  />
                    
                        <div class='row '>
                            <div class='col-md-12'>
                                Title :
                                <input type="text" class="form-control-sm form-control "  value="{{ $request->title }}"  name="title" required/>
                            </div>
                        </div>
                    <div class='row '>
                        <div class='col-md-4'>
                            Company :
                            <select name='company' class='form-control-sm form-control cat' required>
                                @foreach($companies as $company)
                                    @if(auth()->user()->company_id == $company->id)
                                    <option value='{{$company->id}}' @if($request->company_id == $company->id) selected @endif>{{$company->code}} - {{$company->name}}</option>
                                    @endif
                                    @endforeach
                            </select>
                         </div>
                        <div class='col-md-4'>
                            Department :
                            <select name='department' class='form-control-sm form-control cat' required>
                                @foreach($departments as $dep)
                                    @if(auth()->user()->department_id == $dep->id)
                                    <option value='{{$dep->id}}' @if($request->department_id == $dep->id) selected @endif>{{$dep->code}} - {{$dep->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-4'>
                            Document Type :
                            <select name='category' class='form-control-sm form-control ' required>
                                <option value=""></option>
                                @foreach($document_types as $type)
                                    <option value='{{$type->name}}' @if($request->type_of_document == $type->name) selected @endif>{{$type->code}} - {{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class='row '>
                        <div class='col-md-12 border'>
                            <hr>
                        </div>
                    </div>
                    
                    <div class='row'>
                        {{-- <div class='col-md-4' >
                            Proposed Effective Date :
                            <input type="date" class="form-control-sm form-control " min='{{date('Y-m-d')}}' name="effective_date" required/>
                        </div> --}}
                        <div class='col-md-4' >
                            Draft Link <i>(Google Link)</i> :
                            <input type="name" class="form-control-sm form-control " min='{{date('Y-m-d')}}' value="{{$request->link_draft}}" name="draft_link" required/>
                        </div>
                        <div class='col-md-4' >
                            Supporting Document <small><i>(PSF,Executive Summary,Etc.)</i></small> :
                            <input type="file" class="form-control-sm form-control " accept="application/pdf" name="supporting_document" required/>
                        </div>
                    </div>
                    <div class='row'>
                        @php
                            $reasonForChangeArr = [
                                "Updated Regulations or Standards" => "Updated Regulations or Standards (Legal Compliance and ISO standards)",
                                "Process Improvement" => "Process Improvement (Technological Advancements & Operational Processes)",
                                "Nonconformities" => "Nonconformities (External and Internal Findings)",
                                "Document Modification" => "Document Modification (error correction, change in scope and objective, revision and new forms, minimal modifications such as adding columns, changes in formats, etc.)",
                                "Top Management Directive" => "Top Management Directive"
                            ];
                        @endphp
                        <div class="col-md-6">
                            Reason for New Request :
                            <select name="reason_for_new_request" class="form-control " id="reason-for-new-request" required>
                                <option value=""></option>
                                @foreach ($reasonForChangeArr as $key => $value)
                                    <option value="{{ $key }}" @if($request->reason_for_changes == $key) selected @endif>{{ $value }}</option>
                                @endforeach
                                {{-- <option value="Updated Regulations or Standards">Updated Regulations or Standards (Legal Compliance and ISO standards)</option>
                                <option value="Process Improvement">Process Improvement (Technological Advancements & Operational Processes)</option>
                                <option value="Nonconformities">Nonconformities (External and Internal Findings)</option>
                                <option value="Document Modification">Document Modification (error correction, change in scope and objective, revision and new forms, minimal modifications such as adding columns, changes in formats, etc.)</option>
                                <option value="Top Management Directive">Top Management Directive</option> --}}
                            </select>                            
                        </div>
                        <div class='col-md-12' >
                            Description:
                            <textarea name='description' rows="5" cols="100" charswidth="23" class="form-control-sm form-control " required>{!!nl2br(e($request->change_request))!!}</textarea>
                        </div>
                    </div>

                    @if($request->request_type == "Revision")
                    <br>
                        <div class='row'>
                            <div class='col-md-12 '>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        Reason/s for Change
                                    </div>
                                    <div class="panel-body">
                                        {!!nl2br(e($request->change_request))!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='row '>
                            <div class='col-md-6 '>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        From (Indicate clause)
                                    </div>
                                    <div class="panel-body">
                                        {!! nl2br(e($request->indicate_clause)) !!}
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        To (Indicate the changes)
                                    </div>
                                    <div class="panel-body">
                                        {!! nl2br(e($request->indicate_changes)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if((auth()->user()->role == "Document Control Officer"))
                        <div class='row'>
                            <div class='col-md-4'>
                                SOFT Copy <small><i>(.word,.csv,.ppt,etc)</i></small>
                                <input type="file" class="form-control-sm form-control " accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint"  name="soft_copy" required/>
                            </div>
                            <div class='col-md-4'>
                                PDF/Scanned Copy <small><i>(.pdf)</i></small>
                                <input type="file" class="form-control-sm form-control " accept="application/pdf"  name="pdf_copy" required/>
                            </div>
                            <div class='col-md-4'>
                                FILLABLE Copy <small><i>(.pdf)</i><small>
                                <input type="file" class="form-control-sm form-control "  name="fillable_copy" />
                            </div>
                        </div>
                    @endif
                    <div class='row '>
                        <div class='col-md-12 border'>
                            <hr>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            <b>Requestor : {{auth()->user()->name}}</b>
                        </div>
                    </div>
                    <hr>
                    Action : <b>Pre-Assessment</b>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Approvers
                        </div>
                        <div class="panel-body">
                            @foreach ($pre_assessment_approvers as $key=>$approver)
                                <div class='row'>
                                    <div class='col-md-1 text-right border border-primary border-top-bottom border-left-right'>
                                        {{-- {{$approver->level}} --}}
                                        {{$key+1}}
                                    </div>
                                    <div class='col-md-11 border border-primary border-top-bottom border-left-right'>
                                        {{$approver->user->name}}
                                    </div>
                                </div>
                            @endforeach
                            {{-- @foreach($approvers as $approver)
                                <div class='row'>
                                    <div class='col-md-1 text-right border border-primary border-top-bottom border-left-right'>
                                        {{$approver->level}}
                                    </div>
                                    <div class='col-md-11 border border-primary border-top-bottom border-left-right'>
                                        {{$approver->user->name}}
                                    </div>
                                </div>
                            @endforeach --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" @if(count($approvers) == 0) disabled @endif >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

    