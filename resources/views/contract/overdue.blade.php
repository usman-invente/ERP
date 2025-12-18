@extends('layouts.app')

@section('title', __('contract.contracts'))
<style> 
    .red {
      background-color: #f5365c !important;
    }
    .green {
      background-color: #2dce89 !important;
    }
    .orange {
      background-color: #ffad46 !important;
    }
</style>
@php
    use App\Business;

    $business = Business::where('id','=',session('business.id'))->first();
@endphp
@section('content')
@include('layouts.contract_nav')
@component('components.widget', ['class' => 'box-primary', 'title' => __('contract.contract_overdue')])
      
		    <table class="table table-bordered table-striped" id="contract_overdue_table" style="width: 80%">
                <thead>
                    <tr>
                        <th>@lang('messages.action')</th>
                        <th>@lang('contract.customer_name')</th>
                        <th>@lang('contract.number')</th>
                        <th>@lang('contract.connected_to_number')</th>
                        <th>@lang('contract.contract_feld1')</th>
                        <th>@lang('contract.contract_feld2')</th>
                        <th>@lang('contract.contract_feld3')</th>
                        <th>@lang('contract.contract_feld4')</th>
                        <th>@lang('contract.contract_feld5')</th>
                        <th>@lang('contract.fee_monthly')</th>
                        <th>@lang('contract.discount')</th>
                        <th>@lang('contract.contract_feld6')</th>
                        <th>@lang('contract.discount_duraction')</th>
                        <th>@lang('contract.price_total')</th>
                        <th>@lang('contract.contract_start_date')</th>
                        <th>@lang('contract.contract_end_date')</th>
                        <th>@lang('contract.contract_duraction')</th>
                        <th>@lang('contract.contact_before_end_of_contract')</th>
                        <th>@lang('contract.date_to_contact')</th>
                    </tr>
                </thead>
                 <tbody></tbody>
		    </table>
        
    @endcomponent
</section>
@endsection

@section('javascript')
    <script type="text/javascript">

        $(document).ready(function() {

                    $('#contract_overdue_table thead tr').clone(true).appendTo( '#contract_overdue_table thead' );
					$('#contract_overdue_table thead tr:eq(0) th').each( function (i) {
						var title = $(this).text();
        			    if(i == 0 ){
        			       $(this).html( '<td/>' ); 
        			    }
						
        			    else{
        			        $(this).html( '<input size="8" type="text"  />' );

								$( 'input', this ).on( 'keyup change', function () {
										if ( contract_modul_datatable.column(i).search() !== this.value ) {
											contract_modul_datatable
												.column(i)
												.search( this.value )
												.draw();
										}
									} );
        			    }
        			});

                    contract_modul_datatable = $("#contract_overdue_table").DataTable({
	        			processing: true,
	        	        serverSide: true,
                	    aLengthMenu: [[10, 25, 50, 100, 200, 500, 1000, -1], [5, 10, 25, 50, 100, 200, 500, 1000, LANG.all]],
		        	    scrollY: 400,
		        	    scrollX: true,
		        	    scrollCollapse: true,
                        language: {
                            searchPlaceholder: LANG.search + ' ...',
                            search: '',
                            lengthMenu: LANG.entries + ' _MENU_ ' ,
                            emptyTable: LANG.table_emptyTable,
                            info: LANG.table_info,
                            infoEmpty: LANG.table_infoEmpty,
                            loadingRecords: LANG.table_loadingRecords,
                            processing: LANG.table_processing,
                            zeroRecords: LANG.table_zeroRecords,
                            paginate: {
                                first: LANG.first,
                                last: LANG.last,
                                next: LANG.next,
                                previous: LANG.previous,
                            },
                        },
	        	        ajax: {
	        	             url: "/contracts/overdue",
	        	            data:function(d) {
	        	            	d.lead_id = $("#lead_id").val();

                                d = __datatable_ajax_callback(d);
	        	            }
	        	        },
	        	        columnDefs: [
	        	            {
	        	                targets: [0, 6],
	        	                orderable: false,
	        	                searchable: false,
	        	            },
	        	        ],
	        	        aaSorting: [[1, 'asc']],
	        	        columns: [
	        	            { data: 'action', name: 'action' },
	        	            { data: 'customer_name', name: 'customer_name' },
	        	            { data: 'number', name: 'number' },
	        	            { data: 'connected_to_number', name: 'connected_to_number' },
	        	            { data: 'contract_feld1', name: 'contract_feld1' },
	        	            { data: 'contract_feld2', name: 'contract_feld2' },
	        	            { data: 'contract_feld3', name: 'contract_feld3' },
	        	            { data: 'contract_feld4', name: 'contract_feld4' },
	        	            { data: 'contract_feld5', name: 'contract_feld5' },
	        	            { data: 'fee_monthly', name: 'fee_monthly' },
	        	            { data: 'discount', name: 'discount' },
	        	            { data: 'contract_feld6', name: 'contract_feld6' },
	        	            { data: 'discount_duraction', name: 'discount_duraction' },
	        	            { data: 'price_total', name: 'price_total' },
	        	            { data: 'contract_start_date', name: 'contract_start_date' },
	        	            { data: 'contract_end_date', name: 'contract_end_date' },
	        	            { data: 'contract_duraction', name: 'contract_duraction' },
	        	            { data: 'contact_before_end_of_contract', name: 'contact_before_end_of_contract' },
	        	            { data: 'date_to_contact', name: 'date_to_contact' },
	        	            {{-- { data: 'difference', name: 'difference' }, --}}
	        	            {{-- { data: 'contract_info', name: 'contract_info' }, --}}
	        	        ],
	        	       
                        {{-- "fnDrawCallback": function(oSettings) {
                            var total_fee_monthly = sum_table_col($('#contract_modul_table'), 'fee_monthly');
                            $('.footer_fee_monthly_total').text(total_fee_monthly);

                            var total_discount = sum_table_col($('#contract_modul_table'), 'discount');
                            $('#footer_total_discount').text(total_discount);

                            var total_price_total = sum_table_col($('#contract_modul_table'), 'price_total');
                            $('#footer_total_price_total').text(total_price_total);


                        },  --}}

                        "footerCallback": function ( row, data, start, end, display ) {
                            var footer_fee_monthly_total = 0;
                            var footer_discount_total = 0;
                            var footer_price_total_total = 0;
                            for (var r in data){
                                footer_fee_monthly_total += $(data[r].fee_monthly).data('orig-value') ? parseFloat($(data[r].fee_monthly).data('orig-value')) : 0;
                                footer_discount_total += $(data[r].discount).data('orig-value') ? parseFloat($(data[r].discount).data('orig-value')) : 0;
                                footer_price_total_total += $(data[r].price_total).data('orig-value') ? parseFloat($(data[r].price_total).data('orig-value')) : 0;
                                {{-- console.log((parseFloat($(data[r].fee_monthly).data('orig-value'))) + ' bbaaaa'); --}}
                            }
                            $('.fee_monthly_total').html(__currency_trans_from_en(footer_fee_monthly_total));
                            $('.footer_total_discount').html(__currency_trans_from_en(footer_discount_total));
                            $('.footer_t_price_total').html(__currency_trans_from_en(footer_price_total_total));
                            
                        },                        

                        {{-- createdRow: function(row, data, dataIndex) {
                            $(row).addClass('red');                                
                        }, --}}
	        		});

                    

	       
        });  

    </script>
    {{-- <script src="{{ asset('modules/crm/js/crm.js?v=' . $asset_v) }}"></script> --}}
@endsection