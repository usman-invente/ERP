<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ContactsRegistration extends Model
{
    use Notifiable;
    // use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
   

    public function business()
    {
        return $this->belongsTo(\App\Business::class);
    }

    public function location()
    {
        return $this->belongsTo(\App\BusinessLocation::class);
    }

    public function createContactsRegistration(){
        
        // $contact = ContactsRegistration::create($input);

        return '$contact';
    }

    public function getTextNotification($business_id, $location_id, $token){

        $business = Business::findOrFail($business_id);
        $business_location = BusinessLocation::findOrFail($location_id);
        // $data['email_settings'] = $business->email_settings;
        // $data['business_name'] = $business->name;
        // $data['subject'] = 'Verifizieren die Anmeldung für '.$business->name;
        // $data['email_body'] = 'Thank you from {business_name}';

        $data = [
            'bussines_name' => $business->name,
            'subject' =>'Verifizieren die Anmeldung für '.$business->name,
            'email_body' =>$this->getEmailBodyForContactRegister($business, $business_location, $token),
        ];
        

        return $data;
    }

    public function getEmailBodyForContactRegister($business, $business_location, $token){

        $contacts_registration = ContactsRegistration::where('registration_token','=',$token)
                            ->first();

        $consent_email = 'Nein';
        $consent_mobile = 'Nein';
        $consent_post = 'Nein';
        $consent_messenger = 'Nein';
                    
        if($contacts_registration->consent_email)
            $consent_email = 'Ja';
        if($contacts_registration->consent_mobile)
            $consent_mobile = 'Ja';
        if($contacts_registration->consent_post)
            $consent_post = 'Ja';
        if($contacts_registration->consent_messenger)
            $consent_messenger = 'Ja';
        
        $business_name = str_replace(" ", "_", $business->name);
        $business_name = str_replace("/", "$&", $business_name);

        $html = '<div class="btn-group">

                <div class="col-sm-2">
                <div>';
                    if(!empty($business->logo)){
                     $html .=' <img class="img-responsive" src="'. url( 'uploads/business_logos/' . $business->logo ) .'" alt="Business Logo" style="height: height: 100px; width: 100px;"> ';                                   
                    }
                $html .='
                </div>
            </div> <br>
                ';
        
        $html .= 'Einwilligungserklärung zur Datennutzung gemäß DSGVO <br>
        Ich erkläre mich hiermit freiwillig und ausdrücklich damit einverstanden, dass meine personenbezogenen Daten gemäß der Datenschutz-Grundverordnung (DSGVO) genutzt werden dürfen.
        <br>
        Um die Registrierung abzuschließen, bitte klicken Sie auf dem Button "Registrierung abschließen"
        <br>
        <br>
        <table border=”0″ cellpadding=”0″ cellspacing=”0″ role=”presentation” style="border-collapse:separate;line-height:100%;">
            <tr>                    
              <td align="center" bgcolor="#C3F9C5" role="presentation" style="border:none;border-radius:6px;cursor:auto;padding:11px 20px;background:#C3F9C5;" valign="middle">                    
                <a href="'.url($business->id.'/'.$business_location->id.'/'.$business_name.'/customer-complete-registration/'.$token.'').'" 
                        style="background:#C3F9C5;color:black;font-family:Helvetica, sans-serif;font-size:18px;font-weight:600;line-height:120%;Margin:0;" target="_blank">                    
                './*__('messages.complete_registration')*/'Registrierung abschließen'.'                    
                </a>                    
              </td>                    
            </tr>      
        </table>
        
        <br>
        <br>
        Verantwortlich für die Datennutzung ist <br> 
        Firma: "'.$business->name.'", 
        <br> Adresse: "'.$business_location->getBusinessLocationAddress().'" , 
        <br> Kontaktdaten: "'.$business_location->getBusinessLocationContact().'".
        
        <br>
        <br>
        
        Einwilligung bis auf Widerruf:
        <br>
        E-Mail:'.$consent_email.'
        <br>
        Telefon:'.$consent_messenger.'
        <br>
        Post:'.$consent_mobile.'
        <br>
        Messenger:'.$consent_messenger.'
        <br>
        Weitere Informationen zur Einwilligung finden Sie <a href="'.url($business->id.'/'.$business_location->id.'/'.$business->name.'/einwilligungen').'">hier</a>.      
       
        <br>
        <br>

        Änderung oder Löschung der Einwilligung per Mail an '. $business_location->email.'
        <br>
        Oder über den Link <a href=" {{ route(\'registration-revocation\') }}" target="_blank">https://neoburg.net/registration-revocation</a> unseres Dienstleisters NEOBURG für IT-Sicherheit
        <br> 
        
        <br>
        Vielen Dank für die Registrierung, <br>'.
            $business->name .' '. $business_location->website;

        $html .= '
        </div>';

        return $html;
    }

    public function getChangeDataTextNotification($business_id, $contact_id, $token){

        $business = Business::findOrFail($business_id);
        $contact = Contact::findOrFail($contact_id);

        // $data = ['$business_id = '. $business_id .' <br> ID = '.$contact_id .' + token = '.$token];

        $data = [
            'bussines_name' => $business->name,
            'subject' =>'Verifizieren die Anmeldung für '.$business->name,
            'email_body' =>$this->getEmailBodyForContactChangeData($business, $contact, $token),
        ];        

        return $data;
    }

    public function getEmailBodyForContactChangeData($business, $contact, $token){

        $contacts_registration = ContactsRegistration::where('registration_token','=',$token)
                            ->first();

        $self_change_data = json_decode($contact->self_change_data);
        
        $business_name = str_replace(" ", "_", $business->name);
        $business_name = str_replace("/", "$&", $business_name);        

        $consent_email = 'Nein';
        $consent_mobile = 'Nein';
        $consent_post = 'Nein';
        $consent_messenger = 'Nein';
        
        if($self_change_data->consent_email == true)
            $consent_email = 'Ja';
        if($self_change_data->consent_mobile == true)
            $consent_mobile = 'Ja';
        if($self_change_data->consent_post == true)
            $consent_post = 'Ja';
        if($self_change_data->consent_messenger == true)
            $consent_messenger = 'Ja';

        $html = '<div class="btn-group">
        ';

            //     <div class="col-sm-2">
            //     <div>';
            //         if(!empty($business->logo)){
            //          $html .=' <img class="img-responsive" src="'. url( 'uploads/business_logos/' . $business->logo ) .'" alt="Business Logo" style="opacity: .8; height: 100px; width: 100px;"> ';                                   
            //         }
            //     $html .='
            //     </div>
            // </div> 
            //     ';
                
        $html .= 'Bitte klicken Sie auf dem Button "'./*__('messages.complete_change_contact_data')*/'Datenänderungen abschließen'.'", um die Änderung der Daten vorzunehmen.
        
        <table border=”0″ cellpadding=”0″ cellspacing=”0″ role=”presentation” style="border-collapse:separate;line-height:100%;">
            <tr>                    
              <td align="center" bgcolor="#C3F9C5" role="presentation" style="border:none;border-radius:6px;cursor:auto;padding:11px 20px;background:#C3F9C5;" valign="middle">                    
                <a href="'.url($business->id.'/'.$contact->id.'/customer-complete-change-data/'.$token.'').'" 
                        style="background:#C3F9C5;color:black;font-family:Helvetica, sans-serif;font-size:18px;font-weight:600;line-height:120%;Margin:0;" target="_blank">                    
                './*__('messages.complete_change_contact_data')*/'Datenänderungen abschließen'.'                    
                </a>                    
              </td>                    
            </tr>      
        </table>
        
        <br>Die geänderte Daten sind:';
        if($contact->first_name != $self_change_data->first_name) 
            $html .= ' <br> Vorname: "'.$self_change_data->first_name.'"';

        if($contact->last_name != $self_change_data->last_name)
            $html .= '<br> Nachname: "'.$self_change_data->last_name.'" ';
            
        if($contact->prefix != $self_change_data->prefix)
            $html .= '<br> Anrede: "'.$self_change_data->prefix.'" ';

        if($contact->title != $self_change_data->title)
            $html .= '<br> Titel: "'.$self_change_data->title.'" ';

        if($contact->supplier_business_name != $self_change_data->supplier_business_name)
            $html .= '<br> Firmennamen: "'.$self_change_data->customer_business_name.'" ';
        
        if($contact->business_position != $self_change_data->business_position)
            $html .= '<br> Position: "'.$self_change_data->business_position.'" ';
        
        if($contact->mobile != $self_change_data->mobile)
            $html .= '<br> Handy: "'.$self_change_data->mobile.'" ';        
            
        if($contact->consent_email != $self_change_data->consent_email)
            $html .= '<br> Einwilligung E-Mail : "'.$consent_email.'" ';
            
        if($contact->consent_mobile != $self_change_data->consent_mobile)
            $html .= '<br> Einwilligung Telefon: "'.$consent_mobile.'" ';
            
        if($contact->consent_post != $self_change_data->consent_post)
            $html .= '<br> Einwilligung Post: "'.$consent_post.'" ';
            
        if($contact->consent_messenger != $self_change_data->consent_messenger)
            $html .= '<br> Einwilligung Messenger: "'.$consent_messenger.'" ';
        
        if($contact->street != $self_change_data->street)
            $html .= '<br> Straße: "'.$self_change_data->street.'" ';
        
        if($contact->house_nr != $self_change_data->house_nr)
            $html .= '<br> Haus Nr.: "'.$self_change_data->house_nr.'" ';
        
        if($contact->zip_code != $self_change_data->zip_code)
            $html .= '<br> Postleitzahl: "'.$self_change_data->zip_code.'" ';
        
        if($contact->city != $self_change_data->city)
            $html .= '<br> Stadt: "'.$self_change_data->city.'" ';
        
        if($contact->country != $self_change_data->country)
            $html .= '<br> Land: "'.$self_change_data->country.'" ';

        if($contact->bank != $self_change_data->bank)
            $html .= '<br> Bank: "'.$self_change_data->bank.'" ';

        if($contact->iban != $self_change_data->iban)
            $html .= '<br> IBAN: "'.$self_change_data->iban.'" ';

        if($contact->bic != $self_change_data->bic)
            $html .= '<br> BIC: "'.$self_change_data->bic.'" ';

        if($contact->paypal != $self_change_data->paypal)
            $html .= '<br> '.__('lang_v1.paypal').': "'.$self_change_data->paypal.'" ';
        
        if($contact->consent_field4 != $self_change_data->consent_field4){
            if($self_change_data->consent_field4 == true)
                $html .= '<br> Zahlungsdienstleister: wurde aktiviert.';
            else
                $html .= '<br> Zahlungsdienstleister: wurde deaktiviert.';
        }

        $html .= '
                     </div>';

        return $html;
    }

    public function getChangeOrDeleteDataDataTextNotification($contact){


        // $data = ['$business_id = '. $business_id .' <br> ID = '.$contact_id .' + token = '.$token];

        $data = [
            // 'bussines_name' => $business->name,
            // 'subject' =>'Verifizieren die Anmeldung für '.$business->name,
            'email_body' =>$this->getEmailBodyForChangeOrDeleteData($contact),
        ];        

        return $data;
    }
    
    public function getEmailBodyForChangeOrDeleteData($contact){
        $business = Business::where('id','=',1)
                            ->first();
                            
        $html = '
                    <style>
                      .button {
                          background-color: #75E379;
                          border: none;
                          color: black;
                          padding: 10px 20px;
                          text-align: center;
                          text-decoration: none;
                          display: inline-block;
                          font-size: 13px;
                          margin: 4px 2px;
                          cursor: pointer;
                        }
                    </style>
            <div class="btn-group">';
                // <div class="col-sm-2">
                //     <div>';
                //         if(!empty($business->logo)){
                //          $html .=' <img class="img-responsive" src="'. url( 'uploads/business_logos/' . $business->logo ) .'" alt="Business Logo" style="opacity: .8; height: 100px; width: 100px;"> ';                                   
                //         }
                //     $html .='
                //     </div>
                // </div> 
                //         ';
        $html .= 'Sehr geehrte/r '. $contact->getFullNameAndTitle().'
                <br><br>
                hiermit möchten wir Sie darüber informieren, dass wir von NEOBURG eine Anfrage 
                <br>
                bezüglich der Änderung Ihrer Kundendaten und Einwilligungen erhalten haben. Die wir als 
                <br>
                Software-Unternehmen für unseren Partner und Sie sicher verwalten. Als Software-Unternehmen
                <br>
                legen wir großen Wert auf die sichere Verwaltung Ihrer Informationen und möchten versichern, 
                <br>
                dass wir alle notwendigen Maßnahmen getroffen haben, um die Vertraulichkeit Ihrer Daten zu gewährleisten
                <br><br>

                Wenn Sie diese Anfrage gestell haben, bitten wir Sie den folgenden Link zu betätigen. 
                <br><br>
                
                <table border=”0″ cellpadding=”0″ cellspacing=”0″ role=”presentation” style="border-collapse:separate;line-height:100%;">
                    <tr>                    
                      <td align="center" bgcolor="#C3F9C5" role="presentation" style="border:none;border-radius:6px;cursor:auto;padding:11px 20px;background:#C3F9C5;" valign="middle">                    
                        <a href="'.url($contact->first_name.'/'.$contact->email.'/list-of-registration').'" 
                                style="background:#C3F9C5;color:black;font-family:Helvetica, sans-serif;font-size:18px;font-weight:600;line-height:120%;Margin:0;" target="_blank">                    
                        './*__('lang_v1.list_of_registration')*/'Auflistung aller Partner'.'                    
                        </a>                    
                      </td>                    
                    </tr>      
                </table>
                <br><br>

                Falls Sie diese Änderungsanfrage nicht initiiert haben, können Sie diese E-Mail ignorieren.
                <br><br>
                
                Wir möchten darauf hinweisen, dass alle Ihre Daten bei uns sicher aufbewahrt werden und 
                <br>
                ohne Zugang zu Ihrem Mail-Account von Dritten geschützt sind. Wir möchten uns bei Ihnen 
                <br>
                für das Vertrauen bedanken, dass Sie uns als Partner und Kunde entgegenbringen und sind 
                <br>
                stolz darauf, Ihre Daten sicher und zuverlässig zu verwalten und schützen.
                <br><br>

                Mit freundlichen Grüßen
                <br><br>
                NEOBURG <br>

                Tullastr. 89 <br>

                79108 Freiburg im Breisgau
                <br>
                Germany
                <br>
                Tel.: +49 (0)761-88787329
                <br>
                Telefonischer Support
                <br>
                Mo - Fr 9:00 - 12:00 u. 14:00 - 18:00 Uhr
                <br>
                E-Mail: info@neoburg.net
                <br>
                Web: <a href="https://neoburg.net ">https://neoburg.net</a>
                
                ';
        
        $html .= '
            </div>';

        return $html;
    }
}
