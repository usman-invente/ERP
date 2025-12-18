@extends('layouts.app')
@section('title', __( 'dynamicqrcode::lang.edit_qr_code' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><i class="fa fas fa-qrcode"></i> @lang( 'dynamicqrcode::lang.edit_qr_code' )</h1>
</section>
@php
    $business_name = str_replace(" ", "_", $business->name);
    $business_name = str_replace("/", "$&", $business_name);
@endphp
<div class="row no-print">
    <div class="col-md-7 col-xs-10 mt-15 pull-left">           
        <div class="box-tools">
            <div class="btn-group btn-group-toggle pull-right m-5" >
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" 
                        href="{{action([\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'index'])}}" >
                       @lang( 'crm::lang.list_view' )
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-7">
            {!! Form::open(['url' => action([\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'update'],['link' =>$dynamicQrCode->link]), 'method' => 'put' ]) !!}
    	@component('components.widget', ['class' => 'box-solid'])
            <input hidden type="text" name="business_name" id="business_name" value='{{ $business->name }}'>
            <input hidden type="text" name="business_n" id="business_n" value='{{ $business_name }}'>
            <input hidden type="text" name="qr_link" id="qr_link" value='{{ route('dynamic_qr_code_redirect', $dynamicQrCode->link) }}'>
            {{-- <div class="form-group">
                {!! Form::label('location_id', __('purchase.business_location').':') !!}
                {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control', 'placeholder' => __('messages.please_select')]); !!}
            </div> --}}
            
            {{-- <div class="form-group">
                {!! Form::label('title', __('dynamicqrcode::lang.title').':') !!}
                {!! Form::text('title', $business->name, ['class' => 'form-control']); !!}
            </div> --}}
            {{-- <div class="form-group">
                {!! Form::label('subtitle', __('dynamicqrcode::lang.subtitle').':') !!}
                {!! Form::text('subtitle', __('dynamicqrcode::lang.qr_code_customerregister'), ['class' => 'form-control']); !!}
            </div> --}}
            <div class="form-group">
                {!! Form::label('redirect', __('dynamicqrcode::lang.redirect').':') !!}
                {!! Form::text('redirect', $dynamicQrCode->redirect, ['class' => 'form-control', 'id' =>'redirect', 'required', 'placeholder' => __( 'dynamicqrcode::lang.placeholder_redirect' ) ]); !!}
            </div>
            <div class="form-group">
                {!! Form::label('title', __('dynamicqrcode::lang.title').':') !!}
                {!! Form::text('title', $dynamicQrCode->title, ['class' => 'form-control', 'id' => 'title', 'name' => 'title', 'placeholder' => __( 'dynamicqrcode::lang.placeholder_title' ) ]); !!}
            </div>
            {{-- <div class="form-group">
                {!! Form::label('link', __('dynamicqrcode::lang.link').':') !!}
                {!! Form::text('link', $dynamicQrCode->link, ['class' => 'form-control', 'placeholder' => __( 'dynamicqrcode::lang.link' ) ]); !!}
            </div> --}}
            <div class="form-group">
                {!! Form::label('color', __('dynamicqrcode::lang.qr_code_color').':') !!}
                {!! Form::text('color', '#000000', ['class' => 'form-control']); !!}
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('add_logo', 1, true, ['id' => 'show_logo', 'class' => 'input-icheck']); !!} @lang('dynamicqrcode::lang.show_business_logo_on_qrcode')
                    </label>
                </div>
                <div class="checkbox">
                    <label> 
                        {!! Form::checkbox('add_title', 1, true, ['id' => 'show_title', 'class' => 'input-icheck']); !!} @lang('productcatalogue::lang.show_title_on_qrcode')
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            QR-Code bleibt immer gleich! Nur der Link, <p>der hinterlegt ist, kann verändert werden.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                            <button type="button" class="btn btn-primary" id="generate_qr">@lang( 'dynamicqrcode::lang.generate_qr' )</button>
                        </div>
                    </div>
                </div>
              
            </div>
            
        @endcomponent
        {!! Form::close() !!}
        @component('components.widget', ['class' => 'box-solid'])        
            <div class="row">
                <div class="col-md-12">
                    <strong>@lang('dynamicqrcode::lang.instruction'):</strong>
                    <table class="table table-striped">
                        <tr>
                            <td>1</td>
                            <td>@lang( 'dynamicqrcode::lang.catalogue_instruction_1' )</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>@lang( 'dynamicqrcode::lang.catalogue_instruction_2' )</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>@lang( 'dynamicqrcode::lang.catalogue_instruction_3' )</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>@lang( 'dynamicqrcode::lang.catalogue_instruction_4' )</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endcomponent
        </div>
        
        <div class="col-md-5">
            @component('components.widget', ['class' => 'box-solid'])

                <div class="text-center">
                    <div id="qrcode"></div>
                    <span id="catalogue_link"></span>
                    <br>
                    <a href="#" class="btn btn-success hide" id="download_image">@lang( 'dynamicqrcode::lang.download_image' )</a>
                </div>
            @endcomponent
        </div>
    </div>
</section>
@stop
@section('javascript')
<script src="{{ asset('modules/productcatalogue/plugins/easy.qrcode.min.js') }}"></script>
<script type="text/javascript">
    (function($) {
        "use strict";

    $(document).ready( function(){
        $('#color').colorpicker();
    });

    $(document).on('click', '#generate_qr', function(e){
        $('#qrcode').html('');
        var redirect = document.getElementById("title").value; 
        
        // if ($('#redirect').val()) {
        if (document.getElementById("redirect").value) {
            //  var link = "https://www.youtube.com"; 
            //  var link = document.getElementById("redirect").value; 
            // var link = "{{url('crm-new-customer/'. session('business.id'))}}/" + $('#location_id').val()+"/" + $('#business_n').val();
            {{-- var link = "{{url('crm-new-customer/'. session('business.id'))}}/" + $('#location_id').val(); --}}
            var link = "{{ route('dynamic_qr_code_redirect', $dynamicQrCode->link) }}" ;
            var color = '#000000';
            if ($('#color').val().trim() != '') {
                color = $('#color').val();
            }
            var opts = {
                text: link,
                margin: 4,
                width: 256,
                height: 256,
                quietZone: 20,
                colorDark: color,
                colorLight: "#ffffffff", 
            }

            if ($('#title').val().trim() !== '' && $('#show_title').is(':checked')) {
                opts.title = $('#title').val();
                opts.titleFont = "bold 18px Arial";
                opts.titleColor = "#004284";
                opts.titleBackgroundColor = "#ffffff";
                opts.titleHeight = 60;
                opts.titleTop = 20;
            }

            // if ($('#subtitle').val().trim() !== '') {
            //     opts.subTitle = $('#subtitle').val();
            //     opts.subTitleFont = "14px Arial";
            //     opts.subTitleColor = "#4F4F4F";
            //     opts.subTitleTop = 40;
            // }

            if ($('#show_logo').is(':checked')) {
                opts.logo = "{{asset( 'uploads/business_logos/' . $business->logo)}}";
            }

            new QRCode(document.getElementById("qrcode"), opts);
            $('#catalogue_link').html('<a target="_blank" href="'+ link +'">Link</a>');
            $('#download_image').removeClass('hide');
            $('#qrcode').find('canvas').attr('id', 'qr_canvas')

            
        } else {
            alert("{{__('dynamicqrcode::lang.write_the_url')}}")
        }
    });
    })(jQuery);

    $('#download_image').click(function(e) {
        e.preventDefault();
        var link = document.createElement('a');
        link.download = 'qrcode.png';
        link.href = document.getElementById('qr_canvas').toDataURL()
        link.click();
    });
</script>
@endsection