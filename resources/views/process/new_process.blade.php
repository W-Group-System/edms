
<div class="modal" id="new_process" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">New Process</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='new-process' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                <div class="modal-body">
                    <div class='row'>
                        {{ csrf_field() }}
                        <div class='col-md-12'>
                            Process Name :
                            <input type="text" class="form-control-sm form-control "  value="{{ old('process_name') }}"  name="process_name" required/>
                        </div>
                        <div class='col-md-12'>
                            Company <i>(optional)</i> :
                            <select name='company_id' class='form-control-sm form-control cat' >
                                <option value=""></option>
                                @foreach($companies as $company)
                                    <option value='{{$company->id}}' @if(old('company_id') == $company->id) selected @endif>{{$company->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            Department :
                            <select name='department_id' class='form-control-sm form-control cat' >
                                <option value=""></option>
                                @foreach($departments as $department)
                                    <option value='{{$department->id}}' @if(old('department_id') == $department->id) selected @endif>{{$department->name}}</option>
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