<div class="modal" id="acceptPRR{{ $request->id }}" data-backdrop="status" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10">
                    <h5 class="modal-title">
                        Accept Quality Process Review (PRR)
                    </h5>
                </div>
                <div class="col-md-2">
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>   
                </div>
            </div>

            <form action="{{ route('process-review.accept', $request->id) }}"
                method="POST"
                onsubmit="show();"
                class="form-horizontal"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            Remarks <span class="text-danger">*</span>:
                            {{-- <input type="text" class="form-control-sm form-control"  
                            value="{{ old('remarks') }}" name="remarks" required/> --}}
                             <textarea class="form-control" name="remarks" rows="3" maxlength="255" placeholder="Remarks..." required>{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submission_action" value="return" class="btn btn-danger">Return</button>
                    <button type="submit" name="submission_action" value="submit" class="btn btn-primary">Accept</button>
                </div>
            </form>
        </div>
    </div>
</div>