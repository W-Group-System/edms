<div class="modal" id="approved{{ $memo->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approved Memorandum</h5>
            </div>
        
            <form method="POST" action="{{ route('approvedMemorandum', $memo->id) }}" enctype="multipart/form-data" onsubmit="show()">
                @csrf

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
                                <input type="text"
                                    name="title"
                                    class="form-control input-sm"
                                    value="{{ $memo->title }}"
                                    readonly>
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

                    <!-- Action Processing Section -->
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <h4 style="font-size: 16px; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 8px;">
                                <i class="fa fa-cog text-primary"></i> Process Memorandum
                            </h4>
                        </div>

                        <!-- Action Dropdown-->
                        <div class="col-md-12 form-group">
                            <label for="memoStatus_{{ $memo->id }}" class="control-label">Action <span class="text-danger">*</span></label>
                            <select name="status" id="memoStatus_{{ $memo->id }}" class="form-control" onchange="toggleVisibility('{{ $memo->id }}')" required>
                                <option value="" disabled selected>-- Select Action --</option>
                                <option value="Approved">Approved</option>
                                <option value="Declined">Declined</option>
                            </select>
                        </div>

                        <!-- Visibility Dropdown -->
                        <div class="col-md-12 form-group" id="visibilityWrapper_{{ $memo->id }}" style="display: none;">
                            <label for="memoVisibility_{{ $memo->id }}" class="control-label">Visibility <span class="text-danger">*</span></label>
                            <select name="visibility" id="memoVisibility_{{ $memo->id }}" class="form-control">
                                <option value="" disabled selected>-- Select Visibility --</option>
                                <option value="Public">Public</option>
                                <option value="Private">Private</option>
                            </select>
                        </div>

                        <!-- Comments -->
                        <div class="col-md-12 form-group" style="margin-top: 10px;">
                            <label for="memo_comment_{{ $memo->id }}" class="control-label">
                                Comments <span class="text-muted" style="font-weight: normal; font-style: italic;">(Optional)</span>
                            </label>
                            <textarea class="form-control"
                                    name="memo_comment"
                                    placeholder="Leave a comment here..."
                                    id="memo_comment_{{ $memo->id }}"
                                    rows="3"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleVisibility(id) {
        var status = document.getElementById('memoStatus_' + id).value;
        var visibilityWrapper = document.getElementById('visibilityWrapper_' + id);
        var visibilityInput = document.getElementById('memoVisibility_' + id);

        if (status === 'Approved') {
            visibilityWrapper.style.display = 'block';
            visibilityInput.setAttribute('required', 'required');
        } else {
            visibilityWrapper.style.display = 'none';
            visibilityInput.removeAttribute('required');
            visibilityInput.value = ''; 
        }
    }
</script>

