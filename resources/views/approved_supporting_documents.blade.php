<div class="modal fade" id="approved{{ $supporting_document->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Approved Supporting Documents</h4>
            </div>

            <form method="POST" action="{{ route('approvedSupportingDocs', $supporting_document->id) }}" enctype="multipart/form-data" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <!-- Details -->
                    <div class="well well-sm" style="background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                        <div class="row">
                            <!-- Uploaded by -->
                            <div class="col-md-6 form-group">
                                <label class="control-label text-muted" style="font-size: 11px; font-weight: bold;">Uploaded By</label>
                                <input type="text"
                                    name="uploaded_by_name"
                                    class="form-control input-sm"
                                    value="{{ $supporting_document->uploadedBy->name }}"
                                    readonly>
                            </div>
                            <!-- Supporting Document # / Type -->
                            <div class="col-md-6 form-group">
                                <label class="control-label text-muted" style="font-size: 11px; font-weight: bold;">Document Type</label>
                                <div class="form-control input-sm" style="background-color: #eee; height: auto;">
                                    @if($supporting_document->others)
                                        {{ $supporting_document->supporting_docs }} - {{ $supporting_document->others }} 
                                    @else
                                        {{ $supporting_document->supporting_docs }}
                                    @endif
                                </div>
                            </div>
                            <!--Title-->
                            <div class="col-md-12 form-group">
                                <label class="control-label text-muted" style="font-size: 11px; font-weight: bold;">Title</label>
                                <input type="text"
                                    name="supporting_document_title"
                                    class="form-control input-sm"
                                    value="{{ $supporting_document->title }}"
                                    readonly>
                            </div>
                            <!-- Department -->
                            <div class="col-md-12 form-group">
                                <label class="control-label text-muted" style="font-size: 11px; font-weight: bold;">Department</label>
                                <div style="max-height: 150px; overflow-y: auto; background-color: #fff; border: 1px solid #ccc; border-radius: 4px; padding: 6px 12px;">
                                    @if($supporting_document->supporting_document_dept->count() > 0)
                                        <ul style="margin: 0; padding-left: 15px;">
                                            @foreach ($supporting_document->supporting_document_dept as $key => $support_docs_dept)
                                                <li>
                                                    {{ $support_docs_dept->department->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted" style="font-style: italic;">No departments assigned</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Attached File -->
                            <div class="col-md-12" style="padding-top: 5px; border-top: 1px solid #e5e5e5; margin-top: 5px;">
                                <span class="text-muted" style="font-size: 11px; font-weight: bold;">ATTACHED FILE:</span>
                                @if($supporting_document->file)
                                    <a href="{{ url($supporting_document->file) }}"
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
                    <!-- End Details -->

                    <!-- Action Processing Section -->
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 10px;">
                            <h4 style="font-size: 16px; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 8px;">
                                <i class="fa fa-cog text-primary"></i> Process Supporting Documents
                            </h4>
                        </div>

                        <!-- Action Dropdown-->
                        <div class="col-md-12 form-group">
                            <label for="supportingStatus_{{ $supporting_document->id }}" class="control-label">Action <span class="text-danger">*</span></label>
                            <select name="status" id="supportingStatus_{{ $supporting_document->id }}" class="form-control" onchange="toggleVisibility('{{ $supporting_document->id }}')" required>
                                <option value="" disabled selected>-- Select Action --</option>
                                <option value="Approved">Approved</option>
                                <option value="Declined">Declined</option>
                            </select>
                        </div>

                        <!-- Visibility Dropdown -->
                        <div class="col-md-12 form-group" id="visibilityWrapper_{{ $supporting_document->id }}" style="display: none;">
                            <label for="supportingVisibility_{{ $supporting_document->id }}" class="control-label">Visibility <span class="text-danger">*</span></label>
                            <select name="visibility" id="supportingVisibility_{{ $supporting_document->id }}" class="form-control">
                                <option value="" disabled selected>-- Select Visibility --</option>
                                <option value="Public">Public</option>
                                <option value="Private">Private</option>
                            </select>
                        </div>

                        <!-- Comments -->
                        <div class="col-md-12 form-group" style="margin-top: 10px;">
                            <label for="supporting_comment_{{ $supporting_document->id }}" class="control-label">
                                Comments <span class="text-muted" style="font-weight: normal; font-style: italic;">(Optional)</span>
                            </label>
                            <textarea class="form-control"
                                    name="supporting_comment"
                                    placeholder="Leave a comment here..."
                                    id="supporting_comment_{{ $supporting_document->id }}"
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
        var status = document.getElementById('supportingStatus_' + id).value;
        var visibilityWrapper = document.getElementById('visibilityWrapper_' + id);
        var visibilityInput = document.getElementById('supportingVisibility_' + id);

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