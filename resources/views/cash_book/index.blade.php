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

@endphp
<!-- Main content -->

<section class="content">
    @if(count($business_locations) > 1 || count($cash_registers_details) > 1)    
        @include('cash_book.index_more_location_or_cashregister')
    @endif

    @if(count($business_locations) == 1 && count($cash_registers_details) < 2)
        @include('cash_book.index_one_location_and_cashregister')
    @endif
    
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
            $('#generatePdfButton').on('click', function(e) {
                e.preventDefault(); // Verhindere das Standardverhalten des Links

                // Holen Sie sich den Start- und Enddatum vom Date Range Picker
                var startDate = $('#cash_register_date').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var endDate = $('#cash_register_date').data('daterangepicker').endDate.format('YYYY-MM-DD');

                // Baue die URL mit den ausgewählten Daten
                var pdfUrl = "{{ action([\App\Http\Controllers\CashBookController::class, 'getCashBookOrderPdf']) }}";
                pdfUrl += "?start_date=" + startDate + "&end_date=" + endDate;

                // Navigiere zur generierten PDF-URL
                window.location.href = pdfUrl;
            });             

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var locationId = $(e.target).attr('aria-controls').replace('location_', '');
                $('#datatables-container').data('location-id', locationId);
                var cashRegisterId = $(e.target).attr('aria-controls').replace('cash_register_', '');
                $('#cash-register-datatables-container').data('cash-register-id', cashRegisterId);
                
                // Reload Datatables with the updated location_id
                $('#cash_book_table').DataTable().ajax.reload();
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
                        console.log('eeeaa' + $('#datatables-container').data('location-id'));
                        console.log('ketu' + $('#cash-register-datatables-container').data('cash-register-id'));
                        // d.type = 'purchase';
                        d.new_location_id = $('#new_location_id').val();
                        // d.contact_id = $('#tax_report_contact_id').val();
                        var start = $('input#cash_register_date')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        var end = $('input#cash_register_date')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');

                        var new_location_id = $('input#new_location_id')
                        d.start_date = start;
                        d.end_date = end;
                        // d.locationId = $('#locationId').val();   
                        d.locationId = $('#datatables-container').data('location-id');
                        d.cashRegisterId = $('#cash-register-datatables-container').data('cash-register-id');
                        // d.locationId = locationId;                    
                    }
	            },
	            columnDefs: [
	                {                        
	                    targets: [],
	                    orderable: false,
	                    searchable: false,
	                },
	            ],
	            aaSorting: [[5, 'desc']],
	            columns: [
	                // { data: 'action', name: 'action' },
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