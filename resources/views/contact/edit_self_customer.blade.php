@extends('layouts.new_customer')
{{-- @extends('layouts.app') --}}
@section('title', __('contact.edit_contact'))
@section('content')

<style>
    .table { 
        margin-left: auto;
        margin-right: auto;
        display: table;
        padding: 1px 20%;
        text-align: center;
    }
    .table-ein { 
         margin-left: auto;
        margin-right: auto;
        display: table;
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

<script>
    function checkAll(o) {
      {{-- var boxes = document.getElementsByTagName("input"); --}}
       var boxes = document.getElementsByClassName("input-icheck");
      for (var x = 0; x < boxes.length; x++) {
        var obj = boxes[x];
        if (obj.type == "checkbox") {
          if (obj.name != "check")
            obj.checked = o.checked;
        }
      }
    }

    function displayRadioValue() {
        //var ele = document.getElementsByName('contact_type_radio');          
        
        if(document.getElementById("inlineRadio2").checked ){
            document.getElementById("customer_business_name").readOnly = false;
            document.getElementById("business_position").readOnly = false;
        }else{
             document.getElementById("customer_business_name").readOnly = true;
             document.getElementById("customer_business_name").value = null;
             document.getElementById("business_position").readOnly = true;
             document.getElementById("business_position").value = null;
        }
    }    
</script>
@php
    $form_id = 'crm_contact_edit_form';
    $url = action([\App\Http\Controllers\ContactsRegistrationController::class, 'saveSelfChangeData']);

    $first_name = str_replace("_", " ", request()->route("first_name"));

    if(old('contact_type_radio')=="business"){
        $readonly = '';
    }else{
        $readonly = 'readonly';
    }
@endphp		

{!! Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id]) !!}
<div class="login-form col-md-12 col-xs-12 right-col-content-register">
    
    <div class="form-group">
        <div class="row"> 
            <div class="col-md-12"> 
                <div class="col-md-4">
                </div> 
                <div class="col-sm-2">
                    <div>
                        @if(!empty($business->logo))
                            <img class="img-responsive" src="{{ url( 'uploads/business_logos/' . $business->logo ) }}" alt="Business Logo">                                    
                        @endif
                    </div>
                </div> 
            </div>
        </div>
    </div> 
    {{-- <form method="POST" action="{{action('\Modules\Crm\Http\Controllers\LeadController@save_crm_new_customer')}}" > --}}
   
        <div class="form-group">
            <div class="row">
                <div class="col-md-12"> 
                    <div class="col-md-4">
                    </div> 
                    <div class="col-sm-8">
                        <span class="form-header ">{{__('contact.edit_contact'). " bei ". $business->name}}</span>
                    </div> 
                </div> 
            </div>
        </div>
    <input hidden name="business_name" id="business_name" type="text" value='{{ $business->name }}'>
    <input hidden name="first_name" id="first_name" type="text" value='{{ $first_name }}'>
    <input hidden name="business_id" id="business_id" type="number" value='{{ request()->route("business_id") }}'>
    <input hidden name="contact_id" id="contact_id" type="number" value='{{ request()->route("contact_id") }}'>
    @if(session('message'))
      <div class='alert alert-success'>
      {{ session('message') }}
      </div>
    @endif
    @if(session('msg-alert'))
          <div class="alert alert-danger">
              {{ session('msg-alert') }}
          </div>
    @endif
    
