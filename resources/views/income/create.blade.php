@extends('layouts.app')
@section('title', __('income.add_income'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('income.add_income')</h1>
</section>

<!-- Main content -->
<section class="content">
	{!! Form::open(['url' => action([\App\Http\Controllers\IncomeController::class, 'store']), 'method' => 'post', 'id' => 'add_income_form', 'files' => true ]) !!}
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">

				@if(count($business_locations) == 1)
					@php 
						$default_location = current(array_keys($business_locations->toArray())) 
					@endphp
				@else
					@php $default_location = null; @endphp
				@endif
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('location_id', __('purchase.business_location').':*') !!}
						{!! Form::select('location_id', $business_locations, $default_location, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required'], $bl_attributes); !!}
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('income_category_id', __('income.income_category').':') !!}
						{!! Form::select('income_category_id', $income_categories, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
			            {!! Form::label('income_sub_category_id', __('product.sub_category') . ':') !!}
			              {!! Form::select('income_sub_category_id', [],  null, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']); !!}
			          </div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('ref_no', __('purchase.ref_no').':') !!}
						{!! Form::text('ref_no', null, ['class' => 'form-control']); !!}
						<p class="help-block">
			                @lang('lang_v1.leave_empty_to_autogenerate')
			            </p>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('transaction_date', __('messages.date') . ':*') !!}
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							{!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required', 'id' => 'income_transaction_date']); !!}
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('income_for', __('income.income_for').':') !!} @show_tooltip(__('tooltip.income_for'))
						{!! Form::select('income_for', $users, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('contact_id', __('lang_v1.income_for_contact').':') !!} 
						{!! Form::select('contact_id', $contacts, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('document', __('purchase.attach_document') . ':') !!}
                        {!! Form::file('document', ['id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                        <small><p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                        @includeIf('components.document_help_text')</p></small>
                    </div>
                </div>
				{{-- <div class="col-md-4">
			    	<div class="form-group">
			            {!! Form::label('tax_id', __('product.applicable_tax') . ':' ) !!}
			            <div class="input-group">
			                <span class="input-group-addon">
			                    <i class="fa fa-info"></i>
			                </span>
			                {!! Form::select('tax_id', $taxes['tax_rates'], null, ['class' => 'form-control'], $taxes['attributes']); !!}

							<input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
							value="0">
			            </div>
			        </div>
			    </div>
			    <div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('final_total', __('sale.total_amount') . ':*') !!}
						{!! Form::text('final_total', null, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_amount'), 'required']); !!}
					</div>
				</div>
				<div class="clearfix"></div> --}}
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('additional_notes', __('income.income_note') . ':') !!}
								{!! Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 3]); !!}
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
					<br>
					<label>
		              {!! Form::checkbox('is_refund', 1, false, ['class' => 'input-icheck', 'id' => 'is_refund']); !!} @lang('lang_v1.is_refund')?
		            </label>@show_tooltip(__('lang_v1.is_refund_help'))
				</div>
				@if(auth()->user()->id == 2)
				<div class="clearfix"></div>
                	@foreach($tax_rates as $key => $taxe)
                	    <div class="col-sm-4">
                	        <div class="form-group">
                	            {!! Form::label('income_final_total', __('sale.incomes_amount').' ' . $taxe->name . ':') !!}
                	            {!! Form::text('total_'.$key, null, ['class' => 'form-control input_number', 'placeholder' => __('sale.incomes_amount'), 'id' => 'total_'.$key, 'onchange="calculate('. $key .', '. $taxe->amount .')"']); !!}
                	        </div>
                	    </div>
                	    {{-- @if($taxe->amount > 0) --}}
                	        <div class="col-sm-4">
                	            <div class="form-group">
                	                {!! Form::label('income_final_total', __('sale.incomes_tax').' ' . number_format($taxe->amount, 2, ',', '.').'%' ) !!}
                	                {!! Form::text('tax_'.$key, null, ['class' => 'form-control input_number','readonly', 'placeholder' => __('sale.incomes_amount'), 'id' => 'tax_'.$key]); !!}
                	            </div>
                	        </div>   
                	    {{-- @endif              --}}
                	    <div class="clearfix"></div>
                	@endforeach
					<div class="col-sm-4">
						<div class="form-group">
							{!! Form::label('final_total', __('sale.total_amount') . ':*') !!}
							{!! Form::text('final_total', null, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_amount'), 'readonly']); !!}
						</div>
					</div>
				@endif
			</div>
		</div>
	</div> <!--box end-->
	{{-- @include('income.recur_income_form_part')
	@component('components.widget', ['class' => 'box-solid', 'id' => "payment_rows_div", 'title' => __('purchase.add_payment')])
	<div class="payment_row">
		@include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true])
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<div class="pull-right">
					<strong>@lang('purchase.payment_due'):</strong>
					<span id="payment_due">{{@num_format(0)}}</span>
				</div>
			</div>
		</div>
	</div>
	@endcomponent --}}
	<div class="col-sm-12 text-center">
		<button type="submit" class="btn btn-primary btn-big">@lang('messages.save')</button>
	</div>
{!! Form::close() !!}
</section>
@endsection
@section('javascript')
<script type="text/javascript">
	$(document).ready( function(){
		$('.paid_on').datetimepicker({
            format: moment_date_format + ' ' + moment_time_format,
            ignoreReadonly: true,
        });
	});
	
	__page_leave_confirmation('#add_income_form');
	$(document).on('change', 'input#final_total, input.payment-amount', function() {
		calculateIncomePaymentDue();
	});

	function calculateIncomePaymentDue() {
		var final_total = __read_number($('input#final_total'));
		var payment_amount = __read_number($('input.payment-amount'));
		var payment_due = final_total - payment_amount;
		$('#payment_due').text(__currency_trans_from_en(payment_due, true, false));
	}

	$(document).on('change', '#recur_interval_type', function() {
	    if ($(this).val() == 'months') {
	        $('.recur_repeat_on_div').removeClass('hide');
	    } else {
	        $('.recur_repeat_on_div').addClass('hide');
	    }
	});

	$('#is_refund').on('ifChecked', function(event){
		$('#recur_income_div').addClass('hide');
	});
	$('#is_refund').on('ifUnchecked', function(event){
		$('#recur_income_div').removeClass('hide');
	});

	$(document).on('change', '.payment_types_dropdown, #location_id', function(e) {
	    var default_accounts = $('select#location_id').length ? 
	                $('select#location_id')
	                .find(':selected')
	                .data('default_payment_accounts') : [];
	    var payment_types_dropdown = $('.payment_types_dropdown');
	    var payment_type = payment_types_dropdown.val();
	    if (payment_type) {
	        var default_account = default_accounts && default_accounts[payment_type]['account'] ? 
	            default_accounts[payment_type]['account'] : '';
	        var payment_row = payment_types_dropdown.closest('.payment_row');
	        var row_index = payment_row.find('.payment_row_index').val();

	        var account_dropdown = payment_row.find('select#account_' + row_index);
	        if (account_dropdown.length && default_accounts) {
	            account_dropdown.val(default_account);
	            account_dropdown.change();
	        }
	    }
	});

	function calculate(index, percent){
        const inputValue = parseFloat(formatToNumber(document.getElementById('total_' + index).value));
        // Number(document.getElementById('total_' + index).value);
        let percent_value = 0;
        if(percent != 0){
            percent_value = inputValue -  (inputValue / (1 + percent/100)) ;
        }

        document.getElementById('tax_' + index).value = formatToNumber1(percent_value.toFixed(2));
        calculateSum(index);
    }

    function calculateSum(index){
        var array_tax =  <?php echo json_encode($tax_rates); ?>;
        let sum_total = 0;
        let sum_p = 0;
        console.log('aaaa' );
        for (let i = 0; i < array_tax.length; i++) {
            if((document.getElementById('total_' + i ).value)){
                sum_total = sum_total + parseFloat(formatToNumber(document.getElementById('total_' + i).value));
                sum_p = sum_p + parseFloat(formatToNumber(document.getElementById('tax_' + i).value));
            }
        }
        console.log('bbbb');
        document.getElementById('final_total').value = formatToNumber1(sum_total.toFixed(2));
        document.getElementById('tax_total').value = formatToNumber1(sum_p.toFixed(2));

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
</script>

<?php
    function string_between_two_string($str, $starting_word, $ending_word)
    {
        $subtring_start = strpos($str, $starting_word);
        //Adding the starting index of the starting word to 
        //its length would give its ending index
        $subtring_start += strlen($starting_word);  
        //Length of our required sub string
        $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;  
        // Return the substring from the index substring_start of length size 
        return substr($str, $subtring_start, $size);  
    }
?>
@endsection