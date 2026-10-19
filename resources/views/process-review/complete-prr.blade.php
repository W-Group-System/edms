<div class="modal" id="completePRR{{ $request->id }}" data-backdrop="status" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10">
                    <h5 class="modal-title">
                        Tag as Completed
                    </h5>
                </div>
                <div class="col-md-2">
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>   
                </div>
            </div>

            <form action="{{ route('process-review.completed', $request->id) }} "
                method="POST"
                onsubmit="show()";
                class="form-horizontal"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                   <div class="row">
                        {{-- <div class="col-md-12" >
                            Draft Link <i>(Google Link) </i> : <span class="text-danger">*</span>
                            <input type="text" class="form-control-sm form-control" min='{{date('Y-m-d')}}' value="{{old('report_draft_link')}}" name="report_draft_link" required/>
                        </div>
                        <div class="col-md-12" >
                            Accomplished Report <small><i>(Signed QPRC or ES)</i></small> :
                            <input type="file" class="form-control-sm form-control " accept="application/pdf" name="report_attachment"/>
                        </div> --}}
                        <div class="col-md-12">
                            Remarks : <span class="text-danger">*</span>
                            {{-- <input type="text" class="form-control-sm form-control"  
                            value="{{ old('remarks') }}" name="remarks" required/> --}}
                            <textarea class="form-control" name="remarks" rows="3" maxlength="255" placeholder="Remarks..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submission_action" value="declined" class="btn btn-danger">Declined</button>
                    <button type="submit" name="submission_action" value="completed" class="btn btn-primary">Completed</button>
                </div>
            </form>
        </div>
    </div>
</div>