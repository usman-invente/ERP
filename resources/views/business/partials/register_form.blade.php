{{-- ===================================================== --}}
{{-- SCHRITT 1: UNTERNEHMENSINFORMATIONEN --}}
{{-- ===================================================== --}}
@if(empty($is_admin))
    <h3>@lang('business.business')</h3>
@endif
{!! Form::hidden('language', request()->lang); !!}

<fieldset>
    <legend>@lang('business.business_details'):</legend>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('name', __('business.business_name') . ':*' ) !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-suitcase"></i>
                </span>
                {!! Form::text('name', null, ['class' => 'form-control','placeholder' => __('business.business_name'), 'required']); !!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('business_logo', __('business.upload_logo') .' ('. __('lang_v1.logo_size').  ') :') !!}
            {!! Form::file('business_logo', ['accept' => 'image/*']); !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('mobile', __('lang_v1.business_telephone') . ':') !!}
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-phone"></i>
            </span>
            {!! Form::text('mobile', null, ['class' => 'form-control','placeholder' => __('lang_v1.business_telephone')]); !!}
        </div>
        </div>
    </div>    
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('currency_id', __('business.currency') . ':*') !!}
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            {!! Form::select('currency_id', $currencies, '', ['class' => 'form-control select2_register','placeholder' => __('business.currency_placeholder'), 'required']); !!}
        </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('business_email', __('business.email') . ':*') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-envelope"></i>
                </span>
                 {!! Form::email('business_email', null, ['class' => 'form-control','placeholder' => __('business.email')]); !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('website', __('lang_v1.website') . ':') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-globe"></i>
                </span>
                {!! Form::text('website', null, ['class' => 'form-control','placeholder' => 'https://'.__('lang_v1.website').'.com']); !!}
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('street',__('business.street'). ':*') !!}
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            {!! Form::text('street', null, ['class' => 'form-control','placeholder' => __('business.street'), 'required']); !!}
        </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('house_nr', __('business.house_nr') . ':*') !!}
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            {!! Form::text('house_nr', null, ['class' => 'form-control','placeholder' => __('business.house_nr'), 'required']); !!}
        </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('city',__('business.city'). ':*') !!}
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            {!! Form::text('city', null, ['class' => 'form-control','placeholder' => __('business.city'), 'required']); !!}
        </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('zip_code', __('business.zip_code') . ':*') !!}
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            {!! Form::text('zip_code', null, ['class' => 'form-control','placeholder' => __('business.zip_code_placeholder'), 'required']); !!}
        </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('state',__('business.state') . ':') !!}
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
            </span>
            {!! Form::text('state', null, ['class' => 'form-control','placeholder' => __('business.state')]); !!}
        </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
        {!! Form::label('country', __('business.country') . ':*') !!}
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-globe"></i>
            </span>
            {!! Form::text('country', null, ['class' => 'form-control','placeholder' => __('business.country'), 'required']); !!}
        </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('time_zone', __('business.time_zone') . ':*') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-clock"></i>
                </span>
                {!! Form::select('time_zone', $timezone_list, config('app.timezone'), ['class' => 'form-control select2_register','placeholder' => __('business.time_zone'), 'required']); !!}
            </div>
        </div>
    </div>
</fieldset>

