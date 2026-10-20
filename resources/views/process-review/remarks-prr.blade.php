<div class="modal prr-scrollable-modal" id="remarks{{ $request->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10">
                    <div class="modal-title">
                        Remarks
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form action="{{ route('process-review.remarks', $request->id) }}"
                method="POST"
                onsubmit="show();"
                class="form-horizontal">
                @csrf
                <div class="modal-body">
                      <div class="row">
                            <div class="col-md-12">
                                
                                <textarea class="form-control" name="remarks" rows="3" maxlength="255" placeholder="Remarks..." required>{{ old('remarks') }}</textarea>
                                {{-- @if ($errors->has('remarks'))
                                    <div class="text-danger">{{ $errors->first('remarks') }}</div>
                                @endif --}}
                            </div>
                      </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     <button type="submit" name="submission_action" value="submit" class="btn btn-primary">Submit</button>
                </div>  
            </form>
                
        </div>
    </div>
</div>