
<div class="modal" id="newRequest" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">New Request</h5>
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
            <form method='post' action='{{url('new-change-request')}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
       
                <div class="modal-body">
                        <input type="hidden" class="form-control-sm form-control " name="request_type" value='New'  />
                    <div class='row '>
                        <div class='col-md-12'>
                            Title :
                            <input type="text" class="form-control-sm form-control "  value="{{ old('title') }}"  name="title" required/>
                        </div>
                    </div>
                    <div class='row '>
                        {{ csrf_field() }}
                        <div class='col-md-4'>
                            Company :
                            <select name='company' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($companies as $company)
                                    <option value='{{$company->id}}' @if(old('company') == $company->id) selected @endif>{{$company->code}} - {{$company->name}}</option>
                                @endforeach
                            </select>
                         </div>
                        <div class='col-md-4'>
                            Department :
                            <select name='department' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($departments as $dep)
                                    <option value='{{$dep->id}}' @if(old('department') == $dep->id) selected @endif>{{$dep->code}} - {{$dep->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-4'>
                            Document Type :
                            <select name='category' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($document_types as $type)
                                    <option value='{{$type->name}}' @if(old('category') == $type->name) selected @endif>{{$type->code}} - {{$type->name}}</option>
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
                        <div class='col-md-4' >
                            Proposed Effectice Date :
                            <input type="date" class="form-control-sm form-control " min='{{date('Y-m-d')}}' name="effective_date" required/>
                        </div>
                        <div class='col-md-8' >
                            Draft Link <i>(Google Link)</i> :
                            <input type="name" class="form-control-sm form-control " min='{{date('Y-m-d')}}' name="draft_link" required/>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12' >
                            Description:
                            <textarea name='description' rows="5" cols="100" charswidth="23" class="form-control-sm form-control " required></textarea>
                        </div>
                    </div>
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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Approvers
                        </div>
                        <div class="panel-body">
                            @foreach($approvers as $approver)
                                <div class='row'>
                                    <div class='col-md-1 text-right border border-primary border-top-bottom border-left-right'>
                                        {{$approver->level}}
                                    </div>
                                    <div class='col-md-11 border border-primary border-top-bottom border-left-right'>
                                        {{$approver->user->name}}
                                    </div>
                                </div>
                            @endforeach
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
