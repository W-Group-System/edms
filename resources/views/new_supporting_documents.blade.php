<div class="modal" id="new">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new supporting documents</h5>
            </div>
            <form method="POST" action="{{url('store_supporting_document')}}" enctype="multipart/form-data" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Supporting Documents
                            <select data-placeholder="Select supporting documents" name="supporting_documents" class="form-control cat">
                                <option value=""></option>
                                <option value="Problem Solving Form">Problem Solving Form</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            Title :
                            <input type="text" name="title" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            Upload Attachment :
                            <input type="file" name="attachment" class="form-control" required>
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