@extends('layouts.new_customer')
@php
    $business_name = str_replace("_", " ", request()->route("business_name"));
    $business_name = str_replace("$&", "/", $business_name);
@endphp	
@section('title', 'Vollmacht')
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
            <h2> Vollmacht zur Datenspeicherung</h2>

            <h4> Allgemeine Vollmacht zur Speicherung und Einsicht von Verträgen und Dokumenten.</h4>

            Ich erteile hiermit {{ $business_name }}, im Folgenden 
            "Vollmachtnehmer" genannt, eine Vollmacht zur Speicherung und Einsicht von Verträgen und sonstigen Dokumenten in meinem Namen.

            Diese Vollmacht dient allein zu Dienstleistungszwecken und Serviceleistungen, die der Vollmachtnehmer im Rahmen seiner 
            Geschäftstätigkeit erbringt. Der Vollmachtnehmer ist ausdrücklich dazu verpflichtet, sämtliche Verträge und Dokumente 
            vertraulich zu behandeln und keinesfalls an Dritte weiterzugeben, es sei denn, es besteht eine gesetzliche Verpflichtung 
            hierzu oder der Vollmachtnehmer hat hierzu eine explizite schriftliche Einwilligung des Vollmachtgebers erhalten.

            Diese Vollmacht bleibt bis auf Widerruf des Vollmachtgebers in Kraft.
            
            <h4> Widerruf</h4>

            Ich bin mir bewusst, dass ich jederzeit die Möglichkeit habe, meine Einwilligung zu widerrufen. Sollte ich meinen Widerruf 
            erklären und/oder auf die Löschung meiner Daten bestehen, wird das Unternehmen meine personenbezogenen Daten unverzüglich löschen, 
            sofern keine gesetzlichen Bestimmungen einer Löschung entgegenstehen.<br>
            Hierzu genügt ein Mail {{ $business_location->email }} und eine Verifizierung.<br>
            Oder über den Link <a href=" {{ route('registration-revocation') }}" target="_blank">https://neoburg.net/registration-revocation</a> unseres Dienstleisters <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> für IT-Sicherheit.
            Ich erkenne an, dass der Widerruf der Einwilligung keine Auswirkungen auf bereits abgeschlossene Datenverarbeitungsprozesse hat.
           
            <br>
            <br>
            <h4>Datensicherheit Dienstleister: <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a></h4>

            Für die sichere Speicherung, Verwaltung und Verarbeitung Ihrer vertraulichen Dokumente und Verträge arbeitet <b>{{ $business->name }}</b> mit 
            einem erfahrenen IT-Dienstleister zusammen: <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a>. 
            Als Unternehmen mit Sitz in Tullastrasse 89, 79108 Freiburg im Breisgau verfügt 
            <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> über umfassende Expertise im Bereich der 
            Datensicherheit und erfüllt alle gesetzlichen Anforderungen im Hinblick auf die Verarbeitung und Speicherung personenbezogener 
            Daten und Dokumente gemäß der DSGVO.

            Um sicherzustellen, dass die Verarbeitung Ihrer Daten in stets hohem Maße geschützt erfolgt, haben <b>{{ $business->name }}</b> und 
            <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> eine 
            Vereinbarung zur Auftragsdatenverarbeitung getroffen. Somit bleiben wir als <b>{{ $business->name }}</b> datenschutzrechtlich verantwortlich, 
            während wir die Dienstleistungen von <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> 
            in Anspruch nehmen. Diese Vereinbarung gewährleistet einen hohen Schutz Ihrer persönlichen Daten vor unbefugtem Zugriff 
            und Missbrauch. Eine Weitergabe Ihrer persönlichen Daten an Dritte ist nicht vorgesehen, 
            es sei denn dies wurde ausdrücklich vereinbart.

            Unsere höchste Priorität bei <b>{{ $business->name }}</b> gilt dem Schutz und der Vertraulichkeit Ihrer Daten. Deshalb arbeiten wir ausschließlich mit 
            Dienstleistern wie <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> zusammen, die höchste Standards 
            in Sachen Datenschutz und Datensicherheit garantieren.
    </div>
   
@endsection 
