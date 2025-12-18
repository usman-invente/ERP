@extends('layouts.new_customer')
@php
    $business_name = str_replace("_", " ", request()->route("business_name"));
    $business_name = str_replace("$&", "/", $business_name);
@endphp	
@section('title', 'Einwilligungserklärung')
@section('content')
{{-- 
<div class="modal fade bd-example-modal-lg" id="{{$modal_id}}" role="dialog" tabindex="-1" aria-labelledby="NewOfferOrganisationLabel" aria-hidden="true">
    <?php //$object_id = str_replace(".", "", basename(__FILE__)).$modal_id; ?>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="NewOfferOrganisationLabel">Organisation anlegen</h5>
                
            </div>
            <div class="modal-body">                 --}}

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
            <h2> Einwilligung für verschiedene Kontaktarten</h2>

            Durch die Eingabe meiner Daten, Auswahl der Kontaktmöglichkeiten und Betätigen des Sende Buttons, 
            gebe hiermit meine Einwilligung für Verarbeitung meiner personenbezogenen Daten durch <b>{{ $business->name }}</b>,<br> 
            um mich über Neuigkeiten, Angebote oder andere Informationen zu informieren. Hierfür können die 
            folgenden Kontaktarten verwendet werden
            </p>
            <h4>Entsprechend meiner Auswahl:</h4>
            <ol>
                <li> 
                    <b>E-Mail-Kontakt:</b> Ich erkläre mich einverstanden, dass meine E-Mail-Adresse verwendet wird, 
                    um mich per E-Mail zu kontaktieren und zu informieren. Dazu gehören Informationen über Produkte 
                    und Dienstleistungen des Unternehmens, saisonale Angebote oder Services, die dem Unternehmen zuzuordnen sind. 
                </li>
                </p>
                <li> 
                    <b>Telefonischer Kontakt:</b> Ich erkläre mich einverstanden, dass meine Telefonnummer verwendet wird, 
                    um mich telefonisch zu kontaktieren und zu informieren. Dazu gehören Informationen über Produkte 
                    und Dienstleistungen des Unternehmens, saisonale Angebote oder Services, die dem Unternehmen zuzuordnen sind. 
                    Eine Kontaktaufnahme kann auch aus qualitative Gründen erfolgen (z.B. Kundenzufriedenheitsbefragung).
                </li>
                </p>
                <li> 
                    <b>Postalischer Kontakt:</b> Ich erkläre mich einverstanden, dass meine Adresse verwendet wird, um mich per Post 
                    zu kontaktieren und zu informieren. Dazu gehören Informationen über Produkte und Dienstleistungen des Unternehmens, 
                    saisonale Angebote oder Services, die dem Unternehmen zuzuordnen sind.
                </li>
                </p>
                <li> 
                    <b>SMS und Social-Media-Messenger:</b> Ich erkläre mich einverstanden, dass meine Mobiltelefonnummer und/oder 
                    Social-Media-Kontakte zur Nutzung von SMS und Social-Media-Messenger-Diensten verwendet werden können, 
                    um mich zu kontaktieren und zu informieren. Dazu gehören Informationen über Produkte und Dienstleistungen 
                    des Unternehmens, saisonale Angebote oder Services, die dem Unternehmen zuzuordnen sind.
                </li>
            </ol>  
            <br>
            <h4> Widerruf</h4>
            
            Ich bin mir bewusst, dass ich jederzeit die Möglichkeit habe, meine Einwilligung zu widerrufen. Sollte ich meinen Widerruf 
            erklären und/oder auf die Löschung meiner Daten bestehen, wird das Unternehmen meine personenbezogenen Daten unverzüglich löschen, 
            sofern keine gesetzlichen Bestimmungen einer Löschung entgegenstehen.<br>
            Hierzu genügt ein Mail {{ $business_location->email }} und eine Verifizierung.<br>
            Oder über den Link <a href="{{ route('registration-revocation') }}" target="_blank">https://neoburg.net/registration-revocation</a> unseres Dienstleisters <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> für IT-Sicherheit.
            Ich erkenne an, dass der Widerruf der Einwilligung keine Auswirkungen auf bereits abgeschlossene Datenverarbeitungsprozesse hat.

            {{-- <a href="https://portal.neoburg.net/{{ request()->route("business_id") }}/{{ request()->route("location_id") }}/{{ request()->route("business_name")}}/datenschutzerklaerung" target="_blank">hier</a>.</div> --}}
            <br>
            <br>
            <h4>Datensicherheit Dienstleister: <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a></h4>

            Ich habe zur Kenntnis genommen und willige ein, dass <b>{{ $business->name }}</b> zum Schutze meiner Daten mit der Firma <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> als IT-Spezialisten zusammenarbeitet.

            Die Verarbeitung von personenbezogenen Daten ist ein sensibler Bereich, der gesetzlichen Regelungen und Anforderungen unterliegt. <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a>, 
            ein Unternehmen mit Sitz in Tullastrasse 89, 79108 Freiburg im Breisgau, erhebt, verarbeitet und nutzt Ihre personenbezogenen Daten im Rahmen 
            einer Auftragsdatenverarbeitung gemäß Artikel 28 DSGVO.

            Hierfür wurde eine gesonderte Vereinbarung zwischen <b>{{ $business->name }}</b> und <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> getroffen, um sicherzustellen, dass die Verarbeitung von Daten 
            in Übereinstimmung mit den geltenden gesetzlichen Vorgaben erfolgt. Die Inanspruchnahme der Dienstleistungen von <a href="https://www.neoburg.net/" target="_blank">NEOBURG</a> erfolgt ausschließlich 
            in unserem Auftrag, sodass wir weiterhin datenschutzrechtlich verantwortlich bleiben.

            Dabei gewährt diese Vereinbarung den Schutz Ihrer persönlichen Daten vor unbefugtem Zugriff und Missbrauch. Es ist daher nicht vorgesehen, 
            dass Ihre persönlichen Daten an Dritte im Sinne des Datenschutzes weitergegeben werden, wenn dies nicht ausdrücklich vereinbart wurde.

            Insgesamt sind wir bei <b>{{ $business->name }}</b> bestrebt, Ihren Schutz und die Vertraulichkeit Ihrer persönlichen Daten zu gewährleisten, um den höchsten 
            Standard an Datenschutz und Datenschutzgarantien zu gewährleisten.            
                
            
    </div>
   
@endsection 
