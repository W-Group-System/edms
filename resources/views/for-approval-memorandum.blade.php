@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">

    {{-- Card status --}}
    <div class="row">

        {{-- For Approval Counter --}}
        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>For Approval</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        {{ $memos->where('final_status', 'Pending')->count() }}
                    </h1>
                </div>
            </div>
        </div>

        {{-- Declined Counter --}}
        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Declined</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        {{ $memos->where('final_status', 'Declined')->count() }}
                    </h1>
                </div>
            </div>
        </div>

    </div>
    {{-- End card status --}}


    {{-- Table --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <!-- Header -->
                <div class="ibox-title">
                    <h5>For Approval Memorandum</h5>
                    
                    {{-- DROPDOWN FILTER START --}}
                    <div class="ibox-tools">
                        <form method="GET" action="" id="filterForm" style="display: inline-block; width: 150px;">
                            <select name="status_filter" id="status_filter" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="Pending" {{ request('status_filter') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Declined" {{ request('status_filter') == 'Declined' ? 'selected' : '' }}>Declined</option>
                            </select>
                        </form>
                    </div>
                    {{-- DROPDOWN FILTER END --}}

                </div>
                
                <!-- Content -->
                <div class="ibox-content">
                    <div class="table table-responsive">
                        <table class="table table-striped table-bordered table-hover tables">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Department</th>
                                    <th>Memo Number</th>
                                    <th>Title</th>
                                    <th>Released Date</th>
                                    <th>Uploaded By</th>
                                    <th>Align Policy</th>
                                    <th>Attachment</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                               @foreach ($memos as $memo)
                                
                                {{-- Filter collection --}}
                                @if(request('status_filter'))
                                    @php
                                        if ($memo->final_status != request('status_filter')) {
                                            continue;
                                        }
                                    @endphp
                                @endif

                                <tr>
                                    <td>
                                        @if(empty($memo->final_status) || $memo->final_status == 'Pending' || $memo->final_status == 'For approval')
                                            <button type="button"
                                                class="btn btn-sm btn-primary"
                                                title="Approved"
                                                data-toggle="modal"
                                                data-target="#approved{{ $memo->id }}">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @else
                                            <span class="text-muted" style="font-size: 11px; font-style: italic;">Processed</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $memo->department->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $memo->memo_number }}
                                    </td>

                                    <td>
                                        {{ $memo->title }}
                                    </td>

                                    <td>
                                        {{ date('M d Y', strtotime($memo->released_date)) }}
                                    </td>

                                    <td>
                                        {{ $memo->user->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        @foreach ($memo->memorandum_document as $memo_docs)
                                            <a href="{{ url('view-document/'.$memo_docs->document->id) }}">
                                                {{ $memo_docs->document->control_code }}
                                            </a>
                                            <br>
                                        @endforeach
                                    </td>

                                    <td>
                                        <a href="{{ url($memo->file_memo) }}" target="_blank">
                                            <i class="fa fa-file"></i>
                                        </a>
                                    </td>

                                    <td>
                                        @if($memo->final_status == 'Declined')
                                            <span class="text-danger">{{ $memo->remarks ?? 'N/A' }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($memo->status == 'Private' || $memo->status == 'Declined' || $memo->final_status == 'Declined')
                                            <span class="label label-danger">
                                                Declined
                                            </span>
                                        @else
                                            <span class="label label-primary">
                                                {{ $memo->final_status ?? 'Pending' }}
                                            </span>
                                        @endif
                                    </td>
                                    

                                </tr>
                                
                                {{-- Modal --}}
                                @include('approved_memorandum')
                                
                            @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.tables').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend: 'copy' },
            { extend: 'csv' },
            { extend: 'excel', title: 'For Approval Memorandum' },
            { extend: 'pdf', title: 'For Approval Memorandum' },
            { 
                extend: 'print',
                customize: function(win) {
                    $(win.document.body).addClass('white-bg').css('font-size', '10px');
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                }
            }
        ]
    });
});
</script>
@endsection