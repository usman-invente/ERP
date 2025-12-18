@extends('layouts.new_customer')
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
    .textarea {
        font-size:20px;
        width:100%;
    }
</style>
@php
    $form_id = 'complete_change_data';
    $url = action([\App\Http\Controllers\ContactsRegistrationController::class, 'saveCompleteChangedata']);
    $url_new_start_chnage_data = action([\App\Http\Controllers\ContactsRegistrationController::class, 'self_change_data'], ['business_id' => $contact->business_id, 'contact_id' => $contact->id, 'first_name' => $contact->first_name ]);
    if($token == $token_change_data){
        $text = "Sie sind fast fertig. Drücken Sie auf dem Button \""  .  /*__( 'messages.save' )*/'Speichern' . "\". \n
           Mit betätigen des Buttons werden Ihre Daten sicher gespeichert.";
    }else{
        $text =  "Token ist abgelaufen, bitte fangen Sie noch Einmal von Anfang an";
    }
@endphp		

{!! Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id ]) !!}
<div class="login-form col-md-12 col-xs-12 right-col-content">
    <p class="form-header text-black">{{__('messages.complete_change_contact_data')}}</p>
    <input hidden name="token" id="token" type="text" value='{{ request()->route("token") }}'>
    <input hidden name="business_id" id="business_id" type="text" value='{{ request()->route("business_id") }}'>
    <input hidden name="contact_id" id="contact_id" type="text" value='{{ request()->route("contact_id") }}'>
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
    {{-- @if($errors->any())
          <div class="alert alert-danger">
              <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
              </ul>
          </div>
    @endif --}}
        <div class="form-group">        
        	<div class="row">  
                
                <div class="col-md-12">
                    <div class="form-group">
                </div>
		    </div>
        </div>
        <div class="form-group">
            <div class="row"> 
                <div class="col-md-12">
                    <p class="form-header text-black">{{ $text}}</p>
                </div>
                             
                
                
            </div>
        </div>
        @if($token == $token_change_data)
            <div class="col-md-12">
                <div class="form-group">
	                <button type="submit" class="btn btn-primary">Speichern</button>
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button> --}}
	            </div>
	        </div>
        @else
            <div class="col-md-12">
                <div class="form-group">
	                {{-- <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button> --}}
                        <button type="button" class="btn btn-default"><a  target="_blank" href="{{ $url_new_start_chnage_data }}">
                                @lang('lang_v1.link_self_edit_data')
                            </a>
                        </button>
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button> --}}
	            </div>
	        </div>
        @endif
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