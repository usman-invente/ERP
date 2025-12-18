<div class="modal-dialog" role="document">
    <div class="modal-content">
  
      {!! Form::open(['url' => action([\App\Http\Controllers\CashRegisterDetailController::class, 'store']), 'method' => 'post' ]) !!}
  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'business.add_business_location' )</h4>
      </div>
        
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('name', __( 'cash_register.name' ) . ':*') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'cash_register.name' ) ]); !!}
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('description', __( 'cash_register.description' ) . ':*') !!}
                {!! Form::text('description', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'cash_register.description' ) ]); !!}
            </div>
          </div>
          <div class="clearfix"></div>
          @if(count($business_locations) > 1)
            <div class="col-sm-12">
              <div class="form-group">
                {!! Form::label('location_id', __('business.business_location') . ':') !!}
                {!! Form::select('location_id', $business_locations, null, ['id'=> 'location_dropdown','class' => 'form-control select2',
                'placeholder' => __('lang_v1.select_location')]); !!}
              </div>
            </div>
          @else
            {!! Form::hidden('location_id', array_key_first($business_locations->toArray()) ); !!}
          @endif
          <div class="clearfix"></div> 
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('tss_active', __( 'cash_register.tss_active' ) ) !!}
                {!! Form::checkbox('tss_active', 1,false, ['class' => 'input-icheck',  'id' => 'tss_active', 'placeholder' => __( 'cash_register.tss_active' ) ]); !!}
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
  
      {!! Form::close() !!}
  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->