<div class="modal" id="attachReport{{ $request->id }}" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10">
                    <h5 class="modal-title">
                        Attach Accomplished Report
                    </h5>
                </div>
                <div class="col-md-2">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form action="{{ route('process-review.attach', $request->id) }}"
                method="POST"
                onsubmit="show();"
                class="form-horizontal"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" >
                            Draft Link <i>(Google Link)</i> :
                            <input type="text" class="form-control-sm form-control" min='{{date('Y-m-d')}}' value="{{old('report_draft_link')}}" name="report_draft_link" required/>
                        </div>
                        {{-- Supporting document --}}
                        <div class="col-md-12 m-t-md" >
                            Supporting Document <small><i>(PSF,Executive Summary,Etc.)</i></small> :
                            <input type="file" class="form-control-sm form-control " accept="application/pdf" name="report_attachment"/>
                        </div>
                        
                        <div class="col-md-12 m-t-md">
                            Status Remarks :
                            <input type="text" class="form-control-sm form-control"  
                            value="{{ old('remarks') }}" name="remarks" required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>