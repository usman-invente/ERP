<?php

namespace App\Http\Controllers;

use App\ContactsRegistration;
use App\Contact;
use App\Business;
use App\BusinessLocation;
use App\User;
use App\Rules\LegalAgeRule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\ContactsRegistrationNotification;
use App\Notifications\ContactsSelfChangeDataNotification;
use App\Notifications\RegistrationRevocationNotification;
use Illuminate\Notifications\Notification;
use App\Utils\Util;
use Carbon\Carbon;
use DateTimeZone;
use App\ContactInfoHistorie;

class ContactsRegistrationController extends Controller
{
    protected $commonUtil;

    protected $moduleUtil;

    protected $crmUtil;

    protected $contactInfoHistorie;

    /**
     * Constructor
     *
     * @param  Util  $commonUtil
     * @return void
     */
    public function __construct(
        Util $commonUtil,
        ContactInfoHistorie $contactInfoHistorie
    )
    {
        $this->commonUtil = $commonUtil;
        $this->contactInfoHistorie = $contactInfoHistorie;
    }

    public function index_new_customer()
    {
        $business_name = str_replace("_", " ", request()->route("business_name"));
        $business_name = str_replace("$&", "/", $business_name);
        $business_id = request()->route("business_id");

        $business = Business::where('id','=',$business_id)
                            ->where('name','=',  $business_name)
                            ->first();

        return view('contact.index_new_customer')->with(compact('business'));

        // return view('crm::lead.index_new_customer')
        //     ->with(compact('contact', 'leads', 'contact_view_tabs'));
    }

    public function index_new_customer_consent()
    {
        $business_name = str_replace("_", " ", request()->route("business_name"));
        $business_name = str_replace("$&", "/", $business_name);
        $business_id = request()->route("business_id");

        $business = Business::where('id','=',$business_id)
                            ->where('name','=',  $business_name)
                            ->first();

        return view('contact.index_new_customer_consent')->with(compact('business'));

        // return view('crm::lead.index_new_customer')
        //     ->with(compact('contact', 'leads', 'contact_view_tabs'));
    }

    public function index_new_customer_newsletter()
    {
        $business_name = str_replace("_", " ", request()->route("business_name"));
        $business_name = str_replace("$&", "/", $business_name);
        $business_id = request()->route("business_id");

        $business = Business::where('id','=',$business_id)
                            ->where('name','=',  $business_name)
                            ->first();

        return view('contact.index_new_customer_newsletter')->with(compact('business'));
    }