<br>
<div id="result"></div>
        <div class="form-group">        
        	<div class="row">  
                <div class="col-md-12">
                    <div class="col-md-2">
                    <label class="radio-inline">
                        <input onclick="displayRadioValue()" checked type="radio" name="contact_type_radio" id="inlineRadio1" value="individual">
                        @lang('lang_v1.private')
                    </label>
                    </div>
                     <div class="col-md-2">
                    <label class="radio-inline">
                        <input onclick="displayRadioValue()"  type="radio" name="contact_type_radio" id="inlineRadio2" value="business" 
                                {{ old('contact_type_radio')=="business" ? 'checked='.'"'.'checked'.'"' : '' }}                            
                        >
                        @lang('business.business')
                    </label>
                    </div>                    
                     <div class="col-md-4">
                    
                    </div>                    
                    <div class="col-md-4  ">
                        @if(! empty($contact->first_name) && !empty($contact->business_id))
                            <a target="_blank" href="{{ action([\App\Http\Controllers\ContactsRegistrationController::class, 'customer_destroy'], ['business_id' => $contact->business_id, 'contact_id' => $contact->id,'first_name' => $contact->first_name]) }}" >
                                <i class="fa fa-exclamation-circle"></i>
                                @lang('lang_v1.opt_out').
                            </a>
                        @endif
                    </div>                    
                </div> 
                <div class="col-md-12">
                    <div class="form-group">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('title', __( 'business.title' ) . ':') !!}
                            {!! Form::text('title', $contact->title, ['class' => 'form-control', 'placeholder' => __( 'business.title' ) ]); !!}
                            @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('customer_business_name', __( 'business.supplier_business_name' ) . ':*') !!}
                            @if(old('contact_type_radio')=="business")
                                 {!! Form::text('customer_business_name', $contact->supplier_business_name, ['class' => 'form-control','placeholder' => __( 'business.supplier_business_name' ) ]); !!}
                            @else
                                 {!! Form::text('customer_business_name', $contact->supplier_business_name, ['class' => 'form-control','readonly' , 'placeholder' => __( 'business.supplier_business_name' ) ]); !!}
                            @endif
                           
                            @if ($errors->has('customer_business_name'))
                                <span class="text-danger">{{ $errors->first('customer_business_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('business_position', __( 'business.business_position' ) . ':') !!}
                            @if(old('contact_type_radio')=="business")
                                {!! Form::text('business_position', $contact->business_position, ['class' => 'form-control', 'placeholder' => __( 'business.business_position' ) ]); !!}
                            @else
                                {!! Form::text('business_position', $contact->business_position, ['class' => 'form-control','readonly', 'placeholder' => __( 'business.business_position' ) ]); !!}
                            @endif
                           
                            @if ($errors->has('business_position'))
                                <span class="text-danger">{{ $errors->first('business_position') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12 individual">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('prefix', __( 'business.prefix' ) . ':') !!}
                            {!! Form::text('prefix', $contact->prefix, ['class' => 'form-control', 'placeholder' => __( 'business.prefix_placeholder' ) ]); !!}
                            @if ($errors->has('prefix'))
                                <span class="text-danger">{{ $errors->first('prefix') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('first_name', __( 'business.first_name' ) . ':*') !!}
                            {!! Form::text('first_name', $contact->first_name, ['class' => 'form-control', 'readonly', 'placeholder' => __( 'business.first_name' ) ]); !!}
                            @if ($errors->has('first_name'))
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('last_name', __( 'business.last_name' ) . ':') !!}
                            {!! Form::text('last_name', $contact->last_name, ['class' => 'form-control',  'placeholder' => __( 'business.last_name' ) ]); !!}
                            @if ($errors->has('last_name'))
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="col-md-8">
                        <div class="form-group">
                           {!! Form::label('email', __('business.email') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                {!! Form::email('email', $contact->email, ['class' => 'form-control', 'readonly', 'placeholder' => __('business.email')]); !!}
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('mobile', __( 'business.mobile' ) . ':') !!}
                            {!! Form::text('mobile', $contact->mobile, ['class' => 'form-control', 'placeholder' => __( 'business.mobile' ) ]); !!}
                            @if ($errors->has('mobile'))
                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('dob', __( 'lang_v1.dob' ) . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('dob', $contact->dob, ['class' => 'form-control dob-date-picker', 'disabled' ,'placeholder' => __( 'lang_v1.dob' ) ]); !!}
                                @if ($errors->has('dob'))
                                    <span class="text-danger">{{ $errors->first('dob') }}</span>
                                @endif
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
                            {!! Form::text('street', $contact->street, ['class' => 'form-control', 'placeholder' => __( 'business.street' ) ]); !!}
                            @if ($errors->has('street'))
                                <span class="text-danger">{{ $errors->first('street') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('house_nr', __( 'business.house_nr' ) . ':') !!}
                            {!! Form::text('house_nr', $contact->house_nr, ['class' => 'form-control',  'placeholder' => __( 'business.house_nr' ) ]); !!}
                            @if ($errors->has('house_nr'))
                                <span class="text-danger">{{ $errors->first('house_nr') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('zip_code', __( 'business.zip_code' ) . ':') !!}
                            {!! Form::text('zip_code', $contact->zip_code, ['class' => 'form-control',  'placeholder' => __( 'business.zip_code' ) ]); !!}
                            @if ($errors->has('zip_code'))
                                <span class="text-danger">{{ $errors->first('zip_code') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('city', __( 'business.city' ) . ':') !!}
                            {!! Form::text('city', $contact->city, ['class' => 'form-control', 'placeholder' => __( 'business.city' ) ]); !!}
                            @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('country', __( 'business.country' ) . ':') !!}
                            {!! Form::text('country', $contact->country, ['class' => 'form-control',  'placeholder' => __( 'business.country' ) ]); !!}
                            @if ($errors->has('country'))
                                <span class="text-danger">{{ $errors->first('country') }}</span>
                            @endif
                        </div>
                    </div>
                </div>      
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('bank', __( 'lang_v1.bank' ) . ':') !!}
                            {!! Form::text('bank', $contact->bank, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.bank' ) ]); !!}
                            @if ($errors->has('bank'))
                                <span class="text-danger">{{ $errors->first('bank') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('iban', __( 'lang_v1.iban' ) . ':') !!}
                            {!! Form::text('iban', $contact->iban, ['class' => 'form-control',  'placeholder' => __( 'lang_v1.iban' ) ]); !!}
                            @if ($errors->has('iban'))
                                <span class="text-danger">{{ $errors->first('iban') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('bic', __( 'lang_v1.bic' ) . ':') !!}
                            {!! Form::text('bic', $contact->bic, ['class' => 'form-control',  'placeholder' => __( 'lang_v1.bic' ) ]); !!}
                            @if ($errors->has('bic'))
                                <span class="text-danger">{{ $errors->first('bic') }}</span>
                            @endif
                        </div>
                    </div>
                </div>  
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('paypal', __( 'lang_v1.paypal' ) . ':') !!}
                            {!! Form::text('paypal', $contact->paypal, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.paypal' ) ]); !!}
                            @if ($errors->has('paypal'))
                                <span class="text-danger">{{ $errors->first('paypal') }}</span>
                            @endif
                        </div>
                    </div>
                </div>           
                <div class="col-md-12">
                    <div class="table">                    
                        <div calss="table-row">Sie können jederzeit Ihre Daten bei uns löschen lassen.</div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-ein"> 
                        <div>Einwilligung</div>
                    </div>
                    <div class="table">                    
                        <div calss="table-row">Wir von {{ $business->name }} möchten Sie gerne kontaktieren und informieren.
                            Dazu benötigen wir Ihre Einwilligung. Diese können Sie jederzeit widerrufen.
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>                            
                            {{-- <input type="checkbox" id="consent_all" onclick="javascript:checkAll(this)"/> --}}
                                {!! Form::checkbox('consent_all', 1, $contact->consent, ['class' => 'input-icheck', 'id' => 'consent_all', 'onclick=javascript:checkAll(this)']); !!} 
                                    <strong>@lang('lang_v1.consent_all')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_email', 1, $contact->consent_email, ['class' => 'input-icheck', 'id' => 'consent_email']); !!} 
                                    <strong>@lang('lang_v1.consent_email')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_mobile', 1, $contact->consent_mobile, ['class' => 'input-icheck', 'id' => 'consent_mobile']); !!} 
                                    <strong>@lang('lang_v1.consent_mobile')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_post', 1, $contact->consent_post, ['class' => 'input-icheck', 'id' => 'consent_post']); !!} 
                                    <strong>@lang('lang_v1.consent_post')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_messenger', 1, $contact->consent_messenger, ['class' => 'input-icheck', 'id' => 'consent_messenger']); !!} 
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
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>  
                                {!! Form::checkbox('consent_field2', 1, $contact->consent_field2, [ 'name'=>'consent_field2','id' => 'consent_field2']); !!} 
                                    <strong>@lang('lang_v1.consent_field2')</strong>
                                    @if ($errors->has('consent_field2'))
                                        <span class="text-danger">{{ $errors->first('consent_field2') }}</span>
                                    @endif
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>  
                                {!! Form::checkbox('consent_field4', 1, $contact->consent_field4, [ 'name'=>'consent_field4','id' => 'consent_field4']); !!} 
                                    <strong>@lang('lang_v1.consent_field4')</strong>
                                    @if ($errors->has('consent_field4'))
                                        <span class="text-danger">{{ $errors->first('consent_field4') }}</span>
                                    @endif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table">                    
                        <div calss="table-row">
                            </p><h4>Vielen Dank für Ihr Vertrauen. Nachdem Sie das Formular abgesendet haben, 
                            wird Ihnen automatisch eine Bestätigungsmail zugeschickt. Bitte prüfen Sie Ihr Postfach und bestätigen Sie Ihre Änderungen.</h4>
                        </div>
                        <div calss="table-row">
                         </p>Durch das Absenden, bestätigen Sie, dass die vom Ihnen angegebenen Informationen
                            an {{ $business->name }} zur Verarbeitung gemäß den <a href="https://portal.neoburg.net/{{ $business->id }}/{{ $business->locations->first()->id }}/{{ $business->name}}/einwilligungen" target="_blank"><b>Einwilligungserklärung</b></a>, 
                            <a href="https://portal.neoburg.net/{{ $business->id  }}/{{ $business->locations->first()->id}}/{{ $business->name}}/datenschutzerklaerung" target="_blank"><b>Datenschutzerklärung</b></a>, 
                             <a href="https://portal.neoburg.net/{{ $business->id  }}/{{ $business->locations->first()->id}}/{{ $business->name}}/{{ $contact->first_name.' '.$contact->last_name }}/vollmacht" target="_blank"><b>Vollmacht zur Datenspeicherung</b></a> 
                             und <a href="https://portal.neoburg.net/{{ $business->id  }}/{{ $business->locations->first()->id}}/{{ $business->name}}/payment_service" target="_blank"><b>Zahlungsdienstleister</b></a> 
                            übertragen werden.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-10">
                <div class="form-group">
                    <label>                            
                    {{-- <input type="checkbox" id="consent_field1" /> --}}
                        {!! Form::checkbox('consent_field1', 1, $contact->consent_field1, [ 'name'=>'consent_field1','id' => 'consent_field1']); !!} 
                            Ich bestätige hiermit, dass ich die 
                                <a href="https://portal.neoburg.net/{{ $business->id }}/{{ $business->locations->first()->id }}/{{ $business->name}}/einwilligungen" target="_blank">
                                    <b>@lang('lang_v1.consent_field1')</b>
                                </a> 
                                gelesen habe und ihr zustimme.
                            @if ($errors->has('consent_field1'))
                                <span class="text-danger">{{ $errors->first('consent_field1') }}</span>
                            @endif
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-10">
                <div class="form-group">
                    <label>
                        {!! Form::checkbox('consent_field3', 1, $contact->consent_field3, [ 'name'=>'consent_field3','id' => 'consent_field3']); !!} 
                            Ich bestätige hiermit, dass ich die 
                                <a href="https://portal.neoburg.net/{{ $business->id  }}/{{ $business->locations->first()->id}}/{{ $business->name}}/{{ $contact->first_name.' '.$contact->last_name }}/vollmacht" target="_blank">
                                    <b> @lang('lang_v1.consent_field3')</b>
                                </a>
                            gelesen habe und ihr zustimme.
                            @if ($errors->has('consent_field3'))
                                <span class="text-danger">{{ $errors->first('consent_field3') }}</span>
                            @endif
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-10">
                <div class="form-group">
                    <label>
                        {!! Form::checkbox('consent_field5', 1, $contact->consent_field5, [ 'name'=>'consent_field5','id' => 'consent_field5']); !!} 
                            Ich bestätige hiermit, dass ich die 
                                <a href="https://portal.neoburg.net/{{ $business->id  }}/{{ $business->locations->first()->id}}/{{ $business->name}}/payment_service" target="_blank">
                                    <b> @lang('lang_v1.consent_field5')</b>
                                </a>
                            gelesen habe und ihr zustimme.
                            @if ($errors->has('consent_field5'))
                                <span class="text-danger">{{ $errors->first('consent_field5') }}</span>
                            @endif
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
	            <button type="submit" class="btn-login btn btn-primary btn-flat ladda-button">
	            	@lang('messages.save')
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