
<div class="modal" id="viewPreAssessmentModal-{{$pa->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">View Pre-assessment - {{$pa->status}}</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{url('approve_pre_assessment/'.$pa->id)}}" onsubmit="show()">
                {{csrf_field()}}
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            Effective Date : {{$pa->effective_date}}
                        </div>
                        <div class="col-lg-6">
                            Type of Document : {{$pa->type_of_document}}
                        </div>
                        <div class="col-lg-6">
                            Draft Link : <a href="{{$pa->link_draft}}" target="_blank">Draft Link</a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            Title : {{$pa->title}}
                        </div>
                        <div class="col-lg-6">
                            &nbsp;
                        </div>
                        <div class="col-lg-6">
                            Requested By : {{$pa->user->name}} 
                        </div>
                        <div class="col-lg-6">
                            Date Requested : {{date('M d, Y', strtotime($pa->created_at))}}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            Request Type : <strong>{{$pa->request_type}}</strong>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    Description/Remarks
                                </div>
                                <div class="panel-body">
                                    {!! nl2br(e($pa->change_request)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($pa->status == "Pending")
                        <hr>
                        <div class="row">
                            <div class="col-lg-4">
                                Action : 
                                <select name="action" id="action" class="form-control cat" required>
                                    <option value=""></option>
                                    <option value="Approved">Approved</option>
                                    <option value="Declined">Declined</option>
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @if($pa->status == "Pending")
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
