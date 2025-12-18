@extends('layouts.app')

@section('title', __('crm::lang.view_lead'))

@section('content')
@include('crm::layouts.nav')

@php
    $first_name = str_replace(" ", "_",  $contact->first_name);
    if(!empty($contact->business_id) && !empty($contact->id) && !empty($first_name)){
        $url_opt_out = action([\App\Http\Controllers\ContactsRegistrationController::class, 'customer_destroy'], ['business_id' => $contact->business_id, 'contact_id' => $contact->id, 'first_name' => $first_name ]);
        $url_edit_self = action([\App\Http\Controllers\ContactsRegistrationController::class, 'self_change_data'], ['business_id' => $contact->business_id, 'contact_id' => $contact->id, 'first_name' => $first_name ]);
    }else{
        $url_opt_out = null;
        $url_edit_self = null;
    }
    
@endphp
<section class="content no-print">
    <div class="row no-print">
        <div class="col-md-4">
            <h3>@lang('crm::lang.view_lead')</h3>
        </div>
        <div class="col-md-4 col-xs-12 mt-15 pull-right">           
            <div class="box-tools">
                <div class="btn-group btn-group-toggle pull-right m-5" data-toggle="buttons">
                    <label class="btn btn-info btn-sm active list">
                        <input type="radio" name="lead_view" value="list_view" class="lead_view" data-href="{{action([\Modules\Crm\Http\Controllers\LeadController::class, 'index']).'?lead_view=list_view'}}">
                        @lang('crm::lang.list_view')
                    </label>
                </div>
                {{-- {{ request()->ip() }} --}}
                {{-- <div class="btn-group btn-group-toggle pull-right m-5" data-toggle="buttons">
                    <label class="btn btn-info btn-sm active list">
                        <input type="radio" name="edit_lead" value="edit_lead" class="edit_lead" data-href="{{action([\Modules\Crm\Http\Controllers\LeadController::class, 'edit'], , ['lead' => $contact->id])}}">
                        @lang('messages.edit')
                    </label>
                </div> --}}
            </div>
        </div>
        {{-- <div class="col-md-4 col-xs-12 mt-15 pull-right">
            {!! Form::select('lead_id', $leads, $contact->id , ['class' => 'form-control select2', 'id' => 'lead_id']); !!}
        </div> --}}
    </div><br>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                    <div class="box-body">
            <div class="col-md-2">                
                {{-- <a href="{{ action([\Modules\Crm\Http\Controllers\LeadController::class, 'editByShow'], ['id' => $contact->id]) }}" >
                  <i class="fa fa-edit"></i>
                    @lang('messages.edit')
                </a> 
                <br>
                <br>
                {{-- <a href="{{ $url_edit_self }}" target="_blank" >
                  <i class="fa fa-edit"></i>
                    @lang('messages.edit')
                </a>                    --}}
                    @include('crm::lead.partial.lead_info')
                    
            </div>
             
                <div class="col-md-10">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs nav-justified">
                            
                            <li class="active">
                                <a href="#lead_contract" data-toggle="tab" aria-expanded="true">
                                    <i class="fas fa-file-contract"></i>
                                    @lang('contract.contracts')  <br><small> (CRM PRO)  @show_tooltip(__('lang_v1.contracts_tooltip'))</small>
                                </a>
                            </li>
                            <li>
                                <a href="#lead_contact_info_historie" data-toggle="tab" aria-expanded="true">
                                    <i class="fa fa-history"></i>
                                    @lang('contact.contact_info_historie')  <br><small> (CRM PRO)  @show_tooltip('Verlauf')</small>
                                </a>
                            </li>
                            <li >
                                <a href="#documents_and_notes" data-toggle="tab" aria-expanded="true">
                                    <i class="fas fa-file-image"></i>
                                    @lang('crm::lang.documents_and_notes') <br><small> (CRM PRO)  @show_tooltip(__('lang_v1.documents_and_notes_permissions_tooltip'))</small>
                                </a>
                            </li>
                            @if($business->id == 1) 
                                {{-- <li>
                                    <a href="#lead_schedule" data-toggle="tab" aria-expanded="true">
                                        <i class="fas fa fa-calendar-check"></i>
                                        @lang('crm::lang.schedule')
                                    </a>
                                </li> --}}
                                
                                @if(!empty($contact_view_tabs))
                                    @foreach($contact_view_tabs as $key => $tabs)
                                        @foreach ($tabs as $index => $value)
                                            @if(!empty($value['tab_menu_path']))
                                                @php
                                                    $tab_data = !empty($value['tab_data']) ? $value['tab_data'] : [];
                                                @endphp
                                                @include($value['tab_menu_path'], $tab_data)
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            @endif
                        </ul>
                        <div class="tab-content">
                            
                            <div class="tab-pane active" id="lead_contract">
                                    @include('contract.index')
                            </div>
                            <div class="tab-pane" id="lead_contact_info_historie">
                                    @include('contact.index_info_historie')
                            </div>
                            <div class="tab-pane document_note_body" id="documents_and_notes">
                            </div>
                                <div class="tab-pane" id="lead_schedule">
                                    @include('crm::lead.partial.lead_schedule')
                                </div>
                            @if($business->id == 1)  
                                                         
                                @if(!empty($contact_view_tabs))
                                    @foreach($contact_view_tabs as $key => $tabs)
                                        @foreach ($tabs as $index => $value)
                                            @if(!empty($value['tab_content_path']))
                                                @php
                                                    $tab_data = !empty($value['tab_data']) ? $value['tab_data'] : [];
                                                @endphp
                                                @include($value['tab_content_path'], $tab_data)
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            @endif
                            <!-- model id like project_id, user_id -->
                            <input type="hidden" name="notable_id" id="notable_id" value="{{$contact->id}}">
                            <!-- model name like App\User -->
                            <input type="hidden" name="notable_type" id="notable_type" value="App\Contact">
                            
                        </div>
                    </div>
                    @if(auth()->user()->id == 2)
                    <div>
                        <table align="center" class="table table-bordered " style="width: 90%">
                            
                            <tr>
                                <td colspan=2>
                                    {!! Form::label('contract', __('contract.contract_infos')) !!}
                                </td>
                            </tr>
                            <tr>
                                <td  width="10%">
                                     <a href="#" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#KundeInfoEdit_{{ $contact->id }}"
                                    title="Info-Beschreibung bearbeiten"><i class="fa fa-edit"></i>
                                    </a>
                                    @include('crm::lead.edit_info')
                                </td>
                                <td>
                                    @php
                                        
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{-- {!! $contact->info_description !!} --}}
                                                {!! Form::textarea('contract_info', $contact->info_description, ['class' => 'form-control ', 'id' => 'description', 'disabled']); !!}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @endif
                </div>
            {{-- @endif --}}
        </div>
        </div>
        </div>
    </div>
    
</section>
<div class="modal fade schedule" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade edit_schedule" tabindex="-1" role="dialog"></div>
<div class="modal fade schedule_log_modal" tabindex="-1" role="dialog"></div>
@endsection
@section('javascript')
	<script src="{{ asset('modules/crm/js/crm.js?v=' . $asset_v) }}"></script>
    @includeIf('documents_and_notes.document_and_note_js')
    <script type="text/javascript">
        $(document).ready(function() {
            if((typeof lead_schedule_datatable == 'undefined')) {
	        	lead_schedule_datatable = $("#lead_schedule_table").DataTable({
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
	        	            url: "/crm/lead-follow-ups",
	        	            data:function(d) {
	        	            	d.lead_id = $("#lead_id").val();
	        	            }
	        	        },
	        	        columnDefs: [
	        	            {
	        	                targets: [0, 6],
	        	                orderable: false,
	        	                searchable: false,
	        	            },
	        	        ],
	        	        aaSorting: [[1, 'desc']],
	        	        columns: [
	        	            { data: 'action', name: 'action' },
	        	            { data: 'title', name: 'title' },
	        	            { data: 'status', name: 'status' },
	        	            { data: 'schedule_type', name: 'schedule_type' },
	        	            { data: 'start_datetime', name: 'start_datetime' },
	        	            { data: 'end_datetime', name: 'end_datetime' },
	        	            { data: 'users', name: 'users' },
	        	        ],
	        	        "fnDrawCallback": function( oSettings ) {
	        	        	$('a.view_schedule_log').click(function(){
	        	        		getScheduleLog($(this).data('schedule_id'), true);
	        	        	})
	        		    }, 
	        		});

/*
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
	        	            	d.lead_id = $("#lead_id").val();
	        	            }
	        	        },
	        	        columnDefs: [
	        	            {
	        	                targets: [0, 6],
	        	                orderable: false,
	        	                searchable: false,
	        	            },
	        	        ],
	        	        aaSorting: [[1, 'desc']],
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
	        	            { data: 'contract_completion', name: 'contract_completion' },
	        	            { data: 'contract_duraction', name: 'contract_duraction' },
	        	            { data: 'contact_before_end_of_contract', name: 'contact_before_end_of_contract' },
	        	            { data: 'date_to_contact', name: 'date_to_contact' },
	        	            {{-- { data: 'contract_info', name: 'contract_info' }, --}}
	        	        ],
	        	       
                       fnDrawCallback: function(oSettings) {
                            var total_fee_monthly = sum_table_col($('#contract_modul_table'), 'fee_monthly');
                            $('#footer_fee_monthly_total').text(total_fee_monthly);

                            var total_discount = sum_table_col($('#contract_modul_table'), 'discount');
                            $('#footer_total_discount').text(total_discount);

                            var total_price_total = sum_table_col($('#contract_modul_table'), 'price_total');
                            $('#footer_total_price_total').text(total_price_total);


                            __currency_convert_recursively($('#contract_modul_table'));
                        },
                        createdRow: function(row, data, dataIndex) {
                            $(row)
                                .find('td:eq(3)')
                                .attr('class', 'clickable_td');
                        },
	        		});*/
	        } else {
                lead_schedule_datatable.ajax.reload();
                /* contract_modul_datatable.ajax.reload(); */
            }
        });

        $('#lead_id').change( function() {
            if ($(this).val()) {
                window.location = "{{url('/crm/leads')}}/" + $(this).val();
            }
        });
    </script>
@endsection