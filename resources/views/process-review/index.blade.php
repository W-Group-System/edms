@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<style>
    /* Index table*/
    .ibox-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ibox-title h5 {
        margin: 0;
    }
    .tables td.dataTables_empty {
        text-align: center;
    }
    .tables th.prr-wrap-column,
    .tables td.prr-wrap-column {
        white-space: normal;
        overflow-wrap: anywhere;
    }
    .tables th.prr-title-column,
    .tables td.prr-title-column {
        min-width: 240px;
    }
    .tables th.prr-status-column,
    .tables td.prr-status-column {
        min-width: 280px;
    }

    /* History */
    .history-table-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 4px;
    }
    .history-table-footer .dataTables_paginate,
    .history-table-footer .pagination {
        margin: 0;
        padding-top: 0;
    }
    .historyTables td.dataTables_empty {
        text-align: center;
    }
    /* End */

    /* Revision table */
    .revisionTables_wrapper {
        width: 100%;
    }
    .revisionTables.dataTable {
        width: 100% !important;
    }
    .revisionTables td.dataTables_empty {
        text-align: center;
    }
    /* End */

    .prr-scrollable-modal .modal-content {
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 60px);
    }
    .prr-scrollable-modal form {
        display: flex;
        flex: 1 1 auto;
        flex-direction: column;
        min-height: 0;
    }
    .prr-scrollable-modal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
    }
    .prr-status-filter {
        width: 100%;
        padding: 0;
        border: 0;
        background: transparent;
        text-align: left;
    }
    .prr-status-filter:focus {
        outline: 2px solid #1ab394;
        outline-offset: 3px;
    }
