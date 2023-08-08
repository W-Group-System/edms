
<div class="modal" id="view_request{{$request->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">View Copy Request ({{$request->status}})</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
                <div class="modal-body">
                    <div class='row '>
                        <div class='col-md-6'>
                            Reference Number : <b>CR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</b>
                        </div>
                        <div class='col-md-6'>
                            Type of Document : {{$request->type_of_document}}
                        </div>
                        <div class='col-md-6'>
                            Date Needed : {{date('M d Y',strtotime($request->date_needed))}}
                        </div>
                        <div class='col-md-6'>
                           Copy Needed : {{$request->copy_count}}
                        </div>
                    </div>
                    <hr>
                    <div class='row '>
                        <div class='col-md-6'>
                            Control Code : {{$request->control_code}} Rev. {{$request->revision}}
                        </div>
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
                    <div class='row text-center'>
                        <div class='col-md-3 border  border-primary border-top-bottom border-left-right'>
                           Approver
                        </div>
                        <div class='col-md-3 border  border-primary border-top-bottom border-left-right'>
                           Status
                        </div>
                        <div class='col-md-3 border  border-primary border-top-bottom border-left-right'>
                           Start Date
                        </div>
                        <div class='col-md-3 border  border-primary border-top-bottom border-left-right'>
                           Action Date
                        </div>
                    </div>
                    @foreach($request->approvers as $approver)
                    <div class='row text-left'>
                        <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                           {{$approver->user->name}}
                        </div>
                        <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                            {{$approver->status}}
                        </div>
                        <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                            @if($approver->start_date != null){{$approver->start_date}}@endif &nbsp;
                        </div>
                        <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                            @if($approver->level < $request->level){{$approver->updated_at}}@endif &nbsp;
                        </div>
                    </div>
                    @endforeach
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
