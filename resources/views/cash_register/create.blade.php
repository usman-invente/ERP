@extends('layouts.app')
@section('title',  __('cash_register.open_cash_register'))

@section('content')
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('cash_register.open_cash_register')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>
<!-- Main content -->
<section class="content">
{!! Form::open(['url' => action([\App\Http\Controllers\CashRegisterController::class, 'store']), 'method' => 'post', 
'id' => 'add_cash_register_form' ]) !!}
  <div class="box box-solid">
    <div class="box-body">
    <br><br><br>
    <input type="hidden" name="sub_type" value="{{$sub_type}}">
      <div class="row">
        @if(count($business_locations) > 1)
          <div class="clearfix"></div>
          <div class="col-sm-8 col-sm-offset-2">
            <div class="form-group">
              {!! Form::label('location_id', __('business.business_location') . ':') !!}
                {!! Form::select('location_id', $business_locations, null, ['id'=> 'location_dropdown','class' => 'form-control select2',
                'placeholder' => __('lang_v1.select_location')]); !!}
                {{-- <select  name="location_id" id="location_dropdown" class="form-control">
                  <option value="">-- Select @lang('business.business_location') --</option>
                  @foreach ($business_locations as $data)
                  <option value="{{$data->id}}">
                      {{$data->name}}
                  </option>
                  @endforeach
              </select> --}}
            </div>
          </div>
          <div class="clearfix"></div>
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group mb-3">
                <select name="cash_register_id" id="cash-reg-dropdown" class="form-control">
                </select>
            </div>
          </div>
        @else
          {!! Form::hidden('location_id', array_key_first($business_locations->toArray()) ); !!}
          @if(count($cash_register_details) > 1)
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group mb-3">
                <select  name="cash_register_id" id="cash_register_dropdown" class="form-control">
                  <option value="">-- Select @lang('cash_register.cash_register') --</option>
                  @foreach ($cash_register_details as $data)
                  <option value="{{$data->id}}">
                      {{$data->name}}
                  </option>
                     
                  @endforeach
                </select>
                 
              </div>
            </div>
          @else
            {!! Form::hidden('cash_register_id', $first_cash_register_detail->id ); !!}    
          @endif                
        @endif
        {{-- @if(count($business_locations) > 1)
          <div class="clearfix"></div>
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group mb-3">
                <select name="cash_register_id" id="cash-reg-dropdown" class="form-control">
                </select>
            </div>
          </div>
        @else
          {!! Form::hidden('location_id', array_key_first($business_locations->toArray()) ); !!}
        @endif --}}
        @if($business_locations->count() > 0)
          @if(count($cash_register_details) > 1)
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group">
                {!! Form::label('amount', __('cash_register.cash_in_hand') . ':*') !!}
                {!! Form::text('amount', null, ['class' => 'form-control input_number',
                  'id' => 'close-amount-cash-reg',
                  'placeholder' => __('cash_register.enter_amount'), 'disabled']); !!}
              </div>
            </div>
           
          @else
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group">
                {!! Form::label('amount', __('cash_register.cash_in_hand') . ':*') !!}
                {!! Form::text('amount', $close_amount_first_cach_register, ['class' => 'form-control input_number', 'id'=> 'amount',
                  'placeholder' => __('cash_register.enter_amount'), 'disabled']); !!}
              </div>
            </div>
            {{-- {{ $business_id.' '.$first_cash_register_detail->id }} --}}
            {{-- {{ $cashRegisterUtil->IfCashRegisterOpenIs($business_id, $first_cash_register_detail->id) }} --}}
          @endif
          @if(!$cashRegisterUtil->IfCashRegisterOpenIs($business_id, $first_cash_register_detail->id))
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group">
                {!! Form::label('correction_last_cash_amount', __('cash_register.correction_last_cash_amount'). ':') !!}
                {{-- {!! Form::text('correction_last_cash_amount', null, ['class' => 'form-control input_number', 'id'=> 'correction_last_cash_amount',
                  'placeholder' => "Gezählt heute" ]); !!} --}}

              <div>
                <input onchange=" getDifference()"
                  type="text" name="correction_last_cash_amount" id="correction_last_cash_amount"
                  class="form-control input_number" placeholder="{{ __('cash_register.correction_last_cash_amount') }}"
                  type="text"
                  value=""
                  >
              </div>
              </div>
              
              @php
                $div_style = 'display: none;';
              @endphp
              <div  id="difference" class="form-group">
                {!! Form::label('difference', __('cash_register.difference') . ':') !!}
                {{-- {!! Form::text('difference', null, ['class' => 'form-control input_number',
                  'placeholder' => "Differenz" ,'readonly']); !!} --}}
                <div>                  
                  <input disabled type="text" class="form-control" name="CashAmountDifference"
                                        id="CashAmountDifference" value=" ">
                </div>
              </div>
              <div style="{{ $div_style }}" id="correction_description" class="form-group">
                {!! Form::label('correction_description', __('cash_register.correction_description') . ':*') !!}
                {!! Form::textarea('correction_description', null, ['class' => 'form-control input_number',
                'required', 'placeholder' =>  __('cash_register.placeholder_correction_description') ]); !!}
              </div>
            </div>
          @else
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group">
                Bitte klicken auf dem Button "Kasse eröffnen", um weiter zu gehen!
              </div>
            </div>
          @endif

          <div class="col-sm-8 col-sm-offset-2">
            <button type="submit" class="btn btn-primary pull-right">@lang('cash_register.open_register')</button>
          </div>
          @else
            <div class="col-sm-8 col-sm-offset-2 text-center">
              <h3>@lang('lang_v1.no_location_access_found')</h3>
            </div>
        @endif
      </div>
      <br><br><br>
    </div>
  </div>
  {!! Form::close() !!}
