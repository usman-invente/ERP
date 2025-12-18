
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        
        {{-- {!! Form::open(['url' => action([\App\Http\Controllers\ContractController::class, 'update']), 'method' => 'post']) !!} --}}
         {{-- {!! Form::open(['url' => action([\App\Http\Controllers\ContractController::class, 'update'], ['contract' => $schedule->id]), 'method' => 'put', 'id' => 'edit_schedule' ]) !!} --}}
         <input type="hidden" name="url_previous" value="{{url()->full()}}" id="url_previous">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    @lang('contract.show_contract')
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- <input type="hidden" name="schedule_for" value="{{$schedule_for}}" id="schedule_for"> --}}
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('connected_to_number', __('contract.connected_to_number') . ':' )!!}
                            {!! Form::text('connected_to_number', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld1', __('contract.contract_feld1') . ':' )!!}
                            {!! Form::text('contract_feld1', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld2', __('contract.contract_feld2') . ':' )!!}
                            {!! Form::text('contract_feld2', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld3', __('contract.contract_feld3') . ':' )!!}
                            {!! Form::text('contract_feld3', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_start_date', __('contract.contract_start_date') . ':' )!!}
                            {!! Form::date('contract_start_date', null, ['class' => 'form-control', 'disabled', 'id'=> 'PMDate', 'name'=>'PMDate']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_duraction', __('contract.contract_duraction') . ':' )!!}
                            {!! Form::text('contract_duraction', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_end_date', __('contract.contract_end_date') . ':' )!!}
                            {!! Form::date('contract_end_date', null, ['class' => 'form-control', 'disabled', 'id'=>'NPMDate', 'name'=>'NPMDate', 'readonly']) !!}
                       </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contact_before_end_of_contract', __('contract.contact_before_end_of_contract') . ':' )!!}
                            {!! Form::text('contact_before_end_of_contract', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('date_to_contact', __('contract.date_to_contact') . ':' )!!}
                            {!! Form::date('date_to_contact', null, ['class' => 'form-control', 'disabled', 'id'=>'ConDate', 'name'=>'ConDate', 'readonly']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld4', __('contract.contract_feld4') . ':' )!!}
                            {!! Form::text('contract_feld4', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld5', __('contract.contract_feld5') . ':' )!!}
                            {!! Form::text('contract_feld5', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('fee_monthly', __('contract.fee_monthly') . ':' )!!}
                            {!! Form::text('fee_monthly', null, ['class' => 'form-control', 'disabled', 'id' =>'fee_monthly', 'onchange=javascript:findPriceTotal()']) !!}
                       </div>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('discount', __('contract.discount') . ':' )!!}
                            {!! Form::text('discount', null, ['class' => 'form-control', 'disabled', 'id' =>'discount', 'onchange=javascript:findPriceTotal()']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('contract_feld6', __('contract.contract_feld6') . ':' )!!}
                            {!! Form::text('contract_feld6', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('discount_duraction', __('contract.discount_duraction') . ':' )!!}
                            {!! Form::text('discount_duraction', null, ['class' => 'form-control', 'disabled']) !!}
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group">
                            {!! Form::label('price_total', __('contract.price_total') . ':' )!!}
                            {!! Form::text('price_total', null, ['class' => 'form-control', 'readonly']) !!}
                            
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('contract', __('contract.contract_info') . ':') !!}
                            {!! Form::textarea('contract_info', null, ['class' => 'form-control ', 'disabled', 'id' => 'description']); !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    @lang('messages.close')
                </button>               
            </div>
        {{-- {!! Form::close() !!} --}}
    </div>
    
</div>
{{-- <script src="{{ asset('modules/crm/js/crm.js?v=' . $asset_v) }}"></script> --}}

