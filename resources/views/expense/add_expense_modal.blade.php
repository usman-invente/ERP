<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['url' => action([\App\Http\Controllers\ExpenseController::class, 'store']), 'method' => 'post', 'id' => 'add_expense_modal_form', 'files' => true ]) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang( 'expense.add_expense' )</h4>
        </div>
        <div class="modal-body">
            
<?php
        $cash_register_detail_id = string_between_two_string(URL::previous(), 'cash_register_detail_id=', '&'); 
?>
            <div class="row">
                {!! Form::hidden('cash_register_detail_id', $cash_register_detail_id, ['class' => 'form-control', 'id' => 'cash_register_detail_id']); !!}
                {{-- {{ URL::previous().' -- '. $cash_register_detail_id }} --}}
                @if(count($business_locations) == 1)
                    @php 
                        $default_location = current(array_keys($business_locations->toArray())) 
                    @endphp
                @else
                    @php $default_location = request()->input('location_id'); @endphp
                @endif
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_location_id', __('purchase.business_location').':*') !!}
                        {!! Form::select('location_id', $business_locations, $default_location, ['class' => 'form-control select2', 'readonly', 'placeholder' => __('messages.please_select'), 'required', 'id' => 'expense_location_id'], $bl_attributes); !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_category_id', __('expense.expense_category').':') !!}
                        {!! Form::select('expense_category_id', $expense_categories, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_ref_no', __('purchase.ref_no').':') !!}
                        {!! Form::text('ref_no', null, ['class' => 'form-control', 'id' => 'expense_ref_no']); !!}
                        <p class="help-block">
                            @lang('lang_v1.leave_empty_to_autogenerate')
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_transaction_date', __('messages.date') . ':*') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            {!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control', 'readonly',  'id' => 'expense_transaction_date']); !!}
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('expense_for', __('expense.expense_for').':') !!} @show_tooltip(__('tooltip.expense_for'))
                        {!! Form::select('expense_for', $users, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
                    </div>
                </div>                
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('expense_tax_id', __('product.applicable_tax') . ':' ) !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-info"></i>
                            </span>
                            {!! Form::select('tax_id', $taxes['tax_rates'], null, ['class' => 'form-control', 'id'=>'expense_tax_id'], $taxes['attributes']); !!}

                            <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
                            value="0">
                        </div>
                    </div>
                </div> --}}
               
                
                <div class="clearfix"></div>
                @foreach($tax_rates as $key => $taxe)
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('expense_final_total', __('sale.expenses_amount').' ' . $taxe->name . ':') !!}
                            {!! Form::text('total_'.$key, null, ['class' => 'form-control input_number', 'placeholder' => __('sale.expenses_amount'), 'id' => 'total_'.$key, 'onchange="calculate('. $key .', '. $taxe->amount .')"']); !!}
                        </div>
                    </div>
                    {{-- @if($taxe->amount > 0) --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('expense_final_total', __('sale.expenses_tax').' ' . number_format($taxe->amount, 2, ',', '.').'%' ) !!}
                                {!! Form::text('tax_'.$key, null, ['class' => 'form-control input_number','readonly', 'placeholder' => __('sale.expenses_amount'), 'id' => 'tax_'.$key]); !!}
                            </div>
                        </div>   
                    {{-- @endif              --}}
                    <div class="clearfix"></div>
                @endforeach

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('final_total', __('sale.total_expenses_brutto').':') !!}
                        {!! Form::text('final_total', null, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_expenses_brutto'),'readonly', 'id' => 'final_total']); !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('tax_total', __('sale.total_expenses_tax') ) !!}
                        {!! Form::text('tax_total', null, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_expenses_tax'), 'readonly', 'id' => 'tax_total']); !!}
                    </div>
                </div> 

                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('expense_additional_notes', __('expense.expense_note') . ':') !!}
                                {!! Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 3, 'id' => 'expense_additional_notes']); !!}
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

            {{-- <div class="payment_row">
                <h4>@lang('purchase.issue_paid'):</h4>
                @include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true])
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <strong>@lang('purchase.payment_due'):</strong>
                            <span id="expense_payment_due">{{@num_format(0)}}</span>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
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
