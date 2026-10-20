<div class="modal" id="new">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new memorandum</h5>
            </div>
            <form method="POST" action="{{url('store_memorandum')}}" enctype="multipart/form-data" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Memo Number :
                            <input type="text" name="memo_number" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            Title :
                            {{-- <input type="text" name="title" class="form-control input-sm" required> --}}
                            <textarea
                                name="title"
                                class="form-control input-sm"
                                rows="3"
                                ></textarea>
                        </div>
                        <div class="col-md-12">
                            Released Date :
                            <input type="date" name="released_date" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            Type :
                            <select data-placeholder="Select type" name="type" id="type" class="form-control cat">
                                <option value=""></option>
                                <option value="Informative">Informative</option>
                                <option value="Align Policy">Align Policy</option>
                            </select>
                        </div>
                        <div class="col-md-12" id="policySelectOption" hidden>
                            Align Policy :
                            <select data-placeholder="Choose policy" name="document[]" class="form-control cat" multiple required>
                                <option value=""></option>
                                @if(auth()->user()->role == 'User')
                                    @foreach ($documents->where('department_id', auth()->user()->department_id) as $document)
                                        <option value="{{$document->id}}">{{$document->control_code .' - '.$document->title}}</option>
                                    @endforeach
                                @else
                                    @foreach ($documents as $document)
                                        <option value="{{$document->id}}">{{$document->control_code .' - '.$document->title}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-12">
                            Upload Memo :
                            <input type="file" name="memo_file" class="form-control" required>
                        </div>
                    </div>

                    <div class="row m-t-md">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Document Control Officer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($memo->departmentDcos as $key => $dco)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $dco->user->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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