@extends('layouts.app')
@section('title', __( 'dynamicqrcode::lang.list_qr_code' ))

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><i class="fa fas fa-qrcode"></i> @lang( 'dynamicqrcode::lang.list_qr_code' )</h1>
</section>
@php
    $business_name = str_replace(" ", "_", $business->name);
    $business_name = str_replace("/", "$&", $business_name);
@endphp

<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'dynamicqrcode::lang.dyn_qr_code' )])
        @slot('tool')
            @if(count($dyn_qr_codes) < $class_dyn_qr_code->getAllowedNumberOfDynamicQrCode())
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" 
                        href="{{action([\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'create'])}}" >
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )
                    </a>
                </div>
            @endif
        @endslot
        {{-- {{request()->fullUrl()}} --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dyn_qr_code_table">
                <thead>
                    <tr>
                        <th>@lang( 'messages.action' )</th>
                        <th>@lang( 'dynamicqrcode::lang.redirect' )</th>
                        <th>@lang( 'dynamicqrcode::lang.title' )</th>
                        <th>@lang( 'dynamicqrcode::lang.link' )</th>                       
                        <th>@lang( 'dynamicqrcode::lang.total_scans' )</th>   
                    </tr>
                </thead>
            </table>
        </div>
        <br>
        <br>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    Ein dynamischer QR-Code ist ein spezieller QR-Code, der eine kurze Weiterleitungs-URL enthält, die zu einer anderen URL führt, 
                    die den eigentlichen Inhalt oder die Webseite anzeigt. <b> Vorteil eines dynamischen QR-Codes ist, 
                    dass man die Ziel-URL jederzeit ändern kann, ohne den QR-Code neu drucken zu müssen.</b>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    Man kann einen dynamischen QR-Code für verschiedene Zwecke einsetzen, zum Beispiel für:
                    <ul>                        
                        <li>Marketingkampagnen: Man kann den Inhalt oder das Angebot anpassen, je nachdem, wie die Nutzer reagieren.</li>
                        <li>Digitale Visitenkarten: Man kann seine Kontaktdaten aktualisieren, ohne eine neue Karte zu erstellen.</li>
                        <li>Veranstaltungstickets: Man kann die Informationen über die Veranstaltung ändern oder ergänzen, <p>
                            z.B. den Ort, die Zeit oder das Programm.</li>
                        
                    </ul>
                    <p>
                    

                </div>
            </div>
        </div>
    @endcomponent  
    

</section>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {                   

            dyn_qr_code_datatable = $("#dyn_qr_code_table").DataTable({
	        	processing: true,
	            serverSide: true,
            
	            ajax: {
	                 url: "/dynamic-qr-code/list-dynamic-qr-codes",
	            },
	            columnDefs: [
	                {
	                    targets: [0],
	                    orderable: false,
	                    searchable: false,
	                },
	            ],
	            aaSorting: [[1, 'asc']],
	            columns: [
	                { data: 'action', name: 'action' },
                    { data: 'redirect', name: 'redirect' },
	                { data: 'title', name: 'title' },
	                { data: 'link', name: 'link' },
	                { data: 'view_count', name: 'view_count' },	                
	            ],         
	        });
        }); 
</script>
@endsection