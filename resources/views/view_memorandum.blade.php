<div class="modal" id="view{{$memo->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View memorandum</h5>
            </div>
            <div class="modal-body">
                <!-- Memo Details Box -->
                <div class="well well-sm" style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    <div class="row">
                        
                        <!-- Memo Number -->
                        <div class="col-md-6 form-group">
                            <label class="control-label text-muted" style="font-size: 11px; font-weight: bold;">MEMO NUMBER</label>
                            <input type="text"
                                name="memo_number"
                                class="form-control input-sm"
                                value="{{ $memo->memo_number }}"
                                readonly>
                        </div>

                        <!-- Released Date -->
                        <div class="col-md-6 form-group">
                            <label class="control-label text-muted" style="font-size: 11px; font-weight: bold;">RELEASED DATE</label>
                            <input type="date"
                                name="released_date"
                                class="form-control input-sm"
                                min="{{ date('Y-m-d') }}"
                                value="{{ $memo->released_date }}"
                                readonly>
                        </div>

                        <!-- Title -->
                        <div class="col-md-12 form-group">
                            <label class="control-label text-muted" style="font-size: 11px; font-weight: bold;">TITLE</label>
                            <textarea
                                name="title"
                                class="form-control input-sm"
                                rows="3"
                                readonly>{{ $memo->title }}</textarea>
                        </div>

                        <!-- Attached File -->
                        <div class="col-md-12" style="padding-top: 5px; border-top: 1px solid #e5e5e5; margin-top: 5px;">
                            <span class="text-muted" style="font-size: 11px; font-weight: bold;">ATTACHED FILE:</span>
                            @if($memo->file_memo)
                                <a href="{{ url($memo->file_memo) }}"
                                target="_blank"
                                class="btn btn-default btn-xs" style="margin-left: 10px;">
                                    <i class="fa fa-file-pdf-o"></i> View Memo
                                </a>
                            @else
                                <span class="text-muted" style="font-size: 12px; margin-left: 10px;">
                                    <i class="fa fa-ban"></i> No file uploaded
                                </span>
                            @endif
                        </div>

                    </div>
                </div>

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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>