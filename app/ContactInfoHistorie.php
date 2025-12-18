<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Util;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contact;

class ContactInfoHistorie extends Model
{
    function saveContactInfoHistorie($contact_id, $input, $data){
        // $contact_info_history = ContactInfoHistorie::where('contact_id', $contact_id)
                                    // ->first();
        $contact = Contact::where('id', $contact_id)->first();
        
        $new_data = json_decode($data);
       
        

        
        // if($input['type'] == "Customer"){
        if($input['title'] != "Neue Kunde erstellt"){
            if( $this->haveAnyChange($contact, $new_data) )
            {
                // $html = $contact->first_name .'- '. $new_data->first_name;
                // $html .= $contact->last_name .'- '. $new_data->last_name;
                // $html .= $contact->prefix .'- '. $new_data->prefix;
                // $html .= $contact->supplier_business_name .'- '. $new_data->supplier_business_name;

                $consent_details = array('consent_email' => $new_data->consent_email,
                                            'consent_mobile' => $new_data->consent_mobile,
                                            'consent_post' => $new_data->consent_post,
                                            'consent_messenger' => $new_data->consent_messenger
                                        );

                $contact_info_histroie = new ContactInfoHistorie();
                $contact_info_histroie->business_id = $input['business_id'];
                $contact_info_histroie->user_id = $input['user_id'];
                $contact_info_histroie->contact_id = $contact->id;
                $contact_info_histroie->title = $input['title'];
                $contact_info_histroie->description = $input['description'];
                $contact_info_histroie->type = $input['type'];
                if($input['ip_address'])
                    $contact_info_histroie->ip_address = $input['ip_address'];
                $contact_info_histroie->details = $this->getChangedData($contact, $new_data);            
                $contact_info_histroie->info_1 = $data;
                // $contact_info_histroie->info_2 = 'ketu'. $html;
                $contact_info_histroie->consent = json_encode($consent_details);
                $contact_info_histroie->save();
            }
        }

        if($input['title'] == "Neue Kunde erstellt"){
            
             $consent_details = array('consent_email' => $new_data->consent_email,
                                        'consent_mobile' => $new_data->consent_mobile,
                                        'consent_post' => $new_data->consent_post,
                                        'consent_messenger' => $new_data->consent_messenger
                                    );

            $contact_info_histroie = new ContactInfoHistorie();
            $contact_info_histroie->business_id = $input['business_id'];
            $contact_info_histroie->user_id = $input['user_id'];
            $contact_info_histroie->contact_id = $contact->id;
            $contact_info_histroie->title = $input['title'];
            $contact_info_histroie->description = $input['description'];
            $contact_info_histroie->type = $input['type'];
            if($input['ip_address'])
                $contact_info_histroie->ip_address = $input['ip_address'];
            $contact_info_histroie->details = $this->getSavedData($new_data);            
            $contact_info_histroie->info_1 = $data;
            $contact_info_histroie->consent = json_encode($consent_details);
            $contact_info_histroie->save();
        }
    }

