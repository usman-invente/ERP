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

        
        /*.row {
            background-color: #2b80ec;
        }*/
    </style>
    <nav class="navbar navbar-default bg-white m-4">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="row">
                 <div class="col-md-12 col-sm-6 col-xs-12 col-custom">
                </div>
            </div>
            <div class="navbar-header" style="float: left; width: 80%; margin-left: 10px;margin-top: 10px;">
                {{-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{action([\App\Http\Controllers\ContractController::class, 'getContractExtensionThisWeek'])}}"> 
                <i class="fas fa-file-contract fa-lg"></i>--}}
                 <span style="color:black;font-size:24px;font-weight:bold"> {{__('contract.contact_for_contract_extension')}} </span> </a>
            </div>
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            {{-- <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li @if(request()->segment(2) == 'this-month') class="active" @endif>
                       <a href="{{action([\App\Http\Controllers\ContractController::class, 'getContractExtensionThisMonth'])}}">
                           <span style="color:#2dce89;font-weight:bold"> @lang('contract.contract_this_month') </span>
                        </a>
                    </li>
                    <li @if(request()->segment(2) == 'overdue') class="active" @endif>
                        <a href="{{action([\App\Http\Controllers\ContractController::class, 'getContractExtensionOverdue'])}}">
                           <span style="color:#f5365c;font-weight:bold"> @lang('contract.contract_overdue') </span>
                        </a>
                    </li>
                    <li 
                        @if(request()->segment(2) == 'next-month') class="active" @endif>
                        <a href="{{action([\App\Http\Controllers\ContractController::class, 'getContractExtensionNextMonth'])}}">
                           <span style="color:#11cdef;font-weight:bold"> @lang('contract.contract_next_month') </span>
                        </a>
                    </li> 
                                      
                </ul>

            </div> --}}
            <!-- /.navbar-collapse -->                
            <div class="row" style="float: left; width: 80%; margin-left: 10px;margin-top: 15px;">
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\App\Http\Controllers\ContractController::class, 'getContractExtensionThisWeek'])}}">
                    @if(request()->segment(2) == 'this-week')
                        <div class="info-box info-box-new-style" style="background:#ffad46">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-yellow"><i class="fas fa-file-contract"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('home.contract_this_week') }}</span>
                        <span class="info-box-number" style="color:black">{{ $class_contract->getNumberOfContracts( $business->id, 'this_week') }}</span> 
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\App\Http\Controllers\ContractController::class, 'getContractExtensionThisMonth'])}}">
                    @if(request()->segment(2) == 'this-month')
                        <div class="info-box info-box-new-style" style="background:#2dce89">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif                   
                        <span class="info-box-icon bg-green">
                            <i class="fas fa-file-contract"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="color:black">{{ __('home.contract_this_month') }}</span>
                            <span class="info-box-number" style="color:black">{!! $class_contract->getNumberOfContracts( $business->id, 'this_month') !!}</span>
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\App\Http\Controllers\ContractController::class, 'getContractExtensionOverdue'])}}">
                    @if(request()->segment(2) == 'overdue')
                        <div class="info-box info-box-new-style" style="background:#f5365c">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-red"><i class="fas fa-file-contract"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('home.contract_overdue') }}</span>
                         <span class="info-box-number" style="color:black">{!! $class_contract->getNumberOfContracts( $business->id, 'overdue') !!}</span> 
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\App\Http\Controllers\ContractController::class, 'getContractExtensionNextMonth'])}}">
                    @if(request()->segment(2) == 'next-month')
                        <div class="info-box info-box-new-style" style="background:#11cdef">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-aqua"><i class="fas fa-file-contract"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('home.contract_next_month') }}</span>
                         <span class="info-box-number" style="color:black">{!! $class_contract->getNumberOfContracts( $business->id, 'next_month') !!}</span> 
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </nav>
</section>