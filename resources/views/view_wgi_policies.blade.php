<div class="modal" id="viewWGI">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View WGI Policies</h5>
            </div>
            <div class="modal-body">
                {{-- <ul class="list-group">
                    @foreach ($company_policies->where('company_id',1)->groupBy('department_id') as $key=>$policies)
                    @php
                    $department = $departments->where('id', $key)->first();
                    @endphp
                    <li class="list-group-item">
                        <b>{{ $department->name }} - {{ count($policies) }}</b>
                        <hr>
                        @foreach ($policies as $policy)
                        {{ $policy->control_code .' - '.$policy->title }} 
                        @php
                            $change_request = ($policy->change_requests)->sortByDesc('id')->first();
                        @endphp
                        @if($change_request)
                            @if($change_request->status == "Approved")
                            <span class="label label-primary">{{ date('M d Y', strtotime($change_request->updated_at)) }}</span>
                            @endif
                        @endif
                            <br>
                        @endforeach
                    </li>
                    @endforeach
                </ul> --}}
                <div class="table-responsive">
                    <table class="table table-bordered policiesTable">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Policies</th>
                                <th>Last Revision Date</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company_policies->where('company_id',1) as $company_policy)
                                <tr>
                                    <td>{{ $company_policy->department->code.' - '.$company_policy->department->name }} </td>
                                    <td>{{ $company_policy->control_code .' - '.$company_policy->title }} </td>
                                    <td>
                                        @php
                                            $change_request = ($company_policy->change_requests)->where('status','Approved')->sortByDesc('id')->first();
                                        @endphp
                                        @if($change_request)
                                            <span class="label label-primary">{{ date('Y-m-d', strtotime($change_request->updated_at)) }}</span>
                                        @endif
                                    </td>
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