<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('device.index'); // Dies ist Ihre Blade-Datei
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'base_price' => 'required|numeric|min:0',
            'condition' => 'required|string',
        ]);

        // Beispiel: einfache Schätzlogik
        $age = now()->year - $data['year'];
        $depreciation = min($age * 0.15, 0.8); // max 80% Wertverlust
        $conditionFactor = [
            'like_new' => 1.0,
            'very_good' => 0.9,
            'good' => 0.75,
            'used' => 0.5,
            'defect' => 0.2,
        ][$data['condition']] ?? 0.5;

        $estimatedValue = $data['base_price'] * (1 - $depreciation) * $conditionFactor;

        return view('device.index', compact('estimatedValue'));
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
