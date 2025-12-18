@extends('layouts.app')
@section('title', __('cash_book.cash_book'))

@section('content')
<style type="text/css">
    #contacts_login_dropdown::after {
        display: inline-block;
        width: 0;
        height: 0;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'cash_book.cash_book' )
     {{--    <small>@lang( 'cash_book.manage_your_cash_registers' )</small>--}}
    </h1> 
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>
@php
use App\TaxRate;
use App\CashRegisterDetail;
$tax_rates = TaxRate::where('business_id', 1)
                                ->orderBy('amount', 'desc')
                                ->get();
    // echo count($tax_rates).' - '. $tax_rates[0]->id;
//    echo  intval('99998')+1 .'<br>';
//    $var_name = 0002; 
//    echo strval($var_name).'<br>'. strlen($var_name); 
@endphp
<!-- Main content -->
<section class="content">
    @if(count($business_locations) > 1 || count($cash_registers_details) > 1)    
        <nav class="navbar navbar-default bg-white m-4">

            <div class="container-fluid">
                {{-- @if(auth()->user()->business->id == 1)  
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{action([\Modules\Crm\Http\Controllers\CrmDashboardController::class, 'index'])}}"><i class="fas fa fa-users"></i> {{__('crm::lang.crm')}}</a>
                </div>
                @endif --}}
                <h4>Business Locations</h4>

                <!-- Nav tabs -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                    @foreach($business_locations as $location)
                        <li role="presentation" class="{{ $loop->first ? 'active' : '' }}">
                            <a href="#location_{{ $location->id }}" aria-controls="location_{{ $location->id }}" role="tab" data-toggle="tab"><i class="fas fa fa-business-time"></i>
                                {{ $location->name }}
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </div>
                <div class="tab-content">
                    @foreach($business_locations as $location)
                        <div role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}" id="location_{{ $location->id }}">
                            {{-- <h2>{{ $location->name }}</h2> --}}

                            @php


                                $cashRegisters = CashRegisterDetail::where('business_id',$location->business_id)
                                                                    ->where('location_id', $location->id)
                                                                    ->get()
                            @endphp
                            <!-- Cash Registers -->

                                @foreach($cashRegisters as $cashRegister)
                                <ul class="nav navbar-nav"> 
                                    <li role="presentation" class="{{ $loop->first ? 'active' : '' }}">
                                        {{-- <a href="#cashRegister_{{ $cashRegister->id }}" aria-controls="cashRegister_{{ $cashRegister->id }}" role="tab" data-toggle="tab"> --}}
                                        {{ $cashRegister->name }}
                                        {{-- </a> --}}
                                    </li>
                                </ul>
                                @endforeach
                            
                        </div>
                    @endforeach
                </div>
            </div>
        </nav>
    @endif
    <div class="row">
        <div class="col-md-12">
            @component('cash_book.receipts.filters', ['title' => __('cash_book.filter_pdf')])
                {{-- <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('location_id',  __('cash_book.business_location') . ':') !!}
                        {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div> --}}
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('cash_register_date_pdf', __('cash_book.date_range_pdf') . ':') !!}
                        {!! Form::text('cash_register_date_pdf', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'cash_register_date_pdf', 'readonly']); !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('cash_register_date_pdf', __('cash_book.generate_pdf') . ':') !!}
                        <div class="box-tools">
                            <a class="btn btn-block btn-primary" href="{{action([\App\Http\Controllers\CashBookController::class, 'getCashBookOrderPdf'] , ['business_id' => $business_id, 'cash_book_id' => 422 ])}}">
                            <i class="fa fa-file-pdf"></i> @lang('cash_book.generate_pdf')</a>
                        </div>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
    {{ count($business_locations).' and '. count($cash_registers_details) }}
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
                        <th>@lang( 'messages.action' )</th>
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
                        <td colspan="14"><strong>@lang('cash_book.total'):</strong></td>
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
    {{-- <div class="modal fade cash_register_add_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade cash_register_edit_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div> --}}
    

</section>
<!-- /.content -->

@endsection
@section('javascript')
    <script type="text/javascript">

        $(document).ready(function() {                   

            $('#cash_register_date').daterangepicker(
                dateRangeSettings, 
                function(start, end) {
                    $('#cash_register_date').val(
                        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                    );
                }
            );   
            $('#cash_register_date_pdf').daterangepicker(
                dateRangeSettings, 
                function(start, end) {
                    $('#cash_register_date_pdf').val(
                        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                    );
                }
            );   
            
            $.ajax({ 
                    type: 'get',
                    url: 'call_op_combination/getFoerderquote/',
                    cache: false,
                    dataType: 'json',
                    data: {
                        get_option: 1
                    },
                    success: function (d) {
                        var start_pdf = $('input#cash_register_date_pdf')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        var end_pdf = $('input#cash_register_date_pdf')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                        d.start_date_pdf = start_pdf;
                        d.end_date_pdf = end_pdf;
                    }
                });

            $('#cash_book_table thead tr').clone(true).appendTo( '#cash_book_table thead' );
		    $('#cash_book_table thead tr:eq(0) th').each( function (i) {
		    	var title = $(this).text();
                // if(i == 0 ){
                //    $(this).html( '<td/>' ); 
                // }
		    	// if(title.includes( '{{ __('contact.info_historie_type') }}') == true){
		    	// 	var select = $(' <select><option value="">Alle</option><option>{{ __('contact.customer') }}</option><option>{{ __('contact.info_historie_type_dokument') }}</option><option>{{ __('contact.info_historie_type_contract') }}</option><option>{{ __('contact.info_historie_type_invoice') }}</option></select>')
		    	// 	  .appendTo($(this).empty())
		    	// 	  .on('change', function() {
		    	// 		var term = $(this).val();
		    	// 		contact_info_modul_datatable.column(i).search(term, false, false).draw();
		    	// 	  });
                // }
                // else{
                    $(this).html( '<input size="8" type="text"  />' );
		    			$( 'input', this ).on( 'keyup change', function () {
		    					if ( cash_reg_detail_datatable.column(i).search() !== this.value ) {
		    						cash_reg_detail_datatable
		    							.column(i)
		    							.search( this.value )
		    							.draw();
		    					}
		    				} );
                // }
            });
            
            cash_reg_detail_datatable = $("#cash_book_table").DataTable({
	        	processing: true,
	            serverSide: true,
            
	            ajax: {
	                 url: "/reports/list-cash-book-historie",
                     data: function(d) {
                        // d.type = 'purchase';
                        // d.location_id = $('#location_id').val();
                        // d.contact_id = $('#tax_report_contact_id').val();
                        var start = $('input#cash_register_date')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        var end = $('input#cash_register_date')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                        d.start_date = start;
                        d.end_date = end;
                    }
	            },
	            columnDefs: [
	                {                        
	                    targets: [0],
	                    orderable: false,
	                    searchable: false,
	                },
	            ],
	            aaSorting: [[6, 'desc']],
	            columns: [
	                { data: 'action', name: 'action' },
                    { data: 'transaction_year', name: 'transaction_year' },
	                { data: 'transaction_month', name: 'transaction_month' },
	                { data: 'transaction_date', name: 'transaction_date' },
	                { data: 'transaction_time', name: 'transaction_time' },
	                { data: 'numbering_year', name: 'numbering_year' },
	                { data: 'numbering_cash', name: 'numbering_cash' },
	                { data: 'process', name: 'process' },
	                { data: 'pay_method_de', name: 'pay_method_de' },
	                { data: 'amount', name: 'amount' },
	                { data: 'description', name: 'description' },
	                { data: 'invoice_nr', name: 'invoice_nr' },
	                { data: 'created_by_name', name: 'created_by_name' },
	                { data: 'customer_name', name: 'customer_name' },
	                { data: 'brutto_amount', name: 'brutto_amount' },
	                { data: 'netto_amount', name: 'netto_amount' },
	                { data: 'sum_tax_rate', name: 'sum_tax_rate' },
	                { data: 'netto_tax_rate', name: 'netto_tax_rate' },
	                { data: 'brutto_tax_rate', name: 'brutto_tax_rate' },
	                { data: 'sum_tax_rate_1', name: 'sum_tax_rate_1' },
	                { data: 'netto_tax_rate_1', name: 'netto_tax_rate_1' },
	                { data: 'brutto_tax_rate_1', name: 'brutto_tax_rate_1' },
	                { data: 'netto_tax_rate_2', name: 'netto_tax_rate_2' },
	                { data: 'sum_all_tax_rate', name: 'sum_all_tax_rate' },
	                // { data: 'transaction_type', name: 'transaction_type' },
	            ],         

                "footerCallback": function ( row, data, start, end, display ) {
                    var footer_brutto_amount = 0;
                    var footer_netto_amount = 0;
                    var footer_sum_tax_rate = 0;
                    var footer_netto_tax_rate = 0;
                    var footer_brutto_tax_rate = 0;
                    var footer_sum_tax_rate_1 = 0;
                    var footer_netto_tax_rate_1 = 0;
                    var footer_brutto_tax_rate_1 = 0;
                    var footer_netto_tax_rate_2 = 0;
                    var footer_sum_all_tax_rate = 0;
                    for (var r in data){
                        footer_brutto_amount += $(data[r].brutto_amount).data('orig-value') ? parseFloat($(data[r].brutto_amount).data('orig-value')) : 0;
                        footer_netto_amount += $(data[r].netto_amount).data('orig-value') ? parseFloat($(data[r].netto_amount).data('orig-value')) : 0;
                        footer_sum_tax_rate += $(data[r].sum_tax_rate).data('orig-value') ? parseFloat($(data[r].sum_tax_rate).data('orig-value')) : 0;
                        footer_netto_tax_rate += $(data[r].netto_tax_rate).data('orig-value') ? parseFloat($(data[r].netto_tax_rate).data('orig-value')) : 0;
                        footer_brutto_tax_rate += $(data[r].brutto_tax_rate).data('orig-value') ? parseFloat($(data[r].brutto_tax_rate).data('orig-value')) : 0;
                        footer_sum_tax_rate_1 += $(data[r].sum_tax_rate_1).data('orig-value') ? parseFloat($(data[r].sum_tax_rate_1).data('orig-value')) : 0;
                        footer_netto_tax_rate_1 += $(data[r].netto_tax_rate_1).data('orig-value') ? parseFloat($(data[r].netto_tax_rate_1).data('orig-value')) : 0;
                        footer_brutto_tax_rate_1 += $(data[r].brutto_tax_rate_1).data('orig-value') ? parseFloat($(data[r].brutto_tax_rate_1).data('orig-value')) : 0;
                        footer_netto_tax_rate_2 += $(data[r].netto_tax_rate_2).data('orig-value') ? parseFloat($(data[r].netto_tax_rate_2).data('orig-value')) : 0;
                        footer_sum_all_tax_rate += $(data[r].sum_all_tax_rate).data('orig-value') ? parseFloat($(data[r].sum_all_tax_rate).data('orig-value')) : 0;
                    }
                    $('.total_brutto_amount').html(__currency_trans_from_en(footer_brutto_amount));
                    $('.total_netto_amount').html(__currency_trans_from_en(footer_netto_amount));
                    $('.total_sum_tax_rate').html(__currency_trans_from_en(footer_sum_tax_rate));
                    $('.total_netto_tax_rate').html(__currency_trans_from_en(footer_netto_tax_rate));
                    $('.total_brutto_tax_rate').html(__currency_trans_from_en(footer_brutto_tax_rate));
                    $('.total_sum_tax_rate_1').html(__currency_trans_from_en(footer_sum_tax_rate_1));
                    $('.total_netto_tax_rate_1').html(__currency_trans_from_en(footer_netto_tax_rate_1));
                    $('.total_brutto_tax_rate_1').html(__currency_trans_from_en(footer_brutto_tax_rate_1));
                    $('.total_netto_tax_rate_2').html(__currency_trans_from_en(footer_netto_tax_rate_2));
                    $('.total_sum_all_tax_rate').html(__currency_trans_from_en(footer_sum_all_tax_rate));
                    
                },
                
	        });

            $('#cash_register_date').change( function(){
                cash_reg_detail_datatable.ajax.reload();            
            });
        }); 
    </script>
@endsection