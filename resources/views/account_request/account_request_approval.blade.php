@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Account Requests</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
                            {{ $account_requests->filter(fn($doc) => $doc->request_status == 'Approved' || is_null($doc->request_status))->count() }}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Declined Account Requests</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
                            {{ $account_requests->filter(fn($doc) => $doc->request_status == 'Declined')->count() }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title" style="display: flex; align-items: center; justify-content: space-between;">

                        <div class="ibox-tools" style="float: none;">
                            <form method="GET" action="" id="filterForm" style="display: inline-block; width: 150px;">
                                <select name="status_filter"
                                        id="status_filter"
                                        class="form-control input-sm"
                                        onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="Pending" {{ request('status_filter') == 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="Approved" {{ request('status_filter') == 'Approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>
                                    <option value="Declined" {{ request('status_filter') == 'Declined' ? 'selected' : '' }}>
                                        Declined
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table table-responsive">
                            <table class="table table-striped table-bordered table-hover tables" >
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Company</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Approver Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($account_requests as $account_request)

                                        @if(request('status_filter'))
                                            @php
                                                $filter = request('status_filter');
                                                if ($filter == 'Pending' && $account_request->status != 'Pending') {
                                                    continue;
                                                }

                                                if ($filter == 'Approved' && $account_request->status != 'Approved') {
                                                    continue;
                                                }

                                                if ($filter == 'Declined' && $account_request->status != 'Declined') {
                                                    continue;
                                                }
                                            @endphp
                                        @endif

                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary"  data-toggle="modal" data-target="#approveAccountrequest{{$account_request->id}}">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                            <td>
                                                {{ $account_request->name }}
                                            </td>
                                            <td>{{ $account_request->position }}</td>
                                            <td>
                                                {{ $account_request->department->name }}
                                            </td>
                                            <td>
                                                {{ $account_request->company->name }}
                                            </td>
                                            <td>{{ $account_request->reason }}</td>
                                            <td>
                                                @if($account_request->status == 'Declined')
                                                    <span class="label label-danger">
                                                        Declined
                                                    </span>
                                                @else
                                                    <span class="label label-success">
                                                        {{ $account_request->status ?? 'Approved' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $account_request->approver_remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@foreach ($account_requests as $account_request)
    @include('account_request.approve_account_requests')
@endforeach

@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function(){
         $('.cancelAccountRequest').click(function () {
            var id = this.id;
            
            swal({
                title: "Are you sure?",
                text: "This account request will be cancelled!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, cancel it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("destroy_account_request")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Cancelled!", "Request is now cancelled.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    swal("Cancelled!", "Request is now cancelled.", "success");
                    location.reload();
                });
            });
        });
        $(".cat").chosen({width:"100%"})

        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
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
    });

</script>
@endsection