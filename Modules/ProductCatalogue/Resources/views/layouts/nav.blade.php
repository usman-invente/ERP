<section class="no-print">
    <style type="text/css">
        #contacts_login_dropdown::after {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }
    </style>
    <nav class="navbar navbar-default bg-white m-4">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header" style="float: left; width: 80%; margin-left: 10px;margin-top: 10px;">
                {{--<div class="navbar-header">
                 <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{action([\Modules\ProductCatalogue\Http\Controllers\ProductCatalogueController::class, 'generateQr'])}}"><i class="fa fas fa-qrcode"></i> {{__('productcatalogue::lang.vollmacht_generate_qr')}}</a> --}}
                <span style="color:black;font-size:24px;font-weight:bold"> {{__('productcatalogue::lang.qr_code')}} </span> </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling 
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    {{-- <li @if(request()->segment(2) == 'consent-dokument-generate-qr') class="active" @endif>
                        <a href="{{action([\Modules\ProductCatalogue\Http\Controllers\ProductCatalogueController::class, 'consent_dokumentGenerateQr'])}}">
                            @lang('productcatalogue::lang.consent_dokument_generate_qr')
                        </a>
                    </li> --}}
                    <li @if(request()->segment(2) == 'consent-generate-qr') class="active" @endif>
                        <a href="{{action([\Modules\ProductCatalogue\Http\Controllers\ProductCatalogueController::class, 'consentGenerateQr'])}}">
                            @lang('productcatalogue::lang.consent_generate_qr')
                        </a>
                    </li>
                    <li @if(request()->segment(2) == 'newsletter-generate-qr') class="active" @endif>
                        <a href="{{action([\Modules\ProductCatalogue\Http\Controllers\ProductCatalogueController::class, 'newsletterGenerateQr'])}}">
                            @lang('productcatalogue::lang.newsletter_generate_qr')
                        </a>
                    </li>
                    
                </ul>

            </div>-->
            <!-- /.navbar-collapse -->
            <div class="row" style="float: left; width: 80%; margin-left: 10px;margin-top: 15px;">
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\Modules\ProductCatalogue\Http\Controllers\ProductCatalogueController::class, 'generateQr'])}}">
                    @if(request()->segment(2) == 'generate-qr')
                        <div class="info-box info-box-new-style" style="background:#3595f6">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-aqua"><i class="fa fas fa-qrcode"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('productcatalogue::lang.vollmacht_generate_qr') }}</span>
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\Modules\ProductCatalogue\Http\Controllers\ProductCatalogueController::class, 'consentGenerateQr'])}}">
                    @if(request()->segment(2) == 'consent-generate-qr')
                        <div class="info-box info-box-new-style" style="background:#3595f6">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-aqua"><i class="fa fas fa-qrcode"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('productcatalogue::lang.consent_generate_qr') }}</span>
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\Modules\ProductCatalogue\Http\Controllers\ProductCatalogueController::class, 'newsletterGenerateQr'])}}">
                    @if(request()->segment(2) == 'newsletter-generate-qr')
                        <div class="info-box info-box-new-style" style="background:#3595f6">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-aqua"><i class="fa fas fa-qrcode"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('productcatalogue::lang.newsletter_generate_qr') }}</span>
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </nav>
</section>