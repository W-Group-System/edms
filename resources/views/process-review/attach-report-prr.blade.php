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
                        {{-- <div class="col-md-12" >
                            Draft Link <i>(Google Link)</i> :
                            <input type="text" class="form-control-sm form-control" min='{{date('Y-m-d')}}' value="{{old('report_draft_link')}}" name="report_draft_link" required/>
                        </div> --}}
                        {{-- Accomplished Report document --}}
                        <div class="col-md-12" >
                            Accomplished Report <span class="text-danger">*</span> : 
                            <input type="file" class="form-control-sm form-control " accept="application/pdf" name="report_attachment" required/>
                        </div>
                        
                        <div class="col-md-12 m-t-md">
                            Remarks  <span class="text-danger">*</span>  :
                            {{-- <input type="text" class="form-control-sm form-control"  
                            value="{{ old('remarks', $request->remarks) }}" name="remarks" required/> --}}
                            <textarea class="form-control" name="remarks" rows="3" maxlength="255" placeholder="Remarks..." required>{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>