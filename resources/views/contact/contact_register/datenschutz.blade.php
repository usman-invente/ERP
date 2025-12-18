@extends('layouts.new_customer')
@php
    $modal_id = 'NewOfferOrganisation';

    $business_name = str_replace("_", " ", request()->route("business_name"));
    $business_name = str_replace("$&", "/", $business_name);
@endphp	
@section('title', 'Datenschutzerklärung')
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
                   <h2> Datenschutzerklärung </h2>
                    </p>
                    <h4> Datenschutzerklärung für {{ $business_name }} </h4>
                    </p>
                    Wir von {{ $business_name }} nehmen den Schutz Ihrer persönlichen Daten sehr ernst und halten uns strikt an die Regeln der 
                    Datenschutzgesetze.
                    Personenbezogene Daten werden auf dieser Webseite nur im technisch notwendigen Umfang erhoben.
                    In keinem Fall werden die erhobenen Daten verkauft oder aus anderen Gründen an Dritte weitergegeben.

                    Die folgende Erklärung gibt Ihnen einen Überblick darüber, wie wir diesen Schutz gewährleisten und welche Art von Daten 
                    zu welchem Zweck erhoben werden.
                    
                    <br>
                    <br>
                    </p>
                    <h4> Datenverarbeitung auf dieser Internetseite </h4>
                    </p>
                    {{ $business_name }} erhebt und speichert automatisch in ihren Server Log Files Informationen, die Ihr Browser an uns übermittelt. 
                    Dies sind:
                    <ul>
                        <li>Browsertyp/ -version</li>
                        <li>verwendetes Betriebssystem</li>
                        <li>Referrer URL (die zuvor besuchte Seite)</li>
                        <li>Hostname des zugreifenden Rechners (IP Adresse)</li>
                        <li>Uhrzeit der Serveranfrage</li>
                    </ul>
                    Diese Daten sind für {{ $business_name }} nicht bestimmten Personen zuordenbar. Eine Zusammenführung dieser Daten mit 
                    anderen Datenquellen wird nicht vorgenommen.

                    <br> <br>
                    </p>
                    <h4> Cookies </h4>
                    </p>

                    Die Internetseiten verwenden an mehreren Stellen so genannte Cookies.
                    Sie dienen dazu, unser Angebot nutzerfreundlicher, effektiver und sicherer zu machen.
                    Cookies sind kleine Textdateien, die auf Ihrem Rechner abgelegt werden und die Ihr Browser speichert.
                    Die meisten der von uns verwendeten Cookies sind so genannte „Session-Cookies“. Sie werden nach Ende Ihres Besuchs automatisch gelöscht.
                     Cookies richten auf Ihrem Rechner keinen Schaden an und enthalten keine Viren.

                   <br> <br>
                    </p>
                     <h4> Newsletter </h4>
                    </p>
                    Wenn Sie den auf der Webseite angebotenen Newsletter empfangen möchten, benötigen wir von Ihnen eine valide Email-Adresse sowie Informationen, 
                    die uns die Überprüfung gestatten, dass Sie der Inhaber der angegebenen Email-Adresse sind bzw. deren Inhaber mit dem Empfang des Newsletters 
                    einverstanden ist. Weitere Daten werden nicht erhoben. Ihre Einwilligung zur Speicherung der Daten, der Email-Adresse sowie deren Nutzung zum 
                    Versand des Newsletters können Sie jederzeit widerrufen.

                    <br> <br>
                    </p>
                    <h4> Auskunftsrecht </h4>
                    </p>
                    Sie haben jederzeit das Recht auf Auskunft über die bezüglich Ihrer Person gespeicherten Daten. Scheiben Sie dazu eine Mail an:

                    </p><b>{{ $business_location->email }}</b> 

                    <br> <br>
                    </p>
                    <h4> Auftragsdatenverarbeitung </h4>
                    </p>
                    Aufgrund einer gesonderten Vereinbarung über die Verarbeitung von personenbezogenen Daten werden Ihre personenbezogenen Daten 
                    von der Firma NEOBURG, Tullastrasse 89, 79108 Freiburg im Breisgau im Rahmen einer Auftragsdatenverarbeitung nach Artikel 28 
                    DSGVO gemäß den entsprechenden gesetzlichen Vorgaben in unserem Auftrag erhoben, verarbeitet und genutzt. Hiermit ist jedoch 
                    keine Übermittlung Ihrer persönlichen Daten an Dritte im datenschutzrechtlichen Sinne verbunden. Wir bleiben Ihnen gegenüber 
                    datenschutzrechtlich verantwortlich.
                    
                    <br> <br>
                    </p>
                    <h4> Datenschutz </h4>
                    </p>
                    Wir von {{ $business_name }} nehmen den Schutz Ihrer persönlichen Daten sehr ernst und halten uns streng an alle geltenden 
                    Gesetze und Vorschriften zum Datenschutz, insbesondere an die Datenschutzgrundverordnung, (DSGVO), das Bundesdatenschutzgesetz 
                    (BDSG) und das Telemediengesetz (TMG).
                    <br>
                    {{ $business_name }} 
                    <br>
                    {!! $business_location->getBusinessLocationAddress() !!}
                    <br>
                    {!! $business_location->getBusinessLocationContact() !!}
                    <br>
                    <br>
                    Die folgenden Erläuterungen geben Ihnen einen Überblick darüber, wie wir diesen Schutz sicherstellen und welche Daten wir zu welchem Zweck verarbeiten.
                    <br>
                    <br>
                    <ol type=1>
                        <li><b>Nutzungsdaten</b></li>
                        </p>
                        Bei jedem Zugriff auf unsere Webseite und bei jedem Abruf einer Datei, werden automatisch über diesen Vorgang allgemeine Daten in 
                        einer Protokolldatei gespeichert. Die Speicherung dient ausschließlich systembezogenen und statistischen Zwecken (auf Grundlage von 
                        Art. 6 Abs. 1 Buchst. b) DSGVO), sowie in Ausnahmefällen zur Anzeige von Straftaten (auf Grundlage von Art. 6 Abs. 1 Buchst. e) DSGVO).
                        Eine Weitergabe der Daten an Dritte oder eine sonstige Auswertung findet nicht statt, es sei denn, es besteht eine gesetzliche 
                        Verpflichtung dazu (Art. 6 Abs. 1 Buchst. e) DSGVO).Im Einzelnen wird über jeden Abruf folgender Datensatz gespeichert:
                        </p>
                        Name der abgerufenen Datei<br>
                        Datum und Uhrzeit des Abrufs<br>
                        übertragene Datenmenge<br>
                        Meldung über erfolgreichen Abrufv
                        Browsertyp nebst Version<br>
                        Betriebssystem des Nutzers<br>
                        Referrer URL (die zuvor besuchte Seite<br>
                        IP-Adresse und der anfragende Provider<br>         
                    </ol>
                    <br>
                    <ol type=1>
                        <li><b>Umgang mit personenbezogenen Daten</b></li>
                        </p>
                        Personenbezogene Daten sind Informationen, mit deren Hilfe eine Person bestimmbar ist, also Angaben, die zurück zu einer 
                        Person verfolgt werden können. Dazu gehören der Name, die Emailadresse oder die Telefonnummer. Aber auch Daten über Vorlieben, 
                        Hobbies, Mitgliedschaften oder welche Webseiten von jemandem angesehen wurden zählen zu personenbezogenen Daten.
                        </p>
                        Personenbezogene Daten werden von dem Anbieter nur dann erhoben, genutzt und weiter gegeben, wenn dies gesetzlich erlaubt ist 
                        oder die Nutzer in die Datenerhebung einwilligen.                        
                    </ol>                    
                    <ol type=a>
                        <li><b>Kontaktaufnahme</b></li>
                        </p>
                        Wenn Sie mit uns in Kontakt treten (z. B. über Telefon, E-Mail oder Kontaktformular), speichern wir Ihre Daten auf Grundlage 
                        von Art. 6 Abs. 1 Buchst. b) DSGVO zum Zwecke der Bearbeitung Ihrer Anfrage, sowie für den Fall, dass eine weitere Korrespondenz 
                        stattfinden sollte. Ihre Daten werden gelöscht, sobald Ihre Anfrage vollständig bearbeitet und/oder beantwortet wurde.

                        </p>
                        <li><b>Newsletter</b></li>
                        </p>
                        Gerne informieren wir Sie über unsere Newsletter über aktuelle Themen.
                        </p>
                        Zum Versand eines Newsletters benötigen wir Ihren Namen und Ihre E-Mail-Adresse. Beides können Sie in die dafür vorgesehenen 
                        Felder eingeben. Nachdem Sie diese Daten abgesendet haben, erhalten Sie von uns eine E-Mail an die von Ihnen angegebene 
                        E-Mail-Adresse, in der Sie zur Verifizierung der von Ihnen angegebenen E-Mail-Adresse einen Bestätigungslink anklicken müssen.
                        </p>
                        Ihre Daten werden von uns nur zum Zwecke des Newsletterversands verarbeitet.
                        </p>
                        Sie können sich jederzeit vom Newsletter wieder abmelden und damit der weiteren Verwendung Ihrer Daten widersprechen. Sie können 
                        sich hierzu am Ende eines jeden Newsletters aus dem Verteiler austragen.
                        </p>
                        Alternativ können Sie uns auch eine E-Mail zukommen lassen:<br>
                        {{ $business_location->email }}<br>
                        </p>
                        Rechtsgrundlage der Datenverarbeitung ist Art. 6 Abs. 1 Buchst. b) DSGVO, bzw. mit Anklicken des Bestätigungslinks Ihre 
                        Einwilligung und damit Art. 6 Abs. 1 Buchst. a) DSGVO).

                    </ol>
                    <br>
                    <ol type=1>
                        <li><b>Cookies</b></li>
                        </p>                        
                        Wir setzen in einigen Bereichen unserer Webseite sogenannte Cookies ein. Durch solche Dateielemente kann Ihr Computer als 
                        technische Einheit während Ihres Besuchs auf dieser Webseite identifiziert werden, um Ihnen die Verwendung unseres Angebotes - 
                        auch bei Wiederholungsbesuchen - zu erleichtern.Sie haben aber in der Regel die Möglichkeit, Ihren Internetbrowser so einzustellen, 
                        dass Sie über das Auftreten von Cookies informiert werden, so dass Sie diese zulassen oder ausschließen, beziehungsweise bereits 
                        vorhandene Cookies löschen können.Bitte verwenden Sie die Hilfefunktion Ihres Internetbrowsers, um Informationen zu der Änderung 
                        dieser Einstellungen zu erlangen. Wir weisen darauf hin, dass einzelne Funktionen unserer Webseite möglicherweise nicht 
                        funktionieren, wenn Sie die Verwendung von Cookies deaktiviert haben.Cookies erlauben nicht, dass ein Server private Daten von 
                        Ihrem Computer oder die von einem anderen Server abgelegten Daten lesen kann. Sie richten auf Ihrem Rechner keinen Schaden an und 
                        enthalten keine Viren.Wir stützen den Einsatz von Cookies auf Art. 6 Abs. 1 Buchst. f) DSGVO: die Verarbeitung erfolgt zur 
                        Verbesserung der Funktionsweise unserer Webseite. Sie ist daher zur Wahrung unserer berechtigten Interessen erforderlich.
                        </p>
                        Wir stützen den Einsatz des vorgenannten Analyse-Tools auf Art. 6 Abs. 1 Buchst. f) DSGVO: die Verarbeitung erfolgt zur Analyse 
                        des Nutzungsverhaltens und ist daher zur Wahrung unserer berechtigten Interessen erforderlich.
                       
                    </ol>
                    <br>
                    <ol type=1>
                        <li><b>Verwendung der Social Plugins von Facebook, Twitter, Google+ und Instagram</b></li>
                        </p>                        
                        Der Schutz Ihrer personenbezogenen Daten ist uns sehr wichtig. Deshalb verzichten wir auf die direkte Implementierung der 
                        verschiedenen Social Plugins.
                        </p>
                        Auf unseren Seiten setzen wir lediglich Links auf diese sozialen Netzwerke. Erst wenn Sie den Links folgen, werden die 
                        entsprechenden Social Plugins aktiviert.
                        </p>
                        Bitte beachten Sie, dass es erst dann zu einer Übermittlung von Daten kommt, wenn Sie die entsprechenden Social Plugins 
                        durch einen Klick auf den korrespondierenden Link aktiviert haben. Allein der Besuch unserer Seiten löst keine Datenübermittlung aus.
                        </p>
                        Aktivieren Sie die Datenübermittlung durch Betätigen des entsprechenden Links kommt es zu folgender Datenübertragung:                       
                    </ol>
                    <ol type=a>
                        <li><b>Facebook</b></li>
                        </p>                        
                        Auf unserer Seite sind dann Plugins des sozialen Netzwerks Facebook (Meta Platforms Inc., 1601 Willow Road, Menlo Park, CA 94025, USA) integriert. Die Facebook-Plugins erkennen Sie an dem Facebook-Logo oder dem "Like-Button" ("Gefällt mir") auf unserer Seite.
                        </p> 
                        Eine Übersicht über die Facebook-Plugins finden Sie hier:
                        </p> 
                        <a href="http://developers.facebook.com/docs/plugins/" target="_blank">http://developers.facebook.com/docs/plugins/ </a>                        
                        </p> 
                        Es wird über das Plugin eine direkte Verbindung zwischen Ihrem Browser und dem Facebook-Server hergestellt. Facebook erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Facebook "Like-Button" anklicken während Sie in Ihrem Facebook-Account eingeloggt sind, können Sie die Inhalte unserer Plattform auf Ihren Facebook-Account verlinken. Dadurch kann Facebook den Besuch unserer Seite Ihrem Account zuordnen.
                        </p> 
                        Wir weisen darauf hin, dass wir als Seitenbetreiber keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Facebook erhalten.
                        </p> 
                        Weitere Informationen finden Sie in der Datenschutzerklärung von Facebook:
                        </p> 
                        <a href="http://de-de.facebook.com/policy.php " target="_blank">http://de-de.facebook.com/policy.php </a>                        
                        </p> 
                        Wenn Sie nicht wünschen, dass Facebook den Besuch unserer Seite Ihrem Facebook-Account zuordnen kann, loggen Sie sich bitte aus Ihrem Facebook-Account aus, oder aktivieren Sie die Social Plugins nicht.

                        </p>
                        <li><b>Twitter</b></li>
                        </p>
                        Auf unserer Webseite sind dann Funktionen des Dienstes Twitter eingebunden. Diese Funktionen werden angeboten durch die Twitter Inc., 1355 Market St, Suite 900, San Francisco, CA 94103, USA.
                        </p>
                        Durch das Benutzen von Twitter und der Funktion "Re-Tweet" werden die von Ihnen besuchten Seiten mit Ihrem Twitter-Account verknüpft und anderen Nutzern bekannt gegeben. Dabei werden auch Daten an Twitter übertragen.
                        </p>
                        Wir weisen darauf hin, dass wir als Seitenbetreiber keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Twitter erhalten.
                        </p>
                        Weitere Informationen finden Sie in der Datenschutzerklärung von Twitter:
                        </p>
                        <a href="http://twitter.com/privacy" target="_blank">http://twitter.com/privacy</a>.
                        </p>
                        Ihre Datenschutzeinstellungen bei Twitter können Sie in den Konto-Einstellungen ändern:
                        </p>
                        <a href="http://twitter.com/account/settings" target="_blank">http://twitter.com/account/settings</a>
                        

                        </p>
                        <li><b>Google +</b></li>
                        </p>
                        Auf unserer Seite sind dann Funktionen des Dienstes Google+ eingebunden. Diese Funktionen werden angeboten durch die Google Ireland Limited, Gordon House, 
                        Barrow Street, Dublin 4, Irland.
                        </p>
                        Durch das Benutzen von Google+ und der „+1“-Schaltfläche baut der Browser eine direkte Verbindung mit den Servern von Google auf. Der Inhalt der 
                        “+1″-Schaltfläche wird von Google direkt an seinen Browser übermittelt und von diesem in die Webseite eingebunden.
                        </p>
                        Wir haben keinen Einfluss auf den Umfang der Daten, die Google mit der Schaltfläche erhebt.
                        </p>
                        Zweck und Umfang der Datenerhebung und die weitere Verarbeitung und Nutzung der Daten durch Google sowie Ihre diesbezüglichen Rechte und 
                        Einstellungsmöglichkeiten zum Schutz Ihrer Privatsphäre können Sie den Googles Datenschutzhinweisen zu der “+1"-Schaltfläche entnehmen:
                        </p>                        
                        <a href="http://www.google.com/intl/de/+/policy/+1button.html" target="_blank">http://www.google.com/intl/de/+/policy/+1button.html</a>
                        </p>
                        Alternativ siehe auch:
                        </p>
                        <a href="http://www.google.com/intl/de/+1/button/" target="_blank">http://www.google.com/intl/de/+1/button/</a>
                        

                        </p>
                        <li><b>Instagram</b></li>
                        </p>
                        Auf unserer Seite sind dann Funktionen des Dienstes Instagram eingebunden. Diese Funktionen werden angeboten durch die 
                        Instagram LLC, 1601 Willow Road Menlo Park, CA 94025, USA. Seit 2012 gehört die Instagram LLC zum Konzernverbund der Meta 
                        Platforms, Inc., 1601 Willow Road, Menlo Park, CA 94025, USA.
                        </p>
                        Eine Übersicht über die Instagram-Plugins finden Sie hier:
                        </p>
                        <a href="http://blog.instagram.com/post/36222022872/introducing-instagram-badges" target="_blank">http://blog.instagram.com/post/36222022872/introducing-instagram-badges</a>
                        </p>
                        Wenn Sie mit den Plugins interagieren, zum Beispiel den „Instagram“-Button betätigen, wird über das Plugin eine direkte 
                        Verbindung zwischen Ihrem Browser und dem Instagram-Server hergestellt. Instagram erhält dadurch die Information, dass Ihr 
                        Browser die entsprechende Seite unserer Webseite aufgerufen hat, auch wenn Sie keinen Instagram-Account besitzen oder gerade 
                        nicht bei Instagram eingeloggt sind. Diese Information (einschließlich Ihrer IP-Adresse) wird von Ihrem Browser direkt an 
                        den Server von Instagram in die USA übermittelt und dort gespeichert. Dadurch kann Instagram den Besuch unserer Seite Ihrem 
                        Instagram-Account zuordnen.
                        Wir weisen darauf hin, dass wir als Seitenbetreiber keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung 
                        durch Instagram erhalten.
                        </p>
                        Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von Instagram:
                        </p>
                        
                        <a href="https://www.instagram.com/about/legal/privacy/ " target="_blank">https://www.instagram.com/about/legal/privacy/ </a>
                        </p>
                        Wenn Sie nicht wünschen, dass Instagram den Besuch unserer Seite Ihrem Instagram-Account zuordnen kann, loggen Sie sich 
                        bitte aus Ihrem Instagram-Account aus, oder aktivieren Sie die Social Plugins nicht.
                        </p> 
                        Wir stützen den Einsatz der vorgenannten Social Plugins auf Art. 6 Abs. 1 Buchst. a) DSGVO. Mit Aktivierung des entsprechenden 
                        Links erteilen Sie uns Ihre Einwilligung zur Datenverarbeitung. Jedenfalls ist die Datenverarbeitung nach Art. 6 Abs. 1 Buchst. 
                        f) DSGVO zulässig; mit Aktivierung des entsprechenden Links erfolgt die Datenverarbeitung zu Werbezwecken und liegt daher in 
                        unserem berechtigten Interesse.

                        </p>
                        <li><b>TikTok</b></li>
                        Wenn Sie eine unserer Seiten besuchen, auf der TikTok-Plugins integriert sind, wird eine direkte Verbindung zwischen Ihrem 
                        Browser und den Servern von TikTok hergestellt. TikTok erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere 
                        Seite besucht haben. Wenn Sie TikTok Plugins anklicken und währenddessen in Ihrem TikTok-Account eingeloggt sind, können Sie 
                        den Inhalt unserer Seiten auf Ihrem TikTok-Profil verlinken. Dadurch kann TikTok den Besuch unserer Seiten Ihrem Benutzerkonto 
                        zuordnen. Wir weisen darauf hin, dass wir als Anbieter dieser Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie 
                        deren Nutzung durch TikTok erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von TikTok unter 
                        <a href="https://www.tiktok.com/legal/privacy-policy " target="_blank">https://www.tiktok.com/legal/privacy-policy </a>
                        . Wenn Sie nicht wünschen, dass TikTok den Besuch unserer Seiten Ihrem 
                        TikTok-Nutzerkonto zuordnen kann, loggen Sie sich bitte aus Ihrem TikTok-Benutzerkonto aus bevor Sie unsere Seiten besuchen. 
                        </p>
                    </ol>
                    <br>
                    <ol type=1>
                        <li><b>Verwendung der Social Plugins von YouTube</b></li>
                        </p>
                        Unsere Webseite verweist durch Links zudem auf die Seite des sozialen Netzwerkes YouTube. Wir betreiben auch hier eine Social Media Seite.
                        </p>
                        Wenn Sie auf einen Link zu YouTube klicken, werden Sie auf die jeweilige externe Seite bei YouTube weitergeleitet. Sind Sie zudem 
                        als Mitglied bei YouTube eingeloggt, kann der Betreiber, die YouTube LLC, 901 Cherry Ave., San Bruno, CA 94066 USA Ihren Besuch auf 
                        unserer Seite Ihrem jeweiligen Nutzer-Account zuordnen. Hierbei weisen wir darauf hin, dass die YouTube LLC zum Konzernverbund der Google 
                        Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland gehört.
                        </p>
                        Wenn Sie nicht möchten, dass YouTube über Ihren Besuch auf unserer Internetseite Daten sammelt und speichert, müssen Sie sich vor dem Klick 
                        auf den Link aus Ihrem YouTube-Account ausloggen.
                        </p>
                        Zweck und Umfang der Datenerhebung und die weitere Verarbeitung und Nutzung Ihrer Daten durch YouTube entnehmen Sie bitte der entsprechenden 
                        Datenschutzerklärung:
                        </p>
                        <a href="http://www.google.de/intl/de/policies/privacy/" target="_blank">http://www.google.de/intl/de/policies/privacy/ </a>                        
                        </p>
                        Wir stützen den Einsatz von YouTube auf Art. 6 Abs. 1 Buchst. a) DSGVO. Mit Betätigung des Links unter gleichzeitiger Anmeldung bei YouTube 
                        erteilen Sie uns Ihre Einwilligung zur Datenverarbeitung. Jedenfalls ist die Datenverarbeitung nach Art. 6 Abs. 1 Buchst. f) DSGVO zulässig; 
                        mit Betätigung des Links unter gleichzeitiger Anmeldung im jeweiligen Sozialen Netzwerk erfolgt die Datenverarbeitung zu Werbezwecken und 
                        liegt daher in unserem berechtigten Interesse.                      
                    </ol>
                     <br>
                    <ol type=1>
                        <li><b>Verwendung der Plugins von Vimeo</b></li>
                        </p>
                        Wir nutzen für die Einbindung von Videos auf unserer Webseite die Plugins von Vimeo. Vimeo wird betrieben von der Vimeo LLC, 555 West 18th Street, 
                        New York, New York 10011, USA.
                        </p>
                        Wenn Sie eine Webseite mit dem Vimeo-Plugin aufrufen, wird eine Verbindung zu den Servern von Vimeo hergestellt. Hierdurch wird an den Server von 
                        Vimeo übermittelt, welche Webseite Sie besucht haben. Sind Sie dabei als Mitglied bei Vimeo eingeloggt, ordnet Vimeo diese Information Ihrem 
                        persönlichen Benutzerkonto zu. Bei Nutzung des Plugins wie z.B. dem Abspielen eines Video durch Aktivieren des Play-Buttons wird diese Information 
                        ebenfalls Ihrem Benutzerkonto zugeordnet. Sie können diese Zuordnung verhindern, indem Sie sich vor der Nutzung unserer Webseite aus ihrem 
                        Vimeo-Benutzerkonto abmelden und die entsprechenden Cookies von Vimeo löschen.
                        </p>
                        Weitere Informationen zur Datenverarbeitung und Hinweise zum Datenschutz durch Vimeo:
                        </p>
                        <a href="https://vimeo.com/privacy" target="_blank">https://vimeo.com/privacy</a>                         
                        </p>
                        Wir stützen den Einsatz von Vimeo auf Art. 6 Abs. 1 Buchst. a) DSGVO. Mit Betätigung des Plugins unter gleichzeitiger Anmeldung bei Vimeo erteilen 
                        Sie uns Ihre Einwilligung zur Datenverarbeitung. Jedenfalls ist die Datenverarbeitung nach Art. 6 Abs. 1 Buchst. f) DSGVO zulässig; mit Betätigung 
                        des Plugins unter gleichzeitiger Anmeldung im jeweiligen Sozialen Netzwerk erfolgt die Datenverarbeitung zu Werbezwecken und liegt daher in unserem 
                        berechtigten Interesse.                      
                    </ol>
                    <br>
                    <ol type=1>
                        <li><b>Verwendung von Google Webfonts, Google Maps und OpenStreetMap</b></li>
                    </ol>
                    <ol type=a>
                        <li><b>Verwendung von Google Webfonts und Google Maps</b></li>                                               
                        </p>
                        Auf unserer Webseite werden externe Schriften, sog. Google Webfonts, verwendet. Zudem benutzen wir Google Maps, um für Sie den Anfahrtsweg zu unserem 
                        Unternehmen darzustellen und Ihnen die Planung der Anfahrt zu vereinfachen.
                        </p>
                        Auch hierbei greifen wir auf Dienste der Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland zurück. 
                        </p>
                        Dazu lädt beim Aufrufen unserer Seite Ihr Browser die benötigten Informationen vom Google-Server in den USA in ihren Browsercache. Dies ist notwendig 
                        damit auch Ihr Browser eine optisch verbesserte Darstellung unserer Texte anzeigen kann, bzw. die Karte auf unserer Webseite dargestellt wird.
                        </p>
                        Hierdurch wird an den Google-Server übermittelt, welche unserer Internetseiten Sie besucht haben. Auch wird Ihre IP-Adresse von Google gespeichert.
                        </p>
                        Weitergehende Informationen zu Google Webfonts:
                        </p>
                        <a href="https://developers.google.com/fonts/faq?hl=de-DE&csw=1" target="_blank">https://developers.google.com/fonts/faq?hl=de-DE&csw=1</a> 
                        </p>
                        Weitergehende Informationen zu Google Maps:
                        </p>
                        <a href="https://www.google.com/intl/de_de/help/terms_maps.html" target="_blank">https://www.google.com/intl/de_de/help/terms_maps.html</a> 
                        </p>
                        Allgemeine Informationen zum Thema Datenschutz bei Google:
                        </p>
                        <a href="http://www.google.com/policies/privacy/" target="_blank">http://www.google.com/policies/privacy/</a> 
                        
                        <br>
                        <br>

                        <li><b>Verwendung von OpenStreetMap</b></li>                                               
                        </p>
                        Wir verwenden auf unserer Webseite einen Kartenausschnitt von OpenStreetMap:
                        </p>
                        <a href="https://www.openstreetmap.de" target="_blank">https://www.openstreetmap.de</a>
                        </p>
                        Bei OpenStreetMap handelt es sich um ein Open-Source-Mapping-Werkzeug. Damit Ihnen die Karte angezeigt werden kann, wird Ihre IP-Adresse an 
                        OpenStreetMap weitergeleitet. Wie OpenStreetMap Ihre Daten speichert, können Sie auf der Datenschutzseite von OpenStreetMap einsehen:
                        </p>
                        <a href="https://wiki.openstreetmap.org/wiki/DE:Datenschutz" target="_blank">https://wiki.openstreetmap.org/wiki/DE:Datenschutz</a>
                        </p>
                        Alternativ siehe auch:
                        </p>
                        <a href="https://wiki.openstreetmap.org/wiki/DE:Legal_FAQ" target="_blank">https://wiki.openstreetmap.org/wiki/DE:Legal_FAQ</a> 
                        </p>
                        Wir stützen den Einsatz der vorgenannten Tools auf Art. 6 Abs. 1 Buchst. f) DSGVO: die Datenverarbeitung erfolgt zur Verbesserung der 
                        Nutzerfreundlichkeit auf unserer Webseite und liegt daher in unserem berechtigten Interesse.                      
                    </ol>
                    <br>
                    <ol type=1>
                        <li><b>Dauer der Speicherung</b></li>
                        Ihre Daten werden so lange gespeichert, wie es für die Bearbeitung Ihres Anliegens erforderlich ist, bzw. bis die einschlägigen 
                        Lösch- und Aufbewahrungsfristen abgelaufen sind.
                    </ol>
                    <ol type=1 start="10">
                        <li><b>Betroffenenrechte</b></li>
                    </ol>
                    <ol type=a>
                        <li><b>Auskunftsrecht</b></li>                                               
                        </p>
                        Sie haben das Recht, von uns eine Bestätigung darüber zu verlangen, ob Sie betreffende personenbezogene Daten verarbeitet werden. 
                        Senden Sie hierfür bitte einfach eine E-Mail an: 
                        </br>{{ $business_location->email }}

                        <br>
                        
                        <li><b>Berichtigung / Löschung / Einschränkung der Verarbeitung</b></li>                                               
                        </p>
                        Des Weiteren haben Sie das Recht, von uns zu verlangen, dass 
                        </p>
                        Sie betreffende unrichtige personenbezogene Daten unverzüglich berichtigt werden (Recht auf Berichtigung);
                        </p>
                        Sie betreffende personenbezogene Daten unverzüglich gelöscht werden (Recht auf Löschung) und
                        </p>
                        die Verarbeitung eingeschränkt wird (Recht auf Einschränken der Verarbeitung).
                        </p>
                        Senden Sie hierfür bitte einfach eine E-Mail an:
                        </br>{{ $business_location->email }}

                        <br>
                        
                        <li><b>Recht auf Datenübertragbarkeit</b></li>                                               
                        </p>
                        Sie haben das Recht, Sie betreffende personenbezogene Daten, die Sie uns bereitgestellt haben, in einem strukturierten, 
                        gängigen und maschinenlesbaren Format zu erhalten und diese Daten einem anderen Verantwortlichen zu übermitteln.
                        </p>
                        Senden Sie hierfür bitte einfach eine E-Mail an:
                        </br>{{ $business_location->email }}

                        <br>
                        
                        <li><b>Widerrufsrecht</b></li>                                               
                        </p>
                        Sie haben das Recht, Ihre Einwilligung jederzeit zu widerrufen. Durch den Widerruf der Einwilligung wird die 
                        Rechtmäßigkeit der aufgrund der Einwilligung bis zum Widerruf erfolgten Verarbeitung nicht berührt.
                        </p>
                        Senden Sie hierfür bitte einfach eine E-Mail an:
                        </br>{{ $business_location->email }}

                        <br>
                        
                        <li><b>Widerspruchsrecht</b></li>                                               
                        </p>
                        Ist die Verarbeitung Sie betreffender personenbezogener Daten für die Wahrnehmung einer Aufgabe, die im 
                        öffentlichen Interesse liegt (Art. 6 Abs. 1 Buchst. e) DSGVO) oder zur Wahrung unserer berechtigten 
                        Interessen (Art. 6 Abs. 1 Buchst. f) DSGVO) erforderlich, steht Ihnen ein Widerspruchsrecht zu.
                        </p>
                        Senden Sie hierfür bitte einfach eine E-Mail an:
                        </br>{{ $business_location->email }}

                        <br>
                        
                        <li><b>Beschwerderecht</b></li>                                               
                        </p>
                        Sind Sie der Ansicht, dass die Verarbeitung der Sie betreffenden personenbezogenen Daten gegen die DSGVO 
                        verstößt, haben Sie unbeschadet anderweitiger Rechtsbehelfe das Recht auf Beschwerde bei einer Aufsichtsbehörde.
                    </ol>
                    <ol type=1 start="11">
                        <li><b>Datenschutzbeauftragter</b></li>
                        </p>
                        Unseren Datenschutzbeauftragten erreichen Sie unter:
                        </br>{{ $business_location->email }}
                    </ol>
                    <ol type=1 start="12">
                        <li><b>Datenschutzbeauftragter</b></li>
                        </p>
                        Für die Sicherheit Ihrer Daten verwenden wir modernste Internettechnologien. Während des Onlineanfrageprozesses sind 
                        Ihre Angaben mit einer SSL-Verschlüsselung gesichert. Für eine sichere Speicherung Ihrer Daten werden unsere Systeme 
                        durch Firewalls geschützt, die unberechtigte Zugriffe von außen verhindern.
                        </p>
                        <b>Datensicherheit Dienstleister: NEOBURG</b>
                        </p>
                        Ich habe zur Kenntnis genommen und willige ein, dass {{ $business_name }} zum Schutze meiner Daten mit der Firma NEOBURG als 
                        IT-Spezialisten zusammenarbeitet.
                        </p>
                        Die Verarbeitung von personenbezogenen Daten ist ein sensibler Bereich, der gesetzlichen Regelungen und Anforderungen 
                        unterliegt. NEOBURG, ein Unternehmen mit Sitz in Tullastrasse 89, 79108 Freiburg im Breisgau, erhebt, verarbeitet und 
                        nutzt Ihre personenbezogenen Daten im Rahmen einer Auftragsdatenverarbeitung gemäß Artikel 28 DSGVO.
                        </p>
                        Hierfür wurde eine gesonderte Vereinbarung zwischen {{ $business_name }} und NEOBURG getroffen, um sicherzustellen, dass die 
                        Verarbeitung von Daten in Übereinstimmung mit den geltenden gesetzlichen Vorgaben erfolgt. Die Inanspruchnahme der 
                        Dienstleistungen von NEOBURG erfolgt ausschließlich in unserem Auftrag, sodass wir weiterhin datenschutzrechtlich 
                        verantwortlich bleiben.
                        </p>
                        Dabei gewährt diese Vereinbarung den Schutz Ihrer persönlichen Daten vor unbefugtem Zugriff und Missbrauch. Es ist 
                        daher nicht vorgesehen, dass Ihre persönlichen Daten an Dritte im Sinne des Datenschutzes weitergegeben werden, wenn 
                        dies nicht ausdrücklich vereinbart wurde.
                        </p>
                        Insgesamt sind wir bei {{ $business_name }} bestrebt, Ihren Schutz und die Vertraulichkeit Ihrer persönlichen Daten zu gewährleisten, 
                        um den höchsten Standard an Datenschutz und Datenschutzgarantien zu gewährleisten.

                        </br>
                    </ol>
    </div>
            <!-- /.card-body -->
@endsection 
