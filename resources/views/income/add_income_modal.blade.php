<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['url' => action([\App\Http\Controllers\IncomeController::class, 'store']), 'method' => 'post', 'id' => 'add_income_modal_form', 'files' => true ]) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang( 'income.add_income' )</h4>
        </div>
        <div class="modal-body">
            <?php
                $cash_register_detail_id = string_between_two_string(URL::previous(), 'cash_register_detail_id=', '&'); 
?>
            <div class="row">
                {!! Form::hidden('cash_register_detail_id', $cash_register_detail_id, ['class' => 'form-control', 'id' => 'cash_register_detail_id']); !!}
                {!! Form::hidden('sub_type', 'income') !!}
                @if(count($business_locations) == 1)
                    @php 
                        $default_location = current(array_keys($business_locations->toArray())) 
                    @endphp
                @else
                    @php $default_location = request()->input('location_id'); @endphp
                @endif
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('income_location_id', __('purchase.business_location').':*') !!}
                        {!! Form::select('location_id', $business_locations, $default_location, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required', 'id' => 'income_location_id'], $bl_attributes); !!}
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6">
                    <div class="form-group">
                            {!! Form::label('income_category_id', __('income.income_category').':') !!}
                            {!! Form::select('income_category_id', $income_categories, null, [
                                'class' => 'form-control select2', 
                                'placeholder' => __('messages.please_select'),
                                'id' => 'income_category_id'
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('income_sub_category_id', __('income.income_sub_category').':') !!}
                            {!! Form::select('income_sub_category_id', [], null, [
                                'class' => 'form-control select2', 
                                'placeholder' => __('messages.please_select'),
                                'id' => 'income_sub_category_id'
                            ]) !!}
                        </div>
                    </div>

                <div class="clearfix"></div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('income_ref_no', __('income.ref_no').':') !!} @show_tooltip(__('tooltip.income_ref_no'))
                        {!! Form::text('ref_no', null, ['class' => 'form-control', 'id' => 'income_ref_no']); !!}
                        <p class="help-block">
                            @lang('lang_v1.leave_empty_to_autogenerate')
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('income_transaction_date', __('messages.date') . ':*') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            {!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required', 'id' => 'income_transaction_date']); !!}
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                {{-- <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('income_for', __('income.income_for').':') !!} @show_tooltip(__('tooltip.income_for'))
                        {!! Form::select('income_for', $users, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
                    </div>
                </div>                 --}}
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('income_tax_id', __('product.applicable_tax') . ':' ) !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-info"></i>
                            </span>
                            {!! Form::select('tax_id', $taxes['tax_rates'], null, ['class' => 'form-control', 'id'=>'income_tax_id'], $taxes['attributes']); !!}

                            <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
                            value="0">
                        </div>
                    </div>
                </div> --}}
                @foreach($tax_rates as $key => $taxe)
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('income_final_total', __('sale.expenses_amount').' ' . $taxe->name . ':') !!}
                            {!! Form::text('total_'.$key, null, ['class' => 'form-control input_number total-input', 'placeholder' => __('sale.expenses_amount'), 'id' => 'total_'.$key, 'onchange="calculate('. $key .', '. $taxe->amount .')"']); !!}
                        </div>
                    </div>
                    {{-- @if($taxe->amount > 0) --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('income_final_total', __('sale.expenses_tax').' ' . number_format($taxe->amount, 2, ',', '.').'%' ) !!}
                                {!! Form::text('tax_'.$key, null, ['class' => 'form-control input_number','readonly', 'placeholder' => __('sale.expenses_amount'), 'id' => 'tax_'.$key]); !!}
                            </div>
                        </div> 
                         {!! Form::hidden('tax_rate_amount_'.$key, number_format($taxe->amount, 2, ',', '.'), ['class' => 'form-control input_number',  'id' => 'tax_rate_amount_'.$key]); !!}  
                    {{-- @endif              --}}
                    <div class="clearfix"></div>
                @endforeach
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('final_total', __('income.total_incomes_brutto').':') !!}
                        {!! Form::text('final_total', null, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_expenses_brutto'),'readonly', 'id' => 'final_total']); !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('tax_total', __('income.total_incomes_tax').':' ) !!}
                        {!! Form::text('tax_total', null, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_expenses_tax'), 'readonly', 'id' => 'tax_total']); !!}
                    </div>
                </div> 
                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('income_additional_notes', __('income.income_note') . ':') !!}
                                {!! Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 3, 'id' => 'income_additional_notes']); !!}
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('document', __('purchase.attach_document') . ':') !!}
                        {!! Form::file('document', ['id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                        <small><p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                        @includeIf('components.document_help_text')</p></small>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
{{-- 
            <div class="payment_row">
                <h4>@lang('purchase.add_payment'):</h4>
                @include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true])
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <strong>@lang('purchase.payment_due'):</strong>
                            <span id="income_payment_due">{{@num_format(0)}}</span>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="modal-footer">
            <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white">@lang( 'messages.save' )</button>
            <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang( 'messages.close' )</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#income_category_id').on('change', function () {
            var categoryId = $(this).val();
            $('#income_sub_category_id').html('<option value="">Lade...</option>');

            if (categoryId) {
                $.ajax({
                    url: '/income-categories/' + categoryId + '/subcategories',
                    type: 'GET',
                    success: function (data) {
                        $('#income_sub_category_id').empty();
                        $('#income_sub_category_id').append('<option value="">Bitte wählen</option>');
                        $.each(data, function (key, value) {
                            $('#income_sub_category_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#income_sub_category_id').html('<option value="">Bitte zuerst Kategorie wählen</option>');
            }
        });

        const rawDigitMap = {};

        $('.total-input').each(function () {
            const input = $(this);
            const id = input.attr('id');
            rawDigitMap[id] = '';

            if (!input.val()) {
                input.val('0,00');
            }

            input.on('keydown', function (e) {
                let rawDigits = rawDigitMap[id];

                if (e.key >= '0' && e.key <= '9') {
                    rawDigits += e.key;
                    e.preventDefault();
                } else if (e.key === 'Backspace') {
                    rawDigits = rawDigits.slice(0, -1);
                    e.preventDefault();
                } else if (['Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    return;
                } else {
                    e.preventDefault();
                }

                rawDigitMap[id] = rawDigits;
                updateFormattedValue(input, rawDigits);
            });
        });

        function updateFormattedValue(input, rawDigits) {
        if (rawDigits.length === 0) {
            input.val('0,00');
            return;
        }

        while (rawDigits.length < 3) {
            rawDigits = '0' + rawDigits;
        }

        const cents = rawDigits.slice(-2);
        const euros = rawDigits.slice(0, -2);
        const euroFormatted = parseInt(euros, 10).toLocaleString('de-DE');
        const formattedValue = `${euroFormatted},${cents}`;
        input.val(formattedValue);

        // Recalculate on input change
        input.trigger('change');
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
            const total_input = document.getElementById('total_' + i).value;
            const tax_input = document.getElementById('tax_' + i).value;

            let total_val = formatToNumber(total_input);
            let tax_val = formatToNumber(tax_input);

            // Sicherheitsprüfung
            total_val = isNaN(total_val) ? 0 : total_val;
            tax_val = isNaN(tax_val) ? 0 : tax_val;

            sum_total += total_val;
            sum_p += tax_val;
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