<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\Contact;
use App\ContactInfoHistorie;
use App\Contract;
use App\CustomerGroup;
use App\Notifications\CustomerNotification;
use App\PurchaseLine;
use App\Transaction;
use App\TransactionPayment;
use App\User;
use App\Utils\ContactUtil;
use App\Utils\ModuleUtil;
use App\Utils\NotificationUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use DB;
use Excel;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;
use Modules\Crm\Utils\CrmUtil;
use App\DocumentAndNote;

class ContactInfoHistorieController extends Controller
{
    
    protected $commonUtil;

    protected $contactUtil;

    protected $transactionUtil;

    protected $moduleUtil;

    protected $notificationUtil;

    protected $crmUtil;
    
     /**
     * Constructor
     *
     * @param  Util  $commonUtil
     * @return void
     */
    public function __construct(
        Util $commonUtil,
        ModuleUtil $moduleUtil,
        TransactionUtil $transactionUtil,
        NotificationUtil $notificationUtil,
        ContactUtil $contactUtil,
        CrmUtil $crmUtil
    ) {
        $this->commonUtil = $commonUtil;
        $this->contactUtil = $contactUtil;
        $this->moduleUtil = $moduleUtil;
        $this->transactionUtil = $transactionUtil;
        $this->notificationUtil = $notificationUtil;
        $this->crmUtil = $crmUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $reques)
    {
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $business_locations = BusinessLocation::forDropdown($business_id);

        // $contact_id = 194;
        $contact_id = $request->get('lead_id');
        // $contract = Contract::all();

        $contract_info_histories = ContactInfoHistorie::where('contact_id', $contact_id)
                    ->select('*');
        $contracts = Contract::where('contact_id', $contact_id)
                    ->select('*');
        $contact = Contact::where('id', $contact_id)
                    ->first();

        $view_values = [
            'test_data' => "eeee",
            'contract_info_histories' => $contract_info_histories,
            'business_locations' => $business_locations,
            'contracts' => $contracts,
            'contact' => $contact,
        ];                                   
                    
        return view('contact.index_info_historie')->with(($view_values));            

    }

    public function getListContactInfoHistorie(Request $request){
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        // $contact_id = 194;
        $contact_id = $request->get('contact_id');
        
        $contract_info_histories = ContactInfoHistorie::where('contact_id', $contact_id)
                    ->select('*');
        $contract = Contract::where('contact_id', $contact_id)
                    ->select('*');

        return Datatables::of($contract_info_histories)
            ->addColumn('action', function ($row) {
                $html = "";
                if($row->info_2 && ($row->type == "Dokument" || $row->type == "Notize&Dokumente")){
                    $html .= '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <li>
                                    <a data-href="'.action([\App\Http\Controllers\DocumentAndNoteController::class, 'show'], [$row->info_2, 'notable_id' => $row->contact_id, 'notable_type' => "App\Contact"]).'" class="cursor-pointer view_a_docs_note">
                                        <i class="fa fa-eye"></i>
                                        '.__('messages.view').'
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                }
                if($row->info_2 && $row->type == "Rechnung"){
                    $html .= '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <li>
                                    <a href="'.$row->info_2.'" target="_blank">
                                        <i class="fa fa-download"></i>
                                        Download die Rechnung
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                }
                
                return $html;
            })
            // ->editColumn('date_created_at', function ($row) {
            //     if($row->created_at)
            //         return $this->commonUtil->format_date($row->created_at);
            //     else
            //         return null;
            // })
            ->editColumn('created_at',
                    '
                    {{@format_datetime($created_at)}}
                    '
                )
                ->editColumn('details', function ($row) {
                    if($row->type == "Notize&Dokumente" && $row->info_2 != null) {
                        $documentAndNote = DocumentAndNote::where('id', $row->info_2)
                                                        ->first();
    
                        $icon = '';
                        if(is_object($documentAndNote) && $documentAndNote->media->count() > 0){
                            $media_tooltip = __('lang_v1.contains_media');
                            $icon = '<i class="fas fa-file-image text-primary" data-toggle="tooltip" title="'.$media_tooltip.'"></i>';
                            return $row->details. '&nbsp;'. $icon;
                        }else{                               
                            return $row->details;
                        }
                    } else                                 
                        return $row->details;
                })
            ->editColumn('consent', function ($row) {
                $consent_detail = json_decode($row->consent);
                $return = "";

                if($consent_detail != NULL){
                    if($consent_detail->consent_email)
                        $return .= __('lang_v1.consent_email').': '.  '<span style="color:green;font-weight:bold">'. __('messages.yes').'  </span>';
                    else
                        $return .= __('lang_v1.consent_email'). ': '. '<span style="color:red;font-weight:bold">'. __('messages.no').'  </span>';
                    
                    if($consent_detail->consent_mobile)
                        $return .= '<br> '.__('lang_v1.consent_mobile').': '.  '<span style="color:green;font-weight:bold">'. __('messages.yes').'  </span>';
                    else
                        $return .= '<br> '.__('lang_v1.consent_mobile'). ': '. '<span style="color:red;font-weight:bold">'. __('messages.no').'  </span>';
                    
                    if($consent_detail->consent_post)
                        $return .= '<br> '.__('lang_v1.consent_post').': '. '<span style="color:green;font-weight:bold">'. __('messages.yes').'  </span>';
                    else
                        $return .= '<br> '.__('lang_v1.consent_post').': '.  '<span style="color:red;font-weight:bold">'. __('messages.no').'  </span>';
                    
                    if($consent_detail->consent_messenger)
                        $return .= '<br> '.__('lang_v1.consent_messenger').': '.  '<span style="color:green;font-weight:bold">'. __('messages.yes').'  </span>';
                    else
                        $return .= '<br> '.__('lang_v1.consent_messenger').': '.  '<span style="color:red;font-weight:bold">'. __('messages.no').'  </span>';
                }
                    
                return $return;
            })
            ->editColumn('type', function ($row) {
                if($row->type == "Kunde")
                    return '<span">'. __('contact.customer').'  </span>';
                if($row->type == "Dokument" || $row->type == "Notize&Dokumente")
                    return '<span">'. __('contact.info_historie_type_dokument').'  </span>';
                if($row->type == "Vertrag")
                    return '<span">'. __('contact.info_historie_type_contract').'  </span>';
                if($row->type == "Rechnung")
                    return '<span">'. __('contact.info_historie_type_invoice').'  </span>';
                
            })
            ->removeColumn('id')
            ->rawColumns(['action', 'person_id','consent', 'type','details'])
            ->make(true);
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
