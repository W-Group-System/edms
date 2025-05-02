<div class="modal" id="edit{{ $department->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit DCO</h5>
            </div>
            <form method="POST" action="{{ url('edit_dco/'.$department->id) }}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-12'>
                            Dco :
                            
                            <select name='dco[]' class='form-control-sm form-control cat' multiple required>
                                <option value=""></option>
                                @php
                                    $dco_array = $department->dco->pluck('user_id')->toArray();
                                @endphp
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if(in_array($user->id, $dco_array)) selected @endif>{{ $user->name }}</option>
                                @endforeach
                            </select>
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