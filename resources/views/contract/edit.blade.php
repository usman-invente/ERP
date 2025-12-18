
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        {{-- {!! Form::open(['url' => action([\App\Http\Controllers\ContractController::class, 'update']), 'method' => 'post']) !!} --}}
         {!! Form::open(['url' => action([\App\Http\Controllers\ContractController::class, 'update'], ['contract' => $contract->id]), 'method' => 'put' ]) !!}
         <input type="hidden" name="url_previous" value="{{url()->full()}}" id="url_previous">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    @lang('contract.edit_contract')
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- <input type="hidden" name="schedule_for" value="{{$schedule_for}}" id="schedule_for"> --}}
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('connected_to_number', __('contract.connected_to_number') . ':' )!!}
                            {!! Form::text('connected_to_number', $contract->connected_to_number, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld1', __('contract.contract_feld1') . ':' )!!}
                            {!! Form::text('contract_feld1', $contract->contract_feld1, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld2', __('contract.contract_feld2') . ':' )!!}
                            {!! Form::text('contract_feld2', $contract->contract_feld2, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld3', __('contract.contract_feld3') . ':' )!!}
                            {!! Form::text('contract_feld3', $contract->contract_feld3, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_start_date', __('contract.contract_start_date') . ':' )!!}
                            {!! Form::date('contract_start_date', $contract->contract_start_date, ['class' => 'form-control', 'id'=> 'PMDate', 'name'=>'PMDate']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_duraction', __('contract.contract_duraction') . ':' )!!}
                            {!! Form::text('contract_duraction', $contract->contract_duraction, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_end_date', __('contract.contract_end_date') . ':' )!!}
                            {!! Form::date('contract_end_date', $contract->contract_end_date, ['class' => 'form-control', 'id'=>'NPMDate', 'name'=>'NPMDate', 'readonly']) !!}
                       </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contact_before_end_of_contract', __('contract.contact_before_end_of_contract') . ':' )!!}
                            {!! Form::text('contact_before_end_of_contract', $contract->contact_before_end_of_contract, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('date_to_contact', __('contract.date_to_contact') . ':' )!!}
                            {!! Form::date('date_to_contact', $contract->date_to_contact, ['class' => 'form-control', 'id'=>'ConDate', 'name'=>'ConDate', 'readonly']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld4', __('contract.contract_feld4') . ':' )!!}
                            {!! Form::text('contract_feld4', $contract->contract_feld4, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld5', __('contract.contract_feld5') . ':' )!!}
                            {!! Form::text('contract_feld5', $contract->contract_feld5, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('fee_monthly', __('contract.fee_monthly') . ':' )!!}
                            {!! Form::text('fee_monthly', $contract->fee_monthly, ['class' => 'form-control', 'id' =>'fee_monthly', 'onchange=javascript:findPriceTotal()']) !!}
                       </div>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('discount', __('contract.discount') . ':' )!!}
                            {!! Form::text('discount', $contract->discount, ['class' => 'form-control', 'id' =>'discount', 'onchange=javascript:findPriceTotal()']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld6', __('contract.contract_feld6') . ':' )!!}
                            {!! Form::text('contract_feld6', $contract->contract_feld6, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('discount_duraction', __('contract.discount_duraction') . ':' )!!}
                            {!! Form::text('discount_duraction', $contract->discount_duraction, ['class' => 'form-control']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('price_total', __('contract.price_total') . ':' )!!}
                            {!! Form::text('price_total', $contract->price_total, ['class' => 'form-control', 'readonly']) !!}
                            
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('contract', __('contract.contract_info') . ':') !!}
                            {!! Form::textarea('contract_info', $contract->contract_info, ['class' => 'form-control ', 'id' => 'description']); !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    @lang('messages.save')
                </button>
                 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    @lang('messages.close')
                </button>               
            </div>
        {!! Form::close() !!}
    </div>
    
</div>
{{-- <script src="{{ asset('modules/crm/js/crm.js?v=' . $asset_v) }}"></script> --}}
<script>
        function findPriceTotal() {
            if (document.getElementById("fee_monthly").value != '' && document.getElementById("discount").value == ''){
                fee_monthly = parseFloat(formatToNumber(document.getElementById("fee_monthly").value));
                if (fee_monthly != null ) {
                    var diff_price_total = (fee_monthly);
                } else {
                    var diff_price_total = formatToNumber1(0.00);
                }
                var divobj = document.getElementById('price_total');
                divobj.value = formatToNumber1(diff_price_total.toFixed(2));
            }else{            
                fee_monthly = parseFloat(formatToNumber(document.getElementById("fee_monthly").value));
                discount = parseFloat(formatToNumber(document.getElementById("discount").value));
                if (fee_monthly != null && discount != null) {
                    var diff_price_total = (fee_monthly - discount);
                } else {
                    var diff_price_total = formatToNumber1(0.00);
                }
                var divobj = document.getElementById('price_total');
                divobj.value = formatToNumber1(diff_price_total.toFixed(2));
            }
        }

        function formatToNumber(number) {
            var str = number;
            var t = str.replace(/[.]/g, "");
            var ft = t.replace(/[,]/g, ".");
            var num = parseFloat(ft);
            return num;
        }

        function formatToNumber1(number) {
            var str = number;
            var ft = str.replace(/[.]/g, ",");
            return ft;
        }

    $(document).ready(function(){
        $('#contract_duraction').change(function(){
            var period=this.value;
            var start_date=new Date($('#PMDate').val());
            

            var result_date=new Date(start_date.getFullYear(), start_date.getMonth()+parseFloat(period), start_date.getDate());
            result_date=moment(result_date).format('Y-MM-DD');
            $('#NPMDate').val(result_date);
            
            console.log(start_date+'-'+period+'--'+ result_date);
            /*else if(period=='Monthly'){
                var result_date=new Date(start_date.getFullYear(), start_date.getMonth()+1, start_date.getDate());
                result_date=moment(result_date).format('Y-MM-DD');
                $('#NPMDate').val(result_date);
            }
            else if(period=='Quarterly'){
                var result_date=new Date(start_date.getFullYear(), start_date.getMonth()+3, start_date.getDate());
                result_date=moment(result_date).format('Y-MM-DD');
                $('#NPMDate').val(result_date);
            }
            else if(period=='HalfYear'){
                var result_date=new Date(start_date.getFullYear(), start_date.getMonth()+6, start_date.getDate());
                result_date=moment(result_date).format('Y-MM-DD');
                $('#NPMDate').val(result_date);
            }*/
        })

        $('#contact_before_end_of_contract').change(function(){
            var day_nr=this.value;

            var end_date=new Date($('#NPMDate').val());

            var result_date=new Date(end_date.getFullYear(), end_date.getMonth(), end_date.getDate()-parseFloat(day_nr));
            result_date=moment(result_date).format('Y-MM-DD');
            $('#ConDate').val(result_date);
        })
    });
</script>
