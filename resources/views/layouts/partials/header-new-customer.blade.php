@inject('request', 'Illuminate\Http\Request')
<!-- Main Header -->
  <header class="main-header ">
    {{-- <a href=" " class="logo">      
      <span class="logo-lg">{{ Session::get('business.name') }} <i class="fa fa-circle text-success" id="online_indicator"></i></span> 
    </a> --}}
  @php
    use App\BusinessLocation;
    $business_name = str_replace("_", " ", request()->route("business_name"));
    $business_name = str_replace("$&", "/", $business_name);

    $business_location = BusinessLocation::where('id','=',request()->route("location_id"))
                            ->where('business_id','=',  request()->route("business_id"))
                            ->first();
@endphp	
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      {{-- @if(!empty($business_location->website ))
        <a target="_blank" href="{{ $business_location->website }}" class="logo">      
          <span style="font-size:small; align:right" class="logo-lg"> {{ $business_location->website }} <i class="fa fa-circle text-success" id="online_indicator"></i></span> 
        </a>
      @endif --}}
      <!-- Sidebar toggle button-->
      {{-- <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        &#9776;
        <span class="sr-only">Toggle navigation</span>
      </a> --}}

      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">  
        @if(!empty($business_location->website ))
          <a target="_blank" href="{{ $business_location->website }}" >      
            <span style="font-size:large; align='center'" class="logo-lg"> {{ $business_location->website }} <i class="fa fa-circle text-success" id="online_indicator"></i></span> 
          </a>
        @endif
       
          {{-- <button id="header_shortcut_dropdown" type="button" class="btn btn-success dropdown-toggle btn-flat pull-left m-8 btn-sm mt-10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-plus-circle fa-lg"></i>
          </button> --}}
     
        {{-- <button id="btnCalculator" title="@lang('lang_v1.calculator')" type="button" class="btn btn-success btn-flat pull-right m-8 btn-sm mt-10 popover-default hidden-xs" data-toggle="popover" data-trigger="click" data-content='@include("layouts.partials.calculator")' data-html="true" data-placement="bottom">
            <strong><i class="fa fa-calculator fa-lg" aria-hidden="true"></i></strong>
        </button> --}}

        <div class="m-8 pull-right mt-10 hidden-xs" ><span style="font-size:large;"><strong>Datum: {{ @format_date('now') }}</strong></span></div>

        <ul class="nav navbar-nav">

          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{action([\App\Http\Controllers\UserController::class, 'getProfile'])}}" class="btn btn-default btn-flat">@lang('lang_v1.profile')</a>
                </div>
                <div class="pull-right">
                  <a href="{{action([\App\Http\Controllers\Auth\LoginController::class, 'logout'])}}" class="btn btn-default btn-flat">@lang('lang_v1.sign_out')</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>