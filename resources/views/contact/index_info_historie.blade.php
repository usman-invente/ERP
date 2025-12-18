
<div class="pull-right">
    <input type="hidden" name="contract_create_url" id="contract_create_url" value="{{action([\App\Http\Controllers\ContactInfoHistorieController::class, 'create'])}}?contract_for=lead&contact_id={{$contact->id}}">
    <input type="hidden" name="contact_id" id="contact_id" value="{{$contact->id}}" id="contact_id">
    <input type="hidden" name="view_type" value="lead_info" id="view_type"> 
</div> 
<br><br>
<div class="table-responsive">
	<table class="table table-bordered table-striped" id="contact_info_historie_modul_table" style="width: 100%">
        <thead>
            <tr>
                <th>@lang('messages.action')</th>                
                <th>@lang('contact.info_historie_created_at')</th>
                <th>@lang('contact.info_historie_title')</th>
                <th>@lang('contact.info_historie_description')</th>
                <th>@lang('contact.info_historie_details')</th>
                <th>@lang('contact.info_historie_ip_address')</th>
                <th>@lang('contact.info_historie_type')</th>
                <th>@lang('contact.info_historie_consent')</th>
            </tr>
        </thead>
        <tbody></tbody>
        
    </table>
</div>
@section('javascript')
<script type="text/javascript">
    /* $(document).ready(function() {
        
     See the javascript-function for this Table in the Script:
     ".../views/contract/index.blade.php" 
     });
     */

    //On display of add contact modal

</script>
@endsection