@extends('layouts.new_customer')
@section('title', __('lang_v1.opt_out'))
@section('content')


@php
    $form_id = 'opt_out';
    $url = action([\App\Http\Controllers\ContactsRegistrationController::class, 'complete_customer_destroy'], ['business_id' => $contact->business_id, 'contact_id' => $contact->id, 'first_name' => $contact->first_name ]);
    $text = "Achtung! Hiermit löschen Sie Ihre komplette Registrierung.
             ";
@endphp		

{!! Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id ]) !!}
<div class="login-form col-md-12 col-xs-12 right-col-content">
    {{-- <p class="form-header text-black">{{__('messages.delete')}}</p> --}}
    {{-- <form method="POST" action="{{action('\Modules\Crm\Http\Controllers\LeadController@save_crm_new_customer')}}" > --}}
    {{-- <div class="col-md-12">
        <div class="table-ein"> 
            <div>{{ $business->name }}</div>
        </div>
    </div> --}}
    <input hidden name="token" id="token" type="text" value='{{ request()->route("token") }}'>
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
                    <b><p class="form-header text-black">{{ $text}}</p></b>
                </div>                          
                
                
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
	            <button type="submit" class="btn btn-primary">@lang( 'messages.delete' )</button>
                {{-- <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button> --}}
	        </div>
	    </div>
   {{-- </form> --}}
   {!! Form::close() !!}
</div>
<div class="col-md-12 col-xs-12">
 	<div class="row repair_status_details"></div>
</div>
@endsection