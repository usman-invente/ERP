<!-- <strong>{{ $contact->name }}</strong><br><br> -->
{{--<h3 class="profile-username">
    <i class="fas fa-user-tie"></i>
     {{ $contact->first_name .' ' . $contact->last_name  }}
    {{-- {{ $contact->full_name_with_business }} <small>
        @if($contact->type == 'both')
            {{__('role.customer')}} & {{__('role.supplier')}}
        @elseif(($contact->type != 'lead'))
            {{__('role.'.$contact->type)}}
        @endif
    </small> 
</h3><br>--}}
<style>
    .text-danger{
        /* symbole mit fester Breite */
        
        width: 28px;
        height: 2px;
    }
</style>
<strong><i class="fas fa-user-tie"></i> </strong>
<b class="text-muted">
    {{  $contact->title .' ' . $contact->prefix .' ' . $contact->first_name .' ' . $contact->last_name  }}
</b>
</p>
@if($contact->street || $contact->house_nr || $contact->zip_code || $contact->city || $contact->country)
    <strong><i class="fa fa-map-marker margin-r-5"></i> @lang('business.address')</strong>
    </br>
        @if($contact->street)
             <strong> @lang('business.street'): </strong> {!! $contact->street !!}<br>
        @endif    
        @if($contact->house_nr)
             <strong>@lang('business.house_nr'): </strong> {!! $contact->house_nr !!}<br>
        @endif

        @if($contact->zip_code)
            <strong> @lang('business.zip_code'): </strong> {!! $contact->zip_code !!}<br>
        @endif
        @if($contact->city)
            <strong> @lang('business.city'): </strong>  {!! $contact->city !!}<br>
        @endif
        @if($contact->country)
            <strong> @lang('business.country'): </strong> {!! $contact->country !!}<br>
        @endif
    </p>
@endif
{{-- Start Einwilligung --}}
<strong><i class="fa fa-check-circle margin-r-5" style="font-size:18px"></i> Einwilligungen</strong>
    </p>
   @if($contact->consent_email)
        <strong><i class="nav-icon fas fa-check-circle text-success" style="font-size:18px"></i> @lang('lang_v1.consent_email')</strong>
   @else
        <strong><i class="fa fa-times-circle "  style="font-size:18px;color:red"></i> @lang('lang_v1.consent_email')</strong>
   @endif
   </p>
   @if($contact->consent_mobile)
        <strong><i class="nav-icon fas fa-check-circle text-success" style="font-size:18px"></i> @lang('lang_v1.consent_mobile')</strong>
   @else
        <strong><i class="fa fa-times-circle "  style="font-size:18px;color:red"></i> @lang('lang_v1.consent_mobile')</strong>
   @endif
   </p>
   @if($contact->consent_post)
        <strong><i class="nav-icon fas fa-check-circle text-success" style="font-size:18px"></i> @lang('lang_v1.consent_post')</strong>
   @else
        <strong><i class="fa fa-times-circle "  style="font-size:18px;color:red"></i> @lang('lang_v1.consent_post')</strong>
   @endif
   </p>
   @if($contact->consent_messenger)
        <strong><i class="nav-icon fas fa-check-circle text-success" style="font-size:18px"></i> @lang('lang_v1.consent_messenger')</strong>
   @else
        <strong><i class="fa fa-times-circle "  style="font-size:18px;color:red"></i> @lang('lang_v1.consent_messenger')</strong>
   @endif
</p>
   @if($contact->consent_field2)
        <strong><i class="nav-icon fas fa-check-circle text-success" style="font-size:18px"></i> @lang('lang_v1.vollmacht')</strong>
   @else
        <strong><i class="fa fa-times-circle "  style="font-size:18px;color:red"></i> @lang('lang_v1.vollmacht')</strong>
   @endif
</p>
</p>
   @if($contact->consent_field4)
        <strong><i class="nav-icon fas fa-check-circle text-success" style="font-size:18px"></i> @lang('lang_v1.payment_service')</strong>
   @else
        <strong><i class="fa fa-times-circle "  style="font-size:18px;color:red"></i> @lang('lang_v1.payment_service')</strong>
   @endif
</p>

@if($contact->last_consent_date)
<strong><i class="fa fa-calendar margin-r-5"></i> @lang('lang_v1.last_consent_date')</strong>
<p class="text-muted">
     {{ @format_date($contact->last_consent_date) }}
</p>
@endif
@if($contact->last_consent_from)
<strong><i class="fa fa-user-circle margin-r-5"></i> @lang('lang_v1.last_consent_from')</strong>
<p class="text-muted">
    {{ $contact->last_consent_from }}
</p>
@endif
{{-- End Einwilligung --}}
@if($contact->supplier_business_name)
    <strong><i class="fa fa-briefcase margin-r-5"></i> 
    @lang('business.business_name')</strong>
    <p class="text-muted">
        {{ $contact->supplier_business_name }}
    </p>
@endif
@if($contact->business_position)
    <strong><i class="fa fa-briefcase margin-r-5"></i> 
    @lang('business.business_position')</strong>
    <p class="text-muted">
        {{ $contact->business_position }}
    </p>
@endif
@if($contact->email)
<strong><i class="fa fa-envelope margin-r-5"></i> @lang('business.email')</strong>
<p class="text-muted">
    {{ $contact->email }}
</p>
@endif
@if($contact->mobile)
<strong><i class="fa fa-mobile margin-r-5"></i> @lang('contact.mobile')</strong>
<p class="text-muted">
    {{ $contact->mobile }}
</p>
@endif

@if($contact->bank || $contact->iban || $contact->bic )
    <strong><i class="fa fa-university margin-r-5"></i> @lang('lang_v1.bank')institut</strong>
    </br>
        @if($contact->bank)
            <strong> @lang('lang_v1.bank')name: </strong> {!! $contact->bank !!}<br>
        @endif    
        @if($contact->iban)
            <strong> @lang('lang_v1.iban'): </strong> {!! $contact->iban !!}<br>
        @endif
        @if($contact->bic)
            <strong> @lang('lang_v1.bic'): </strong> {!! $contact->bic !!}<br>
        @endif
    </p>
@endif

@if($contact->paypal)
    <strong><i class="fab fa-paypal margin-r-5"></i> @lang('lang_v1.paypal')</strong>
    <p class="text-muted">
        {{ $contact->paypal }}
    </p>
@endif
@if($contact->alternate_number)
    <strong><i class="fa fa-phone margin-r-5"></i> @lang('contact.alternate_contact_number')</strong>
    <p class="text-muted">
        {{ $contact->alternate_number }}
    </p>
@endif
@if($contact->dob)
    <strong><i class="fa fa-calendar margin-r-5" style="font-size:18px"></i> @lang('lang_v1.dob')</strong>
    <p class="text-muted">
        {{ @format_date($contact->dob) }}
    </p>
@endif
    
    <strong>
     <a href="{{ $url_edit_self }}" target="_blank">
        <i class="fa fa-edit margin-r-2" style="font-size:18px"></i>
            @lang('lang_v1.link_self_edit_data')
        </p>
    </a> 
    </strong>
    <strong>
        <a href="{{ $url_opt_out }}" target="_blank">
            <i class="fa fa-exclamation-circle margin-r-2" style="font-size:18px"></i>
                @lang('lang_v1.opt_out')
                </p>
            </a> 
    </strong>



   
     {{-- <i class="fa fa-times-circle "  style="font-size:18px;color:red"></i>
      <i class="nav-icon fas fa-check-circle text-success" style="font-size:18px"></i> --}}