<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCheckoutSession(Request $request)
{
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $package = Package::findOrFail($request->package_id);

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card', 'paypal', 'sepa_debit'],
        'mode' => 'subscription',
        'line_items' => [[
            'price' => $package->stripe_price_id,
            'quantity' => 1,
        ]],
        'customer_email' => $request->email,
        'success_url' => route('business.postRegister') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('business.getRegister'),
    ]);

    return response()->json(['session_id' => $session->id]);
}

     public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
