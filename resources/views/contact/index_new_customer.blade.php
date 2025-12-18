@extends('layouts.new_customer')
{{-- @extends('layouts.app') --}}
@section('title', __('crm::lang.register_new_customer'))
@section('content')

<style>
    .table { 
        margin-left: auto;
        margin-right: auto;
        display: table;
        padding: 1px 0%;
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
    function selects(){  
                var ele=document.getElementsByName('chk');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=true;  
                }  
            }  
            function deSelect(){  
                var ele=document.getElementsByName('chk');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=false;  
                      
                }  
            }    
</script>

@php
    $form_id = 'crm_contact_add_form';
    $url = action([\App\Http\Controllers\ContactsRegistrationController::class, 'saveNewCustomer']);

    $business_name = str_replace("_", " ", request()->route("business_name"));
    $business_name = str_replace("$&", "/", $business_name);
    //$business_name = request()->route("business_name");
    if(old('contact_type_radio')=="business"){
        $readonly = '';
    }else{
        $readonly = 'readonly';
    }

    $first_name = "Max Musterman";
@endphp		

{!! Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id ,'autocomplete'=>'on']) !!}
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
                        <span class="form-header ">{{__('crm::lang.register_new_customer'). " bei ". $business_name}}</span><br>
                        <span class="form-header "><small>mit * gekennzeichnete Felder sind Pflichtfelder.</small></span>
                    </div> 
                </div> 
            </div>
        </div>
    <input hidden name="business_name" id="business_name" type="text" value='{{ $business_name }}'>
    <input hidden name="business_id" id="business_id" type="number" value='{{ request()->route("business_id") }}'>
    <input hidden name="location_id" id="location_id" type="number" value='{{ request()->route("location_id") }}'>
    <input hidden name="formular_type" id="formular_type" type="text" value='{{ request()->segment(1) }}'>
    
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
                        {{-- @lang('lang_v1.private') --}}
                        Privat
                    </label>
                    </div>
                     <div class="col-md-2">
                    <label class="radio-inline">
                        <input onclick="displayRadioValue()"  type="radio" name="contact_type_radio" id="inlineRadio2" value="business" 
                                {{ old('contact_type_radio')=="business" ? 'checked='.'"'.'checked'.'"' : '' }}                            
                        >
                        {{-- @lang('business.business') --}}
                        Geschäftlich
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
                            {{-- {!! Form::label('title', __( 'business.title' ) . ':') !!} --}}
                            {!! Form::label('title',  'Titel:') !!}
                            {!! Form::text('title',  old('title', null) , ['class' => 'form-control', 'placeholder' => 'Titel' ]); !!}
                            @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{-- {!! Form::label('customer_business_name', __( 'business.supplier_business_name' ) . ':*') !!} --}}
                            {!! Form::label('customer_business_name', 'Firmennamen:*') !!}
                            @if(old('contact_type_radio')=="business")
                                 {!! Form::text('customer_business_name', old('customer_business_name', null), ['class' => 'form-control','placeholder' => 'Firmennamen' ]); !!}
                            @else
                                 {!! Form::text('customer_business_name', old('customer_business_name', null), ['class' => 'form-control','readonly' , 'placeholder' => 'Firmennamen' ]); !!}
                            @endif
                           
                            @if ($errors->has('customer_business_name'))
                                <span class="text-danger">{{ $errors->first('customer_business_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('business_position', 'Position:') !!}
                            @if(old('contact_type_radio')=="business")
                                {!! Form::text('business_position', old('business_position', null), ['class' => 'form-control', 'placeholder' => 'Position' ]); !!}
                            @else
                                {!! Form::text('business_position', old('business_position', null), ['class' => 'form-control','readonly', 'placeholder' => 'Position' ]); !!}
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
                            {!! Form::label('prefix', 'Anrede:') !!}
                            {!! Form::text('prefix', old('prefix', null), ['class' => 'form-control', 'placeholder' => 'Herr, Frau, Fräulein' ]); !!}
                            @if ($errors->has('prefix'))
                                <span class="text-danger">{{ $errors->first('prefix') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('first_name', 'Vorname:*') !!}
                            {!! Form::text('first_name', old('first_name', null), ['class' => 'form-control', {{-- 'required', --}} 'placeholder' => 'Vorname' ]); !!}
                            @if ($errors->has('first_name'))
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('last_name', 'Nachname:') !!}
                            {!! Form::text('last_name', old('last_name', null), ['class' => 'form-control',  'placeholder' => 'Nachname' ]); !!}
                            @if ($errors->has('last_name'))
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>              
                <div class="col-md-12 business" style="display: none;"  id="business">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('supplier_business_name','Firmennamen:*') !!}
                            {!! Form::text('supplier_business_name', old('supplier_business_name', null), ['class' => 'form-control', 'placeholder' => 'Firmennamen']); !!}
                            @if ($errors->has('supplier_business_name'))
                                <span class="text-danger">{{ $errors->first('supplier_business_name') }}</span>
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
                                {!! Form::email('email', old('email', null), ['class' => 'form-control','placeholder' => __('business.email')]); !!}
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
                            {!! Form::label('mobile', 'Handy, Mobiltelefon:') !!}
                            {!! Form::text('mobile', old('mobile', null), ['class' => 'form-control', 'placeholder' => 'Handy, Mobiltelefon' ]); !!}
                            @if ($errors->has('mobile'))
                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('dob','Geburtsdatum:*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('dob', old('dob', null), ['class' => 'form-control dob-date-picker',  'placeholder' => 'Geburtsdatum' ]); !!}
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
                            {{-- {!! Form::label('street', __( 'business.street' ) . ':') !!} --}}
                            {!! Form::label('street', 'Straße:') !!}
                            {!! Form::text('street',  old('street', null) , ['class' => 'form-control', 'placeholder' => 'Straße' ]); !!}
                            @if ($errors->has('street'))
                                <span class="text-danger">{{ $errors->first('street') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{-- {!! Form::label('house_nr', __( 'business.house_nr' ) . ':') !!} --}}
                            {!! Form::label('house_nr', 'Haus Nr.:') !!}
                            {!! Form::text('house_nr', old('house_nr', null), ['class' => 'form-control',  'placeholder' => 'Haus Nr.']); !!}
                            @if ($errors->has('house_nr'))
                                <span class="text-danger">{{ $errors->first('house_nr') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{-- {!! Form::label('zip_code', __( 'business.zip_code' ) . ':') !!} --}}
                            {!! Form::label('zip_code','Postleitzahl:') !!}
                            {!! Form::text('zip_code', old('zip_code', null), ['class' => 'form-control',  'placeholder' => 'Postleitzahl' ]); !!}
                            @if ($errors->has('zip_code'))
                                <span class="text-danger">{{ $errors->first('zip_code') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{-- {!! Form::label('city', __( 'business.city' ) . ':') !!} --}}
                            {!! Form::label('city', 'Stadt:') !!}
                            {!! Form::text('city', old('city', null), ['class' => 'form-control', 'placeholder' => 'Stadt' ]); !!}
                            @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{-- {!! Form::label('country', __( 'business.country' ) . ':') !!} --}}
                            {!! Form::label('country', 'Land:') !!}
                            {!! Form::text('country', old('country', null), ['class' => 'form-control',  'placeholder' => 'Land' ]); !!}
                            @if ($errors->has('country'))
                                <span class="text-danger">{{ $errors->first('country') }}</span>
                            @endif
                        </div>
                    </div>
                </div>                
                <div class="col-md-12">
                    <div class="table">                    
                        <div calss="table-row">Sie können jederzeit Ihre Daten bei uns löschen lassen.</div>
                        <div calss="table-row">Weitere Informationen hierzu finden Sie  <a href="https://portal.neoburg.net/{{ request()->route("business_id") }}/{{ request()->route("location_id") }}/{{ request()->route("business_name")}}/datenschutzerklaerung" target="_blank">hier</a>.</div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-ein"> 
                        <div>Einwilligung</div>
                    </div>
                    <div class="table">                    
                        <div calss="table-row">Wir von {{ $business_name }} möchten Sie gerne kontaktieren und informieren.
                            Dazu benötigen wir Ihre Einwilligung. Diese können Sie jederzeit widerrufen.
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>                            
                            {{-- <input type="checkbox" id="consent_all" onclick="javascript:checkAll(this)"/> --}}
                                {!! Form::checkbox('consent_all', 1,false, ['class' => 'input-icheck', 'id' => 'consent_all', 'onclick=javascript:checkAll(this)']); !!} 
                                {{-- {!! Form::checkbox('consent_all', 1, false, ['class' => 'input-icheck', 'id' => 'consent_all', 'onclick=javascript:selects()']); !!}  --}}
                                    {{-- <strong>@lang('lang_v1.consent_all')</strong> --}}
                                    <strong>Alle auswählen</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_email', 1, false, ['class' => 'input-icheck',  'id' => 'consent_email']); !!} 
                                    <strong>@lang('lang_v1.consent_email')</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_mobile', 1, false, ['class' => 'input-icheck',  'id' => 'consent_mobile']); !!} 
                                    {{-- <strong>@lang('lang_v1.consent_mobile')</strong> --}}
                                    <strong>Telefon</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('consent_post', 1,  false, ['class' => 'input-icheck', 'id' => 'consent_post']); !!} 
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
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  
                                {!! Form::checkbox('consent_field2', 1, false, [ 'name'=>'consent_field2','id' => 'consent_field2']); !!} 
                                    {{-- <strong>@lang('lang_v1.consent_field2')</strong> --}}
                                    <strong>Vollmacht Einsicht und Speicherung Dokumente und Verträge</strong>
                                    @if ($errors->has('consent_field2'))
                                        <span class="text-danger">{{ $errors->first('consent_field2') }}</span>
                                    @endif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table">                    
                        <div calss="table-row">
                            </p><h4>Vielen Dank für Ihr Vertrauen. Nachdem Sie das Formular abgesendet haben, 
                            wird Ihnen automatisch eine Bestätigungsmail zugeschickt. Bitte prüfen Sie Ihr Postfach und bestätigen Sie Ihre Mail-Adresse.</h4>
                        </div>
                        <div calss="table-row">
                         </p>Durch das Absenden, bestätigen Sie, dass die vom Ihnen angegebenen Informationen
                            an {{ $business_name }} zur Verarbeitung gemäß den 
                            <a href="{{ route('einwilligungen', ['business_id' => $business->id, 'location_id' => request()->route('location_id'), 'business_name' => $business_name]) }}" target="_blank"><b>Einwilligungserklärung</b></a>,
                            <a href="{{ route('datenschutzerklaerung', ['business_id' => $business->id, 'location_id' => request()->route('location_id'), 'business_name' => $business_name]) }}" target="_blank"><b>Datenschutzerklärung</b></a>
                            und <a href="{{ route('vollmacht', ['business_id' => $business->id, 'location_id' => request()->route('location_id'), 'business_name' => $business_name, 'first_name' => $first_name]) }}" target="_blank"><b>Vollmacht zur Datenspeicherung</b></a>
                            {{-- <a href="https://portal.neoburg.net/{{ request()->route("business_id") }}/{{ request()->route("location_id") }}/{{ request()->route("business_name")}}/einwilligungen" target="_blank"><b>Einwilligungserklärung</b></a>, 
                            <a href="https://portal.neoburg.net/{{ request()->route("business_id") }}/{{ request()->route("location_id") }}/{{ request()->route("business_name")}}/datenschutzerklaerung" target="_blank"><b>Datenschutzerklärung</b></a>
                            und <a href="https://portal.neoburg.net/{{ request()->route("business_id") }}/{{ request()->route("location_id") }}/{{ request()->route("business_name")}}/{{ $first_name }}/vollmacht" target="_blank"><b>Vollmacht zur Datenspeicherung</b></a>  --}}
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
                        {!! Form::checkbox('consent_field1', 1, false, [ 'name'=>'consent_field1','id' => 'consent_field1']); !!} 
                            Ich bestätige hiermit, dass ich die 
                                <a href="{{ route('einwilligungen', ['business_id' => $business->id, 'location_id' => request()->route('location_id'), 'business_name' => $business_name]) }}" target="_blank">
                                    <b>Einwilligungserklärung</b>
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
                        {!! Form::checkbox('consent_field3', 1, false, [ 'name'=>'consent_field3','id' => 'consent_field3']); !!} 
                            Ich bestätige hiermit, dass ich die 
                                <a href="{{ route('vollmacht', ['business_id' => $business->id, 'location_id' => request()->route('location_id'), 'business_name' => $business_name, 'first_name' => $first_name]) }}" target="_blank">
                                    <b>Vollmacht zur Datenspeicherung</b>
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
            <div class="form-group">
	            <button type="submit" class="btn-login btn btn-primary btn-flat ladda-button">
	            	{{-- @lang('lang_v1.send') --}}
                    Senden
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