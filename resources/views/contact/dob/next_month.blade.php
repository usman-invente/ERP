@extends('layouts.app')

@section('title', __('contact.dobs'))
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
@include('layouts.contact_dob_nav')
@component('components.widget', ['class' => 'box-primary', 'title' => __('contact.dob_today')])
      
		    <table class="table table-bordered table-striped" id="contact_dob_today_table" style="width: 100%">
                <thead>
                    <tr>
                        <th>@lang('messages.action')</th>
                        <th>@lang('contact.name')</th>
                        <th>@lang('contact.dob')</th>
                        <th>@lang('business.email')</th>
                        <th>@lang('business.mobile')</th>
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

                    $('#contact_dob_today_table thead tr').clone(true).appendTo( '#contact_dob_today_table thead' );
					$('#contact_dob_today_table thead tr:eq(0) th').each( function (i) {
						var title = $(this).text();
        			    if(i == 0 ){
        			       $(this).html( '<td/>' ); 
        			    }
						
        			    else{
        			        $(this).html( '<input size="8" type="text"  />' );

								$( 'input', this ).on( 'keyup change', function () {
										if ( contact_dob_modul_datatable.column(i).search() !== this.value ) {
											contact_dob_modul_datatable
												.column(i)
												.search( this.value )
												.draw();
										}
									} );
        			    }
        			});

                    contact_dob_modul_datatable = $("#contact_dob_today_table").DataTable({
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
	        	             url: "/contacts/dob_next_month",
	        	            data:function(d) {
	        	            	

                                d = __datatable_ajax_callback(d);
	        	            }
	        	        },
	        	        columnDefs: [
	        	            {
	        	                targets: [0],
	        	                orderable: false,
	        	                searchable: false,
	        	            },
	        	        ],
	        	        aaSorting: [[1, 'asc']],
	        	        columns: [
	        	            { data: 'action', name: 'action' },
	        	            { data: 'name', name: 'name' },
	        	            { data: 'dob', name: 'dob' },
	        	            { data: 'email', name: 'email' },
	        	            { data: 'mobile', name: 'mobile' },
	        	       
                        ]
	        		});

                    

	       
        });  

    </script>
    {{-- <script src="{{ asset('modules/crm/js/crm.js?v=' . $asset_v) }}"></script> --}}
@endsection