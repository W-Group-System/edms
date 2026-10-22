<div class="modal prr-scrollable-modal edit-prr-modal" id="editRequest{{ $request->id }}" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10">
                    <h5 class="modal-title">
                        Edit Quality Process Review Request Details
                    </h5>
                </div>
                <div class="col-md-2">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form action="{{ route('process-review.update', $request->id) }}"
                method="POST"
                onsubmit="show();"
                class="form-horizontal"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        {{-- Request type --}}
                        <div class="col-md-6">
                            Request Type :
                            <select name="type_of_request" class="form-control-sm form-control edit-type-of-request" required>
                                <option value="" {{ old('type_of_request', $request->type_of_request) == '' ? 'selected' : '' }}>
                                    Select Options
                                </option>
                                <option value="New" {{ old('type_of_request', $request->type_of_request) == 'New' ? 'selected' : '' }}>
                                    New
                                </option>
                                <option value="Revision" {{ old('type_of_request', $request->type_of_request) == 'Revision' ? 'selected' : '' }}>
                                    Revision
                                </option>
                            </select>
                        </div>

                        {{-- Reason for request --}}
                        <div class="col-md-6">
                            Reason for Request :
                            <select name="reason_for_new_request" class="form-control" required>
                                <option value="" {{ old('reason_for_new_request', $request->reason_of_request) == '' ? 'selected' : '' }}>
                                    Select an option
                                </option>
                                <option value="Updated Regulations or Standards" {{ old('reason_for_new_request', $request->reason_of_request) == 'Updated Regulations or Standards' ? 'selected' : '' }}>
                                    Updated Regulations or Standards (Legal Compliance and ISO standards)
                                </option>
                                <option value="Process Improvement" {{ old('reason_for_new_request', $request->reason_of_request) == 'Process Improvement' ? 'selected' : '' }}>
                                    Process Improvement (Technological Advancements & Operational Processes)
                                </option>
                                <option value="Nonconformities" {{ old('reason_for_new_request', $request->reason_of_request) == 'Nonconformities' ? 'selected' : '' }}>
                                    Nonconformities (External and Internal Findings)
                                </option>
                                <option value="Document Modification" {{ old('reason_for_new_request', $request->reason_of_request) == 'Document Modification' ? 'selected' : '' }}>
                                    Document Modification (error correction, change in scope and objective, revision and new forms, minimal modifications such as adding columns, changes in formats, etc.)
                                </option>
                                <option value="Top Management Directive" {{ old('reason_for_new_request', $request->reason_of_request) == 'Top Management Directive' ? 'selected' : '' }}>
                                    Top Management Directive
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="edit-revision-document-list m-t-md" style="{{ old('type_of_request', $request->type_of_request) === 'Revision' ? '' : 'display: none;' }}">
                        <h4>Select the document to revise</h4>
                        <input type="search"
                            class="form-control edit-revision-document-search"
                            placeholder="Search documents..."
                            aria-label="Search documents to revise">
                        <div style="max-height: 300px; overflow-y: auto; padding: 0 10px;">
                            @forelse($documents as $document)
                                <label class="search-result edit-revision-document-option" style="display: block; cursor: pointer;">
                                    <input type="radio"
                                        name="document_id"
                                        value="{{ $document->id }}"
                                        data-title="{{ $document->title }}"
                                        data-control-code="{{ $document->control_code }}"
                                        data-category="{{ $document->category }}"
                                        {{ old('document_id', $request->document_id) == $document->id ? 'checked' : '' }}>
                                    <strong>
                                        <i>({{ $document->old_control_code }})</i>
                                        {{ $document->control_code }} Rev. {{ $document->version }}
                                    </strong>
                                    @if($document->public == null)
                                        <span class="label label-danger">Private</span>
                                    @else
                                        <span class="label label-primary">Public</span>
                                    @endif
                                    <br>
                                    Title : {{ $document->title }}<br>
                                    Process Owner :
                                    @if($document->processOwner)
                                        <span class="label label-info">{{ $document->processOwner->name }}</span>
                                    @elseif($document->department && $document->department->dep_head)
                                        <span class="label label-info">{{ $document->department->dep_head->name }}</span>
                                    @else
                                        N/A
                                    @endif
                                    <p>
                                        Date Effective : {{ $document->updated_at ? $document->updated_at->format('M d, Y') : 'N/A' }}<br>
                                        Company : {{ $document->department->name ?? 'N/A' }}
                                    </p>
                                </label>
                                <div class="hr-line-dashed"></div>
                            @empty
                                <p>No active documents are available for revision.</p>
                            @endforelse
                            <p class="edit-revision-document-no-results" style="display: none;">No documents match your search.</p>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 m-t-md">
                            Title :
                            <input type="text" class="form-control-sm form-control"
                                value="{{ old('title', $request->document_title) }}" name="title" required/>
                        </div>
                    </div>
                    <div class="row edit-revision-control-code-row" style="{{ old('type_of_request', $request->type_of_request) === 'Revision' ? '' : 'display: none;' }}">
                        <div class="col-md-12 m-t-md">
                            Control Code :
                            <input type="text" name="control_code" class="form-control-sm form-control edit-revision-control-code"
                                value="{{ old('control_code', $request->control_code) }}" readonly
                                {{ old('type_of_request', $request->type_of_request) === 'Revision' ? 'required' : '' }}/>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Company --}}
                        <div class="col-md-4 m-t-md">
                            Company :
                            <select name="company" class="form-control-sm form-control cat" required>
                                @foreach($companies as $company)
                                    @if(auth()->user()->company_id == $company->id)
                                        <option value="{{ $company->id }}" {{ old('company', $request->company_id) == $company->id ? 'selected' : '' }}>
                                            {{$company->code}} {{$company->name}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        {{-- Department --}}
                        <div class="col-md-4 m-t-md">
                            Department :
                            <select name="department" class="form-control-sm form-control cat" required>
                                @foreach($departments as $dep)
                                    @if(auth()->user()->department_id == $dep->id)
                                        <option value="{{ $dep->id }}" {{ old('department', $request->department_id) == $dep->id ? 'selected' : '' }}>
                                            {{$dep->code}} {{$dep->name}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        {{-- Document Type --}}
                        <div class="col-md-4 m-t-md">
                            Document Type :
                            <input type="text"
                                   class="form-control-sm form-control edit-new-category"
                                   name="category"
                                   value="Executive Summary"
                                   style="{{ old('type_of_request', $request->type_of_request) === 'New' ? '' : 'display: none;' }}"
                                   {{ old('type_of_request', $request->type_of_request) === 'New' ? '' : 'disabled' }}
                                   {{ old('type_of_request', $request->type_of_request) === 'New' ? 'required' : '' }}
                                   readonly
                            >
                            <select name="category"
                                    class="form-control-sm form-control edit-revision-category"
                                    style="{{ old('type_of_request', $request->type_of_request) === 'New' ? 'display: none;' : '' }}"
                                    {{ old('type_of_request', $request->type_of_request) === 'New' ? 'disabled' : '' }}
                                    {{ old('type_of_request', $request->type_of_request) === 'New' ? '' : 'required' }}>
                                <option value="" {{ old('type_of_request', $request->type_of_request) === '' || old('category', $request->type_of_document) == '' ? 'selected' : '' }}>Select an option</option>
                                @foreach($document_types as $type)
                                    <option value="{{ $type->name }}" {{ old('category', $request->type_of_document) == $type->name ? 'selected' : '' }}>
                                        {{$type->code}} {{$type->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row m-t-md">
                        {{-- Draft link --}}
                        {{-- <div class="col-md-6">
                            Draft Link <i>(Google Link)</i> :
                            <input type="text" class="form-control-sm form-control"
                                value="{{ old('link_draft', $request->link_draft) }}" name="link_draft" required/>
                        </div> --}}
                        {{-- Supporting document --}}
                        <div class="col-md-6">
                            Supporting Document <small><i>(PSF,Executive Summary,Etc.)</i></small> :
                            <input type="file" class="form-control-sm form-control" accept="application/pdf" name="supporting_document"/>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="row m-t-md">
                        <div class="col-md-12">
                            Description:
                            <textarea name="description" rows="5" class="form-control-sm form-control" required>{{ old('description', $request->document_description) }}</textarea>
                        </div>
                    </div>

                    <div class="row m-t-md">
                        <div class="col-md-12">
                            <b>Requestor : {{auth()->user()->name}}</b>
                        </div>
                    </div>
                    <div class="panel panel-primary m-t-md">
                        <div class="panel-heading">Revision and DCO Information</div>
                        <div class="panel-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active" role="presentation">
                                    <a href="#edit-prr-dco-{{ $request->id }}" aria-controls="edit-prr-dco-{{ $request->id }}" role="tab" data-toggle="tab">
                                        Approvers
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#edit-prr-revision-list-{{ $request->id }}" aria-controls="edit-prr-revision-list-{{ $request->id }}" role="tab" data-toggle="tab">
                                        Revisions
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content m-t-md">
                                <div class="tab-pane active" id="edit-prr-dco-{{ $request->id }}" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Quality Process Review</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($department_dco as $key => $dco)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $dco->user->name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="edit-prr-revision-list-{{ $request->id }}" role="tabpanel">
                                    <div class="edit-selected-document-change-requests">
                                        <h4>Revision List/s</h4>
                                        <p class="edit-no-document-selected" style="{{ old('document_id', $request->document_id) ? 'display: none;' : '' }}">
                                            Select a document to view its revision requests.
                                        </p>
                                        @foreach($documents as $document)
                                            <div class="edit-selected-document-change-request"
                                                data-document-id="{{ $document->id }}"
                                                style="{{ old('document_id', $request->document_id) == $document->id ? '' : 'display: none;' }}">
                                                <div class="table-responsive">
                                                    <table class="table table-striped revisionTables">
                                                        <thead>
                                                            <tr>
                                                                <th>Control Code</th>
                                                                <th>Title</th>
                                                                <th>Type of Request</th>
                                                                <th>Requested Date</th>
                                                                <th>Requestor</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $documentChangeRequests = $changeRequests->where('control_code', $document->control_code);
                                                            @endphp
                                                            @foreach($documentChangeRequests as $changeRequest)
                                                                <tr>
                                                                    <td>{{ $changeRequest->control_code }}</td>
                                                                    <td>{{ $changeRequest->title ?? 'N/A' }}</td>
                                                                    <td>{{ $changeRequest->request_type ?? 'N/A' }}</td>
                                                                    <td>{{ $changeRequest->created_at ? $changeRequest->created_at->format('M d, Y') : 'N/A' }}</td>
                                                                    <td>{{ optional($changeRequest->user)->name ?? 'N/A' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submission_action" value="draft" class="btn btn-secondary">Save as Draft</button>
                    <button type="submit" name="submission_action" value="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>