    public function haveAnyChange($contact, $new_data){
        $new_data_consent_email = true;        
        $new_data_consent_mobile = true;        
        $new_data_consent_post = true;        
        $new_data_consent_messenger = true;        
        $new_data_consent_field1 = true;         
        $new_data_consent_field2 = true;         
        $new_data_consent_field3 = true;         
        $new_data_consent_field4 = true;         
        $new_data_consent_field5 = true;         

        if($new_data->consent_email == null)
            $new_data_consent_email = false;
        if($new_data->consent_mobile == null)
            $new_data_consent_mobile = false;
        if($new_data->consent_post == null)
            $new_data_consent_post = false;
        if($new_data->consent_messenger == null)
            $new_data_consent_messenger = false;
        if($new_data->consent_field1 == null)
            $new_data_consent_field1 = false;
        if($new_data->consent_field2 == null)
            $new_data_consent_field2 = false;
        if($new_data->consent_field3 == null)
            $new_data_consent_field3 = false;
        if($new_data->consent_field4 == null)
            $new_data_consent_field4 = false;
        if($new_data->consent_field5 == null)
            $new_data_consent_field5 = false;

        if($contact->first_name != $new_data->first_name 
             || $contact->title != $new_data->title 
             || $contact->prefix != $new_data->prefix 
             || $contact->first_name != $new_data->first_name 
             || $contact->last_name != $new_data->last_name 
             || $contact->supplier_business_name != $new_data->supplier_business_name 
             || $contact->business_position != $new_data->business_position 
             || $contact->mobile != $new_data->mobile 
             || $contact->street != $new_data->street 
             || $contact->house_nr != $new_data->house_nr 
             || $contact->city != $new_data->city 
             || $contact->state != $new_data->state 
             || $contact->country != $new_data->country 
             || $contact->zip_code != $new_data->zip_code 
             || $contact->consent_email != $new_data_consent_email 
             || $contact->consent_mobile != $new_data_consent_mobile 
             || $contact->consent_post != $new_data_consent_post 
             || $contact->consent_messenger != $new_data_consent_messenger 
             || $contact->bank != $new_data->bank 
             || $contact->iban != $new_data->iban 
             || $contact->bic != $new_data->bic 
             || $contact->consent_field1 != $new_data_consent_field1 
             || $contact->consent_field2 != $new_data_consent_field2 
             || $contact->consent_field3 != $new_data_consent_field3 
             || $contact->consent_field4 != $new_data_consent_field4 
             || $contact->consent_field5 != $new_data_consent_field5
        )
        {    
            return true;
        }else{
            return false;
        }
    }

    public function getChangedData($contact, $new_data){

        $html = "Diese Daten wurden geändert:";         
        
        if($contact->title != $new_data->title ){
            $html .= "<br> von: ". $contact->title .' auf: '. $new_data->title;
        }
        if($contact->prefix != $new_data->prefix ){
            $html .= "<br> von: ". $contact->prefix .' auf: '. $new_data->prefix;
        }
        if($contact->first_name != $new_data->first_name ){
            $html .= "<br> von: ". $contact->first_name .' auf: '. $new_data->first_name;
        }
        if($contact->last_name != $new_data->last_name ){
            $html .= "<br> von: ". $contact->last_name .' auf: '. $new_data->last_name;
        }
        if($contact->supplier_business_name != $new_data->supplier_business_name ){
            $html .= "<br> von: ". $contact->supplier_business_name .' auf: '. $new_data->supplier_business_name;
        }
        if($contact->business_position != $new_data->business_position ){
            $html .= "<br> von: ". $contact->business_position .' auf: '. $new_data->business_position;
        }
        if($contact->mobile != $new_data->mobile ){
            $html .= "<br> von: ". $contact->mobile .' auf: '. $new_data->mobile;
        }
        if($contact->street != $new_data->street ){
            $html .= "<br> von: ". $contact->street .' auf: '. $new_data->street;
        }
        if($contact->house_nr != $new_data->house_nr ){
            $html .= "<br> von: ". $contact->house_nr .' auf: '. $new_data->house_nr;
        }
        if($contact->city != $new_data->city ){
            $html .= "<br> von: ". $contact->city .' auf: '. $new_data->city;
        }
        if($contact->state != $new_data->state ){
            $html .= "<br> von: ". $contact->state .' auf: '. $new_data->state;
        }
        if($contact->country != $new_data->country ){
            $html .= "<br> von: ". $contact->country .' auf: '. $new_data->country;
        }
        if($contact->zip_code != $new_data->zip_code ){
            $html .= "<br> von: ". $contact->zip_code .' auf: '. $new_data->zip_code;
        }
        if($contact->bank != $new_data->bank ){
            $html .= "<br> von: ". $contact->bank .' auf: '. $new_data->bank;
        }
        if($contact->iban != $new_data->iban ){
            $html .= "<br> von: ". $contact->iban .' auf: '. $new_data->iban;
        }
        if($contact->bic != $new_data->bic ){
            $html .= "<br> von: ". $contact->bic .' auf: '. $new_data->bic;
        }
        if($contact->bic != $new_data->paypal ){
            $html .= "<br> von: ". $contact->paypal .' auf: '. $new_data->paypal;
        }

        return $html;
    }

