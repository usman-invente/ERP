<style>  

    .red {
      background-color: #ff9999 !important;
    }
    .green {
      background-color: #2dce89 !important;
    }
    .orange {
      background-color: #ffad46 !important;
    }
    .blue {
      background-color: #11cdef !important;
    }
</style>
<div class="pull-right">
    {{-- <button type="button" class="btn btn-sm btn-primary btn-add-schedule pull-right">
        @lang('messages.add')&nbsp;
        <i class="fa fa-plus"></i>
    </button>    --}}
    <div class="box-tools">
        <button type="button" class="btn btn-sm btn-primary btn-modal" 
            data-href="{{action([\App\Http\Controllers\ContractController::class, 'create'])}}" 
            data-container=".contract_add_modal">
            <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
    </div>

    <input type="hidden" name="contract_create_url" id="contract_create_url" value="{{action([\App\Http\Controllers\ContractController::class, 'create'])}}?contract_for=lead&contact_id={{$contact->id}}">
    <input type="hidden" name="contact_id" value="{{$contact->id}}" id="contact_id">
    <input type="hidden" name="view_type" value="lead_info" id="view_type">
    
</div> 
<br><br>
<div class="table-responsive">
	<table class="table table-bordered table-striped" id="contract_modul_table" style="width: 100%">
        <thead>
            <tr>
                <th>@lang('messages.action')</th>
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
                {{-- <th>Diff</th> --}}
                {{-- <th>@lang('contract.contract_info')</th> --}}
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr class="bg-gray font-17 footer-total text-center">
                <td colspan="8"><strong>@lang('contract.total'):</strong></td>
                <td class="fee_monthly_total"> </td>
                <td class="footer_total_discount"></td>
                <td colspan="2"></td>
                <td class="footer_t_price_total"></td>
                <td colspan="5"></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="modal fade contract_add_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
@section('javascript')
    @includeIf('documents_and_notes.document_and_note_js')
    <script type="text/javascript">

        $(document).ready(function() {
            {{-- if((typeof lead_schedule_datatable == 'undefined')) { --}}
                //----Customer Historie
                $('#contact_info_historie_modul_table thead tr').clone(true).appendTo( '#contact_info_historie_modul_table thead' );
		        $('#contact_info_historie_modul_table thead tr:eq(0) th').each( function (i) {
		        	var title = $(this).text();
                    // if(i == 0 ){
                    //    $(this).html( '<td/>' ); 
                    // }
		        	if(title.includes( '{{ __('contact.info_historie_type') }}') == true){
		        		var select = $(' <select><option value="">Alle</option><option>{{ __('contact.customer') }}</option><option>{{ __('contact.info_historie_type_dokument') }}</option><option>{{ __('contact.info_historie_type_contract') }}</option><option>{{ __('contact.info_historie_type_invoice') }}</option></select>')
		        		  .appendTo($(this).empty())
		        		  .on('change', function() {
		        			var term = $(this).val();
		        			contact_info_modul_datatable.column(i).search(term, false, false).draw();
		        		  });
                    }
                    else{
                        $(this).html( '<input size="8" type="text"  />' );
		        			$( 'input', this ).on( 'keyup change', function () {
		        					if ( contact_info_modul_datatable.column(i).search() !== this.value ) {
		        						contact_info_modul_datatable
		        							.column(i)
		        							.search( this.value )
		        							.draw();
		        					}
		        				} );
                    }
                });
                    contact_info_modul_datatable = $("#contact_info_historie_modul_table").DataTable({
                    processing: true,
                    serverSide: true,
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
                         url: "/list-contact-info-historie",
                        data:function(d) {
                            d.contact_id = $("#contact_id").val();
                            d = __datatable_ajax_callback(d);
                            
                        }
                    },
                    columnDefs: [
                        {
                            targets: [],
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    aaSorting: [[1, 'desc']],
                    columns: [                        
                        { data: 'action', name: 'action' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'title', name: 'title' },
                        { data: 'description', name: 'description' },
                        { data: 'details', name: 'details' },
                        { data: 'ip_address', name: 'ip_address' },
                        { data: 'type', name: 'type' },
                        { data: 'consent', name: 'consent' },
                        
                    ],
                });
                
                //---- Contract
                    $('#contract_modul_table thead tr').clone(true).appendTo( '#contract_modul_table thead' );
					$('#contract_modul_table thead tr:eq(0) th').each( function (i) {
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

                    contract_modul_datatable = $("#contract_modul_table").DataTable({
	        			processing: true,
	        	        serverSide: true,
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
	        	             url: "/customer-contracts",
	        	            data:function(d) {
	        	            	d.contact_id = $("#contact_id").val();

                                d = __datatable_ajax_callback(d);
                                console.log($("#contact_id").val()+ 'ketuVertrag');
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

                        "createdRow": function( row, data, dataIndex){
                            if(data["is_date_overdue"]){
                                $(row).addClass('red');
                            }

                            if(!data["is_date_overdue"] && data["is_date_in_this_week"]){
                                $(row).addClass('orange');
                            }
                            if(!data["is_date_overdue"] && data["is_date_in_this_month"]){
                                $(row).addClass('green');
                            }
                            if(data["is_date_in_next_month"]){
                                $(row).addClass('blue');
                            }
                            
                        },

	        		});

                    
            {{-- console.log(diffDays )           --}}

	        {{-- } else {
                contract_modul_datatable.ajax.reload();
            } --}}
        });

        $(document).on('click', '.delete_a_contract', function(e) {
	    	e.preventDefault();
	    	var url = $(this).data('href');
	    	swal({
	            title: LANG.sure,
	            icon: "warning",
	            buttons: true,
	            dangerMode: true,
	        }).then((confirmed) => {
	            if (confirmed) {
	                $.ajax({
	                    method: 'DELETE',
	                    url: url,
	                    dataType: 'json',
	                    success: function(result) {
	                        if (result.success) {
	                            toastr.success(result.msg);

                                contract_modul_datatable.ajax.reload();
	                            /*var lead_view = urlSearchParam('lead_view');

	    						if (lead_view == 'kanban') {
	    						    initializeLeadKanbanBoard();
	    						} else if(lead_view == 'list_view') {
	    						    leads_datatable.ajax.reload();
	    						}*/

	                        } else {
	                            toastr.error(result.msg);
	                        }
	                    }
	                });
	            }
	        });
	    });

        $(document).on('click', '.edit_contract', function(e) {
            e.preventDefault();
            var url = $(this).data('href');
            $.ajax({
                method: 'GET',
                url: url,
                dataType: 'html',
                success: function(result) {
                    $('.contract_modal').html(result).modal('show');

                    console.log('url '+url);
                    console.log('ketu jam '+result);
                }
            });
        });

        //On display of add contact modal
  
    </script>
    <script src="{{ asset('modules/crm/js/crm.js?v=' . $asset_v) }}"></script>
@endsection