    public function saveNewCustomer(Request $request, ContactsRegistration $contactsRegistration){
        // dd($request->dob);
        // $business_name = str_replace("_", " ", $request->business_name);
        $business = Business::where('id',$request->business_id)
                                ->where('name', $request->business_name)
                                ->first();


        if(!is_object($business))
            return redirect()->back() ->with('msg-alert', 'Ein Fehler wurde aufgetreten, bitte versuchen Sie noch ein Mal!');

            $request->validate([
                // 'title' => 'required',
                'email' => 'required|email',
                'first_name' => 'required',
                // 'dob' => 'required',
                'consent_field1' => 'accepted',
                'customer_business_name' => 'required_if:contact_type_radio,=,business',
                'dob' => 'required_if:contact_type_radio,=,individual',
                // 'business_position' => 'required_if:contact_type_radio,=,business',
                // 'prefix' => 'required',
            ]);

            if(!empty($request->dob)){
                $request->validate([
                    'dob' => [ new LegalAgeRule()]
                ]);
            }
            // if(!empty($request->zip_code || $request->city || $request->country || $request->house_nr )){
            //     $request->validate([
            //         'street' => 'required',
            //         'house_nr' => 'required',
            //     ]);
            // }
            if(!empty($request->consent_field2)){
                $request->validate([
                    'consent_field3' => 'accepted',
                ]);
            }
        try {


            if(Contact::where('business_id','=',$request->business_id)
                        ->where('email','=',$request->email)
                        ->whereNull('deleted_at')
                        ->exists()
                )
            {
                return redirect()->back() ->with('msg-alert', 'Die E-Mail ('.$request->email.') in "'.$request->business_name.'" existiert schon ');
            }

            // if(!$request->consent_field1)
            // {
            //     return redirect()->back() ->with('msg-alert', 'Bitte bestätigen Sie die Kenntnisnahme der Einwilligungserklärung.');
            // }

            $contact = new ContactsRegistration();
            $contact->business_id = $request->business_id;
            $contact->location_id = $request->location_id;
            if($request->contact_type_radio == "business"){
                $contact->supplier_business_name = $request->customer_business_name;
                $contact->business_position = $request->business_position;
            }
            $contact->title = $request->title;
            $contact->prefix = $request->prefix;
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->email = $request->email;
            $contact->mobile = $request->mobile;
            $contact->street = $request->street;
            $contact->house_nr = $request->house_nr;
            $contact->country = $request->country;
            $contact->city = $request->city;
            $contact->zip_code = $request->zip_code;
            $contact->dob = $request->dob;

            $token = Hash::make($request->email.$request->first_name) . 'a1';
            $contact->registration_token = $token;
            if ($request->consent_all) {
                $contact['consent'] = true;
                $contact['consent_email'] = true;
                $contact['consent_mobile'] = true;
                $contact['consent_messenger'] = true;
                if($request->formular_type != "crm-new-customer-newsletter")
                    $contact['consent_post'] = true;
            }else{
                if($request->input('consent_email') == true && $request->input('consent_mobile') == true &&
                    $request->input('consent_post') == true && $request->input('consent_messenger') == true){

                    $contact['consent'] = true;
                }else{
                    $contact['consent'] = false;
                }
                $contact['consent_email'] = $request->input('consent_email');
                $contact['consent_mobile'] = $request->input('consent_mobile');
                $contact['consent_messenger'] = $request->input('consent_messenger');
                if($request->formular_type != "crm-new-customer-newsletter")
                    $contact['consent_post'] = $request->input('consent_post');
                
            }
            $contact->dsvgo_accept = true;
            $contact->consent_field2 = $request->consent_field2;
            $contact->consent_field3 = $request->consent_field3;
            $contact->save();
           //dd(($contactsRegistration->getTextNotification($request->business_id, $request->location_id, $token)));
        //    dd( $request->business_id, $request->location_id, $token);registration-revocation"
            $contact->notify(new ContactsRegistrationNotification($contact->getTextNotification($request->business_id, $request->location_id, $token)));

            return redirect()->back()->with('message',"Sie erhalten in Kürze eine Email. Bitte bestätigen Sie Ihre Eingabe.");
        }
        catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];