{{-- ===================================================== --}}
{{-- SCHRITT 2: UNTERNEHMENSEINSTELLUNGEN --}}
{{-- ===================================================== --}}
@if(empty($is_admin))
    <h3>@lang('business.business_settings')</h3>

    <fieldset>
        <legend>@lang('business.business_settings'):</legend>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('tax_number_1', __('business.tax_1_no') . ':') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    {!! Form::text('tax_number_1', null, ['class' => 'form-control']); !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('tax_number_2',__('business.tax_2_no') . ':') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-info"></i>
                    </span>
                    {!! Form::text('tax_number_2', null, ['class' => 'form-control',]); !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('fy_start_month', __('business.fy_start_month') . ':*') !!} @show_tooltip(__('tooltip.fy_start_month'))
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    {!! Form::select('fy_start_month', $months, null, ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;']); !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('accounting_method', __('business.accounting_method') . ':*') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calculator"></i>
                    </span>
                    {!! Form::select('accounting_method', $accounting_methods, null, ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;']); !!}
                </div>
            </div>
        </div>
    </fieldset>
@endif

{{-- ===================================================== --}}
{{-- SCHRITT 3: INHABER-INFORMATIONEN --}}
{{-- ===================================================== --}}
@if(empty($is_admin))
    <h3>@lang('business.owner')</h3>
@endif

<fieldset>
    <legend>@lang('business.owner_info')</legend>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('surname', __('business.prefix') . ':') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                </span>
                {!! Form::text('surname', null, ['class' => 'form-control','placeholder' => __('business.prefix_placeholder')]); !!}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('first_name', __('business.first_name') . ':*') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                </span>
                {!! Form::text('first_name', null, ['class' => 'form-control','placeholder' => __('business.first_name'), 'required']); !!}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('last_name', __('business.last_name') . ':') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-info"></i>
                </span>
                {!! Form::text('last_name', null, ['class' => 'form-control','placeholder' =>  __('business.last_name')]); !!}
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('username', __('business.username') . ':*') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                {!! Form::text('username', null, ['class' => 'form-control','placeholder' => __('business.username'), 'required']); !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('email', __('business.email') . ':*') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-envelope"></i>
                </span>
                {!! Form::text('email', null, ['class' => 'form-control','placeholder' => __('business.email'), 'required', 'id' => 'owner_email']); !!}
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('password', __('business.password') . ':*') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-lock"></i>
                </span>
                {!! Form::password('password', ['class' => 'form-control','placeholder' => __('business.password'), 'required']); !!}
            </div>
             {{-- ========================================================== --}}
        {{-- HIER DIE LÖSUNG HINZUFÜGEN --}}
        {{-- ========================================================== --}}
        @error('email')
            <span class="text-danger">
                {{ $message }} 
                <br>
                <strong>Besitzen Sie bereits ein Konto? <a href="{{ route('login') }}">Hier einloggen</a>.</strong>
            </span>
        @enderror
        {{-- ========================================================== --}}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('confirm_password', __('business.confirm_password') . ':*') !!}
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-lock"></i>
                </span>
                {!! Form::password('confirm_password', ['class' => 'form-control','placeholder' => __('business.confirm_password'), 'required']); !!}
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        @if(!empty($system_settings['superadmin_enable_register_tc']))
            <div class="form-group">
                <label>
                    {!! Form::checkbox('accept_tc', 0, false, ['required', 'class' => 'input-icheck']); !!}
                    <u><a class="terms_condition cursor-pointer" data-toggle="modal" data-target="#tc_modal">
                        @lang('lang_v1.accept_terms_and_conditions') <i></i>
                    </a></u>
                </label>
            </div>
            @include('business.partials.terms_conditions')
        @endif
    </div>
    <div class="clearfix"></div>
</fieldset>


{{-- ===================================================== --}}
{{-- SCHRITT 4: ZAHLUNG (NEU INTEGRIERT) --}}
{{-- ===================================================== --}}
@if(empty($is_admin))
    <h3>Zahlung</h3>
@endif

<fieldset>
    <legend>Zahlungsinformationen</legend>
    
    {{-- Custom styles for the payment layout --}}
    <style>
        /* General Layout */
        .payment-container {
            display: flex;  
            gap: 40px;
            max-width: 1100px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .left-column, .right-column { padding: 0; }
        .left-column { flex: 1; padding-right: 20px; }
        .right-column { flex: 1.7; }

        /* Summary Section Styling */
        .summary-header { font-size: 1.5rem; font-weight: 500; margin-bottom: 20px; color: #333; }
        .new-business-info { display: flex; align-items: center; margin-bottom: 10px; }
        .new-business-info h5 { margin-bottom: 0; font-weight: 600; }
        .new-business-info .badge { margin-left: 8px; background-color: #e0e0e0; color: #555; padding: 5px 10px; border-radius: 4px; font-weight: normal; font-size: 0.85em; }
        .summary-details p { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.95em; color: #555; }
        .summary-details p strong { font-weight: 600; color: #333; }
        .summary-details hr { border-top: 1px solid #eee; margin: 15px 0; }
        .total-summary { font-size: 1.1em; font-weight: 700; color: #333; margin-top: 15px; }

        /* Payment Method Buttons */
        .payment-method-options { margin-bottom: 20px; }
        .payment-method-item { display: flex; align-items: center; border: 1px solid #dee2e6; border-radius: 5px; padding: 10px 15px; margin-bottom: 10px; cursor: pointer; transition: border-color 0.2s ease, box-shadow 0.2s ease; position: relative; }
        .payment-method-item:hover { border-color: #007bff; }
        .payment-method-item input[type="radio"] { -webkit-appearance: none; -moz-appearance: none; appearance: none; width: 18px; height: 18px; border: 1px solid #ced4da; border-radius: 50%; margin-right: 15px; flex-shrink: 0; position: relative; cursor: pointer; outline: none; }
        .payment-method-item input[type="radio"]:checked { border-color: #007bff; background-color: #007bff; }
        .payment-method-item input[type="radio"]:checked::after { content: ''; width: 8px; height: 8px; background-color: #fff; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: block; }
        .payment-method-item.selected { border-color: #007bff; box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); }
        .payment-method-content { display: flex; align-items: center; flex-grow: 1; }
        .payment-method-content .method-name { font-weight: 500; color: #333; flex-grow: 1; }
        .payment-method-content .method-logos img { max-height: 25px; margin-left: 5px; vertical-align: middle; }
        .payment-method-content .method-logos img[alt="PayPal"], .payment-method-content .method-logos img[alt="SEPA"] { max-height: 28px; }

        /* General Form Styling */
        .form-label { font-weight: 600; color: #333; margin-bottom: 8px; }
        .form-control { border-radius: 5px; padding: 10px 15px; }
        .btn-primary { background-color: #007bff; border-color: #007bff; padding: 12px 20px; font-size: 1.1em; border-radius: 5px; font-weight: 600; }
        .btn-primary:hover { background-color: #0056b3; border-color: #0056b3; }
        .text-muted.small { font-size: 0.85em; line-height: 1.4; }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .payment-container { flex-direction: column; padding: 20px; margin: 20px auto; }
            .left-column { padding-right: 0; margin-bottom: 20px; }
            .right-column { padding-left: 0; }
        }
    </style>

    <div class="payment-container">
        {{-- Linke Seite: Zusammenfassung --}}
        <div class="left-column">
            <h3 class="summary-header">Stripe Zahlung</h3>
            <div class="new-business-info">
                <h5>NEOBURG GmbH <span class="badge">Sandbox</span></h5>
            </div>
            <small class="text-muted">pro Monat</small>
            <hr>
            <div class="summary-details">
                <p>erste Produkt: <strong>1,00 €</strong></p>
                <p>Zwischensumme: <strong>1,00 €</strong></p>
                <p>Steuer: <strong>0,00 €</strong></p>
            </div>
            <hr>
            <p class="total-summary"><strong>Heute fällige Gesamtsumme: 1,00 €</strong></p>
        </div>

        {{-- Rechte Seite: Zahlungsmethoden --}}
        <div class="right-column">
            
            {{-- Zahlungsmethode --}}
            <label class="form-label">Zahlungsmethode</label>
            <div class="payment-method-options">

                {{-- Kreditkarte --}}
                <label class="payment-method-item" for="card_radio">
                    <input type="radio" name="payment_method" id="card_radio" value="card" autocomplete="off" checked>
                    <div class="payment-method-content">
                        <span class="method-name">Kreditkarte</span>
                        <div class="method-logos">
                            <img src="https://img.icons8.com/color/32/visa.png" alt="Visa" />
                            <img src="https://img.icons8.com/color/32/mastercard-logo.png" alt="Mastercard" />
                            <img src="https://img.icons8.com/color/32/amex.png" alt="Amex" />
                        </div>
                    </div>
                </label>

                {{-- PayPal --}}
                <label class="payment-method-item" for="paypal_radio">
                    <input type="radio" name="payment_method" id="paypal_radio" value="paypal" autocomplete="off">
                    <div class="payment-method-content">
                        <span class="method-name">PayPal</span>
                        <div class="method-logos">
                            <img src="https://img.icons8.com/ios-filled/32/paypal.png" alt="PayPal" />
                        </div>
                    </div>
                </label>

                {{-- SEPA --}}
                <label class="payment-method-item" for="sepa_radio">
                    <input type="radio" name="payment_method" id="sepa_radio" value="sepa" autocomplete="off">
                    <div class="payment-method-content">
                        <span class="method-name">SEPA</span>
                        <div class="method-logos">
                            <img src="https://img.icons8.com/ios/32/bank.png" alt="SEPA" />
                        </div>
                    </div>
                </label>
            </div>

            {{-- Checkbox --}}
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="saveData" required>
                <label class="form-check-label" for="saveData" style="font-weight: 500;">
                    Meine Daten sicher speichern für Zahlungsvorgänge mit einem Klick
                </label>
            </div>

            {{-- Button --}}
            {{-- DIESER BUTTON IST NUN DER ABSENDE-BUTTON FÜR DAS GESAMTE FORMULAR --}}
            <button type="submit" class="btn btn-primary w-100">
                Zahlungspflichtig abonnieren
            </button>

            {{-- Info-Text --}}
            <p class="text-muted mt-2 small">
                Indem Sie Ihr Abonnement bestätigen, gestatten Sie New business Sandbox, zukünftige Zahlungen Ihrem Zahlungsmittel gemäß den allgemeinen Geschäftsbedingungen zu belasten. Sie können Ihr Abonnement jederzeit kündigen.
            </p>
        </div>
    </div>
</fieldset>

<script>
    // JavaScript to add 'selected' class to the parent label when a radio button is checked
    document.addEventListener('DOMContentLoaded', function() {
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

        function updateSelectedClass() {
            paymentRadios.forEach(radio => {
                const parentLabel = radio.closest('.payment-method-item');
                if (radio.checked) {
                    parentLabel.classList.add('selected');
                } else {
                    parentLabel.classList.remove('selected');
                }
            });
        }

        // Add event listeners
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', updateSelectedClass);
        });

        // Set initial state
        updateSelectedClass();
    });
</script>