<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    {!! Form::open(['url' => action([\App\Http\Controllers\CashRegisterController::class, 'postCloseRegister']), 'method' => 'post' ]) !!}

    {!! Form::hidden('user_id', $register_details->user_id); !!}
    {!! Form::hidden('cash_register_detail_id', $register_details->cash_register_detail_id); !!}
    {!! Form::hidden('location_id', $register_details->location_id); !!}
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h3 class="modal-title">@lang( 'cash_register.current_register' ) ( {{ \Carbon::createFromFormat('Y-m-d H:i:s', $register_details->open_time)->format('jS M, Y h:i A') }} - {{ \Carbon::now()->format('jS M, Y h:i A') }})</h3>
    </div>

    <div class="modal-body">
        @include('cash_register.payment_details')
        <hr>
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('display_currency', __( 'cash_register.cash_in_hand' ) . ':') !!}
              {!! Form::text('display_currency', @num_format($cashRegisterUtil->getCloseAmountByCashRegisterDetailId($register_details->cash_register_detail_id)), ['class' => 'form-control input_number', 'readonly', 'placeholder' => __( 'cash_register.cash_in_hand' ) ]); !!}
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('closing_amount', __( 'cash_register.total_cash' ) . ':') !!}
              {!! Form::text('closing_amount', @num_format($register_details->cash_in_hand + $register_details->total_cash - $sum_sells_return - $register_details->total_cash_refund - $register_details->total_cash_expense), ['class' => 'form-control input_number', 'readonly', 'placeholder' => __( 'cash_register.total_cash' ) ]); !!}
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('total_cash_money', __( 'cash_register.total_cash_money' ) . ':') !!}
              {!! Form::text('total_cash_money', @num_format($cashRegisterUtil->getCloseAmountByCashRegisterDetailId($register_details->cash_register_detail_id) + $register_details->cash_in_hand + 
                                                              $register_details->total_cash - $sum_sells_return - $register_details->total_cash_refund - $register_details->total_cash_expense 
                                                              + $cashRegisterUtil->getIcomesSumByCashRegisterId($register_details->id, $register_details->cash_register_detail_id)), ['class' => 'form-control input_number', 'readonly', 'placeholder' => __( 'cash_register.total_cash_money' ) ]); !!}
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('cash_payout', __( 'cash_register.cash_payout' ) . ':*') !!}
              {!! Form::text('cash_payout', @num_format(0), ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'cash_register.cash_payout' ), 'onchange=javascript:getDifference()' ]); !!}
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('remaining_change', __( 'cash_register.remaining_change' ) . ':*') !!}
              {!! Form::text('remaining_change', @num_format(0), ['class' => 'form-control input_number', 'readonly', 'placeholder' => __( 'cash_register.remaining_change' ) ]); !!}
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('total_card_slips', __( 'cash_register.total_card_slips' ) . ':*') !!} @show_tooltip(__('tooltip.total_card_slips'))
              {!! Form::number('total_card_slips', $register_details->total_card_slips, ['class' => 'form-control', 'required', 'placeholder' => __( 'cash_register.total_card_slips' ), 'min' => 0 ]); !!}
          </div>
        </div> 
        
        {{-- <div class="col-sm-4">
          <div class="form-group">
            {!! Form::label('total_cheques', __( 'cash_register.total_cheques' ) . ':*') !!} @show_tooltip(__('tooltip.total_cheques'))
              {!! Form::number('total_cheques', $register_details->total_cheques, ['class' => 'form-control', 'required', 'placeholder' => __( 'cash_register.total_cheques' ), 'min' => 0 ]); !!}
          </div>
        </div>  --}}
        <hr>
        {{-- <div class="col-md-8 col-sm-12">
          <h3>@lang( 'lang_v1.cash_denominations' )</h3>
          @if(!empty($pos_settings['cash_denominations']))
            <table class="table table-slim">
              <thead>
                <tr>
                  <th width="20%" class="text-right">@lang('lang_v1.denomination')</th>
                  <th width="20%">&nbsp;</th>
                  <th width="20%" class="text-center">@lang('lang_v1.count')</th>
                  <th width="20%">&nbsp;</th>
                  <th width="20%" class="text-left">@lang('sale.subtotal')</th>
                </tr>
              </thead>
              <tbody>
                @foreach(explode(',', $pos_settings['cash_denominations']) as $dnm)
                <tr>
                  <td class="text-right">{{$dnm}}</td>
                  <td class="text-center" >X</td>
                  <td>{!! Form::number("denominations[$dnm]", null, ['class' => 'form-control cash_denomination input-sm', 'min' => 0, 'data-denomination' => $dnm, 'style' => 'width: 100px; margin:auto;' ]); !!}</td>
                  <td class="text-center">=</td>
                  <td class="text-left">
                    <span class="denomination_subtotal">0</span>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" class="text-center">@lang('sale.total')</th>
                  <td><span class="denomination_total">0</span></td>
                </tr>
              </tfoot>
            </table>
          @else
            <p class="help-block">@lang('lang_v1.denomination_add_help_text')</p>
          @endif
        </div> --}}
        <hr>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('closing_note', __( 'cash_register.closing_note' ) . ':') !!}
              {!! Form::textarea('closing_note', null, ['class' => 'form-control', 'placeholder' => __( 'cash_register.closing_note' ), 'rows' => 3 ]); !!}
          </div>
        </div>
      </div> 

      <div class="row">
        <div class="col-xs-6">
          {{-- <b>@lang('report.user'):</b> {{ $register_details->user_name}}<br> --}}
          <b>@lang('report.user'):</b> {{ auth()->user()->getUserNameById(auth()->user()->id) }}<br>
          <b>@lang('business.email'):</b> {{ auth()->user()->getUserById(auth()->user()->id)->email }}<br>
          {{-- <b>@lang('business.email'):</b> {{ $register_details->email}}<br> --}}
          <b>@lang('business.business_location'):</b> {{ $register_details->location_name}}<br>
        </div>
        @if(!empty($register_details->closing_note))
          <div class="col-xs-6">
            <strong>@lang('cash_register.closing_note'):</strong><br>
            {{$register_details->closing_note}}
          </div>
        @endif
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary no-print" aria-label="Print" onclick="$(this).closest('div.modal-content').printThis();">
        <i class="fa fa-print"></i> @lang( 'messages.print' )     
      </button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.cancel' )</button>
      <button type="submit" id="btn-1" class="btn btn-primary">@lang( 'cash_register.close_register' )</button>
    </div>
    {!! Form::close() !!}
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>

  function findDifference( x, y ) {
    if ( Math.sign( x ) === Math.sign( y ) ) {    
        return Math.abs( x - y );    
    } else {    
        return Math.abs( x ) + Math.abs( y );    
    };
  };
  console.log('aaa'+findDifference(-3, -3));  

  function formatToNumber(number) {
      var str = number;
      var t = str.replace(/[.]/g, "");
      var ft = t.replace(/[,]/g, ".");
      var num = parseFloat(ft);
      return num;
  }

  function formatToNumber1(number) {
        var str = number;
        var ft = str.replace(/[.]/g, ",");
        return ft;
    }

  function getDifference() {

        total_cash_money = parseFloat(formatToNumber(document.getElementById("total_cash_money").value));
        cash_payout = parseFloat(formatToNumber(document.getElementById("cash_payout").value));

        if (cash_payout > total_cash_money) {
          document.getElementById('btn-1').style.visibility = 'hidden';
          alert("Abschöpfung ("+cash_payout+") darf nicht größer als Gesamt ("+total_cash_money+") sein!");
          return false;
        }else{
          document.getElementById('btn-1').style.visibility = 'visible';
        }
        
        console.log(document.getElementById("total_cash_money").value);
        console.log(document.getElementById("cash_payout").value);

        
        // if (Number.isNaN(cash_payout) || Number.isNaN(amount)) {
        //     var divobj = document.getElementById('CashAmountDifference');
        //     divobj.value = '';
        //     return;
        // }

        var diff_cash_total = findDifference(total_cash_money, cash_payout);
        var divobj = document.getElementById('remaining_change');
        
        divobj.value = formatToNumber1(diff_cash_total.toFixed(2));
    }
</script>