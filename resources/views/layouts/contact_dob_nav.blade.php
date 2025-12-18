<section class="no-print">
    
    <nav class="navbar navbar-default bg-white m-4">
        <div class="container-fluid">
            <div class="row">
                 <div class="col-md-12 col-sm-6 col-xs-12 col-custom">
                </div>
            </div>
            <div class="navbar-header" style="float: left; width: 80%; margin-left: 10px;margin-top: 10px;">
                
                 <span style="color:black;font-size:24px;font-weight:bold"> {{__('contact.dobs')}} </span> </a>
            </div>
            <!-- /.navbar-collapse -->                
            <div class="row" style="float: left; width: 80%; margin-left: 10px;margin-top: 15px;">
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\App\Http\Controllers\ContactController::class, 'getAllContactsWithDobToday'])}}">
                    @if(request()->segment(2) == 'dob_today')
                        <div class="info-box info-box-new-style" style="background:#3595f6">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-aqua"><i class="fa fa-birthday-cake"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('contact.dob_today') }}</span>
                        <span class="info-box-number" style="color:black">{!! count($class_contact->getBirthdayByCondition( $business->id, 'today')) !!}</span> 
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\App\Http\Controllers\ContactController::class, 'getAllContactsWithDobThisWeek'])}}">
                    @if(request()->segment(2) == 'dob_this_week')
                        <div class="info-box info-box-new-style" style="background:#3595f6">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif                   
                        <span class="info-box-icon bg-aqua">
                            <i class="fa fa-birthday-cake"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="color:black">{{ __('contact.dob_this_week') }}</span>
                            <span class="info-box-number" style="color:black">{!! count($class_contact->getBirthdayByCondition( $business->id, 'this_week')) !!}</span>
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\App\Http\Controllers\ContactController::class, 'getAllContactsWithDobThisMonth'])}}">
                    @if(request()->segment(2) == 'dob_this_month')
                        <div class="info-box info-box-new-style" style="background:#3595f6">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-aqua"><i class="fa fa-birthday-cake"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('contact.dob_this_month') }}</span>
                         <span class="info-box-number" style="color:black">{!! count($class_contact->getBirthdayByCondition( $business->id, 'this_month')) !!}</span> 
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <a href="{{action([\App\Http\Controllers\ContactController::class, 'getAllContactsWithDobNextMonth'])}}">
                    @if(request()->segment(2) == 'dob_next_month')
                        <div class="info-box info-box-new-style" style="background:#3595f6">
                    @else    
                        <div class="info-box info-box-new-style" style="background:#f4f4f4">
                    @endif
                        <span class="info-box-icon bg-aqua"><i class="fa fa-birthday-cake"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text" style="color:black">{{ __('contact.dob_next_month') }}</span>
                         <span class="info-box-number" style="color:black">{!! count($class_contact->getBirthdayByCondition( $business->id, 'next_month')) !!}</span> 
                        </div>
                   </div>
                   </a>
                  <!-- /.info-box -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </nav>
</section>