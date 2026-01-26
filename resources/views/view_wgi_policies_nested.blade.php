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
                            @php $processNo = 1; @endphp
                            @foreach($department->process_rel as $process)

                                @php
                                    $policies = $process->policies;

                                    $processRowCount = $policies->sum(function ($policy) {
                                        return max($policy->annexes->count(), 1);
                                    });

                                    $processRendered = false;
                                @endphp

                                @foreach($policies as $policy)

                                    @php
                                        $subPolicies     = $policy->annexes;
                                        $subPolicyCount = max($subPolicies->count(), 1);
                                        $policyRendered = false;
                                    @endphp

                                    @if($subPolicies->isNotEmpty())
                                        @foreach($subPolicies as $subPolicy)
                                            <tr>
                                                @if(!$processRendered)
                                                    <td rowspan="{{ $processRowCount }}">
                                                        {{ $process->process_name }}
                                                    </td>
                                                    @php $processRendered = true; @endphp
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
                                            </tr>
                                        @endforeach

                                    @else
                                        <tr>
                                            @if(!$processRendered)
                                                <td rowspan="{{ $processRowCount }}">
                                                    {{ $process->process_name }}
                                                </td>
                                            @endif

                                            <td>
                                                {{ $policy->document->control_code ?? '' }} -
                                                {{ $policy->document->title ?? '' }}
                                            </td>

                                            <td>—</td>

                                            @if(!$processRendered)
                                                @php $processRendered = true; @endphp
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
@endforeach
