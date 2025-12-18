
<div class="row">
    <div class="col-md-12">
        @component('cash_book.receipts.filters', ['title' => __('cash_book.filter_pdf')])
            {{-- <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('location_id',  __('cash_book.business_location') . ':') !!}
                    {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                </div>
            </div> 
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('cash_register_date_pdf', __('cash_book.date_range_pdf') . ':') !!}
                    {!! Form::text('cash_register_date_pdf', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'cash_register_date_pdf', 'readonly']); !!}
                </div>
            </div>--}}
            <div class="col-md-2">
                <div class="form-group">
                    {!! Form::label('cash_register_date_pdf', __('cash_book.generate_pdf') . ':') !!}
                    <div class="box-tools">
                        {{-- <a class="btn btn-block btn-primary" href="{{action([\App\Http\Controllers\CashBookController::class, 'getCashBookOrderPdf'])}}">
                            <i class="fa fa-file-pdf"></i> @lang('cash_book.generate_pdf')</a> --}}
                            <a id="generatePdfButton" class="btn btn-block btn-primary" href="#">
                                <i class="fa fa-file-pdf"></i> @lang('cash_book.generate_pdf')
                            </a>
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
</div>
{{-- {{ count($business_locations).' and '. count($cash_registers_details) }} --}}
@component('components.widget', ['class' => 'box-primary', 'title' => __( 'cash_book.cash_book' )])
    {{-- @slot('tool')
        @if(count($cash_registers_details) < $cashRegisterDetail->getAllowedNumberOfCashRegister())
            <div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal" 
                    data-href="{{action([\App\Http\Controllers\CashRegisterDetailController::class, 'create'])}}" 
                    data-container=".cash_register_add_modal">
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
            </div>
        @endif
    @endslot --}}
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('cash_register_date', __('report.date_range') . ':') !!}
                    {!! Form::text('cash_register_date', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'cash_register_date', 'readonly']); !!}
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        {{-- <table class="table table-bordered table-striped" id="cash_reg_detail_table"> --}}
        <table class="table table-bordered table-striped" id="cash_book_table">
            <thead>
                <tr>                       
                    {{-- <th>@lang( 'messages.action' )</th> --}}
                    <th>@lang( 'cash_book.transaction_year' )</th>
                    <th>@lang( 'cash_book.transaction_month' )</th>
                    <th>@lang( 'cash_book.transaction_date' )</th>
                    <th>@lang( 'cash_book.transaction_time' )</th>
                    <th>@lang( 'cash_book.numbering_year' )</th>
                    <th>@lang( 'cash_book.numbering_cash' )</th>
                    <th>@lang( 'cash_book.process' )</th>
                    <th>@lang( 'cash_book.pay_method_de' )</th>
                    <th>@lang( 'cash_book.amount' )</th>
                    <th>@lang( 'cash_book.description' )</th>
                    <th>@lang( 'cash_book.invoice_nr' )</th>
                    <th>@lang( 'cash_book.created_von' )</th>
                    <th>@lang( 'cash_book.customer_name' )</th>                        
                    <th>@lang( 'cash_book.brutto_amount' )</th>                        
                    <th>@lang( 'cash_book.netto_amount' )</th>
                    <th>@lang( 'cash_book.sum_tax_rate' )</th>                        
                    <th>@lang( 'cash_book.netto_tax_rate' )</th>                        
                    <th>@lang( 'cash_book.brutto_tax_rate' )</th>                        
                    <th>@lang( 'cash_book.sum_tax_rate_1' )</th>                        
                    <th>@lang( 'cash_book.netto_tax_rate_1' )</th>                        
                    <th>@lang( 'cash_book.brutto_tax_rate_1' )</th>                        
                    <th>@lang( 'cash_book.netto_tax_rate_2' )</th>
                    <th>@lang( 'cash_book.sum_all_tax_rate' )</th>                        
                    {{-- <th>@lang( 'cash_book.type' )</th>  --}}
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr class="bg-gray font-17 footer-total text-center">
                    <td colspan="13"><strong>@lang('cash_book.total'):</strong></td>
                    <td style="text-align: right;" class="total_brutto_amount"> </td>
                    <td style="text-align: right;" class="total_netto_amount"> </td>
                    <td style="text-align: right;" class="total_sum_tax_rate"> </td>
                    <td style="text-align: right;" class="total_netto_tax_rate"> </td>
                    <td style="text-align: right;" class="total_brutto_tax_rate"> </td>
                    <td style="text-align: right;" class="total_sum_tax_rate_1"> </td>
                    <td style="text-align: right;" class="total_netto_tax_rate_1"> </td>
                    <td style="text-align: right;" class="total_brutto_tax_rate_1"> </td>
                    <td style="text-align: right;" class="total_netto_tax_rate_2"> </td>
                    <td style="text-align: right;" class="total_sum_all_tax_rate"> </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endcomponent