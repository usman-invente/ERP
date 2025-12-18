<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @if(is_object($business))
        <link rel="icon" type="image/x-icon" href="{{ url( 'uploads/business_logos/' . $business->logo ) }}" style="opacity: .8; height: auto; width: 70px;">                   
    @endif
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Software') }}</title> 

    @include('layouts.partials.css')

    @yield('css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
    table {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
}
</style>    
</head>

<body>
{{-- @include('layouts.partials.sidebar') --}}
@include('layouts.partials.header-new-customer')

                {{-- <input type="hidden" id="__code" value="{{session('currency')['code']}}">
                <input type="hidden" id="__symbol" value="{{session('currency')['symbol']}}">
                <input type="hidden" id="__thousand" value="{{session('currency')['thousand_separator']}}">
                <input type="hidden" id="__decimal" value="{{session('currency')['decimal_separator']}}">
                <input type="hidden" id="__symbol_placement" value="{{session('business.currency_symbol_placement')}}">
                <input type="hidden" id="__precision" value="{{session('business.currency_precision', 2)}}">
                <input type="hidden" id="__quantity_precision" value="{{session('business.quantity_precision', 2)}}"> --}}
                <!-- End of currency related field-->
                {{-- @can('view_export_buttons')
                    <input type="" id="view_export_buttons">
                @endcan
                @if(isMobile())
                    <input type="hidden" id="__is_mobile">
                @endif
                @if (session('status'))
                    <input type="" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
                @endif --}}
                @yield('content')
    {{-- <table>

    @if (session('status'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
    @endif
    @inject('request', 'Illuminate\Http\Request')
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-sm-4 hidden-xs left-col eq-height-col" >
                <div class="left-col-content login-header"> 
                    <div style="margin-top: 50%;">
                    <a href="/">
                    @if(file_exists(public_path('uploads/logo.png')))
                        <img src="/uploads/logo.png" class="img-rounded" alt="Logo" width="150">
                    @else
                       {{ config('app.name', 'ultimatePOS') }}
                    @endif 
                    </a>
                    <br/>
                    @if(!empty(config('constants.app_title')))
                        <small>{{config('constants.app_title')}}</small>
                    @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 right-col eq-height-col">
                @yield('content')
                <div class="row">
                   <div class="col-md-3 col-xs-4" style="text-align: left;">
                        <select class="form-control input-sm" id="change_lang" style="margin: 10px;">
                        @foreach(config('constants.langs') as $key => $val)
                            <option value="{{$key}}" 
                                @if( (empty(request()->lang) && config('app.locale') == $key) 
                                || request()->lang == $key) 
                                    selected 
                                @endif
                            >
                                {{$val['full_name']}}
                            </option>
                        @endforeach
                        </select>
                    </div>  
                    
                    
                </div>
            </div>
        </div>
    </div>
    </table> --}}
    <!-- Scripts -->
    {{-- @include('layouts.partials.javascripts')
    <script src="{{ asset('js/login.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2_register').select2();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            $('#change_lang').change( function(){
                window.location = "{{ route('repair-status') }}?lang=" + $(this).val();
            });
        });
    </script> --}}
</body>

</html>