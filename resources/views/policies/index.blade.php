@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    @include('error')
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Major Processes</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $major_processes->filter(fn($mp) => $mp->policies->isNotEmpty())->count() }}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $major_processes->where('status',null)->filter(fn($mp) => $mp->policies->isNotEmpty())->count()}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Deactivated</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($major_processes->where('status','!=',null))}}</h1>
                </div>
            </div>
        </div>
        
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Major Processes <button class="btn btn-success "  data-target="#new_policy_setup" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button></h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                            <thead>
                                <tr>
                                    
                                    <th>Process</th>
                                    <th>Policy</th>
                                    <th>Annex</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                           <tbody>
                            @foreach($major_processes as $major_process)

                                @php
                                    $policies = $major_process->policies;

                                    $majorRowCount = $policies->sum(function ($policy) {
                                        return max($policy->annexes->count(), 1);
                                    });

                                    $majorRendered = false;
                                @endphp

                                @foreach($policies as $policy)

                                    @php
                                        $subPolicies = $policy->annexes;
                                        $subPolicyCount = max($subPolicies->count(), 1);
                                        $policyRendered = false;
                                    @endphp

                                    @if($subPolicies->isNotEmpty())
                                        @foreach($subPolicies as $subPolicy)
                                            <tr>
                                                @if(!$majorRendered)
                                                    <td rowspan="{{ $majorRowCount }}">
                                                        {{ $major_process->process->process_name }}
                                                    </td>
                                                    @php $majorRendered = true; @endphp
                                                @endif

                                                @if(!$policyRendered)
                                                    <td rowspan="{{ $subPolicyCount }}">
                                                        {{ $policy->document->control_code ?? '' }} -
                                                        {{ $policy->document->title ?? '' }}
                                                    </td>
                                                    @php $policyRendered = true; @endphp
                                                @endif

                                                <td>
                                                    {{ $subPolicy->document->control_code ?? '' }} -
                                                    {{ $subPolicy->document->title ?? '' }}
                                                </td>

                                                @if($majorRendered && $loop->first && $loop->parent->first)
                                                    <td rowspan="{{ $majorRowCount }}">
                                                        @if($major_process->status)
                                                            <small class="label label-danger">Inactive</small>
                                                        @else
                                                            <small class="label label-primary">Active</small>
                                                        @endif
                                                    </td>
                                                    <td rowspan="{{ $majorRowCount }}">
                                                        @if($major_process->status)
                                                            <button class="btn btn-primary activate-major_process" id="{{ $major_process->id }}">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-warning" data-toggle="modal"
                                                                data-target="#editMajorProcessModal{{ $major_process->id }}">
                                                                <i class="fa fa-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-danger deactivate-major_process" id="{{ $major_process->id }}">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            @if(!$majorRendered)
                                                <td rowspan="{{ $majorRowCount }}">
                                                    {{ $major_process->process->process_name }}
                                                </td>
                                            @endif
                                            <td>
                                                {{ $policy->document->control_code ?? '' }} -
                                                {{ $policy->document->title ?? '' }}
                                            </td>
                                            <td>—</td>
                                            @if(!$majorRendered)
                                                <td rowspan="{{ $majorRowCount }}">
                                                    @if($major_process->status)
                                                        <small class="label label-danger">Inactive</small>
                                                    @else
                                                        <small class="label label-primary">Active</small>
                                                    @endif
                                                </td>
                                                <td rowspan="{{ $majorRowCount }}">
                                                    @if($major_process->status)
                                                        <button class="btn btn-primary activate-major_process"
                                                                id="{{ $major_process->id }}">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-warning" data-toggle="modal"
                                                                data-target="#editMajorProcessModal{{ $major_process->id }}">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-danger deactivate-major_process"
                                                                id="{{ $major_process->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>

                                                @php $majorRendered = true; @endphp
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@include('policies.new_setup')
@foreach($major_processes as $major_process)
@include('policies.edit_policy_setup', ['major_process' => $major_process])                                     
@endforeach
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>

    $(document).ready(function(){
        $('.deactivate-major_process').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This Process will be deactivated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, deactivated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("deactivate-major-process")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deactivated!", "Process is now deactivated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Deactivated!", "Process is now deactivated.", "success");
                location.reload();
                });
            });
        });
        $('.activate-major_process').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This Process will be activated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Activated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("activate-major-process")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Activated!", "Process is now activated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Activated!", "Process is now activated.", "success");
                location.reload();
                });
            });
        });
        
        $('.tables').DataTable({
            pageLength: 25,
            responsive: false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        function initChosen(context = document) {
            $(context).find('.cat').chosen({
                width: '100%'
            });
        }

    });
    document.addEventListener('click', function(e) {
         initChosen();

        if(e.target.classList.contains('addPolicyBtnEdit')) {
            const modalId = e.target.dataset.modalId;
            const policyContainerEdit = document.getElementById('policyContainerEdit' + modalId);

            const policyId = 'new_' + Date.now();
            const policyRow = document.createElement('div');
            policyRow.className = 'policyRow border p-3 rounded mb-3';
            policyRow.dataset.policyId = policyId;
            policyRow.innerHTML = `
                <div class="form-group mb-2">
                    <label>Policy :</label>
                    <select name="policy_id[${modalId}][]" class="form-control form-control-sm cat">
                        <option value=""></option>
                        @foreach($major_process->available_documents as $document)
                            <option value="{{ $document->id }}">
                                {{ $document->control_code }} - {{ $document->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="subPolicyWrapperEdit mb-2">
                    <button type="button" class="btn btn-secondary addSubPolicyBtn mb-2">+ Add Sub Policy</button>
                    <div class="subpolicyContainerEdit mt-2"></div>
                </div>

                <button type="button" class="btn btn-danger removePolicyEditButton mt-2">Remove Policy</button>
            `;
            policyContainerEdit.appendChild(policyRow);
        }

        if (e.target.classList.contains('addSubPolicyBtn')) {
            const policyRow = e.target.closest('.policyRow');
            const container = policyRow.querySelector('.subpolicyContainerEdit');
            const policyId = policyRow.dataset.policyId;

            const subRow = document.createElement('table');
            subRow.setAttribute('width', '100%');
            subRow.style.marginBottom = '6px';

            subRow.innerHTML = `
                <tr>
                    <td style="width:95%;">
                        <select name="sub_policy_id[${policyId}][]" class="form-control form-control-sm cat">
                            <option value=""></option>
                            @foreach($major_process->available_documents as $document)
                                <option value="{{ $document->id }}">
                                    {{ $document->control_code }} - {{ $document->title }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width:5%; text-align:center;">
                        <button type="button" class="btn btn-danger removeSubBtn">×</button>
                    </td>
                </tr>
            `;

            container.appendChild(subRow);
        }


        if(e.target.classList.contains('removeSubBtn')) {
            e.target.closest('table').remove();
        }

        if(e.target.classList.contains('removePolicyEditButton')) {
            const row = e.target.closest('.policyRow');
            row.remove();
        }

        if (e.target.classList.contains('removeSubPolicyBtn')) {

            const subPolicyId = e.target.dataset.subpolicyId;

            if (subPolicyId) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleted_sub_policies[]';
                input.value = subPolicyId;
                document.getElementById('deletedSubPoliciesContainer').appendChild(input);
            }

            e.target.closest('table').remove();
        }

    });

</script>
@endsection