    public function getSavedData($new_data){
        $html = "Diese Daten wurden gespeichert:";         
        
        if($new_data->title ){
            $html .= "<br> ". $new_data->title;
        }
        if($new_data->prefix ){
            $html .= "<br> ". $new_data->prefix;
        }
        if($new_data->first_name ){
            $html .= "<br> ". $new_data->first_name;
        }
        if($new_data->last_name ){
            $html .= "<br> ". $new_data->last_name;
        }
        if($new_data->supplier_business_name ){
            $html .= "<br> ". $new_data->supplier_business_name;
        }
        if($new_data->business_position ){
            $html .= "<br> ". $new_data->business_position;
        }
        if($new_data->email ){
            $html .= "<br> ". $new_data->email;
        }
        if($new_data->dob ){
            $html .= "<br> ". $new_data->dob;
        }
        if($new_data->mobile ){
            $html .= "<br> ". $new_data->mobile;
        }
        if($new_data->street ){
            $html .= "<br> ". $new_data->street;
        }
        if($new_data->house_nr ){
            $html .= "<br> ". $new_data->house_nr;
        }
        if($new_data->city ){
            $html .= "<br> ". $new_data->city;
        }
        if($new_data->state ){
            $html .= "<br> ". $new_data->state;
        }
        if($new_data->country ){
            $html .= "<br> ". $new_data->country;
        }
        if($new_data->zip_code ){
            $html .= "<br> ". $new_data->zip_code;
        }
        if($new_data->custom_field1 ){
            $html .= "<br> ". $new_data->custom_field1;
        }
        if($new_data->custom_field2 ){
            $html .= "<br> ". $new_data->custom_field2;
        }
        if($new_data->custom_field3 ){
            $html .= "<br> ". $new_data->custom_field3;
        }
        if($new_data->custom_field4 ){
            $html .= "<br> ". $new_data->custom_field4;
        }
        if($new_data->bank ){
            $html .= "<br> ". $new_data->bank;
        }
        if($new_data->iban ){
            $html .= "<br> ". $new_data->iban;
        }
        if($new_data->bic ){
            $html .= "<br> ". $new_data->bic;
        }
        if($new_data->paypal ){
            $html .= "<br> ". $new_data->paypal;
        }
        return $html;
    }

    public function returnSavedDataAsJson($contact){
        
        $contact_data = array(
                            'title' => $contact->title,
                            'prefix' => $contact->prefix,
                            'first_name' => $contact->first_name,
                            'last_name' => $contact->last_name,
                            'supplier_business_name' => $contact->supplier_business_name,
                            'business_position' => $contact->business_position,
                            'email' => $contact->email,
                            'dob' => $contact->dob,
                            'mobile' => $contact->mobile,
                            'consent_email' => $contact->consent_email,
                            'consent_mobile' => $contact->consent_mobile,
                            'consent_post' => $contact->consent_post,
                            'consent_messenger' => $contact->consent_messenger,
                            'street' => $contact->street,
                            'house_nr' => $contact->house_nr,
                            'city' => $contact->city,
                            'state' => $contact->state,
                            'country' => $contact->country,
                            'zip_code' => $contact->zip_code,
                            'custom_field1' => $contact->custom_field1,
                            'custom_field2' => $contact->custom_field2,
                            'custom_field3' => $contact->custom_field3,
                            'custom_field4' => $contact->custom_field4,
                            'bank' => $contact->bank,
                            'iban' => $contact->iban,
                            'bic' => $contact->bic,
                            'paypal' => $contact->paypal,
                            'consent_field1' => $contact->consent_field1,
                            'consent_field2' => $contact->consent_field2,
                            'consent_field3' => $contact->consent_field3,
                            'consent_field4' => $contact->consent_field4,
                            'consent_field5' => $contact->consent_field5,

                        );

        return json_encode($contact_data);
    }

