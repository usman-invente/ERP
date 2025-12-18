@extends('layouts.app')

@section('title', __('crm::lang.lead'))

@section('content')
@include('crm::layouts.nav')
<!-- Content Header (Page header) -->
<section class="content-header no-print">
   <h1>@lang('crm::lang.leads')</h1>
</section>
		@php
			$business_id = auth()->user()->business->id;
    	    $business_location = (auth()->user()->first_location(auth()->user()->business->id));
			$business_name = str_replace(" ", "_", auth()->user()->business->name);
			$business_name = str_replace("/", "$&", $business_name);
    	@endphp	
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        <div class="row">
            {{-- <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('source', __('crm::lang.source') . ':') !!}
                    {!! Form::select('source', $sources, null, ['class' => 'form-control select2', 'id' => 'source', 'placeholder' => __('messages.all')]); !!}
                </div>    
            </div>
            @if($lead_view != 'kanban')
                <div class="col-md-4">
                    <div class="form-group">
                         {!! Form::label('life_stage', __('crm::lang.life_stage') . ':') !!}
                        {!! Form::select('life_stage', $life_stages, null, ['class' => 'form-control select2', 'id' => 'life_stage', 'placeholder' => __('messages.all')]); !!}
                    </div>
                </div>
            @endif --}}
            @if(count($users) > 0)
            <div class="col-md-4">
                <div class="form-group">
					{!! Form::label('user_id', __('lang_v1.assigned_to') . ':') !!}
					{!! Form::select('user_id', $users, null, [
						'class' => 'form-control select2',
						'style' => 'width:100%',
						'id' => 'user_id',
						'placeholder' => __('messages.all'),
					]) !!}
				</div>   
            </div>
            @endif
			{{-- @if(empty($only) || in_array('only_subscriptions', $only)) 
				<div class="col-md-3">
				    <div class="form-group">
				        <div class="checkbox">
				            <label>
				                <br>
				              {!! Form::checkbox('consent_email', 1, false, 
				              [ 'class' => 'input-icheck', 'id' => 'consent_email']); !!} {{ __('lang_v1.consent_email') }}
				            </label>
				        </div>
				    </div>
				</div>
			{{-- @endif --}}
        </div>
    @endcomponent
	@component('components.widget', ['class' => 'box-primary', 'title' => __('crm::lang.all_leads')])
        @slot('tool')
            <div class="box-tools">
                <button type="button" class="btn btn-sm btn-primary btn-add-lead pull-right m-5" data-href="{{action([\Modules\Crm\Http\Controllers\LeadController::class, 'create'])}}">
                    <i class="fa fa-plus"></i> @lang('messages.add')
                </button>
				<a target="_blank" class="fcc-btn" href="{{route('crm-new-customer',[$business_id,$business_location->id,$business_name])}}">
                	<button type="button" class="btn btn-sm btn-primary pull-right m-5">
                	    <i class="fa fa-plus"></i> Link
                	</button>
				</a>  
				{{-- <a href="https://www.freecodecamp.org/">
  				  <button>freeCodeCamp</button>
  				</a> --}} 
				{{-- var link = "{{url('crm-new-customer/'. session('business.id'))}}/" + $('#location_id').val()+"/" + $('#business_n').val(); --}}
                <div class="btn-group btn-group-toggle pull-right m-5" data-toggle="buttons">
                    {{-- <label class="btn btn-info btn-sm active list">
                        <input type="radio" name="lead_view" value="list_view" class="lead_view" data-href="{{action([\Modules\Crm\Http\Controllers\LeadController::class, 'index']).'?lead_view=list_view'}}">
                        @lang('crm::lang.list_view')
                    </label> --}}
                    {{-- <label class="btn btn-info btn-sm kanban">
                        <input type="radio" name="lead_view" value="kanban" class="lead_view" data-href="{{action([\Modules\Crm\Http\Controllers\LeadController::class, 'index']).'?lead_view=kanban'}}">
                        @lang('crm::lang.kanban_board')
                    </label> --}}
                </div>
            </div>					
        @endslot
        @if($lead_view == 'list_view')
        	<table class="table table-bordered table-striped" id="leads_table">
		        <thead>
		            <tr>
		                <th> @lang('messages.action')</th>
		                <th>@lang('lang_v1.contact_id')</th>
                        <th>@lang('business.first_name')</th>
                        <th>@lang('business.last_name')</th>						
                        <th>@lang('crm::lang.type_status')</th>						
						<th>@lang('lang_v1.consent_as_text') @lang('lang_v1.consent_email')</th> 
						<th>@lang('lang_v1.consent_as_text') @lang('lang_v1.consent_mobile')</th> 
						<th>@lang('lang_v1.consent_as_text') @lang('lang_v1.consent_post')</th> 
						<th>@lang('lang_v1.consent_as_text') @lang('lang_v1.consent_messenger')</th>   
                        <th>@lang('lang_v1.assigned_to')</th>
                        <th>@lang('lang_v1.added_on')</th>
                        <th>@lang('business.supplier_business_name')</th> 
                        <th>@lang('business.business_position')</th> 
                        <th>@lang('business.prefix')</th>
		                {{-- <th>@lang('contact.name')</th> --}}
                        <th>@lang('business.email')</th>
                        <th>@lang('contact.mobile')</th>    
                        <th>@lang('business.street')</th>                  
                        <th>@lang('business.house_nr')</th>                  
                        <th>@lang('business.zip_code')</th>                  
                        <th>@lang('business.city')</th>                  
                        <th>@lang('business.country')</th>                  
                        <th>@lang('lang_v1.dob')</th>                  
                        {{-- <th>@lang('business.address')</th>                         --}}
                        @php
                            $custom_labels = json_decode(session('business.custom_labels'), true);
                        @endphp
                        <th>
                            {{ $custom_labels['contact']['custom_field_1'] ?? __('lang_v1.contact_custom_field1') }}
                        </th>
                        <th>
                            {{ $custom_labels['contact']['custom_field_2'] ?? __('lang_v1.contact_custom_field2') }}
                        </th>
                        <th>
                            {{ $custom_labels['contact']['custom_field_3'] ?? __('lang_v1.contact_custom_field3') }}
                        </th>
                        <th>
                            {{ $custom_labels['contact']['custom_field_4'] ?? __('lang_v1.contact_custom_field4') }}
                        </th>
                        
		            </tr>
		        </thead>
                {{-- <tfoot>
                    <tr class="bg-gray font-17 text-center footer-total">
                        <td colspan="23" class="text-left">
                            <button type="button" class="btn btn-xs btn-success update_contact_location" data-type="add">@lang('lang_v1.add_to_location')</button>
                                &nbsp;
                                <button type="button" class="btn btn-xs bg-navy update_contact_location" data-type="remove">@lang('lang_v1.remove_from_location')</button>
                        </td>
                    </tr>
                </tfoot> --}}
		    </table>
        @endif
        {{-- @if($lead_view == 'kanban')
            <div class="lead-kanban-board">
                <div class="page">
                    <div class="main">
                        <div class="meta-tasks-wrapper">
                            <div id="myKanban" class="meta-tasks">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}
    @endcomponent
    <div class="modal fade contact_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade schedule" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
</section>
@endsection
@section('javascript')
    <script type="text/javascript">
{{-- <script src="{{ asset('modules/crm/js/crm.js?v=' . $asset_v) }}"></script> --}}

    function initializeLeadDatatable() {
    	if((typeof leads_datatable == 'undefined')) {
			$(document).ready(function() {
		
				$('#leads_table thead tr').clone(true).appendTo( '#leads_table thead' );
					$('#leads_table thead tr:eq(0) th').each( function (i) {
						var title = $(this).text();
        			    if(i == 0 || (title.includes( '{{ __('lang_v1.assigned_to') }}') == true)){
        			       $(this).html( '<td/>' ); 
        			    }
						else if(title.includes( '{{ __('business.prefix') }}') == true){
							var select = $(' <select><option value="">Alle</option><option>Frau</option><option>Herr</option><option>Firma</option></select>')
							  .appendTo($(this).empty())
							  .on('change', function() {
								var term = $(this).val();
								leads_datatable.column(i).search(term, false, false).draw();
							  });

        			    }
						else if(title.includes( '{{ __('crm::lang.type_status') }}') == true){
							var select = $(' <select><option value="">Alle</option> <option>{{ __('crm::lang.customer') }}</option><option>{{ __('crm::lang.lead') }}</option><option>{{ __('crm::lang.supplier') }}</option></select>')
							  .appendTo($(this).empty())
							  .on('change', function() {
								var term = $(this).val();
								leads_datatable.column(i).search(term, false, false).draw();
							  });

        			    }
        			    else{
        			        {{-- $(this).html( '<input size="8" type="text" placeholder="'+title+'" />' ); --}}
        			        $(this).html( '<input size="8" type="text"  />' );

								$( 'input', this ).on( 'keyup change', function () {
										if ( leads_datatable.column(i).search() !== this.value ) {
											leads_datatable
												.column(i)
												.search( this.value )
												.draw();
										}
									} );
        			    }
        			});
			
    			leads_datatable = $("#leads_table").DataTable({
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
		        	    url: "/crm/leads",
		        	    data:function(d) {
		        	    	d.source = $("#source").val();
		        	    	d.life_stage = $("#life_stage").val();
		        	    	d.user_id = $("#user_id").val();
		        	    	d.lead_view = urlSearchParam('lead_view');

							if($('#consent_email').is(':checked')) {
                			    d.consent_email = 1;
                			}else{
								d.consent_email = 0;
							}
							{{-- console.log(d.user_id) --}}

		        	    }
		        	},
		        	columnDefs: [
		        	    {
		        	        targets: [0],
		        	        orderable: false,
		        	        searchable: true,
		        	    },
                	    { type: 'date-eu', targets: 13},
		        	],
		        	aaSorting: [[10, 'desc']],
		        	columns: [
		        	    { data: 'action', name: 'action' },
		        	    { data: 'contact_id', name: 'contact_id' }, 
						{ data: 'first_name', name: 'first_name' },
                	    { data: 'last_name', name: 'last_name' },
                	    { data: 'type_status', name: 'type_status' },
						{ data: 'consent_email', name: 'consent_email' },  
						{ data: 'consent_mobile', name: 'consent_mobile' },  
						{ data: 'consent_post', name: 'consent_post' },  
						{ data: 'consent_messenger', name: 'consent_messenger' },  
                	    { data: 'leadUsers', name: 'leadUsers' },
                	    { data: 'created_at', name: 'created_at' },
                	    { data: 'supplier_business_name', name: 'supplier_business_name' },	 
                	    { data: 'business_position', name: 'business_position' },	 
                	    { data: 'prefix', name: 'prefix' },                	    
		        	    {{-- { data: 'name', name: 'name', searchable: true }, --}}
		        	    { data: 'email', name: 'email' },
                	    { data: 'mobile', name: 'mobile' },                   	    
                	    { data: 'street', name: 'street' },   
                	    { data: 'house_nr', name: 'house_nr' },   
                	    { data: 'zip_code', name: 'zip_code' },   
                	    { data: 'city', name: 'city' },   
                	    { data: 'country', name: 'country' },   
                	    { data: 'dob', name: 'dob' },   
		        	    {{-- { data: 'address', name: 'address', orderable: false }, --}}
		        	    { data: 'custom_field1', name: 'custom_field1' },
		        	    { data: 'custom_field2', name: 'custom_field2' },
		        	    { data: 'custom_field3', name: 'custom_field3' },
		        	    { data: 'custom_field4', name: 'custom_field4' }
		        	] 
				});
			});
    	} else{
			 leads_datatable.ajax.reload();
		}
    }

    
    $(document).ready(function() {	
			
        /*Start Table Data*/
       initializeLeadDatatable();
        
            /*End Table Data*/

	    $(document).on('click', '#delete_contact_login', function(e) {
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
	                            all_contact_login_datatable.ajax.reload();
	                            contact_login_datatable.ajax.reload();
	                        } else {
	                            toastr.error(result.msg);
	                        }
	                    }
	                });
	            }
	        });
	    });
    
	    $(document).on('click', '.edit_contact_login', function() {
	        var url = $(this).data('href');
	        var data = {
	        			crud_type: $("input#login_view_type").val()
	        		};
	        $.ajax({
	            method: 'GET',
	            url: url,
	            dataType: 'html',
	            data: data,
	            success: function(result) {
	                $('.contact_login_modal').html(result).modal('show');
	            }
	        });
	    });

	    $(document).on('submit', 'form#contact_login_edit', function(e) {
	        e.preventDefault();
	        var data = $('form#contact_login_edit').serialize();
	        var url = $('form#contact_login_edit').attr('action');
	        $.ajax({
	            method: 'PUT',
	            url: url,
	            dataType: 'json',
	            data: data,
	            success: function(result) {
	                if (result.success) {
	                    $('.contact_login_modal').modal('hide');
	                    toastr.success(result.msg);
	                    all_contact_login_datatable.ajax.reload();
	                    contact_login_datatable.ajax.reload();
	                } else {
	                    toastr.error(result.msg);
	                }
	            }
	        });
	    });

	    /**
	    * Crm Ledger
	    * related code
	    */

	    $('#ledger_date_range').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#ledger_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            }
        );

        $('#ledger_date_range').change( function(){
            getLedger();
        });

	    $(document).on('click', '#create_ledger_pdf', function() {
	        var start_date = $('#ledger_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
	        var end_date = $('#ledger_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');

	        var url = $(this).data('href') + '&start_date=' + start_date + '&end_date=' + end_date;
	        window.location = url;
	    });

	    /**
	    * Crm Profile
	    * edit/update related
	    * code
	    */

        $("form#edit_contact_profile").validate({
        	errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            rules: {
                first_name: {
                    required: true,
                },
                email: {
                    email: true,
                    remote: {
                        url: "/business/register/check-email",
                        type: "post",
                        data: {
                            email: function() {
                                return $( "#email" ).val();
                            },
                            user_id: $('input#user_id').val()
                        }
                    }
                }
            },
            messages: {
                email: {
                    remote: '{{ __("validation.unique", ["attribute" => __("business.email")]) }}'
                }
            }
        });

	    /**
	    * Crm Purchase
	    * related code
	    */
	    $('#date_range_filter').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#date_range_filter').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
               contact_purchase_datatable.ajax.reload();
            }
        );

	    $('#date_range_filter').on('cancel.daterangepicker', function(ev, picker) {
            $('#date_range_filter').val('');
            contact_purchase_datatable.ajax.reload();
        });

	    
	    /**
	    * Crm schedule
	    * related code
	    */
	    $(document).on('click', '.btn-add-schedule', function() {
	        load_schedule_modal();
	    });
	    
		$(document).on('click', '.btn-add-contract', function() {
	        load_contract_modal();
	    });

	    

	    $('.schedule').on('hidden.bs.modal', function(){
	        tinymce.remove("textarea#description");
	    });

	    

	    /**
	    * Crm schedule log
	    * related code
	    */
	    
	    /**
	    * Crm lead
	    * related code
	    */
	    $(document).on('click', '.btn-add-lead', function() {
	    	var url = $(this).data('href');
	    	$.ajax({
	            method: 'GET',
	            url: url,
	            dataType: 'html',
	            success: function(result) {
	                $('.contact_modal').html(result).modal('show');
	            }
	        });
	    });

	    $(document).on('click', '.delete_a_lead', function(e) {
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
    
	                            var lead_view = urlSearchParam('lead_view');

	    						if (lead_view == 'kanban') {
	    						    initializeLeadKanbanBoard();
	    						} else if(lead_view == 'list_view') {
	    						    leads_datatable.ajax.reload();
	    						}

	                        } else {
	                            toastr.error(result.msg);
	                        }
	                    }
	                });
	            }
	        });
	    });

	    $(document).on('click', '.convert_to_customer', function() {
        	var url = $(this).data('href');
	    	$.ajax({
	            method: 'GET',
	            url: url,
	            dataType: 'json',
	            success: function(result) {
	                if (result.success) {
                        toastr.success(result.msg);
                        leads_datatable.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
	            }
	        });
        });

        $(document).on('click', '.edit_lead', function() {
            var url = $(this).data('href');
            $.ajax({
                method: 'GET',
                url: url,
                dataType: 'html',
                success: function(result) {
                    $('.contact_modal').html(result).modal('show');
                }
            });
        });

        $(document).on('change', "#life_stage, #source, #user_id, #consent_email", function(){
	       var lead_view = urlSearchParam('lead_view');
	    	if (lead_view == 'kanban') {
	    	    initializeLeadKanbanBoard();
	    	} else if(lead_view == 'list_view') {
	    	    leads_datatable.ajax.reload();
	    	}
	    });

        $(document).on('change', '.lead_view', function() {
	        window.location.href = $(this).data('href');
	    });

	    


	    /**
	     * CRM MODULE
	     * campaign related code
	     */
	    $(document).on('click', '.delete_a_campaign', function(e) {
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
	                            campaigns_datatable.ajax.reload();
	                        } else {
	                            toastr.error(result.msg);
	                        }
	                    }
	                });
	            }
	        });
	    });

	    $(document).on('change', '#campaign_type_filter', function() {
	        campaigns_datatable.ajax.reload();
	    });

	    $('.campaign_modal').on('hidden.bs.modal', function(){
	        tinymce.remove("textarea#email_body");
	    });

	    if ($('form#campaign_form').length) {
	    	$('form#campaign_form').validate({
	    		rules: {
	    			'contact_id[]': {
	    				required: true
	    			},
	    			'lead_id[]': {
	    				required: true
	    			}
	    		},
	    		submitHandler: function(form) {
	    			if ($(form).valid()) {
	    				form.submit();
	    				$(".submit-button").prop( "disabled", true );
	    			}
	    		}
	    	});
	    	$(".select2").select2();

	        tinymce.init({
	            selector: 'textarea#email_body'
	        });

	        if ($('select#campaign_type').val() == 'sms') {
                $('div.email_div').hide();
                $('div.sms_div').show();
            } else if ($('select#campaign_type').val() == 'email') {
                $('div.email_div').show();
                $('div.sms_div').hide();
            }

            $('select#campaign_type').change(function() {
                var campaign_type = $(this).val();
                if (campaign_type == 'sms') {
                    $('div.sms_div').show();
                    $('div.email_div').hide();
                } else if (campaign_type == 'email') {
                    $('div.email_div').show();
                    $('div.sms_div').hide();
                }
            });
	    }
    
	    $(document).on('click', '.send_campaign_notification', function() {
	    	var url = $(this).data('href');
	    	$.ajax({
	            method: 'GET',
	            url: url,
	            dataType: 'json',
	            success: function(result) {
	                if (result.success) {
	                    toastr.success(result.msg);
	                    campaigns_datatable.ajax.reload();
	                } else {
	                    toastr.error(result.msg);
	                }
	            }
	        });
	    });

	    $(document).on('click', '.view_a_campaign', function() {
	    	var url = $(this).data('href');
	        $.ajax({
	            method: 'GET',
	            url: url,
	            dataType: 'html',
	            success: function(result) {
	                $('.campaign_view_modal').html(result).modal('show');
	            }
	        });
	    });
    });
    
    </script>
@endsection