@extends('layouts.new_customer')
{{-- @extends('layouts.app') --}}
@section('title', __('lang_v1.revocation'))
@section('content')

<style>
    .table { 
        margin-left: auto;
        display: table;
        padding: 1px 0%;
        text-align: left;
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
      var boxes = document.getElementsByTagName("input");
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
    $form_id = 'crm_contact_add_form';
    $url = action([\App\Http\Controllers\ContactsRegistrationController::class, 'sendRegistrationRevocation']);
    
@endphp		

{!! Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id ]) !!}
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
                    <div class="table">                    
                        <div calss="table-row">
                            Liebe Nutzerin, lieber Nutzer,<br><br>

                            wenn Sie Ihre Einwilligungen oder Daten bei einem unserer Partner ändern oder löschen möchten, sind Sie hier genau richtig. 
                            Bitte geben Sie uns dazu einfach Ihren Vornamen, Ihre Mail-Adresse und Ihr Geburtsdatum an, damit wir Ihre Daten eindeutig zuordnen können.<br>
                            Im nächsten Schritt erhalten Sie eine E-Mail. Bitte klicken Sie auf den Button. Danach listen wir Ihnen alle Partner auf, bei denen Sie Ihre Daten hinterlegt haben. Dort können Sie dann Ihre Kontaktdaten und 
                            Einwilligungen nach Ihren Wünschen bearbeiten oder löschen.<br>
                            Vielen Dank für Ihr Vertrauen in uns und unsere Partner! Ihr Team von NEOBURG
                        </div> 
                    </div> 
                </div> 
            </div>
        </div>
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
                    <div class="form-group">
                    </div>
                </div>
                
                <div class="col-md-12">                   
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('first_name', __( 'business.first_name' ) . ':*') !!}
                            {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'required', 'placeholder' => __( 'business.first_name' ) ]); !!}
                            @if ($errors->has('first_name'))
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>
                    </div>                    
                </div>
               
                <div class="col-md-12">                    
                    <div class="col-md-6">
                        <div class="form-group">
                           {!! Form::label('email', __('business.email') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                {!! Form::email('email', null, ['class' => 'form-control','placeholder' => __('business.email'),  'required']); !!}
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">    
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('dob', __( 'lang_v1.dob' ) . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('dob', null, ['class' => 'form-control dob-date-picker',  'placeholder' => __( 'lang_v1.dob' ), 'required' ]); !!}
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
        
        
        <div class="col-md-12">
            <div class="form-group">
	            <button type="submit" class="btn-login btn btn-primary btn-flat ladda-button">
	            	@lang('messages.request_change_or_delete')
	           	</button>
	        </div>
	    </div>
        <div class="col-md-12">
                    <div class="table">                    
                        <div calss="table-row">
                            </p>
                            Wir möchten Ihnen versichern, dass wir Ihre Daten vertraulich behandeln und ausschließlich für den Zweck verwenden, für den Sie uns diese zur Verfügung gestellt haben.
                            <br>Vielen Dank für Ihr Vertrauen in uns und unsere Dienstleistungen.
                            <br>Für Fragen stehen wir Ihnen gerne zur Verfügung                         
                            <br><br>
                            NEOBURG<br>
                            Tullastr. 89<br>
                            79108 Freiburg im Breisgau<br>
                            Germany<br>
                            Tel.: +49 (0)761-88787329<br>
                            Telefonischer Support<br>
                            Mo - Fr 9:00 - 12:00 u. 14:00 - 18:00 Uhr<br>
                            E-Mail: info@neoburg.net<br>
                            Web: <a href="https://www.neoburg.net" target="_blank">https://www.neoburg.net/</a>
                            
                        </div>
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