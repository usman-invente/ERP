@extends('crm::layouts.new_customer')
@section('title', __('crm::lang.register_new_customer'))
@section('content')
<script>
    function checkAll(o) {
      var boxes = document.getElementsByTagName("input");
      for (var x = 0; x < boxes.length; x++) {
        var obj = boxes[x];
        if (obj.type == "checkbox") {
          if (obj.name != "check")
            obj.checked = o.checked;
        }
      }
    }
</script>
<style>
    .table { 
        margin-left: auto;
        margin-right: auto;
        display: table;
        color: #EEE;
        padding: 1px 20%;
        text-align: center;
    }
    .table-ein { 
         margin-left: auto;
        margin-right: auto;
        display: table;
        color: #EEE;
        padding: 1px 20%;
        text-align: center;
        font-size: 1.5em; 
    }
    .table-row {
        display: table-row; 
        background-color: #EEE;
    }
    .table-cell {
        display: table-cell; 
    }
</style>
@php
    $form_id = 'crm_contact_add_form';
    $url = action([\Modules\Crm\Http\Controllers\LeadController::class, 'saveCrmNewCustomer']);
@endphp		

{!! Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id ]) !!}
<div class="login-form col-md-12 col-xs-12 right-col-content">
    <p class="form-header text-white">{{__('crm::lang.register_new_customer')}}</p>
    {{-- <form method="POST" action="{{action('\Modules\Crm\Http\Controllers\LeadController@save_crm_new_customer')}}" > --}}
    <div class="col-md-12">
        <div class="table-ein"> 
            <div>{{ request()->route("business_name") }}</div>
        </div>
    </div>
    <input hidden name="business_name" id="business_name" type="text" value='{{ request()->route("business_name") }}' >
    <input hidden name="business_id" id="business_id" type="number" value='{{ request()->route("business_id") }}' >
    <input hidden name="location_id" id="location_id" type="number" value='{{ request()->route("location_id") }}' >
        <div class="form-group">        
        	<div class="row">  
                <div class="col-md-12">
                    <div class="col-md-2">
                    <label class="radio-inline">
                        <input checked type="radio" name="contact_type_radio" id="inlineRadio1" value="individual">
                        @lang('lang_v1.individual')
                    </label>
                    </div>
                     <div class="col-md-2">
                    <label class="radio-inline">
                        <input type="radio" name="contact_type_radio" id="inlineRadio2" value="business">
                        @lang('business.business')
                    </label>
                    </div>                    
                </div> 
                <div class="col-md-12">
                    <div class="form-group">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('title', __( 'business.title' ) . ':*') !!}
                            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => __( 'business.title' ) ]); !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 individual">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('prefix', __( 'business.prefix' ) . ':') !!}
                            {!! Form::text('prefix', null, ['class' => 'form-control', 'placeholder' => __( 'business.prefix_placeholder' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('first_name', __( 'business.first_name' ) . ':*') !!}
                            {!! Form::text('first_name', null, ['class' => 'form-control', {{-- 'required', --}} 'placeholder' => __( 'business.first_name' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('last_name', __( 'business.last_name' ) . ':*') !!}
                            {!! Form::text('last_name', null, ['class' => 'form-control',  'placeholder' => __( 'business.last_name' ) ]); !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 business" style="display: none;" >
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('supplier_business_name', __('business.business_name') . ':') !!}
                            {!! Form::text('supplier_business_name', null, ['class' => 'form-control', 'placeholder' => __('business.business_name')]); !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-8">
                        <div class="form-group">
                           {!! Form::label('email', __('business.email') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                {!! Form::email('email', null, ['class' => 'form-control','placeholder' => __('business.email')]); !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                </div>
		    </div>
        </div>
        <div class="form-group">
            <div class="row"> 
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('street', __( 'business.street' ) . ':') !!}
                            {!! Form::text('street', null, ['class' => 'form-control', 'placeholder' => __( 'business.street' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('house_nr', __( 'business.house_nr' ) . ':') !!}
                            {!! Form::text('house_nr', null, ['class' => 'form-control',  'placeholder' => __( 'business.house_nr' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('zip_code', __( 'business.zip_code' ) . ':') !!}
                            {!! Form::text('zip_code', null, ['class' => 'form-control',  'placeholder' => __( 'business.zip_code' ) ]); !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('city', __( 'business.city' ) . ':') !!}
                            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => __( 'business.city' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('country', __( 'business.country' ) . ':*') !!}
                            {!! Form::text('country', null, ['class' => 'form-control',  'placeholder' => __( 'business.country' ) ]); !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('mobile', __( 'business.mobile' ) . ':') !!}
                            {!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => __( 'business.mobile' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('dob', __( 'lang_v1.dob' ) . ':*') !!}
                            {!! Form::text('dob', null, ['class' => 'form-control',  'placeholder' => __( 'lang_v1.dob' ) ]); !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table">                    
                        <div calss="table-row">Sie können jederzeit Ihre Daten bei uns löschen lassen.</div>
                        <div calss="table-row">Weitere Informationen hierzu finden Sie hier.</div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-ein"> 
                        <div>Einwilligung</div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>                            
                            {{-- <input type="checkbox" id="consent_all" onclick="javascript:checkAll(this)"/> --}}
                                {!! Form::checkbox('consent_all', 1, false, ['class' => 'input-icheck', 'id' => 'consent_all', 'onclick=javascript:checkAll(this)']); !!} 
                                    <strong>@lang('lang_v1.consent_all')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_email', 1, false, ['class' => 'input-icheck', 'id' => 'consent_email']); !!} 
                                    <strong>@lang('lang_v1.consent_email')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_mobile', 1, false, ['class' => 'input-icheck', 'id' => 'consent_mobile']); !!} 
                                    <strong>@lang('lang_v1.consent_mobile')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_post', 1, false, ['class' => 'input-icheck', 'id' => 'consent_post']); !!} 
                                    <strong>@lang('lang_v1.consent_post')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_messenger', 1, false, ['class' => 'input-icheck', 'id' => 'consent_messenger']); !!} 
                                    <strong>@lang('lang_v1.consent_messenger')</strong>
                            </label>
                        </div>
                    </div>
                    {{-- <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_messenger', 1, false, ['class' => 'input-icheck', 'id' => 'consent_messenger']); !!} 
                                    <strong>@lang('lang_v1.consent_messenger')</strong>
                            </label>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
	            <button type="submit" class="btn-login btn btn-primary btn-flat ladda-button">
	            	@lang('lang_v1.send')
	           	</button>
	        </div>
	    </div>
   {{-- </form> --}}
   {!! Form::close() !!}
</div>
<div class="col-md-12 col-xs-12">
 	<div class="row repair_status_details"></div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('submit', 'form#check_repair_status', function(e) {
	        e.preventDefault();
		    var data = $('form#check_repair_status').serialize();
		    // var url = $('form#check_repair_status').attr('action');
		    var ladda = Ladda.create(document.querySelector('.ladda-button'));
	    	ladda.start();
		    $.ajax({
		        method: 'POST',
		        url: url,
		        dataType: 'json',
		        data: data,
		        success: function(result) {
		        	ladda.stop();
		            if (result.success) {
		            	$(".repair_status_details").html(result.repair_html);
		                toastr.success(result.msg);
		            } else {
		                toastr.error(result.msg);
		            }
		        }
		    });
	   	});
	});
</script>
@endsection