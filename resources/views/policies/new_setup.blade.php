<div class="modal" id="new_policy_setup" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">New Setup</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form method="post" action="new-policy" onsubmit="show();" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-body">
                     <div class="border p-3 rounded mb-3">

                        <div class="form-group mb-2">
                            <label>Process Name :</label>
                            <select name="process_id[]" class="form-control form-control-sm cat">
                                <option value=""></option>
                                @foreach($processes as $process)
                                    <option value="{{$process->id}}">{{$process->process_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="policyContainerSetup">

                        <div class="policyRow border p-3 rounded mb-3">

                            <div class="form-group mb-2">
                                <label>Policy :</label>
                                <select name="policy_id[]" class="form-control form-control-sm cat">
                                    <option value=""></option>
                                    @foreach($document_policies as $document)
                                        <option value="{{$document->id}}">{{$document->control_code}} - {{$document->title}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="subPolicyWrapper mb-2">
                                <button type="button" class="btn btn-secondary btn-sm addSubPolicyBtn mb-2">+ Add Sub Policy</button>

                                <div class="subpolicyContainerSetup mt-2"></div>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm removePolicyBtn mt-2">Remove Policy</button>

                        </div>

                    </div>

                    <button type="button" class="btn btn-primary btn-sm mb-2" id="addPolicyBtn">+ Add Policy</button>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>

            </form>

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>

<script>
    let policyIndex = 0;

    document.addEventListener('DOMContentLoaded', function () {
        initChosen();
        document.querySelector('.policyRow').dataset.policyIndex = 0;
    });

    function updatePolicyOptions() {
        const selects = document.querySelectorAll('select[name="policy_id[]"]');

        const selectedValues = Array.from(selects).map(s => s.value).filter(v => v);

        selects.forEach(select => {
            const currentValue = select.value;
            select.querySelectorAll('option').forEach(option => {
                if(option.value === "") return; 
                option.hidden = selectedValues.includes(option.value) && option.value !== currentValue;
                option.disabled = false;
            });
            $(select).trigger('chosen:updated');
        });
    }

    document.getElementById('addPolicyBtn').addEventListener('click', function () {
        policyIndex++;

        let original = document.querySelector('.policyRow');
        $(original).find('.cat').chosen('destroy');
        let clone = original.cloneNode(true);

        clone.dataset.policyIndex = policyIndex;

        clone.querySelectorAll('select').forEach(select => select.value = '');
        clone.querySelector('.subpolicyContainerSetup').innerHTML = '';

        clone.querySelectorAll('select[name^="sub_policy_id"]').forEach(select => {
            select.name = `sub_policy_id[${policyIndex}][]`;
        });

        document.getElementById('policyContainerSetup').appendChild(clone);
        initChosen(clone);    
        updatePolicyOptions();
    });



    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('removePolicyBtn')) {
            let rows = document.querySelectorAll('.policyRow');
            if (rows.length > 1) e.target.closest('.policyRow').remove();
            updatePolicyOptions(); 
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target.name === "policy_id[]") {
            updatePolicyOptions();
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('addSubPolicyBtn')) {

            let policyRow = e.target.closest('.policyRow');
            let container = policyRow.querySelector('.subpolicyContainerSetup');

            let groupKey = policyRow.dataset.policyIndex;

            let subRow = document.createElement('table');
            subRow.classList.add('subRow');
            subRow.setAttribute('width', '100%');
            subRow.style.marginBottom = '6px';
            subRow.innerHTML = `
                <tr>
                    <td style="width:95%;">
                        <select name="sub_policy_id[${groupKey}][]" class="form-control form-control-sm cat">
                            <option value=""></option>
                            @foreach($document_policies as $document)
                                <option value="{{$document->id}}">{{$document->control_code}} - {{$document->title}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width:5%; text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm removeSubBtn">×</button>
                    </td>
                </tr>
            `;

            container.appendChild(subRow);
            initChosen(subRow); 
        }
    });



    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('removeSubBtn')) {
            e.target.closest('.subRow').remove();
        }
    });

    function initChosen(context = document) {
        $(context).find('.cat').chosen({
            width: '100%'
        });
    }



</script>