            return $output;
        }

        return back();
    }

    public function completeRegistration(){

        $business_name = str_replace("_", " ", request()->route("business_name"));
        $business_name = str_replace("$&", "/", $business_name);
        $business_id = request()->route("business_id");

        $business = Business::where('id','=',$business_id)
                            ->where('name','=',  $business_name)
                            ->first();

        return view('contact.index_customer_complete_registration')->with(compact('business'));
    }

    public function saveCompleteRegistration(Request $request){
        // dd($request->token);

        $contacts_registration = ContactsRegistration::where('registration_token','=',$request->token)
                            ->first();

        if(!is_object($contacts_registration))
            return redirect()->back() ->with('msg-alert', 'Ein Fehler wurde aufgetreten, bitte versuchen Sie es noch ein Mal!');

        $business = Business::where('id','=', $contacts_registration->business_id)
                        ->first();

        if(Contact::where('business_id','=',$contacts_registration->business_id)
                    ->where('email','=',$contacts_registration->email)
                    ->whereNull('deleted_at')
                    ->exists()
            )
        {
            return redirect()->back() ->with('msg-alert', 'Die E-Mail ('.$contacts_registration->email.') in "'.$business->name.'" existiert schon.
                    Daher können Sie nicht mit der gleichen E-Mail registrieren! ');
        }

            $contact = new Contact();
            $contact->business_id = $contacts_registration->business_id;
            $contact->created_by = $business->owner_id;
            $contact->type = 'lead';
            $contact->location_id = $contacts_registration->location_id;
            $contact->title = $contacts_registration->title;
            $contact->supplier_business_name = $contacts_registration->supplier_business_name;
            $contact->business_position = $contacts_registration->business_position;
            $contact->prefix = $contacts_registration->prefix;
            $contact->first_name = $contacts_registration->first_name;
            $contact->last_name = $contacts_registration->last_name;
            $contact->name = $contacts_registration->prefix ." ". $contacts_registration->first_name." ". $contacts_registration->last_name;
            $contact->email = $contacts_registration->email;
            $contact->mobile = $contacts_registration->mobile;
            $contact->street = $contacts_registration->street;
            $contact->house_nr = $contacts_registration->house_nr;
            $contact->country = $contacts_registration->country;
            $contact->city = $contacts_registration->city;
            $contact->zip_code = $contacts_registration->zip_code;
            $contact->dob = $contacts_registration->dob;
            $contact->consent = $contacts_registration->consent;
            $contact->consent_email = $contacts_registration->consent_email;
            $contact->consent_mobile = $contacts_registration->consent_mobile;
            $contact->consent_post = $contacts_registration->consent_post;
            $contact->consent_messenger = $contacts_registration->consent_messenger;
            $contact->consent_field1 = $contacts_registration->dsvgo_accept;
            $contact->consent_field2 = $contacts_registration->consent_field2;
            $contact->consent_field3 = $contacts_registration->consent_field3;


            $ref_count = $this->commonUtil->setAndGetReferenceCount('contacts', $contacts_registration->business_id);

            //Generate reference number
            $contact->contact_id = $this->commonUtil->generateReferenceNumber('contacts', $ref_count, $contacts_registration->business_id);


            $contact->save();

            $input_historie['business_id'] = $contact->business_id;
            $input_historie['user_id'] = "";
            $input_historie['title'] = "Neue Kunde erstellt";
            $input_historie['description'] = "Erstellt von der E-Mail: " . $contacts_registration->email;
            $input_historie['ip_address'] = request()->ip();
            $input_historie['type'] = "Kunde";
            
            $this->contactInfoHistorie->saveContactInfoHistorie($contact->id, $input_historie , $this->contactInfoHistorie->returnSavedDataAsJson($contact));
            
            // $contact->notify(new ContactsRegistrationNotification($contactsRegistration->getTextNotification($request->business_id, $request->location_id, $token)));

            return redirect()->back()->with('message',"Die Registrierung wurde abgeschloßen. Danke, dass Sie bei uns registiert sind! ");

    }

    public function datenschutzerklaerung()
    {
        $business_name = str_replace("_", " ", request()->route("business_name"));
        $business_name = str_replace("$&", "/", $business_name);
        $business_id = request()->route("business_id");
        $location_id = request()->route("location_id");

        $business = Business::where('id','=',$business_id)
                            ->where('name','=',  $business_name)
                            ->first();

        $business_location = BusinessLocation::where('id','=',$location_id)
                            ->where('business_id','=',  $business_id)
                            ->first();

        return view('contact.contact_register.datenschutz')->with(compact('business', 'business_location'));
    }

    public function einwilligungen()
    {
        $business_name = str_replace("_", " ", request()->route("business_name"));
        $business_name = str_replace("$&", "/", $business_name);
        $business_id = request()->route("business_id");
        $location_id = request()->route("location_id");

        $business = Business::where('id','=',$business_id)
                            ->where('name','=',  $business_name)
                            ->first();

        $business_location = BusinessLocation::where('id','=',$location_id)
                            ->where('business_id','=',  $business_id)
                            ->first();

        return view('contact.contact_register.einwilligungen')->with(compact('business', 'business_location'));
    }

    public function payment_service()
    {
        $business_name = str_replace("_", " ", request()->route("business_name"));
        $business_name = str_replace("$&", "/", $business_name);
        $business_id = request()->route("business_id");
        $location_id = request()->route("location_id");

        $business = Business::where('id','=',$business_id)
                            ->where('name','=',  $business_name)
                            ->first();

        $business_location = BusinessLocation::where('id','=',$location_id)
                            ->where('business_id','=',  $business_id)
                            ->first();

        return view('contact.contact_register.payment_service')->with(compact('business', 'business_location'));
    }

    public function vollmacht()
    {
        $business_name = str_replace("_", " ", request()->route("business_name"));
        $business_name = str_replace("$&", "/", $business_name);
        $business_id = request()->route("business_id");
        $location_id = request()->route("location_id");

        $business = Business::where('id','=',$business_id)
                            ->where('name','=',  $business_name)
                            ->first();

        $business_location = BusinessLocation::where('id','=',$location_id)
                            ->where('business_id','=',  $business_id)
                            ->first();

        return view('contact.contact_register.vollmacht')->with(compact('business', 'business_location'));
    }

    public function customer_destroy(Request $request){

        $business_id = request()->route("business_id");
        $id = request()->route("contact_id");
        $first_name = str_replace("_", " ", request()->route("first_name"));

        $business = Business::where('id','=',$business_id)
                            ->first();

        $contact = Contact::where('id',$id)
                            ->where('business_id', $business_id)
                            ->where('first_name',$first_name)
                            ->first();

        return view('contact.delete_customer')->with(compact('business', 'contact'));
    }

    public function complete_customer_destroy(Request $request)
    {
        $business_id = request()->route("business_id");
        $id = request()->route("contact_id");
        $first_name = request()->route("first_name");

        $business = Business::where('id','=',$business_id)
                            ->first();

        $contact = Contact::where('id',$id)
                            ->where('business_id', $business_id)
                            ->where('first_name',$first_name)
                            ->first();

        if(!is_object($contact))
            return redirect()->back() ->with('msg-alert', 'Ein Fehler wurde aufgetreten, bitte versuchen Sie noch ein Mal!');


        try {
            $contact->delete();
            // return redirect()->home();
            return redirect('https://www.neoburg.net/unsere-werte/');
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }
        return $output;

        // return redirect()->back()->with('message', 'Wurde gelöscht');
    }

    public function self_change_data(){

        $first_name = str_replace("_", " ", request()->route("first_name"));
        $business_id = request()->route("business_id");
        $contact_id = request()->route("contact_id");

        $business = Business::where('id','=',$business_id)
                            ->first();

        $contact = Contact::where('id',$contact_id)
                            ->where('business_id', $business_id)
                            ->where('first_name',$first_name)
                            ->first();

        // if(!is_object($contact))
        //     return redirect()->back() ->with('msg-alert', 'Ein Fehler wurde aufgetreten, bitte versuchen Sie noch ein Mal!');

        return view('contact.edit_self_customer')->with(compact('business', 'contact'));
    }

    public function saveSelfChangeData(Request $request){
        $request->validate([
            'consent_field1' => 'accepted',            
        ]);
        
        if(!empty($request->consent_field2)){
            $request->validate([
                'consent_field3' => 'accepted',
            ]);
        }
        if(!empty($request->consent_field4)){
            $request->validate([
                'consent_field5' => 'accepted',
            ]);
        }
        // dd($request->consent_field4.'-'.$request->consent_field5.'-'.$request->bank.'-'.$request->bic.'-'.$request->iban.'-'.$request->paypal);
        $business_id = $request->business_id;
        $business_name = $request->business_name;
        $contact_id = $request->contact_id;
        $first_name = $request->first_name;

        $contactsRegistration = new ContactsRegistration();

        $contact = Contact::where('id',$contact_id)
                            ->where('business_id', $business_id)
                            ->where('first_name',$first_name)
                            ->whereNull('deleted_at')
                            ->first();

        if(!is_object($contact))
            return redirect()->back() ->with('msg-alert', 'Ein Fehler wurde aufgetreten, bitte versuchen Sie noch ein Mal!');

        try {
            /*$contact->title = $request->title;
            $contact->supplier_business_name = $request->customer_business_name;
            $contact->business_position = $request->business_position;
            $contact->prefix = $request->prefix;
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->email = $request->email;
            $contact->mobile = $request->mobile;
            $contact->street = $request->street;
            $contact->house_nr = $request->house_nr;
            $contact->zip_code = $request->zip_code;
            $contact->city = $request->city;
            $contact->country = $request->country;

            if ($request->input('consent_all') == 1) {
                $contact['consent'] = true;
                $contact['consent_email'] = true;
                $contact['consent_mobile'] = true;
                $contact['consent_post'] = true;
                $contact['consent_messenger'] = true;
            }else{
                $contact['consent'] = false;
                $contact['consent_email'] = ! empty($request->input('consent_email')) ? 1 : 0;
                $contact['consent_mobile'] = ! empty($request->input('consent_mobile')) ? 1 : 0;
                $contact['consent_post'] = ! empty($request->input('consent_post')) ? 1 : 0;
                $contact['consent_messenger'] = ! empty($request->input('consent_messenger')) ? 1 : 0;
            }


                $contact['last_consent_date'] = Carbon::now();
                $contact['last_consent_in'] = 'Self from customer';
                $contact['last_consent_from'] = implode(' ', [$contact->first_name, $contact->last_name]);*/

                $formData = $request->all();
                $jsonData = json_encode($formData);

                // $model = new MyModel;
                $contact->self_change_data = $jsonData;
                // $contact->save();

                $contact->save();

                $self_change_data = json_decode($contact->self_change_data);

                // $contact->notify(new ContactsSelfChangeDataNotification('TEst Email'));
                $contact->notify(new ContactsSelfChangeDataNotification($contactsRegistration->getChangeDataTextNotification($contact->business_id, $contact->id, $self_change_data->_token)));

            return redirect()->back()->with('message',"Sie erhalten in Kürze eine Email. Bitte bestätigen Sie Ihre Eingabe.");
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }
    }

    public function completeChangedata(){
        $business_id = request()->route("business_id");
        $contact_id = request()->route("contact_id");
        $token = request()->route("token");



        $business = Business::where('id','=',$business_id)
                            ->first();

        $contact = Contact::where('id',$contact_id)
                            ->where('business_id', $business_id)
                            ->whereNull('deleted_at')
                            ->first();

        if(!is_object($contact))
            return redirect()->back() ->with('msg-alert', 'Kunde existiert nicht mehr in unsere DB, daher können Sie keine Änderungen mehr vornehmen!');

        $self_change_data = json_decode($contact->self_change_data);

        $token_change_data = $self_change_data->_token;
        // if($self_change_data->_token != $token)
        //     return redirect()->back() ->with('msg-alert', 'Token ist abgelaufen, bitte fangen Sie noch Einmal von Anfang an!');

        return view('contact.index_customer_complete_change_data')->with(compact('business','contact', 'token', 'token_change_data'));
    }
    
    public function saveCompleteChangedata(Request $request){
        $contact = Contact::where('id',$request->contact_id)
                            ->where('business_id', $request->business_id)
                            ->whereNull('deleted_at')
                            ->first();

        $self_change_data = json_decode($contact->self_change_data);

        $input_historie['business_id'] = $contact->business_id;
        $input_historie['user_id'] = " ";
        $input_historie['change_date'] = date("Y-m-d");
        $input_historie['title'] = "Daten wurden geändert";
        $input_historie['type'] = "Kunde";
        $input_historie['description'] = "Geändert von der E-Mail: " . $contact->email;
        $input_historie['ip_address'] = request()->ip();

        $this->contactInfoHistorie->saveContactInfoHistorie($contact->id, $input_historie , $contact->self_change_data);

        if($self_change_data->_token == $request->token){

            if($contact->first_name != $self_change_data->first_name)
                $contact->first_name = $self_change_data->first_name;

            if($contact->last_name != $self_change_data->last_name)
                $contact->last_name = $self_change_data->last_name;

            if($contact->prefix != $self_change_data->prefix)
                $contact->prefix = $self_change_data->prefix;

            if($contact->title != $self_change_data->title)
                $contact->title = $self_change_data->title;

            if($contact->supplier_business_name != $self_change_data->supplier_business_name)
                $contact->supplier_business_name = $self_change_data->supplier_business_name;

            if($contact->business_position != $self_change_data->business_position)
                $contact->business_position = $self_change_data->business_position;

            if($contact->mobile != $self_change_data->mobile)
                $contact->mobile = $self_change_data->mobile;

            if($contact->consent_email != $self_change_data->consent_email)
                $contact->consent_email = $self_change_data->consent_email;

            if($contact->consent_mobile != $self_change_data->consent_mobile)
                $contact->consent_mobile = $self_change_data->consent_mobile;

            if($contact->consent_post != $self_change_data->consent_post)
                $contact->consent_post = $self_change_data->consent_post;

            if($contact->consent_messenger != $self_change_data->consent_messenger)
                $contact->consent_messenger = $self_change_data->consent_messenger;

            if($contact->street != $self_change_data->street)
                $contact->street = $self_change_data->street;

            if($contact->house_nr != $self_change_data->house_nr)
                $contact->house_nr = $self_change_data->house_nr;

            if($contact->zip_code != $self_change_data->zip_code)
                $contact->zip_code = $self_change_data->zip_code;

            if($contact->city != $self_change_data->city)
                $contact->city = $self_change_data->city;

            if($contact->country != $self_change_data->country)
                $contact->country = $self_change_data->country;
                
            if($contact->bank != $self_change_data->bank)
                $contact->bank = $self_change_data->bank;
            
            if($contact->iban != $self_change_data->iban)
                $contact->iban = $self_change_data->iban;
            
            if($contact->bic != $self_change_data->bic)
                $contact->bic = $self_change_data->bic;
            
            if($contact->paypal != $self_change_data->paypal)
                $contact->paypal = $self_change_data->paypal;
            
            if($contact->consent_field1 != $self_change_data->consent_field1)
                $contact->consent_field1 = $self_change_data->consent_field1;

            if($contact->consent_field2 != $self_change_data->consent_field2)
                $contact->consent_field2 = $self_change_data->consent_field2;
            
            if($contact->consent_field3 != $self_change_data->consent_field3)
                $contact->consent_field3 = $self_change_data->consent_field3;

            if($contact->consent_field4 != $self_change_data->consent_field4)
                $contact->consent_field4 = $self_change_data->consent_field4;
            
            if($contact->consent_field5 != $self_change_data->consent_field5)
                $contact->consent_field5 = $self_change_data->consent_field5;

            $contact->save();
        }

        return redirect()->back()->with('message',"Die Daten wurden geändert");
    }

    public function registrationRevocation(){
        
        $business = Business::where('id','=',1)
                            ->first();

        return view('contact.registration_revocation')->with(compact('business'));
    }

    public function sendRegistrationRevocation(Request $request){

        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required',
            // 'dob' => 'required',
        ]);

        $first_name = $request->first_name;
        $email = $request->email;
        $dob = $request->dob;

        if($dob != null){
            $contact = Contact::where('email',$email)
                                ->where('first_name',$first_name)
                                ->where('dob',$dob)
                                ->whereNull('deleted_at')
                                ->first();

            if(!is_object($contact))
                return redirect()->back() ->with('msg-alert', 'Ein User mit dem Name ('.$first_name.'), mit der Email ('.$email.') 
                                                            und mit dem Geburtsdatum ('.$dob.') existiert nicht in unserer DB. 
                                                            Bitte versuchen Sie noch ein Mal!');
        }else{
            $contact = Contact::where('email',$email)
                                ->where('first_name',$first_name)
                                ->whereNull('deleted_at')
                                ->first();

            if(!is_object($contact))
                return redirect()->back() ->with('msg-alert', 'Ein User mit dem Name ('.$first_name.') und mit der Email ('.$email.') 
                                                                existiert nicht in unserer DB. Bitte versuchen Sie noch ein Mal!');
        }
        $contactsRegistration = new ContactsRegistration();
        $contact->notify(new RegistrationRevocationNotification($contactsRegistration->getChangeOrDeleteDataDataTextNotification($contact)));

        return redirect()->back()->with('message',"Sie erhalten in Kürze eine Mail mit einem Link zur Änderung / Löschung Ihrer Daten.");
    }

    public function getListRegistration(Request $request){
        $first_name = request()->route("first_name");
        $email = request()->route("email");

        $business = Business::where('id','=',1)
                            ->first();

        $contacts = Contact::where('email',$email)
                            ->where('first_name',$first_name)
                            ->whereNull('deleted_at')
                            ->get();
        
        return view('contact.list_of_registration')->with(compact('contacts', 'business'));
    }
}
