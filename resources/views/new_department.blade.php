
<div class="modal" id="new_department" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">New Department</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='new-department' onsubmit='show();'  enctype="multipart/form-data" >
                <div class="modal-body">
                    <div class='row'>
                        {{ csrf_field() }}
                        <div class='col-md-12'>
                            Department Code :
                            <input type="text" class="form-control-sm form-control "  value="{{ old('code') }}"  name="code" required/>
                        </div>
                        <div class='col-md-12'>
                            Department Name :
                            <input type="text" class="form-control-sm form-control "  value="{{ old('name') }}"  name="name" required/>
                        </div>
                        <div class='col-md-12'>
                            Department Head :
                            <select name='user_id' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($employees->where('role','Department Head') as $employee)
                                    <option value='{{$employee->id}}' @if(old('user_id') == $employee->id) selected @endif>{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            Permits Accountable :
                            <select name='permit_id' class='form-control-sm form-control cat' >
                                <option value=""></option>
                                @foreach($employees as $employee)
                                    <option value='{{$employee->id}}' @if(old('permit_id') == $employee->id) selected @endif>{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