    public function saveDokumentInfoToContactInfoHistorie($input, $id = null, $new_data = null){
        if($input['action'] == "New"){
            $contact_info_histroie = new ContactInfoHistorie();
            $contact_info_histroie->business_id = $input['business_id'];
            $contact_info_histroie->user_id = $input['user_id'];
            $contact_info_histroie->contact_id = $input['contact_id'];
            $contact_info_histroie->title = $input['title'];
            $contact_info_histroie->details = $input['details'];
            $contact_info_histroie->description = $input['description'];
            $contact_info_histroie->type = $input['type'];
            if($input['ip_address'])
                $contact_info_histroie->ip_address = $input['ip_address'];           
            $contact_info_histroie->info_1 = $input['info_1'];
            $contact_info_histroie->info_2 = $input['info_2'];
            $contact_info_histroie->save();
        }

        if($input['action'] == "edit"){ 

            $document_note = DocumentAndNote::where('business_id', $input['business_id'])
                            ->where('notable_id', $input['contact_id'])
                            ->findOrFail($id);

            if($document_note->heading != $new_data['new_heading'] || 
                $document_note->description != $new_data['new_description'] ||
                $document_note->is_private != $new_data['new_is_private']  )
            {

                $html = "Diese Daten wurden geändert:";         
    
                if($document_note->heading != $new_data['new_heading'] ){
                    $html .= "<br> von: ". $document_note->heading .' auf: '. $new_data['new_heading'];
                }
                if($document_note->description != $new_data['new_description'] ){
                    $html .= "<br> von: ". $document_note->description .' auf: '. $new_data['new_description'];
                }
                if($document_note->is_private != $new_data['new_is_private']){
                    if($new_data['new_is_private'] == 0)
                        $html .= "<br>Privat von: Ja ; auf: Nein";
                    else
                        $html .= "<br>Privat von: Nein ; auf: Ja";
                }

                $contact_info_histroie = new ContactInfoHistorie();
                $contact_info_histroie->business_id = $input['business_id'];
                $contact_info_histroie->user_id = $input['user_id'];
                $contact_info_histroie->contact_id = $input['contact_id'];
                $contact_info_histroie->title = $input['title'];
                $contact_info_histroie->details = "<b>Überschrift:\"". $new_data['new_heading']. "\" </b><br>".$html;
                $contact_info_histroie->description = $input['description'];
                $contact_info_histroie->type = $input['type'];
                if($input['ip_address'])
                    $contact_info_histroie->ip_address = $input['ip_address']; 
                $contact_info_histroie->info_1 = $input['info_1'];
                $contact_info_histroie->info_2 = $input['info_2'];
                $contact_info_histroie->save();
            }
        }
    }

    public function saveContractContactInfoHistorie($contact_id, $input, $data){
        $contact_info_histroie = new ContactInfoHistorie();
        $contact_info_histroie->business_id = $input['business_id'];
        $contact_info_histroie->user_id = $input['user_id'];
        $contact_info_histroie->contact_id = $contact_id;
        $contact_info_histroie->title = $input['title'];
        $contact_info_histroie->description = $input['description'];
        $contact_info_histroie->type = $input['type'];
        if($input['ip_address'])
            $contact_info_histroie->ip_address = $input['ip_address'];
        $contact_info_histroie->details = $input['details'];            
        $contact_info_histroie->info_1 = $data;
        $contact_info_histroie->consent = null;
        $contact_info_histroie->save();
    }

}
