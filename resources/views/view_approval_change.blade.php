
<div class="modal" id="view_request{{$request->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Change Request ({{$request->status}}) - <b class='label label-danger'>{{$request->request_type}}</b></h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='{{url('change-request-action/'.$change_approval->id)}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class='row '>
                        <div class='col-md-6'>
                            Reference Number : <b>DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</b>
                        </div>
                        <div class='col-md-6'>
                            Type of Document : {{$request->type_of_document}}
                        </div>
                        <div class='col-md-6'>
                            Effective Date : {{date('M d Y',strtotime($request->effective_date))}}
                        </div>
                        <div class='col-md-6'>
                            <div class='col-md-6'>
                                @if($request->request_type != "Obsolete")
                               Draft Link : <a href='{{$request->link_draft}}' target="_blank">Draft Link</a> <br>
                               
                                @endif
                               @if($request->original_attachment_pdf != null)
                               Original PDF Link : <a href='{{url($request->original_attachment_pdf)}}' target="_blank">Link</a> <br>
                               @endif
                               {{-- @if($request->original_attachment_soft_copy != null)
                               Original Soft Copy : <a href='{{url($request->original_attachment_soft_copy)}}' target="_blank">Link</a> <br>
                               @endif --}}
                            </div>
                           
                        </div>
                    </div>
                    <hr>
                    <div class='row '>
                        @if($request->request_type != "New")
                        <div class='col-md-6'>
                            Control Code : {{$request->control_code}} Rev. {{$request->revision}}
                        </div>
                        @endif
                        <div class='col-md-6'>
                            Title : {{$request->title}}
                        </div>
                    </div>
                    <div class='row '>
                        <div class='col-md-6'>
                            Requested By : {{$request->user->name}} 
                        </div>
                        <div class='col-md-6'>
                            Date Requested : {{date('M d, Y',strtotime($request->created_at))}}
                        </div>
                    </div>
                    <hr>
                    <div class='row'>
                        <div class='col-md-12 '>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    @if($request->request_type != "Revision")
                                        Descriptions/Remarks
                                    @else
                                    Overall Description of Change
                                    @endif
                                </div>
                                <div class="panel-body">
                                   {!!nl2br(e($request->change_request))!!}
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                    @if($request->request_type == "Revision")
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
                    <hr>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Approvers
                        </div>
                        <div class="panel-body">
                            <div class='row'>
                                <div class='col-md-3  border border-primary border-top-bottom border-left-right'>
                                    Name
                                </div>
                                <div class='col-md-3  border border-primary border-top-bottom border-left-right'>
                                    Status
                                </div>
                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                    Start Date
                                </div>
                                <div class='col-md-2  border border-primary border-top-bottom border-left-right'>
                                   Action Date
                                </div>
                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                    Remarks
                                </div>
                            </div>
                            @foreach($request->approvers as $approver)
                            
                        
                                        <div class='row'>
                                            <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                {{$approver->user->name}}
                                            </div>
                                            <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                {{$approver->status}}
                                            </div>
                                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                @if($approver->start_date != null){{$approver->start_date}}@endif &nbsp;
                                            </div>
                                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                @if($approver->status != "Waiting"){{date('Y-m-d',strtotime($approver->updated_at))}}@endif &nbsp;
                                            </div>
                                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                {!! nl2br(e($approver->remarks))!!}&nbsp;
                                            </div>
                                        </div>
                            
                            @endforeach
                        </div>
                    </div>
                    <hr>
                
                    <div class='row'>
                        @if($request->soft_copy != null)
                        <div class='col-md-4'>
                            SOFT Copy : <a href='{{url($request->soft_copy)}}' target="_blank" ><i class="fa fa-file-word-o"></i> Editable Copy</a>
                        </div>
                        @endif
                        @if($request->pdf_copy != null)
                        <div class='col-md-4'>
                            PDF/Scanned Copy : <a href='{{url($request->pdf_copy)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> PDF Copy</a>
                        </div>
                        @endif
                        @if($request->fillable_copy != null)
                        <div class='col-md-4'>
                            FILLABLE Copy : <a href='{{url($request->fillable_copy)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> Fillable Copy</a>
                        </div>
                        @endif
                    </div>
                    <hr>
                    @if((auth()->user()->role == "Document Control Officer") && ($request->request_type != "Obsolete"))
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
                        
                    <div class='row'>
                        <div class='col-md-4'>
                            Action :
                            <select name='action' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                <option value="Approved" >Approve</option>
                                <option value="Declined" >Decline</option>
                            </select>
                        </div>
                        <div class='col-md-8'>
                            Remarks :
                            <textarea name='remarks' class='form-control-sm form-control' required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type='submit'  class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