</style>
@endsection
@section('content')
<div class="wrapper wrappter-content">
    <div class="row m-t-md">
        @foreach (['Pending', 'Returned', 'Accepted' ,'Completed',] as $status)
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ $status }}</h5>
                    </div>
                    <div class="ibox-content">
                        <button type="button" class="prr-status-filter" data-status="{{ $status }}" aria-pressed="false">
                            <span class="h1 no-margins text-success">{{ $prr->where('status', $status)->count() }}</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>All Requests</h5>
                </div>
                <div class="ibox-content">
                    <button type="button" class="prr-status-filter" data-status="" aria-pressed="true">
                        <span class="h1 no-margins">{{ $prr->count() }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
  
    <div class="row m-t-md">
        <div class="col-lg-12">
            @include('error')
            <div class="ibox float-e-margins">
                <div class="ibox-title d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Process Review Request (PRR)</h5>
                    @if(auth()->user()->role == "User" || auth()->user()->role == "Department Head")
                        <button class="btn btn-sm btn-success" data-target="#createPRRModal" data-toggle="modal" type="button"><i class="fa fa-plus"></i> New</button>
                    @endif
                </div>
                
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Reference No.</th>
                                    <th>Control Code</th>
                                    <th>Date requested</th>
                                    <th class="prr-title-column prr-wrap-column">Title</th>
                                    <th>Document type</th>
                                    <th>Request type</th>
                                    <th>Requested by</th>
                                    <th class="prr-status-column prr-wrap-column">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prr as $request)
                                    <tr>
                                        <td>
                                            <a href="#"  data-target="#viewRequest{{$request->id}}" data-toggle="modal" class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a>
                                            @if (in_array($request->status, ["Draft", "Returned"]) && auth()->user()->id == $request->user_id)
                                                <a href="#"  data-target="#editRequest{{$request->id}}" data-toggle="modal" class='btn btn-sm btn-warning'><i class="fa fa-pencil"></i></a>
                                            @endif

                                            @if (auth()->user()->role == "Document Control Officer")
                                            
                                                @if($request->status == "Pending")
                                                    {{-- Accept --}}
                                                    <a href="#" data-target="#acceptPRR{{ $request->id }}" data-toggle="modal" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>
                                                @elseif($request->status == "Accepted")
                                                    @if(empty($request->report_draft_link))
                                                        {{-- Attachment --}}
                                                        <a href="#" data-target="#attachReport{{ $request->id }}" data-toggle="modal" class="btn btn-sm btn-warning"><i class="fa fa-paperclip"></i></a>
                                                    @else
                                                        {{-- Complete --}}
                                                        <a href="#" data-target="#completePRR{{ $request->id }}" data-toggle="modal" class="btn btn-sm btn-warning"><i class="fa fa-check"></i></a>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $request->reference_num }}</td>
                                        <td>{{ $request->control_code ?? 'N/A' }}</td>
                                        <td>{{ date('M d, Y', strtotime($request->created_at )) ?? 'N/A' }}</td>
                                        <td class="prr-title-column prr-wrap-column">{{ $request->document_title }}</td>
                                        <td>{{ $request->type_of_document }}</td>
                                        <td>{{ $request->type_of_request }}</td>
                                        <td>{{ $request->user->name ?? 'Unknown user' }}</td>
                                        <td class="prr-status-column prr-wrap-column" data-search="{{ $request->status }}">
                                            {{ $request->remarks ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @foreach ($prr as $request)
                        @include('process-review.view-details')
                        @include('process-review.edit-details')
                        @include('process-review.accept-prr')
                        @include('process-review.attach-report-prr')
                        @include('process-review.complete-prr')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@include('process-review.create-prr')
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function(){
        var $revisionDocumentList = $('#revision-document-list');
        var $revisionDocumentChoices = $revisionDocumentList.find('input[name="document_id"]');
        var $revisionControlCodeRow = $('#revision-control-code-row');
        var $revisionControlCode = $('#revision-control-code');
        var $prrTitle = $('#prr-title');
        var $prrCategory = $('#prr-category');
        var $changeRequestHistory = $('#selected-document-change-requests');
        var $documentChangeRequests = $changeRequestHistory.find('.selected-document-change-requests');
        
        function toggleRevisionDocuments() {
            var isRevision = $('#type-of-request').val() === 'Revision';
            $revisionDocumentList.toggle(isRevision);
            $revisionDocumentChoices.prop('required', isRevision);
            $revisionControlCode.prop('required', isRevision);
            updateSelectedRevision();
        }
        function updateSelectedRevision() {
            var isRevision = $('#type-of-request').val() === 'Revision';
            var $selectedDocument = $revisionDocumentChoices.filter(':checked');

            $revisionControlCodeRow.toggle(isRevision);
            $documentChangeRequests.hide();
            $changeRequestHistory.toggle(isRevision && $selectedDocument.length > 0);

            if (isRevision && $selectedDocument.length) {
                $documentChangeRequests
                    .filter('[data-document-id="' + $selectedDocument.val() + '"]')
                    .show();
                var changeRequestTable = $documentChangeRequests
                    .filter('[data-document-id="' + $selectedDocument.val() + '"]')
                    .find('.revisionTables');
                if (changeRequestTable.length && !$.fn.DataTable.isDataTable(changeRequestTable[0])) {
                    changeRequestTable.DataTable({
                        pageLength: 5,
                        paging: true,
                        searching: true,
                        info: false,
                        lengthChange: false,
                        ordering: false,
                        responsive: true,
                        language: {
                            emptyTable: 'No change requests found for this control code.'
                        }
                    });
                } else if (changeRequestTable.length) {
                    changeRequestTable.DataTable().columns.adjust().draw(false);
                }
                $prrTitle.val($selectedDocument.data('title'));
                $revisionControlCode.val($selectedDocument.data('control-code'));
                $prrCategory.val($selectedDocument.data('category')).trigger('change');
            } else {
                $revisionControlCode.val('');
            }
        }
        $('#type-of-request').on('change', function(){
            if ($(this).val() !== 'Revision') {
                $revisionDocumentChoices.prop('checked', false);
                $prrTitle.val('');
                $revisionControlCode.val('');
                $prrCategory.val('').trigger('change');
            }
            toggleRevisionDocuments();
        });
        $revisionDocumentChoices.on('change', updateSelectedRevision);
        $('#createPRRModal').on('shown.bs.modal', updateSelectedRevision);
        toggleRevisionDocuments();

        $('.edit-prr-modal').each(function() {
            var $modal = $(this);
            var $typeOfRequest = $modal.find('.edit-type-of-request');
            var $documentList = $modal.find('.edit-revision-document-list');
            var $documentChoices = $documentList.find('input[name="document_id"]');
            var $controlCodeRow = $modal.find('.edit-revision-control-code-row');
            var $controlCode = $modal.find('.edit-revision-control-code');
            var $title = $modal.find('input[name="title"]');
            var $category = $modal.find('select[name="category"]');
            var $changeRequestTables = $modal.find('.edit-selected-document-change-request');
            var $noDocumentSelected = $modal.find('.edit-no-document-selected');

            function updateEditRevision() {
                var isRevision = $typeOfRequest.val() === 'Revision';
                var $selectedDocument = $documentChoices.filter(':checked');

                $documentList.toggle(isRevision);
                $controlCodeRow.toggle(isRevision);
                $documentChoices.prop('required', isRevision);
                $controlCode.prop('required', isRevision);
                $changeRequestTables.hide();

                if (isRevision && $selectedDocument.length) {
                    $changeRequestTables
                        .filter('[data-document-id="' + $selectedDocument.val() + '"]')
                        .show();
                    $controlCode.val($selectedDocument.data('control-code'));
                } else if (!isRevision) {
                    $documentChoices.prop('checked', false);
                    $controlCode.val('');
                }
                $noDocumentSelected.toggle(!isRevision || !$selectedDocument.length);
            }

            $typeOfRequest.on('change', updateEditRevision);
            $documentChoices.on('change', function() {
                var $selectedDocument = $(this);
                $title.val($selectedDocument.data('title'));
                $controlCode.val($selectedDocument.data('control-code'));
                $category.val($selectedDocument.data('category')).trigger('change');
                updateEditRevision();
            });

            $modal.find('.edit-revision-document-search').on('input', function() {
                var query = $(this).val().toLowerCase();
                var visibleDocuments = 0;

                $documentList.find('.edit-revision-document-option').each(function() {
                    var $option = $(this);
                    var matches = $option.text().toLowerCase().indexOf(query) !== -1;
                    $option.toggle(matches);
                    $option.next('.hr-line-dashed').toggle(matches);
                    if (matches) {
                        visibleDocuments++;
                    }
                });

                $modal.find('.edit-revision-document-no-results')
                    .toggle(visibleDocuments === 0 && $documentChoices.length > 0);
            });

            updateEditRevision();
        });

        $('#revision-document-search').on('input', function(){
            var query = $(this).val().toLowerCase();
            var visibleDocuments = 0;

            $revisionDocumentList.find('.revision-document-option').each(function(){
                var $document = $(this);
                var matches = $document.text().toLowerCase().indexOf(query) !== -1;
                $document.toggle(matches);
                $document.next('.hr-line-dashed').toggle(matches);
                if (matches) {
                    visibleDocuments++;
                }
            });

            $('#revision-document-no-results').toggle(visibleDocuments === 0 && $revisionDocumentChoices.length > 0);
        });

        $('.cat').chosen({width: "100%"});
        var prrTable = $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                    }
                }
            ]
        });
        $('.prr-status-filter').on('click', function() {
            var status = $(this).data('status');
            var exactStatusPattern = status
                ? '^' + $.fn.dataTable.util.escapeRegex(status) + '$'
                : '';

            prrTable.column(8).search(exactStatusPattern, true, false).draw();
            $('.prr-status-filter').attr('aria-pressed', 'false');
            $(this).attr('aria-pressed', 'true');
        });
        $('.historyTables').DataTable({
            paging: true,
            searching: false,
            info: false,
            lengthChange: false,
            ordering: false,
            dom: 't<"history-table-footer"p>'
        });

        $('.revisionTables').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    paging: true,
                    searching: true,
                    info: false,
                    lengthChange: false,
                    ordering: false,
                    autoWidth: false,
                    responsive: true,
                    dom: 't<"history-table-footer"p>',
                    language: {
                        emptyTable: 'No change requests found for this control code.'
                    }
                });
            }
        });

        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function() {
            var target = $(this).attr('href');
            if (!target || target.charAt(0) !== '#') {
                return;
            }

            $(target).find('.revisionTables').each(function() {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable().columns.adjust().draw(false);
                }
            });
        });

        $(document).on('shown.bs.modal', '.prr-scrollable-modal', function() {
            $(this).find('.revisionTables:visible').each(function() {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable().columns.adjust().draw(false);
                }
            });
        });
    });
</script>
@endsection