<div class="modal" id="newaccountrequest">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request User Account</h5>
            </div>
            <form method="POST" action="{{url('store_request_account')}}" enctype="multipart/form-data" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="row">
                        <div class='col-md-12'>
                            Name :
                            <input type="text" class="form-control-sm form-control "  value="{{ old('name') }}"  name="name" required/>
                        </div>
                        <div class='col-md-12'>
                            Email :
                            <input type="email" class="form-control-sm form-control "  value="{{ old('email') }}"  name="email" required/>
                        </div>
                        <div class='col-md-12'>
                            Position :
                            <input type="text"  class="form-control-sm form-control" name="position" value="">
                        </div>
                        <div class='col-md-12'>
                            Company :
                            <input type="hidden" name="company" value="{{ auth()->user()->company_id }}">

                            <input type="text" class="form-control-sm form-control" value="{{ auth()->user()->company->name }}" readonly>
                        </div>
                        <div class='col-md-12'>
                            Department :
                            <input type="hidden" name="department" value="{{ auth()->user()->department_id }}">

                            <input type="text" class="form-control-sm form-control" value="{{ auth()->user()->department->name }}" readonly>
                        </div>
                        <div class='col-md-12'>
                            Reason :
                            <textarea class="form-control-sm form-control" name="reason" rows="5"></textarea>
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