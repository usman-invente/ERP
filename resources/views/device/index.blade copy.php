<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geräte-Wertermittlung</title>

    {{-- Bootstrap 5 CSS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 12px;
        }

        .dv-card {
            position: relative;
            border-radius: 22px;
            border: 1px solid rgba(209, 213, 219, 0.9);
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            padding: 24px 24px 20px;
            overflow: hidden;
        }

        .dv-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right,
                rgba(59, 130, 246, 0.18),
                transparent 55%);
            opacity: 0.9;
            pointer-events: none;
        }

        .dv-card-inner {
            position: relative;
            z-index: 2;
        }

        .dv-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .dv-chip {
            font-size: 0.75rem;
            padding: 3px 10px;
            border-radius: 999px;
            background: rgba(59, 130, 246, 0.08);
            color: #1d4ed8;
            font-weight: 600;
        }

        .dv-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .dv-help {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 3px;
        }

        .dv-result {
            border-radius: 16px;
            border: 1px dashed rgba(59, 130, 246, 0.45);
            background: rgba(239, 246, 255, 0.85);
            padding: 12px 16px;
        }

        .dv-result-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .dv-result-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111827;
        }

        .dv-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f9fafb;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .dv-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: #22c55e;
        }

        .btn-dv-primary {
            border-radius: 999px;
            padding-inline: 18px;
            padding-block: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="dv-card">
                <div class="dv-card-inner">
                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h5 mb-1">Geräte-Wertermittlung</h1>
                            <p class="dv-subtitle mb-0">
                                Gib ein paar Eckdaten ein – wir schätzen den aktuellen Marktwert deines Geräts.
                            </p>
                        </div>
                        <span class="dv-chip">Beta</span>
                    </div>

                    {{-- Infozeile --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="dv-badge-pill">
                            <span class="dv-badge-dot"></span>
                            <span>Schätzung in wenigen Sekunden</span>
                        </div>
                        <small class="text-muted">
                            * unverbindlicher Richtwert
                        </small>
                    </div>

                    {{-- Formular --}}
                    <form method="POST" action="{{ route('device-valuation.calculate') }}" id="device_valuation_form">
                        @csrf

                        {{-- Marke --}}
                        <div class="mb-3">
                            <label for="brand" class="dv-label">Marke</label>
                            <select
                                name="brand"
                                id="brand"
                                class="form-select @error('brand') is-invalid @enderror"
                                required
                            >
                                <option value="">Bitte wählen ...</option>
                                <option value="Apple" @selected(old('brand')=='Apple')>Apple</option>
                                <option value="Samsung" @selected(old('brand')=='Samsung')>Samsung</option>
                                <option value="Huawei" @selected(old('brand')=='Huawei')>Huawei</option>
                                <option value="Xiaomi" @selected(old('brand')=='Xiaomi')>Xiaomi</option>
                                <option value="OnePlus" @selected(old('brand')=='OnePlus')>OnePlus</option>
                                <option value="Other" @selected(old('brand')=='Other')>Andere</option>
                            </select>
                            <div class="dv-help">Z. B. „Apple“, „Samsung“, …</div>
                            @error('brand')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Modell --}}
                        <div class="mb-3">
                            <label for="model" class="dv-label">Modell</label>
                            <input
                                type="text"
                                name="model"
                                id="model"
                                value="{{ old('model') }}"
                                class="form-control @error('model') is-invalid @enderror"
                                placeholder="z. B. iPhone 13 Pro, Galaxy S22"
                                required
                            >
                            <div class="dv-help">Exakte Modellbezeichnung, falls bekannt.</div>
                            @error('model')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Erscheinungsjahr --}}
                        <div class="mb-3">
                            <label for="year" class="dv-label">Erscheinungsjahr</label>
                            <input
                                type="number"
                                name="year"
                                id="year"
                                min="2008"
                                max="{{ now()->year }}"
                                value="{{ old('year') }}"
                                class="form-control @error('year') is-invalid @enderror"
                                placeholder="z. B. 2021"
                                required
                            >
                            <div class="dv-help">Jahr, in dem das Modell auf den Markt gekommen ist.</div>
                            @error('year')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kaufpreis --}}
                        <div class="mb-3">
                            <label for="base_price" class="dv-label">Ursprünglicher Kaufpreis (€)</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="base_price"
                                id="base_price"
                                value="{{ old('base_price') }}"
                                class="form-control @error('base_price') is-invalid @enderror"
                                placeholder="z. B. 899,00"
                                required
                            >
                            <div class="dv-help">Neupreis inkl. MwSt (falls bekannt).</div>
                            @error('base_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Zustand --}}
                        <div class="mb-4">
                            <label for="condition" class="dv-label">Zustand</label>
                            <select
                                name="condition"
                                id="condition"
                                class="form-select @error('condition') is-invalid @enderror"
                                required
                            >
                                <option value="">Bitte wählen ...</option>
                                <option value="like_new"  @selected(old('condition')=='like_new')>Wie neu (keine Gebrauchsspuren)</option>
                                <option value="very_good" @selected(old('condition')=='very_good')>Sehr gut (leichte Gebrauchsspuren)</option>
                                <option value="good"      @selected(old('condition')=='good')>Gut (sichtbare Gebrauchsspuren)</option>
                                <option value="used"      @selected(old('condition')=='used')>Stark gebraucht / kleine Schäden</option>
                                <option value="defect"    @selected(old('condition')=='defect')>Defekt (nur für Teile / Reparatur)</option>
                            </select>
                            <div class="dv-help">Ehrliche Einschätzung des optischen & technischen Zustands.</div>
                            @error('condition')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ergebnis + Button --}}
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div
                                id="valuation_result"
                                class="dv-result mb-3 mb-md-0 @if(empty($estimatedValue)) d-none @endif"
                            >
                                <div class="dv-result-label">Geschätzter Gerätewert</div>
                                <div class="dv-result-value">
                                    @if(!empty($estimatedValue))
                                        {{ number_format($estimatedValue, 2, ',', '.') }} €
                                    @else
                                        <span data-valuation-amount>0,00 €</span>
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-dv-primary">
                                <span>Wert berechnen</span>
                                <span aria-hidden="true">➜</span>
                            </button>
                        </div>
                    </form>

                    {{-- Hinweis --}}
                    <div class="mt-3 text-muted" style="font-size: 0.75rem;">
                        Die angezeigte Bewertung ist ein Richtwert. Der endgültige Ankaufspreis kann nach
                        Sichtprüfung in der Filiale angepasst werden.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Optional: Bootstrap JS (falls du später Modals/Tooltips brauchst) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
