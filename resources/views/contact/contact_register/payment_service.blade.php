@extends('layouts.new_customer')
@php
    $business_name = str_replace("_", " ", request()->route("business_name"));
    $business_name = str_replace("$&", "/", $business_name);
@endphp	
@section('title', 'Zahlungsdienstleister')
@section('content')


    <div class="login-form col-md-12 col-xs-12 right-col-content-register">
        
        <div class="form-group">
            <div class="row"> 
                <div class="col-md-12"> 
                    <div class="col-md-4">
                    </div> 
                    <div class="col-sm-2">
                        <div>
                            @if(!empty($business->logo))
                                <img class="img-responsive" src="{{ url( 'uploads/business_logos/' . $business->logo ) }}" alt="Business Logo">                                    
                            @endif
                        </div>
                    </div> 
                </div>
            </div>
        </div>
            <h2> Zahlungsdienstleister</h2>

            Als Kunde unserer Produkte und Dienstleistungen möchten wir Ihnen ein attraktives Angebot machen, das Ihnen Rückzahlungen und 
            Cashback-Aktionen ermöglicht. Um dies zu erreichen, benötigen wir Ihre Einwilligung zur Erfassung und Nutzung Ihrer Bankdaten und oder Paypal. 
            Diese Daten werden ausschließlich für die Durchführung von Rückzahlungen und Cashback-Aktionen verwendet und sind sicher bei uns gespeichert.
            <br>
            Ihre Einwilligung zur Erfassung und Nutzung Ihrer Bankdaten und Zahlungsdienstleister ist freiwillig und kann jederzeit widerrufen werden. 
            Wenn Sie uns Ihre Zustimmung geben, garantieren wir, dass Ihre Daten nur für den von Ihnen autorisierten Zweck verwendet werden und vertraulich 
            behandelt werden. Darüber hinaus werden wir Ihre Daten nicht an Dritte weitergeben.
            <br>
            Als Kunde von {{ $business_name }} haben Sie die Kontrolle über Ihre Daten. Wenn Sie Fragen zur Erfassung oder Nutzung Ihrer Bankdaten haben, oder Ihre 
            Einwilligung widerrufen möchten, unterstützen wir Sie gerne. Bitte kontaktieren Sie einfach unser per Mail an {{ $business_location->email }}, um weitere Informationen zu erhalten.
            
            
            <h4> Widerruf</h4>

            Ich bin mir bewusst, dass ich jederzeit die Möglichkeit habe, meine Einwilligung zu widerrufen. Sollte ich meinen Widerruf 
            erklären und/oder auf die Löschung meiner Daten bestehen, wird das Unternehmen meine personenbezogenen Daten unverzüglich löschen, 
            sofern keine gesetzlichen Bestimmungen einer Löschung entgegenstehen.<br>
            Hierzu genügt ein Mail {{ $business_location->email }} und eine Verifizierung.<br>
            Oder über den Link <a href="{{ route('registration-revocation') }}" target="_blank">https://neoburg.net/registration-revocation</a> unseres Dienstleisters <a href="https://www.neoburg.net/unsere-werte/" target="_blank">NEOBURG</a> für IT-Sicherheit.
            Ich erkenne an, dass der Widerruf der Einwilligung keine Auswirkungen auf bereits abgeschlossene Datenverarbeitungsprozesse hat.
           
            <br>
    </div>
   
@endsection 
