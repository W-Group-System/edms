<div class="modal" id="viewWHI">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View WHI Policies</h5>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach ($company_policies->where('company_id',2)->groupBy('department_id') as $key=>$policies)
                    @php
                    $department = ($departments)->where('id', $key)->first();
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
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>