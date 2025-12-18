<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\PaymentMethod;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    // Zeigt das Zahlungsformular an (Ihre Blade-Datei)
    public function showPaymentForm()
    {
        return view('payment.index'); // Dies ist Ihre Blade-Datei
    }

    // Verarbeitet die Abonnement-Anfrage
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'payment_method_id' => 'required_if:payment_method,card', // Nur für Karte benötigt
            'payment_method' => 'required|string|in:card,paypal,sepa', // Auswahl der Zahlungsmethode
            // Fügen Sie hier weitere Validierungsregeln für Ihr Produkt/Plan hinzu
        ]);

        $email = $request->input('email');
        $selectedPaymentMethod = $request->input('payment_method');
        $paymentMethodId = $request->input('payment_method_id'); // Wird von Stripe Elements übergeben

        // Annahme: Sie haben einen Stripe-Produkt-ID und Preis-ID
        // Ersetzen Sie dies durch Ihre tatsächlichen Stripe-IDs
        $stripePriceId = 'price_12345'; // Beispiel: Ihre Stripe Price ID für 1,00 € pro Monat

        try {
            // 1. Kunden erstellen oder abrufen
            // Es ist gute Praxis, Kunden in Stripe zu speichern und wiederzuverwenden.
            // Sie können auch Ihre Benutzer-ID als metadata übergeben.
            $customer = Customer::all([
                'email' => $email,
                'limit' => 1
            ])->data;

            if (empty($customer)) {
                $customer = Customer::create([
                    'email' => $email,
                    'description' => 'New business Sandbox customer for ' . $email,
                ]);
            } else {
                $customer = $customer[0];
            }

            // 2. Zahlungsmethode zuordnen (wenn es sich um eine Karte handelt)
            if ($selectedPaymentMethod === 'card') {
                PaymentMethod::retrieve($paymentMethodId)->attach([
                    'customer' => $customer->id,
                ]);

                // Standard-Zahlungsmethode für den Kunden festlegen
                Customer::update(
                    $customer->id,
                    ['invoice_settings' => ['default_payment_method' => $paymentMethodId]]
                );

                // 3. Abonnement erstellen
                $subscription = Subscription::create([
                    'customer' => $customer->id,
                    'items' => [
                        ['price' => $stripePriceId],
                    ],
                    'expand' => ['latest_invoice.payment_intent'], // Für 3D Secure
                ]);

                // Handle 3D Secure (Payment Intent confirmation)
                if ($subscription->latest_invoice && $subscription->latest_invoice->payment_intent && $subscription->latest_invoice->payment_intent->status === 'requires_action') {
                    $clientSecret = $subscription->latest_invoice->payment_intent->client_secret;
                    // Rückgabe zur Bestätigung im Frontend
                    return response()->json([
                        'requires_action' => true,
                        'payment_intent_client_secret' => $clientSecret,
                    ]);
                }

            } elseif ($selectedPaymentMethod === 'paypal') {
                // Für PayPal oder SEPA Lastschrift sind die Flows oft anders (Redirects)
                // Dies ist ein vereinfachtes Beispiel. Stripe Payment Links oder Checkout Sessions sind oft besser.

                // Erstellen einer Checkout Session für PayPal
                $checkout_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['paypal'],
                    'line_items' => [[
                        'price' => $stripePriceId, // Your Stripe Price ID
                        'quantity' => 1,
                    ]],
                    'mode' => 'subscription',
                    'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('payment.cancel'),
                    'customer' => $customer->id,
                ]);

                return response()->json(['redirect_url' => $checkout_session->url]);

            } elseif ($selectedPaymentMethod === 'sepa') {
                // Für SEPA: Sie benötigen Bankkontodaten, die Sie über Stripe Elements sammeln.
                // Dies ist komplexer und erfordert ein Mandat.
                // Beispiel: Erstellen eines Mandats und dann ein Abonnement damit.
                // Normalerweise wird dies auch über Stripe Checkout oder spezielle SEPA Elements gehandhabt.

                // Vereinfachtes Beispiel:
                return response()->json(['error' => 'SEPA-Lastschrift ist noch nicht vollständig implementiert.']);
            }


            // Wenn alles erfolgreich war (für Karte ohne 3D Secure oder nach 3D Secure)
            return response()->json(['success' => true, 'message' => 'Abonnement erfolgreich!', 'subscription_id' => $subscription->id ?? null]);

        } catch (ApiErrorException $e) {
            // Fehler von Stripe API
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            // Andere Fehler
            return response()->json(['error' => 'Ein unerwarteter Fehler ist aufgetreten: ' . $e->getMessage()], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        // Hier können Sie die Session-ID von Stripe verifizieren
        // und den Status des Abonnements aktualisieren
        return view('payment.success');
    }

    public function paymentCancel()
    {
        return view('payment.cancel');
    }
}