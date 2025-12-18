@extends('layouts.new_customer')
{{-- @extends('layouts.app') --}}
@section('title', __('lang_v1.list_of_registration'))
@section('content')

<style>
    .table { 
        margin-left: auto;
        margin-right: auto;
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
    .button {
      background-color: #75E379;
      border: none;
      color: black;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 13px;
       margin: 8% 4%;
      cursor: pointer;
    }
    
</style>


	
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
    
   
        <div class="form-group">
            <div class="row">
                <div class="col-md-12"> 
                    <div class="col-md-4">
                    </div> 
                    <div class="col-sm-8">
                        <span class="form-header ">{{__('lang_v1.list_of_registration')}}</span>
                    </div> 
                </div> 
            </div>
        </div>    
    <br>

        <div class="col-md-12">
            <div class="table">                    
                <div calss="table-row">
                    </p>
                    <h5>
                    Bitte beachten Sie, dass die Änderung oder Löschung Ihrer persönlichen Daten Auswirkungen auf die Dienstleistungen 
                    der Institution haben kann. Insbesondere kann es sein, dass diese Ihnen einige Dienste nicht mehr anbieten können, 
                    wenn Sie bestimmte Daten löschen oder ändern möchten.
                    Sollten Sie Änderungen an Ihren Daten vornehmen oder sie löschen wollen, klicken Sie auf den entsprechenden Button.
                    
                    </h5>
                </div>
            </div>
        </div>
    <br>
    <br>
   <table class="table table-bordered" style="width:50%; ">
        @foreach($contacts as $key => $contact)
            @php
                 $url_edit_self = action([\App\Http\Controllers\ContactsRegistrationController::class, 'self_change_data'], ['business_id' => $contact->business_id, 'contact_id' => $contact->id, 'first_name' => $contact->first_name ]);
            @endphp	
        <tr>
            <td align="center" width="30%">
            <div>
                @if(!empty($contact->getBusinessById($contact->business_id)->logo))
                    <img class="img-responsive" src="{{ url( 'uploads/business_logos/' . $contact->getBusinessById($contact->business_id)->logo ) }}" alt="Business Logo" style="height: 100%; width: 100%;">                                    
                @endif
            </div>
            {{ $contact->getBusinessById($contact->business_id)->name }} 
            </td>
            <td align="center"> 
                    <form action="{{ $url_edit_self }}" method="get">
                        <button class="button" formtarget="_blank">
                            @lang('lang_v1.data_change_or_delete')
                        </button>
                    </form>
               
            </td>
        </tr>

        @endforeach
    </table>
  
{{-- <div id="result"></div>
               
        
        {{--<div class="col-md-12">
            <div class="col-md-1">
                    </div>
            <div class="form-group">
	            <button type="submit" class="btn-login btn btn-primary btn-flat ladda-button">
	            	@lang('lang_v1.send')
	           	</button>
	        </div>
	    </div>
   {{-- </form> --}}
   {{-- {!! Form::close() !!} --}}
</div>
<div class="col-md-12 col-xs-12">
 	<div class="row repair_status_details"></div>
</div>
@endsection
@section('javascript')

@endsection