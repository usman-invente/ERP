@extends('layouts.app')
@section('title', __('cash_register.all_your_cash_register'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'cash_register.all_your_cash_register' )
        <small>@lang( 'cash_register.manage_your_cash_registers' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>
<!-- Main content -->
<section class="content">
    
    
 @if (Session::has('msg'))
   <div class="alert alert-info">{{ Session::get('msg') }}</div>
@endif
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'cash_register.all_your_cash_register' )])
        @slot('tool')
            @if(count($cash_registers_details) < $cashRegisterDetail->getAllowedNumberOfCashRegister())
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\CashRegisterDetailController::class, 'create'])}}" 
                        data-container=".cash_register_add_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endif
        @endslot
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="cash_reg_detail_table">
                <thead>
                    <tr>
                        <th>@lang( 'cash_register.name' )</th>
                        <th>@lang( 'cash_register.location_id' )</th>
                        <th>@lang( 'cash_register.description' )</th>
                        {{-- <th>@lang( 'cash_register.tss_active' )</th>                         --}}
                        <th>@lang( 'messages.action' )</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endcomponent
    <div class="modal fade cash_register_add_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade cash_register_edit_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
    

</section>
<!-- /.content -->

@endsection
@section('javascript')
    <script type="text/javascript">

        $(document).ready(function() {                   

            cash_reg_detail_datatable = $("#cash_reg_detail_table").DataTable({
	        	processing: true,
	            serverSide: true,
            
	            ajax: {
	                 url: "/list-cash-register-detail",
	            },
	            columnDefs: [
	                {
	                    targets: [0, 3],
	                    orderable: false,
	                    searchable: false,
	                },
	            ],
	            aaSorting: [[1, 'asc']],
	            columns: [
	                { data: 'name', name: 'name' },
	                { data: 'location_name', name: 'location_name' },
	                { data: 'description', name: 'description' },
	                {{-- { data: 'active_tss', name: 'active_tss' }, --}}
	                { data: 'action', name: 'action' },
	            ],         
	        });
        }); 
        
        $(document).on('click', 'button.activate-deactivate-cash-register', function(){
            swal({
                title: LANG.sure,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(willDelete => {
                if (willDelete) {
                    $.ajax({
                        url: $(this).data('href'),
                        dataType: 'json',
                        success: function(result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                cash_reg_detail_datatable.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                }
            });
        });

        $('.cash_register_add_modal, .cash_register_edit_modal').on('shown.bs.modal', function(e) {    
            $('form#cash_register_add_form')
                .submit(function(e) {
                    e.preventDefault();
                })
                .validate({
                    rules: {
                        cash_register_detail_id: {
                            remote: {
                                url: '/cash-register-detail/check-cash-register-detail-id',
                                type: 'post',
                                data: {
                                    cash_register_detail_id: function() {
                                        return $('#cash_register_detail_id').val();
                                    },
                                    hidden_id: function() {
                                        if ($('#hidden_id').length) {
                                            return $('#hidden_id').val();
                                        } else {
                                            return '';
                                        }
                                    },
                                },
                            },
                        },
                    },
                    messages: {
                        cash_register_detail_id: {
                            remote: LANG.location_id_already_exists,
                        },
                    },
                    submitHandler: function(form) {
                        e.preventDefault();
                        var data = $(form).serialize();
                    
                        $.ajax({
                            method: 'POST',
                            url: $(form).attr('action'),
                            dataType: 'json',
                            data: data,
                            beforeSend: function(xhr) {
                                __disable_submit_button($(form).find('button[type="submit"]'));
                            },
                            success: function(result) {
                                if (result.success == true) {
                                    $('div.cash_register_add_modal').modal('hide');
                                    $('div.cash_register_edit_modal').modal('hide');
                                    toastr.success(result.msg);
                                    cash_reg_detail_datatable.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    },
                });

        });
    </script>
@endsection