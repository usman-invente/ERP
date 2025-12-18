<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geräte-Wertermittlung</title>

    {{-- Tailwind via CDN (du kannst später dein eigenes CSS nutzen) --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-xl mx-auto bg-white rounded-2xl shadow-2xl p-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-transparent opacity-60"></div>

        <div class="relative z-10">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl font-semibold text-gray-800 mb-1">📱 Geräte-Wertermittlung</h1>
                <p class="text-gray-500 text-sm">
                    Schätze in wenigen Sekunden den aktuellen Marktwert deines Geräts.
                </p>
            </div>

            {{-- Formular --}}
            <form method="POST" action="{{ route('device-valuation.calculate') }}" id="device_valuation_form" class="space-y-5">
                @csrf

                {{-- Marke --}}
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Marke</label>
                    <select name="brand" id="brand" required
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Bitte wählen ...</option>
                        <option>Apple</option>
                        <option>Samsung</option>
                        <option>Huawei</option>
                        <option>Xiaomi</option>
                        <option>OnePlus</option>
                        <option>Andere</option>
                    </select>
                </div>

                {{-- Modell --}}
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Modell</label>
                    <input type="text" name="model" id="model" placeholder="z. B. iPhone 13 Pro"
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                {{-- Erscheinungsjahr --}}
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Erscheinungsjahr</label>
                    <input type="number" name="year" id="year" placeholder="z. B. 2021" min="2008" max="{{ now()->year }}"
    class="w-full rounded-lg border-gray-400 bg-gray-100 text-gray-800 placeholder-gray-500 
           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition"
    required>

                </div>

                {{-- Kaufpreis --}}
                <div>
                    <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1">Ursprünglicher Kaufpreis (€)</label>
                    <input type="number" step="0.01" name="base_price" id="base_price" placeholder="z. B. 899,00"
                           class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                {{-- Zustand --}}
                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-700 mb-1">Zustand</label>
                    <select name="condition" id="condition"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Bitte wählen ...</option>
                        <option value="like_new">Wie neu</option>
                        <option value="very_good">Sehr gut</option>
                        <option value="good">Gut</option>
                        <option value="used">Stark gebraucht</option>
                        <option value="defect">Defekt</option>
                    </select>
                </div>

                {{-- Button --}}
                <div class="pt-3 text-center">
                    <button type="submit"
                        class="px-6 py-3 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                        💰 Wert berechnen
                    </button>
                </div>
            </form>

            {{-- Ergebnisfeld (optional) --}}
            @if(!empty($estimatedValue))
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Geschätzter Gerätewert</p>
                    <p class="text-3xl font-bold text-blue-700 mt-1">
                        {{ number_format($estimatedValue, 2, ',', '.') }} €
                    </p>
                </div>
            @endif

            <p class="text-xs text-gray-400 text-center mt-6">
                * Unverbindliche Richtwert-Schätzung. Gültig nur nach Sichtprüfung.
            </p>
        </div>
    </div>

</body>
</html>
