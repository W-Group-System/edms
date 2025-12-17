@foreach($departments as $department)
<div class="modal fade policies-modal-new" id="policiesModal{{ $department->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $department->name }} Policies
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                            <tr>
                                <th>Process</th>
                                <th>Policy</th>
                                <th>Annex</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $department->process_rel as $proces)
                                @foreach($proces->major_processes as $major_process)
                                    @php
                                        $policies = $major_process->policies;
                                        $policyCount = $policies->sum(function($policy) {
                                            return max($policy->annexes->count(), 1); 
                                        });
                                    @endphp

                                    @foreach($policies as $policy)
                                        @php
                                            $subPolicies = $policy->annexes;
                                            $subPolicyCount = max($subPolicies->count(), 1);
                                        @endphp
                                            @foreach($subPolicies as $index => $subPolicy)
                                                <tr>
                                                    @if($loop->first && $loop->parent->first)
                                                        <td rowspan="{{ $policyCount }}">
                                                            {{ $major_process->process->process_name }}
                                                        </td>
                                                    @endif

                                                    @if($index == 0)
                                                        <td rowspan="{{ $subPolicyCount }}">
                                                            <a href="{{url('view-document/'.$policy->document->id)}}"
                                                                target="_blank">
                                                                {{ $policy->document->control_code }} -
                                                                {{ $policy->document->title }}
                                                            </a>
                                                        </td>
                                                    @endif

                                                    <td>
                                                        <a href="{{url('view-document/'.$subPolicy->document->id)}}"
                                                            target="_blank">
                                                            {{ $subPolicy->document->control_code ?? '' }} - {{ $subPolicy->document->title ?? '' }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if($subPolicies->isEmpty())
                                                <tr>
                                                    @if($loop->first && $loop->parent->first)
                                                        <td rowspan="{{ $policyCount }}">
                                                            {{ $major_process->process->process_name }}
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <a href="{{url('view-document/'.$policy->document->id)}}"
                                                            target="_blank">
                                                            {{ $policy->document->control_code }} -
                                                            {{ $policy->document->title }}
                                                        </a>
                                                    </td>
                                                    <td>—</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endforeach
