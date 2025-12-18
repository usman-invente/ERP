<!-- Calculator Modal -->
<div class="modal fade" id="calculatorModal" tabindex="-1" role="dialog" aria-labelledby="calculatorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calculatorModalLabel">@lang('sale.cash_payment')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display für Wechselgeld -->
                <div id="changeDisplay" class="text-center mb-3 font-weight-bold" style="font-size: 1.2em;"></div>

                <!-- Gesamtbetrag-Anzeige -->
                <div id="totalPayableDisplay" class="mb-3 text-center font-weight-bold" style="font-size: 1.2em;"></div>

                <div class="calculator">
                    <form id="cashPaymentForm">
                        <!-- Display für Eingabe -->
                        <input type="text" name="calculatorDisplay" id="calculatorDisplay" class="form-control text-right mb-3" placeholder="Betrag eingeben">
                        <!-- Buttons -->
                        <div class="calculator-grid">
                            @php
                                $buttons = ['1', '2', '3','4', '5', '6', '7', '8', '9', ',', '0', 'C','00','CE',''];
                            @endphp
                            @foreach($buttons as $button)
                                <button type="button" 
                                        data-value="{{ $button }}" 
                                        class="btn btn-primary calculator-btn">
                                    {{ $button }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.cancel')</button>
                <button type="button" class="btn btn-success" id="submitCalculator">@lang('messages.confirm')</button>
            </div>
        </div>
    </div>
</div>

<style>
    .calculator-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 4 Spalten */
        gap: 10px; /* Abstand zwischen den Buttons */
    }

    .calculator-btn {
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        border-radius: 5px;
    }

    #calculatorDisplay {
        font-size: 24px;
        padding: 10px;
    }
</style>
