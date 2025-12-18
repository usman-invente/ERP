@if(empty($is_admin))
    {{-- Removed redundant <h3>Zahlung </h3> if "Stripe Zahlung" is the main title --}}
@endif
{!! Form::hidden('language', request()->lang); !!}
<fieldset>
    <legend>Zahlung</legend>
    {{-- Make sure Bootstrap CSS is loaded once, preferably in your main layout file --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    {{-- Custom styles for the desired layout --}}
    <style>
        /* General Layout */
        .payment-container {
            display: flex;  
            gap: 40px; /* Space between columns */
            max-width: 1100px; /* Adjust as needed */
            margin: 50px auto; /* Center the form */
            background-color: #fff; /* White background for the whole form area */
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); /* Subtle shadow */
            padding: 30px; /* Padding around the whole content */
        }

        .left-column, .right-column {
            padding: 0; /* Remove default Bootstrap column padding if exists */
        }

        .left-column {
            flex: 1; /* Takes 1 part of available space */
            padding-right: 20px; /* Add some padding to the right of the summary */
        }

        .right-column {
            flex: 1.7; /* Takes more space, adjust ratio as needed */
        }

        /* Summary Section Styling */
        .summary-header {
            font-size: 1.5rem; /* Larger font for 'Stripe Zahlung' */
            font-weight: 500;
            margin-bottom: 20px;
            color: #333;
        }

        .new-business-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .new-business-info h5 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .new-business-info .badge {
            margin-left: 8px;
            background-color: #e0e0e0; /* Light grey for Sandbox badge */
            color: #555;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: normal; /* Match desired screenshot */
            font-size: 0.85em;
        }

        .summary-details p {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px; /* Less space between lines */
            font-size: 0.95em;
            color: #555;
        }

        .summary-details p strong {
            font-weight: 600;
            color: #333;
        }

        .summary-details hr {
            border-top: 1px solid #eee;
            margin: 15px 0;
        }

        .total-summary {
            font-size: 1.1em;
            font-weight: 700;
            color: #333;
            margin-top: 15px;
        }

        /* Payment Method Buttons - MODIFIED for new image layout */
        .payment-method-options {
            margin-bottom: 20px;
        }

        .payment-method-item {
            display: flex;
            align-items: center;
            border: 1px solid #dee2e6; /* Default light grey border */
            border-radius: 5px;
            padding: 10px 15px;
            margin-bottom: 10px; /* Space between stacked items */
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            position: relative; /* For custom radio button positioning */
        }

        .payment-method-item:last-child {
            margin-bottom: 0;
        }

        .payment-method-item:hover {
            border-color: #007bff; /* Highlight border on hover */
        }

        .payment-method-item input[type="radio"] {
            /* Hide default radio button visually */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 18px; /* Size for custom radio circle */
            height: 18px;
            border: 1px solid #ced4da; /* Default border color */
            border-radius: 50%; /* Make it a circle */
            margin-right: 15px; /* Space between radio and content */
            flex-shrink: 0; /* Prevent it from shrinking */
            position: relative;
            cursor: pointer;
            outline: none; /* Remove outline on focus */
        }

        .payment-method-item input[type="radio"]:checked {
            border-color: #007bff; /* Blue border when checked */
            background-color: #007bff; /* Blue background when checked */
        }

        .payment-method-item input[type="radio"]:checked::after {
            content: '';
            width: 8px; /* Size of inner dot */
            height: 8px;
            background-color: #fff; /* White inner dot */
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: block;
        }

        .payment-method-item.selected {
            border-color: #007bff; /* Blue border for selected item */
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); /* Focus-like shadow */
        }

        .payment-method-content {
            display: flex;
            align-items: center;
            flex-grow: 1; /* Allows content to take available space */
        }

        .payment-method-content .method-name {
            font-weight: 500;
            color: #333;
            flex-grow: 1; /* Pushes logos to the right */
        }

        .payment-method-content .method-logos img {
            max-height: 25px; /* Size of logos */
            margin-left: 5px; /* Space between logos */
            vertical-align: middle;
        }
        /* Specific logo size for PayPal and SEPA if they are larger initially */
        .payment-method-content .method-logos img[alt="PayPal"] {
             max-height: 28px;
        }
        .payment-method-content .method-logos img[alt="SEPA"] {
             max-height: 28px;
        }


        /* General Form Styling */
        .form-label {
            font-weight: 600; /* Bolder labels */
            color: #333;
            margin-bottom: 8px; /* More space below labels */
        }

        .form-control {
            border-radius: 5px; /* Slightly rounded inputs */
            padding: 10px 15px;
        }

        .form-check-label {
            font-weight: 500 !important; /* Adjust font weight for checkbox label */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 12px 20px;
            font-size: 1.1em;
            border-radius: 5px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .text-muted.small {
            font-size: 0.85em;
            line-height: 1.4;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .payment-container {
                flex-direction: column;
                padding: 20px;
                margin: 20px auto;
            }
            .left-column {
                padding-right: 0;
                margin-bottom: 20px;
            }
            .right-column {
                padding-left: 0;
            }
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

        {{-- Rechte Seite: Kontakt + Zahlungsmethoden --}}
        <div class="right-column">
            {{-- <form method="POST" action="{{ route('payment.subscribe') }}"> --}}
                {{-- @csrf --}}

                {{-- Kontakt --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Kontaktinformationen</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="email@beispiel.de" required>
                </div>

                {{-- Zahlungsmethode --}}
                <label class="form-label">Zahlungsmethode</label>
                <div class="payment-method-options"> {{-- Changed class name to be more descriptive --}}

                    {{-- Kreditkarte --}}
                    <label class="payment-method-item" for="card_radio"> {{-- Use label to make entire div clickable --}}
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
                    <label class="form-check-label fw-semibold" for="saveData">
                        Meine Daten sicher speichern für Zahlungsvorgänge mit einem Klick
                    </label>
                </div>

                {{-- Button --}}
                <button type="submit" class="btn btn-primary w-100">
                    Zahlungspflichtig abonnieren
                </button>

                {{-- Info-Text --}}
                <p class="text-muted mt-2 small">
                    Indem Sie Ihr Abonnement bestätigen, gestatten Sie New business Sandbox, zukünftige Zahlungen Ihrem Zahlungsmittel gemäß den allgemeinen Geschäftsbedingungen zu belasten. Sie können Ihr Abonnement jederzeit kündigen.
                </p>
            {{-- </form> --}}
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