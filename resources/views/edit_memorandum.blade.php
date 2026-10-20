<div class="modal" id="edit{{$memo->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit memorandum</h5>
            </div>
            <form method="POST" action="{{url('update_memorandum/'.$memo->id)}}" enctype="multipart/form-data" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Memo Number :
                            <input type="text" name="memo_number" class="form-control input-sm" value="{{$memo->memo_number}}" readonly>
                        </div>
                        <div class="col-md-12">
                            Title :
                            {{-- <input type="text" name="title" class="form-control input-sm" value="{{$memo->title}}" required> --}}
                            <textarea
                                name="title"
                                class="form-control input-sm"
                                rows="3">{{ $memo->title }}</textarea>
                        </div>
                        <div class="col-md-12">
                            Released Date :
                            <input type="date" name="released_date" class="form-control input-sm" min="{{date('Y-m-d')}}" value="{{$memo->released_date}}" required>
                        </div>
                        <div class="col-md-12">
                            Align Policy :
                            <select data-placeholder="Choose policy" name="document" class="form-control cat" required>
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
                            <input type="file" name="memo_file" class="form-control" >
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
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>