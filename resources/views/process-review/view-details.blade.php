<div class="modal" id="viewRequest{{ $request->id }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-10">
                    <h5 class="modal-title">
                        Quality Process Review Request (PRR)
                    </h5>
                </div>
                <div class="col-md-2">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        Reference Number : {{ $request->reference_num ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        Type of Document : {{ $request->type_of_document ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        Draft Link:
                        @if(!empty($request->report_draft_link))
                            <a href="{{ url($request->report_draft_link) }}" target="_blank">Draft Link</a>
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="col-md-6">
                        Other Attachment:
                        @if (!empty($request->report_attachment))
                            <a href="{{ url($request->report_attachment) }}" target="_blank">Link</a>
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="col-md-6">
                        Supporting Documents:
                        @if (!empty($request->supporting_documents))
                            <a href="{{ url($request->supporting_documents) }}" target="_blank">Link</a>
                        @else
                            N/A
                        @endif
                    </div>
                    
                </div>
                
                <hr>
                <div class="row">
                    @if ($request->type_of_request != "New")
                        <div class="col-md-6">
                            Control Code : {{$request->control_code ?? 'N/A'}}
                        </div>
                    @endif

                    <div class="col-md-6">
                        Title : {{ $request->document_title ?? 'N/A'}}
                    </div>
                    <div class="col-md-6">
                        Date Requested : {{ date('M d, Y', strtotime($request->created_at)) ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        Requested by : {{ $request->user->name }}
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-12">
                        Request Type : <b>{{ $request->type_of_request ?? 'N/A'}}</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        Reason for changes : <b>{{$request->reason_of_request}}</b>
                    </div>
                </div>

                <div class="row m-t-md">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                @if($request->type_of_request != "Revision")
                                    Descriptions
                                @else
                                    Reason/s for Change
                                @endif
                            </div>
                            <div class="panel-body">
                                {!!nl2br(e($request->document_description))!!}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel {{ $request->status === 'Returned' ? 'panel-danger' : 'panel-primary' }}">
                            <div class="panel-heading">
                              Request Status
                            </div>
                            <div class="panel-body">
                                {!!nl2br(e($request->remarks))!!}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row m-t-md">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Transaction History
                            </div>
                            <div class="panel-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active" role="presentation">
                                        <a href="#prr-transaction-history-{{ $request->id }}" aria-controls="prr-transaction-history-{{ $request->id }}" role="tab" data-toggle="tab">
                                            Transaction History
                                        </a>
                                    </li>
                                    @if ($request->type_of_request === 'Revision' && $request->control_code)
                                        <li role="presentation">
                                            <a href="#prr-related-change-requests-{{ $request->id }}" aria-controls="prr-related-change-requests-{{ $request->id }}" role="tab" data-toggle="tab">
                                                Related Change Requests
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content m-t-md">
                                    <div class="tab-pane active" id="prr-transaction-history-{{ $request->id }}" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped historyTables">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Action</th>
                                                        <th>Action by</th>
                                                        <th>Remarks</th>
                                                        <th>Date Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($request->transactionHistories as $index => $history)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $history->action ?? 'N/A' }}</td>
                                                            <td>{{ $history->user->name ?? 'N/A' }}</td>
                                                            <td>{{ $history->remarks ?? 'N/A' }}</td>
                                                            <td>{{ $history->created_at ? $history->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @if ($request->type_of_request === 'Revision' && $request->control_code)
                                        <div class="tab-pane" id="prr-related-change-requests-{{ $request->id }}" role="tabpanel">
                                            @if ($request->changeRequest->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped revisionTables" style="width: 100%;">
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
                                                            @foreach ($request->changeRequest as $changeRequest)
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
                                            @else
                                                <p class="text-center">No change requests found for this control code.</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
