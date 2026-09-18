<div class="modal" id="approveAccountrequest{{$account_request->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request User Account</h5>
            </div>
            <form method="POST" action="{{url('approve_request_account/'.$account_request->id)}}" enctype="multipart/form-data" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="row">
                        <div class='col-md-12'>
                            Name :
                            <input type="text" class="form-control-sm form-control "  value="{{ $account_request->name }}"  name="name" readonly/>
                        </div>
                        <div class='col-md-12'>
                            Email :
                            <input type="email" class="form-control-sm form-control "  value="{{ $account_request->email }}"  name="email" readonly/>
                        </div>
                        <div class='col-md-12'>
                            Position :
                            <input type="text"  class="form-control-sm form-control" name="position"  value="{{ $account_request->position }}" readonly>
                        </div>
                        <div class='col-md-6'>
                            Company :
                            <input type="hidden" name="company" value="{{ $account_request->company_id }}">

                            <input type="text" class="form-control-sm form-control" value="{{ $account_request->company->name }}" readonly>
                        </div>
                        <div class='col-md-6'>
                            Department :
                            <input type="hidden" name="department" value="{{ $account_request->department_id }}">

                            <input type="text" class="form-control-sm form-control" value="{{ $account_request->department->name }}" readonly>
                        </div>
                        <div class='col-md-12'>
                            Reason :
                            <textarea class="form-control-sm form-control" name="reason" rows="5" readonly>{{ $account_request->reason }}</textarea>
                        </div>
                        <div class='col-md-12'>
                            Approver Remarks :
                            <textarea class="form-control-sm form-control" name="approver_remarks" rows="5" {{ $account_request->status == 'Approved' ? 'readonly' : '' }}>{{ $account_request->approver_remarks }}</textarea>
                        </div>
                        @if ($account_request->status == "Pending")
                            <div class='col-md-12'>
                                Action
                                <select name="status" class="form-control" required>
                                    <option value="" disabled selected>-- Select Action --</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Declined">Declined</option>
                                </select>
                            </div>
                        @endif
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