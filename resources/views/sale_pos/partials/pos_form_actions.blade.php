@php
    $is_mobile = isMobile();
@endphp
<div class="row">
    <div
        class="pos-form-actions tw-rounded-tr-xl tw-rounded-tl-xl tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-bg-white tw-cursor-pointer">
        <div
            class="tw-flex tw-items-center tw-justify-between tw-flex-col sm:tw-flex-row md:tw-flex-row lg:tw-flex-row xl:tw-flex-row tw-gap-2 tw-px-4 tw-py-0 tw-overflow-x-auto tw-w-full">

            <div class="md:!tw-w-none !tw-flex md:!tw-hidden !tw-flex-row !tw-items-center !tw-gap-3">
                <div class="tw-pos-total tw-flex tw-items-center tw-gap-3">
                    <div class="tw-text-black tw-font-bold tw-text-sm tw-flex tw-items-center tw-flex-col tw-leading-1">
                        <div>@lang('sale.total_payable'):</div>
                        {{-- <div>Payable:</div> --}}
                    </div>
                    <input type="hidden" name="final_total" id="final_total_input" value="0.00">
                    <span id="total_payable" class="tw-text-green-900 tw-font-bold tw-text-sm number">0.00</span>
                </div>
            </div>

            <div class="!tw-w-full md:!tw-w-none !tw-flex md:!tw-hidden !tw-flex-row !tw-items-center !tw-gap-3">
                @if (!Gate::check('disable_pay_checkout') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class=" tw-flex tw-flex-row tw-items-center tw-justify-center tw-gap-1 tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-[#001F3E] tw-rounded-md tw-p-2 tw-w-[8.5rem] @if (!$is_mobile)  @endif no-print @if ($pos_settings['disable_pay_checkout'] != 0) hide @endif"
                        id="pos-finalize" title="@lang('lang_v1.tooltip_checkout_multi_pay')"><i class="fas fa-money-check-alt"
                            aria-hidden="true"></i> @lang('lang_v1.checkout_multi_pay') </button>
                @endif

                @if (!Gate::check('disable_express_checkout') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-[rgb(40,183,123)] tw-p-2 tw-rounded-md tw-w-[5.5rem] tw-flex tw-flex-row tw-items-center tw-justify-center tw-gap-1 @if (!$is_mobile)  @endif no-print @if ($pos_settings['disable_express_checkout'] != 0 || !array_key_exists('cash', $payment_types)) hide @endif pos-express-finalize @if ($is_mobile) col-xs-6 @endif"
                        data-pay_method="cash" title="@lang('tooltip.express_checkout')"> <i class="fas fa-money-bill-alt"
                            aria-hidden="true"></i> @lang('lang_v1.express_checkout_cash')</button>
                @endif
                @if (empty($edit))
                    <button type="button" class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-red-600 tw-p-2 tw-rounded-md tw-w-[5.5rem] tw-flex tw-flex-row tw-items-center tw-justify-center tw-gap-1" id="pos-cancel"> <i
                            class="fas fa-window-close"></i> @lang('sale.cancel')</button>
                @else
                    <button type="button" class="btn-danger tw-dw-btn hide tw-dw-btn-xs" id="pos-delete"
                        @if (!empty($only_payment)) disabled @endif> <i class="fas fa-trash-alt"></i>
                        @lang('messages.delete')</button>
                @endif
            </div>
            <div class="tw-flex tw-items-center tw-gap-4 tw-flex-row tw-overflow-x-auto">

                @if (!Gate::check('disable_draft') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-font-bold tw-text-gray-700 tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 @if ($pos_settings['disable_draft'] != 0) hide @endif"
                        id="pos-draft" @if (!empty($only_payment)) disabled @endif><i
                            class="fas fa-edit tw-text-[#009ce4]"></i> @lang('sale.draft')</button>
                @endif

                @if (!Gate::check('disable_quotation') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-font-bold tw-text-gray-700 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 @if ($is_mobile) col-xs-6 @endif"
                        id="pos-quotation" @if (!empty($only_payment)) disabled @endif><i
                            class="fas fa-edit tw-text-[#E7A500]"></i> @lang('lang_v1.quotation')</button>
                @endif

                @if (!Gate::check('disable_suspend_sale') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    @if (empty($pos_settings['disable_suspend']))
                        <button type="button"
                            class="tw-font-bold tw-text-gray-700 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1  no-print pos-express-finalize"
                            data-pay_method="suspend" title="@lang('lang_v1.tooltip_suspend')"
                            @if (!empty($only_payment)) disabled @endif>
                            <i class="fas fa-pause tw-text-[#EF4B51]" aria-hidden="true"></i>
                            @lang('lang_v1.suspend')
                        </button>
                    @endif
                @endif

                @if (!Gate::check('disable_credit_sale') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    @if (empty($pos_settings['disable_credit_sale_button']))
                        <input type="hidden" name="is_credit_sale" value="0" id="is_credit_sale">
                        <button type="button"
                            class=" tw-font-bold tw-text-gray-700 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 no-print pos-express-finalize @if ($is_mobile) col-xs-6 @endif"
                            data-pay_method="credit_sale" title="@lang('lang_v1.tooltip_credit_sale')"
                            @if (!empty($only_payment)) disabled @endif>
                            <i class="fas fa-check tw-text-[#5E5CA8]" aria-hidden="true"></i> @lang('lang_v1.credit_sale')
                        </button>
                    @endif
                @endif
                @if (!Gate::check('disable_card') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-font-bold tw-text-gray-700 tw-cursor-pointer tw-text-xs md:tw-text-sm tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1  no-print @if (!empty($pos_settings['disable_suspend']))  @endif pos-express-finalize @if (!array_key_exists('card', $payment_types)) hide @endif @if ($is_mobile) col-xs-6 @endif"
                        data-pay_method="card" title="@lang('lang_v1.tooltip_express_checkout_card')">
                        <i class="fas fa-credit-card tw-text-[#D61B60]" aria-hidden="true"></i> @lang('lang_v1.express_checkout_card')
                    </button>
                @endif

                @if (!Gate::check('disable_pay_checkout') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-hidden md:tw-flex md:tw-flex-row md:tw-items-center md:tw-justify-center md:tw-gap-1 tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-[#001F3E] tw-rounded-md tw-p-2 tw-w-[8.5rem] @if (!$is_mobile)  @endif no-print @if ($pos_settings['disable_pay_checkout'] != 0) hide @endif"
                        id="pos-finalize" title="@lang('lang_v1.tooltip_checkout_multi_pay')"><i class="fas fa-money-check-alt"
                            aria-hidden="true"></i> @lang('lang_v1.checkout_multi_pay') </button>
                @endif

                {{-- @if (!Gate::check('disable_express_checkout') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button type="button"
                        class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-[rgb(40,183,123)] tw-p-2 tw-rounded-md tw-w-[8.5rem] tw-hidden md:tw-flex lg:tw-flex lg:tw-flex-row lg:tw-items-center lg:tw-justify-center lg:tw-gap-1 @if (!$is_mobile)  @endif no-print @if ($pos_settings['disable_express_checkout'] != 0 || !array_key_exists('cash', $payment_types)) hide @endif pos-express-finalize"
                        data-pay_method="cash" title="@lang('tooltip.express_checkout')"> <i class="fas fa-money-bill-alt"
                            aria-hidden="true"></i> @lang('lang_v1.express_checkout_cash')</button>
                @endif --}}
                @if (!Gate::check('disable_express_checkout') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
                    <button  id="openCalculator" type="button"
                        class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-[rgb(40,183,123)] tw-p-2 tw-rounded-md tw-w-[8.5rem] tw-hidden md:tw-flex lg:tw-flex lg:tw-flex-row lg:tw-items-center lg:tw-justify-center lg:tw-gap-1 
                        @if (!$is_mobile)  @endif no-print 
                        @if ($pos_settings['disable_express_checkout'] != 0 || !array_key_exists('cash', $payment_types)) hide @endif 
                        title="@lang('tooltip.express_checkout')"> <i class="fas fa-money-bill-alt"
                            aria-hidden="true"></i> @lang('lang_v1.express_checkout_cash')</button>
                @endif


                @if (empty($edit))
                    <button type="button"
                        class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-red-600 tw-p-2 tw-rounded-md tw-w-[8.5rem] tw-hidden md:tw-flex lg:tw-flex lg:tw-flex-row lg:tw-items-center lg:tw-justify-center lg:tw-gap-1"
                        id="pos-cancel"> <i class="fas fa-window-close"></i> @lang('sale.cancel')</button>
                @else
                    <button type="button"
                        class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-red-600 tw-p-2 tw-rounded-md tw-w-[8.5rem] tw-hidden md:tw-flex lg:tw-flex lg:tw-flex-row lg:tw-items-center lg:tw-justify-center lg:tw-gap-1 hide"
                        id="pos-delete" @if (!empty($only_payment)) disabled @endif> <i
                            class="fas fa-trash-alt"></i> @lang('messages.delete')</button>
                @endif

                @if (!$is_mobile)
                    {{-- <div class="bg-navy pos-total text-white ">
					<span class="text">@lang('sale.total_payable')</span>
					<input type="hidden" name="final_total" 
												id="final_total_input" value=0>
					<span id="total_payable" class="number">0</span>
					</div> --}}
                    <div class="pos-total md:tw-flex md:tw-items-center md:tw-gap-3 tw-hidden">
                        <div
                            class="tw-text-black tw-font-bold tw-text-base md:tw-text-2xl tw-flex tw-items-center tw-flex-col">
                            <div>@lang('sale.total')</div>
                            <div>@lang('lang_v1.payable'):</div>
                        </div>
                        <input type="hidden" name="final_total" id="final_total_input" value="0.00">
                        <span id="total_payable"
                            class="tw-text-green-900 tw-font-bold tw-text-base md:tw-text-2xl number">0.00</span>
                    </div>
                @endif
            </div>

            <div class="tw-w-full md:tw-w-fit tw-flex tw-flex-col tw-items-end tw-gap-3 tw-hidden md:tw-block">
                @if (!isset($pos_settings['hide_recent_trans']) || $pos_settings['hide_recent_trans'] == 0)
                    <button type="button"
                        class="tw-font-bold tw-bg-[#646EE4] hover:tw-bg-[#414aac] tw-rounded-full tw-text-white tw-w-full md:tw-w-fit tw-px-5 tw-h-11 tw-cursor-pointer tw-text-xs md:tw-text-sm"
                        data-toggle="modal" data-target="#recent_transactions_modal" id="recent-transactions"> <i
                            class="fas fa-clock"></i> @lang('lang_v1.recent_transactions')</button>
                @endif
            </div>
        </div>
    </div>
</div>
@if (isset($transaction))
    @include('sale_pos.partials.edit_discount_modal', [
        'sales_discount' => $transaction->discount_amount,
        'discount_type' => $transaction->discount_type,
        'rp_redeemed' => $transaction->rp_redeemed,
        'rp_redeemed_amount' => $transaction->rp_redeemed_amount,
        'max_available' => !empty($redeem_details['points']) ? $redeem_details['points'] : 0,
    ])
@else
    @include('sale_pos.partials.edit_discount_modal', [
        'sales_discount' => $business_details->default_sales_discount,
        'discount_type' => 'percentage',
        'rp_redeemed' => 0,
        'rp_redeemed_amount' => 0,
        'max_available' => 0,
    ])
@endif

@if (isset($transaction))
    @include('sale_pos.partials.edit_order_tax_modal', ['selected_tax' => $transaction->tax_id])
@else
    @include('sale_pos.partials.edit_order_tax_modal', [
        'selected_tax' => $business_details->default_sales_tax,
    ])
@endif

@include('sale_pos.partials.edit_shipping_modal')
@include('sale_pos.partials.calculator')

<script>
	document.addEventListener('DOMContentLoaded', function () {
        const display = document.getElementById('calculatorDisplay'); // Eingabefeld für den Betrag
        const changeDisplay = document.getElementById('changeDisplay'); // Anzeige für Wechselgeld
        const totalPayableDisplay = document.getElementById('totalPayableDisplay'); // Anzeige für Gesamtbetrag
        const buttons = document.querySelectorAll('.calculator-btn'); // Alle Taschenrechner-Buttons
        const submitCalculator = document.getElementById('submitCalculator'); // Bestätigen-Button
        const openCalculator = document.getElementById('openCalculator'); // Öffne-Taschenrechner-Button

        // Funktion zum Abrufen des zu zahlenden Betrags
        function getTotalPayable() {
            const totalPayableInput = document.getElementById('final_total_input');
            const value = parseFloat(totalPayableInput.value.replace('.', '').replace(',', '.') || '0');
            return value;
        }

        // Funktion zur Formatierung von Beträgen (Tausender-Punkt, Dezimal-Komma)
        function formatCurrency(amount) {
            return amount
                .toFixed(2) // Zwei Dezimalstellen
                .replace('.', ',') // Dezimalpunkt durch Komma ersetzen
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tausendertrennzeichen mit Punkt
        }

        // Funktion zur Echtzeit-Berechnung von Wechselgeld/Restbetrag
        function updateChangeDisplay() {
            const enteredAmount = parseFloat(display.value.replace('.', '').replace(',', '.') || '0'); // Eingabe mit Komma/Punkt behandeln
            const totalPayable = getTotalPayable();
            const change = enteredAmount - totalPayable;

            if (isNaN(enteredAmount)) {
                changeDisplay.textContent = 'Ungültige Eingabe';
            } else if (change >= 0) {
                changeDisplay.textContent = `Wechselgeld: €${formatCurrency(change)}`;
                changeDisplay.classList.remove('text-danger');
                changeDisplay.classList.add('text-success');
            } else {
                changeDisplay.textContent = `Noch zu zahlen: €${formatCurrency(Math.abs(change))}`;
                changeDisplay.classList.remove('text-success');
                changeDisplay.classList.add('text-danger');
            }
        }

        // Öffne Taschenrechner und zeige aktuellen Betrag
        openCalculator.addEventListener('click', () => {
            $('#calculatorModal').modal('show');

            // Initialwert setzen
            const input = $('#calculatorDisplay');
            const id = input.attr('id');
            rawDigitMap[id] = ''; // Reset intern gespeicherte rohen Ziffern
            updateFormattedValue(input, rawDigitMap[id]); // Zeige 0,00 im Input

            changeDisplay.textContent = ''; // Wechselgeld zurücksetzen
            const totalPayable = getTotalPayable();
            totalPayableDisplay.textContent = `Gesamtbetrag: €${formatCurrency(totalPayable)}`;
            display.focus();
        });


        // Taschenrechner-Buttons
        buttons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const value = button.getAttribute('data-value');
                const input = $('#calculatorDisplay');
                const id = input.attr('id');
                let rawDigits = rawDigitMap[id] || '';
            
                if (value === 'C') {
                    // Letztes Zeichen entfernen
                    rawDigits = rawDigits.slice(0, -1);
                } else if (value === 'CE') {
                    // Alles löschen
                    rawDigits = '';
                } else if (value === '00') {
                    // Cent auf 00 setzen
                    if (rawDigits.length < 3) {
                        rawDigits = ''; // alles löschen, wenn zu wenig Stellen
                    } else {
                        rawDigits = rawDigits.slice(0, -2) + '00'; // letzte zwei Ziffern ersetzen
                    }
                } else if (/^\d$/.test(value)) {
                    // Nur Ziffern (0-9) zulassen
                    rawDigits += value;
                }
            
                rawDigitMap[id] = rawDigits;
                updateFormattedValue(input, rawDigits);
                updateChangeDisplay();
            });
        });


        // Direktes Event für Eingabefeld (Tastatureingaben, Copy-Paste usw.)
        display.addEventListener('input', () => {
            updateChangeDisplay();
        });

	    $('#calculatorModal').on('shown.bs.modal', function () {
            display.focus(); // Der Input erhält den Fokus
        });


        // Zahlung bestätigen
        submitCalculator.addEventListener('click', () => {
            // const enteredAmount = parseFloat(display.value.replace('.', '').replace(',', '.') || '0');
            const enteredAmount = parseFloat($('#calculatorDisplay').val().replace('.', '').replace(',', '.') || '0');

            const totalPayable = getTotalPayable();
            const change = enteredAmount - totalPayable;

            if (enteredAmount >= totalPayable) {
                // alert(`Zahlung erfolgreich. Wechselgeld: €${formatCurrency(change)}`);
                $('#calculatorModal').modal('hide');
                // Logik für Zahlungsabschluss hier hinzufügen (z. B. AJAX, Server-Request)
	    		// Simuliere den Klick auf den "pos-express-finalize"-Button
                const expressFinalizeButton = document.querySelector('.pos-express-finalize');
                if (expressFinalizeButton) {
                    expressFinalizeButton.click();
                } else {
                    console.error('Button mit der Klasse pos-express-finalize wurde nicht gefunden.');
                }
            } else {
                alert('Der gezahlte Betrag ist nicht ausreichend.');
            }
        });
        
        const rawDigitMap = {};

        $('#calculatorDisplay').each(function () {
            const input = $(this);
            const id = input.attr('id');
            rawDigitMap[id] = '';

            if (!input.val()) {
                input.val('0,00');
            }

            input.on('keydown', function (e) {
                let rawDigits = rawDigitMap[id];

                if (e.key >= '0' && e.key <= '9') {
                    rawDigits += e.key;
                    e.preventDefault();
                } else if (e.key === 'Backspace') {
                    rawDigits = rawDigits.slice(0, -1);
                    e.preventDefault();
                } else if (['Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    return;
                } else {
                    e.preventDefault();
                }

                rawDigitMap[id] = rawDigits;
                updateFormattedValue(input, rawDigits);
                updateChangeDisplay(); // ✅ Wechselgeld neu berechnen
            });
        });

        function updateFormattedValue(input, rawDigits) {
            if (rawDigits.length === 0) {
                input.val('0,00');
                return;
            }

            while (rawDigits.length < 3) {
                rawDigits = '0' + rawDigits;
            }

            const cents = rawDigits.slice(-2);
            const euros = rawDigits.slice(0, -2);
            const euroFormatted = parseInt(euros, 10).toLocaleString('de-DE');
            const formattedValue = `${euroFormatted},${cents}`;
            input.val(formattedValue);

            // Recalculate on input change
            input.trigger('change');
        }
    });
   
</script>