</section>
<!-- /.content -->
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).ready(function () {
    $('#location_dropdown').on('change', function () {
        var idLocation = this.value;
        console.log(idLocation);
        $("#cash-reg-dropdown").html('');
        $.ajax({
            url: "{{url('cash-register-detail/all-cash-registers')}}",
            type: "POST",
            data: {
                location_id: idLocation,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (result) {
                $('#cash-reg-dropdown').html('<option value="">-- Select  @lang('cash_register.cash_register')  --</option>');
                $.each(result.cash_registers, function (key, value) {
                    $("#cash-reg-dropdown").append('<option value="' + value
                        .id + '">' + value.name + '</option>');
                });
            }
        });
    });

    $('#cash-reg-dropdown, #cash_register_dropdown').on('change', function () {
        var idCashRegisterDetail = this.value;
        console.log(idCashRegisterDetail);
        if (idCashRegisterDetail) {
          $("#close-amount-cash-reg").html('');
          $.ajax({
              url: "{{url('cash-register/close-amount-cash-registers')}}",
              type: "POST",
              data: {
                cash_register_detail_id: idCashRegisterDetail,
                  _token: '{{csrf_token()}}'
              },
              dataType: 'json',
              success: function (result) {
                // parseFloat nicht mehr nötig, da amount schon Zahl ist
              var formattedQuote = result.amount.toLocaleString('de-DE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
              });
            
              $('#close-amount-cash-reg').val(formattedQuote);
              getDifference(); // << neu
              }
          });
        } 
    });

    let rawDigits = '';
    const input = $('#correction_last_cash_amount');
    //input.val('0,00');
    // Wenn das Input-Feld leer ist, setze den Wert auf '0,00'
    if (!input.val()) {
      input.val('0,00');
    }

    // Tastendruck abfangen
    input.on('keydown', function (e) {
      // Erlaubte Tasten: Zahlen (0-9) und Backspace
      if (e.key >= '0' && e.key <= '9') {
        rawDigits += e.key;
        e.preventDefault(); // blockiere Standardverhalten
      } else if (e.key === 'Backspace') {
        rawDigits = rawDigits.slice(0, -1);
        e.preventDefault(); // blockiere Standardverhalten
      } else if (['Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
        // Diese Tasten erlauben wir
        return;
      } else {
        e.preventDefault(); // alles andere blockieren (auch Punkt, Komma etc.)
      }

      updateFormattedValue();
      getDifference(); 
    });

    function updateFormattedValue() {
      if (rawDigits.length === 0) {
        input.val('0,00');
        return;
      }
    
      // Mit führenden Nullen auffüllen
      while (rawDigits.length < 3) {
        rawDigits = '0' + rawDigits;
      }
    
      const cents = rawDigits.slice(-2);
      const euros = rawDigits.slice(0, -2);
    
      const euroFormatted = parseInt(euros, 10).toLocaleString('de-DE');
      const formattedValue = `${euroFormatted},${cents}`;
    
      input.val(formattedValue);
    }

    $('#add_cash_register_form').on('submit', function () {
      const raw = input.val().replace(/\./g, '').replace(',', '.');
      input.val(raw);
    });
  });
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
    correction_last_cash_amount = parseFloat(formatToNumber(document.getElementById("correction_last_cash_amount").value));
    var element = document.getElementById('close-amount-cash-reg');
    var amount = element ? parseFloat(formatToNumber(element.value)) : null;

    if (document.getElementById("correction_last_cash_amount").value == '') {
        document.getElementById('correction_description').style.display = 'none';  
        document.getElementById('CashAmountDifference').value = '';
        document.getElementById('correction_description_input').removeAttribute('required');
        return;            
    } else { 
        document.getElementById('correction_description').style.display = '';
    }       

    console.log(document.getElementById("correction_last_cash_amount").value);
            // console.log(document.getElementById("amount").value);

    correction_last_cash_amount = parseFloat(formatToNumber(document.getElementById("correction_last_cash_amount").value));
        // amount = parseFloat(formatToNumber(document.getElementById("amount").value));

    if (Number.isNaN(correction_last_cash_amount) || Number.isNaN(amount)) {
      document.getElementById('CashAmountDifference').value = '';
      return;
    }

    // var diff_cash_amount = findDifference(correction_last_cash_amount, amount);
    // var divobj = document.getElementById('CashAmountDifference');
    var diff_cash_amount = findDifference(correction_last_cash_amount, amount);
    document.getElementById('CashAmountDifference').value = formatToNumber1(diff_cash_amount.toFixed(2));

    // Prüfen, ob die Differenz 0 ist und required setzen oder entfernen
    if (diff_cash_amount === 0) {
      document.getElementById('correction_description').style.display = 'none';
      document.getElementById('correction_description_input').removeAttribute('required');
    } else {
      document.getElementById('correction_description').style.display = 'block';
      document.getElementById('correction_description_input').setAttribute('required', 'required');
    }
    
    // divobj.value = formatToNumber1(diff_cash_amount.toFixed(2));
  }
</script>