<?php

namespace Modules\Crm\Http\Controllers;

use App\Category;
use App\Contact;
use App\ContactsRegistration;
use App\User;
use App\Utils\ModuleUtil;
use App\Utils\Util;
use App\Utils\ContactUtil;
use App\Utils\NotificationUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Crm\Entities\CrmContact;
use Modules\Crm\Utils\CrmUtil;
use Yajra\DataTables\Facades\DataTables;
use App\Business;
use Carbon\Carbon;
use DateTimeZone;
use DB;
use Excel;
use App\ContactInfoHistorie;

class LeadController extends Controller
{
    protected $commonUtil;

    protected $moduleUtil;

    protected $crmUtil;

    protected $contactUtil;

    protected $transactionUtil;

    protected $notificationUtil;

    protected $contactInfoHistorie;

    /**
     * Constructor
     *
     * @param  Util  $commonUtil
     * @return void
     */
    public function __construct(
        Util $commonUtil, 
        ModuleUtil $moduleUtil, 
        CrmUtil $crmUtil,
        ContactUtil $contactUtil,
        TransactionUtil $transactionUtil,
        NotificationUtil $notificationUtil,
        ContactInfoHistorie $contactInfoHistorie
        
    )
    {
        $this->commonUtil = $commonUtil;
        $this->moduleUtil = $moduleUtil;
        $this->crmUtil = $crmUtil;
        $this->contactUtil = $contactUtil;
        $this->transactionUtil = $transactionUtil;
        $this->notificationUtil = $notificationUtil;
        $this->contactInfoHistorie = $contactInfoHistorie;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        $can_access_all_leads = auth()->user()->can('crm.access_all_leads');
        $can_access_own_leads = auth()->user()->can('crm.access_own_leads');

        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module')) || ! ($can_access_all_leads || $can_access_own_leads)) {
            abort(403, 'Unauthorized action.');
        }

        $life_stages = Category::forDropdown($business_id, 'life_stage');

        if (is_null(request()->get('lead_view'))) {
            $lead_view = 'list_view';
        } else {
            $lead_view = request()->get('lead_view');
        }

        if (request()->ajax()) {
            $leads = $this->crmUtil->getLeadsListQuery($business_id);

            if (! $can_access_all_leads && $can_access_own_leads) {
                $leads->OnlyOwnLeads();
            }

            if (! empty(request()->get('source'))) {
                $leads->where('crm_source', request()->get('source'));
            }

            if (! empty(request()->get('life_stage'))) {
                $leads->where('crm_life_stage', request()->get('life_stage'));
            }

            if (! empty(request()->get('user_id'))) {
                $user_id = request()->get('user_id');
                $leads->where(function ($query) use ($user_id) {
                    $query->whereHas('leadUsers', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                });
            }

            if ($lead_view == 'list_view') {
                return Datatables::of($leads)
                    ->addColumn('address', '{{implode(", ", array_filter([$address_line_1, $address_line_2, $city, $state, $country, $zip_code]))}}')
                    ->addColumn('action', function ($row) {
                        $html = '<div class="btn-group">
                                    <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                        '.__('messages.action').'
                                        <span class="caret"></span>
                                        <span class="sr-only">'
                                           .__('messages.action').'
                                        </span>
                                    </button>
                                      <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                       <li>
                                            <a href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'show'], ['lead' => $row->id]).'" class="cursor-pointer view_lead">
                                                <i class="fa fa-eye"></i>
                                                '.__('messages.view').'
                                            </a>
                                        </li>
                                        <li>
                                            <a data-href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'edit'], ['lead' => $row->id]).'"class="cursor-pointer edit_lead">
                                                <i class="fa fa-edit"></i>
                                                '.__('messages.edit').'
                                            </a>
                                        </li>
                                      <!--<li>
                                            <a data-href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'convertToCustomer'], ['id' => $row->id]).'" class="cursor-pointer convert_to_customer">
                                                <i class="fas fa-redo"></i>
                                                '.__('crm::lang.convert_to_customer').'
                                            </a>
                                        </li>-->
                                        <li>
                                            <a data-href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'destroy'], ['lead' => $row->id]).'" class="cursor-pointer delete_a_lead">
                                                <i class="fas fa-trash"></i>
                                                '.__('messages.delete').'
                                            </a>
                                        </li>';
                                            if(! empty($row->first_name) && !empty($row->business_id)){
                                                $html .= '<li>
                                                    <a  target="_blank" href="'.action([\App\Http\Controllers\ContactsRegistrationController::class, 'self_change_data'], ['business_id' => $row->business_id, 'contact_id' => $row->id, 'first_name' => $row->first_name ]).'">
                                                        <i class="fa fa-edit"></i>
                                                        '.__('lang_v1.link_self_edit_data').'
                                                    </a>
                                                </li>
                                                <li>
                                                    <a  target="_blank" href="'.action([\App\Http\Controllers\ContactsRegistrationController::class, 'customer_destroy'], ['business_id' => $row->business_id, 'contact_id' => $row->id, 'first_name' => $row->first_name ]).'">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                        '.__('lang_v1.opt_out').'
                                                    </a>
                                                </li>                                        
                                                ';
                                            }

                        $html .= '</ul>
                                </div>';

                        return $html;
                    })
                    ->addColumn('last_follow_up', function ($row) {
                        $html = '';

                        if (! empty($row->last_follow_up)) {
                            $html .= $this->commonUtil->format_date($row->last_follow_up, true);
                            $html .= '<br><a href="#" target="_blank" title="'.__('crm::lang.view_follow_up').'" data-toggle="tooltip">
                                <i class="fas fa-external-link-alt"></i>
                            </a><br>';
                        }

                        $infos = json_decode($row->last_follow_up_additional_info, true);

                        if (! empty($infos)) {
                            foreach ($infos as $key => $value) {
                                $html .= $key.' : '.$value.'<br>';
                            }
                        }

                        return $html;
                    })
                    ->orderColumn('last_follow_up', function ($query, $order) {
                        $query->orderBy('last_follow_up', $order);
                    })
                    ->addColumn('upcoming_follow_up', function ($row) {
                        $html = '';

                        if (! empty($row->upcoming_follow_up)) {
                            $html .= $this->commonUtil->format_date($row->upcoming_follow_up, true);
                            $html .= '<br><a href="#" target="_blank" title="'.__('crm::lang.view_follow_up').'" data-toggle="tooltip">
                                <i class="fas fa-external-link-alt"></i>
                            </a><br>';
                        }
                        
                        $html .= '<a href="#" data-href="'.action([\Modules\Crm\Http\Controllers\ScheduleController::class, 'create'], ['schedule_for' => 'lead', 'contact_id' => $row->id]).'" class="btn-modal btn btn-xs btn-primary" data-container=".schedule">
                            <i class="fas fa-plus"></i>'.
                            __('crm::lang.add_schedule').'
                        </a><br>';

                        $infos = json_decode($row->upcoming_follow_up_additional_info, true);

                        if (! empty($infos)) {
                            foreach ($infos as $key => $value) {
                                $html .= $key.' : '.$value.'<br>';
                            }
                        }

                        return $html;
                    })
                    
                    ->orderColumn('upcoming_follow_up', function ($query, $order) {
                        $query->orderBy('upcoming_follow_up', $order);
                    })
                    ->editColumn('created_at', '
                        {{@format_date($created_at)}}
                    ')
                    ->editColumn('dob', function ($row) {
                        if($row->dob)
                            return $this->commonUtil->format_date($row->dob);
                        else
                            return null;
                    })
                    ->editColumn('crm_source', function ($row) {
                        return $row->Source?->name;
                    })
                    ->editColumn('street', function ($row) {
                        return $row->street;
                    })
                    ->editColumn('house_nr', function ($row) {
                        return $row->house_nr;
                    })
                    ->editColumn('crm_life_stage', function ($row) {
                        return $row->lifeStage?->name;
                    })                    
                    ->editColumn('name', '@if(!empty($supplier_business_name)) {{$supplier_business_name}},<br>@endif {{$name}}')
                    ->editColumn('business_position', function ($row) {
                            return $row->business_position;
                    })
                    ->editColumn('consent_email', function ($row) {       
                        if( $row->consent_email == 1 )   
                            return '<span style="color:green;font-weight:bold">'. __('messages.yes').'  </span>';               
                            // return __('messages.yes').' '. '<i class="nav-icon fas fa-check-circle text-success" style="font-size:18px"></i>';
                            // return __('messages.yes');
                        else 
                            return '<span style="color:red;font-weight:bold">'. __('messages.no').'  </span>'; 
                            // return __('messages.no'). ' '.' <i class="fa fa-times-circle "  style="font-size:18px;color:red"></i>';
                            // return __('messages.no');
                    })
                    ->editColumn('consent_mobile', function ($row) {       
                        if( $row->consent_mobile == 1 )                 
                            return '<span style="color:green;font-weight:bold">'. __('messages.yes').'  </span>'; 
                        else 
                            return '<span style="color:red;font-weight:bold">'. __('messages.no').'  </span>'; 
                    })
                    ->editColumn('consent_post', function ($row) {       
                        if( $row->consent_post == 1 )                 
                            return '<span style="color:green;font-weight:bold">'. __('messages.yes').'  </span>'; 
                        else 
                            return '<span style="color:red;font-weight:bold">'. __('messages.no').'  </span>';
                    })
                    ->editColumn('consent_messenger', function ($row) {       
                        if( $row->consent_messenger == 1 )                 
                            return '<span style="color:green;font-weight:bold">'. __('messages.yes').'  </span>'; 
                        else 
                            return '<span style="color:red;font-weight:bold">'. __('messages.no').'  </span>';
                    })
                    ->editColumn('type_status', function ($row) {       
                        if( $row->type == 'customer' )                 
                            return '<span">'. __('crm::lang.customer').'  </span>'; 
                        elseif( $row->type == 'lead' ) 
                            return '<span">'. __('crm::lang.lead').'  </span>'; 
                        elseif( $row->type == 'supplier' ) 
                            return '<span">'. __('crm::lang.supplier').'  </span>'; 
                        else
                            return '<span">  </span>'; 
                    })                    
                    ->editColumn('leadUsers', function ($row) {
                        $html = '&nbsp;';
                        foreach ($row->leadUsers as $leadUser) {
                            if (isset($leadUser->media->display_url)) {
                                $html .= '<img class="user_avatar" src="'.$leadUser->media->display_url.'" data-toggle="tooltip" title="'.$leadUser->user_full_name.'">';
                            } else {
                                $html .= '<img class="user_avatar" src="https://ui-avatars.com/api/?name='.$leadUser->first_name.'" data-toggle="tooltip" title="'.$leadUser->user_full_name.'">';
                            }
                        }

                        return $html;
                    })
                    ->removeColumn('id')
                    ->filterColumn('address', function ($query, $keyword) {
                        $query->where(function ($q) use ($keyword) {
                            $q->where('address_line_1', 'like', "%{$keyword}%")
                            ->orWhere('address_line_2', 'like', "%{$keyword}%")
                            ->orWhere('city', 'like', "%{$keyword}%")
                            ->orWhere('state', 'like', "%{$keyword}%")
                            ->orWhere('country', 'like', "%{$keyword}%")
                            ->orWhere('zip_code', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(COALESCE(address_line_1, ''), ', ', COALESCE(address_line_2, ''), ', ', COALESCE(city, ''), ', ', COALESCE(state, ''), ', ', COALESCE(country, '') ) like ?", ["%{$keyword}%"]);
                        });
                    })
                    ->rawColumns(['action', 'crm_source', 'crm_life_stage', 'leadUsers', 'last_follow_up', 'upcoming_follow_up', 'created_at', 'name',
                                    'business_position','consent_email','consent_mobile','consent_post','consent_messenger','type_status'])
                    ->make(true);
            } 
            /*elseif ($lead_view == 'kanban') {
                $leads = $leads->get()->groupBy('crm_life_stage');
                //sort leads based on life stage
                $crm_leads = [];
                $board_draggable_to = [];
                foreach ($life_stages as $key => $value) {
                    $board_draggable_to[] = strval($key);
                    if (! isset($leads[$key])) {
                        $crm_leads[strval($key)] = [];
                    } else {
                        $crm_leads[strval($key)] = $leads[$key];
                    }
                }

                $leads_html = [];
                foreach ($crm_leads as $key => $leads) {
                    //get all the leads for particular board(life stage)
                    $cards = [];
                    foreach ($leads as $lead) {
                        $edit = action([\Modules\Crm\Http\Controllers\LeadController::class, 'edit'], ['lead' => $lead->id]);

                        $delete = action([\Modules\Crm\Http\Controllers\LeadController::class, 'destroy'], ['lead' => $lead->id]);

                        $view = action([\Modules\Crm\Http\Controllers\LeadController::class, 'show'], ['lead' => $lead->id]);

                        //if member then get their avatar
                        if ($lead->leadUsers->count() > 0) {
                            $assigned_to = [];
                            foreach ($lead->leadUsers as $member) {
                                if (isset($member->media->display_url)) {
                                    $assigned_to[$member->user_full_name] = $member->media->display_url;
                                } else {
                                    $assigned_to[$member->user_full_name] = 'https://ui-avatars.com/api/?name='.$member->first_name;
                                }
                            }
                        }

                        $cards[] = [
                            'id' => $lead->id,
                            'title' => $lead->full_name_with_business,
                            'viewUrl' => $view,
                            'editUrl' => $edit,
                            'editUrlClass' => 'edit_lead',
                            'deleteUrl' => $delete,
                            'deleteUrlClass' => 'delete_a_lead',
                            'assigned_to' => $assigned_to,
                            'hasDescription' => false,
                            'tags' => [$lead->Source->name ?? ''],
                            'dragTo' => $board_draggable_to,
                        ];
                    }

                    //get all the card & board title for particular board(life stage)
                    $leads_html[] = [
                        'id' => strval($key),
                        'title' => $life_stages[$key],
                        'cards' => $cards,
                    ];
                }

                $output = [
                    'success' => true,
                    'leads_html' => $leads_html,
                    'msg' => __('lang_v1.success'),
                ];

                return $output;
            }*/
        }

        $sources = Category::forDropdown($business_id, 'source');

        $users = User::forDropdown($business_id, false, false, false, true);

        return view('crm::lead.index')
            ->with(compact('sources', 'life_stages', 'lead_view', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::forDropdown($business_id, false);
        $sources = Category::forDropdown($business_id, 'source');
        $life_stages = Category::forDropdown($business_id, 'life_stage');

        $types = [];
        $types['customer'] = __('crm::lang.customer');
        $types['lead'] = __('crm::lang.lead');        

        $store_action = action([\Modules\Crm\Http\Controllers\LeadController::class, 'store']);

        $module_form_parts = $this->moduleUtil->getModuleData('contact_form_part');

        return view('contact.create_crm')
            ->with(compact('types', 'store_action', 'sources', 'life_stages', 'users', 'module_form_parts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'first_name' => 'required',
        //     'customer_business_name' => 'required_if:contact_type_radio,=,business',
        // ]);       
        // if(!empty($request->dob)){
        //     $request->validate([
        //         'dob' => [ new LegalAgeRule()]
        //     ]);
        // }

        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        try {            
            $input = $request->only(['prefix', 'first_name', 'middle_name', 'last_name', 'tax_number', 'mobile', 'landline',
             'alternate_number', 'city', 'state', 'country', 'landmark', 'contact_id', 'custom_field1', 'custom_field2', 'custom_field3',
              'custom_field4', 'custom_field5', 'custom_field6', 'custom_field7', 'custom_field8', 'custom_field9', 'custom_field10',
               'email', 'crm_source', 'crm_life_stage', 'dob', 'address_line_1', 'address_line_2', 'zip_code', 'supplier_business_name', 
               'shipping_custom_field_details', 'title','business_position', 'street','house_nr']);

            $input['name'] = implode(' ', [$input['prefix'], $input['first_name'], $input['middle_name'], $input['last_name']]);
            $input['type'] = 'customer';

            if (! empty($request->input('is_export'))) {
                $input['is_export'] = true;
                $input['export_custom_field_1'] = $request->input('export_custom_field_1');
                $input['export_custom_field_2'] = $request->input('export_custom_field_2');
                $input['export_custom_field_3'] = $request->input('export_custom_field_3');
                $input['export_custom_field_4'] = $request->input('export_custom_field_4');
                $input['export_custom_field_5'] = $request->input('export_custom_field_5');
                $input['export_custom_field_6'] = $request->input('export_custom_field_6');
            }

            if (! empty($request->input('consent'))) {
                $input['consent'] = true;
                $input['consent_email'] = true;
                $input['consent_mobile'] = true;
                $input['consent_post'] = true;
                $input['consent_messenger'] = true;
            }else{
                $input['consent'] = false;
                $input['consent'] = ! empty($request->input('consent')) ? 1 : 0;
                $input['consent_email'] = ! empty($request->input('consent_email')) ? 1 : 0;
                $input['consent_mobile'] = ! empty($request->input('consent_mobile')) ? 1 : 0;
                $input['consent_post'] = ! empty($request->input('consent_post')) ? 1 : 0;
                $input['consent_messenger'] = ! empty($request->input('consent_messenger')) ? 1 : 0;
            }

            // $input['consent'] = ! empty($request->input('consent')) ? 1 : 0;
            // $input['consent_email'] = ! empty($request->input('consent_email')) ? 1 : 0;
            // $input['consent_mobile'] = ! empty($request->input('consent_mobile')) ? 1 : 0;
            // $input['consent_post'] = ! empty($request->input('consent_post')) ? 1 : 0;
            // $input['consent_messenger'] = ! empty($request->input('consent_messenger')) ? 1 : 0;
            

            if (! empty($input['dob'])) {
                $input['dob'] = $this->commonUtil->uf_date($input['dob']);
            }

            $input['business_id'] = $business_id;
            $input['created_by'] = $request->session()->get('user.id');

            $assigned_to = $request->input('user_id');
            

            $contact = CrmContact::createNewLead($input, $assigned_to);

            if (! empty($contact)) {
                $this->moduleUtil->getModuleData('after_contact_saved', ['contact' => $contact, 'input' => $request->input()]);       
            }
            
            $input_historie['business_id'] = $business_id;
            $input_historie['user_id'] = $request->session()->get('user.id');
            $input_historie['title'] = "Neue Kunde erstellt";
            $input_historie['description'] = "Erstellt von " . auth()->user()->getUserNameById(auth()->user()->id);
            $input_historie['ip_address'] = request()->ip();
            $input_historie['type'] = "Kunde";
            
            $this->contactInfoHistorie->saveContactInfoHistorie($contact->id, $input_historie , json_encode($request->all()));
            // $contact_info_histroie = new ContactInfoHistorie();
            // $contact_info_histroie->business_id = $business_id;
            // $contact_info_histroie->user_id = auth()->user()->id;
            // $contact_info_histroie->contact_id = $contact->id;
            // $contact_info_histroie->title = "Neue Kontakt erstellt";
            // $contact_info_histroie->details = json_encode($request->all());
            // $contact_info_histroie->save();

            $output = ['success' => true,
                'msg' => __('contact.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $can_access_all_leads = auth()->user()->can('crm.access_all_leads');
        $can_access_own_leads = auth()->user()->can('crm.access_own_leads');

        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module')) || ! ($can_access_all_leads || $can_access_own_leads)) {
            abort(403, 'Unauthorized action.');
        }

        $query = CrmContact::with('leadUsers', 'Source', 'lifeStage')
                    ->where('business_id', $business_id);

        if (! $can_access_all_leads && $can_access_own_leads) {
            $query->OnlyOwnLeads();
        }
        $contact = $query->findOrFail($id);

        // $leads = CrmContact::leadsDropdown($business_id, false);
        $leads = CrmContact::getCustomerAndLeadsDropdown($business_id);

        $contact_view_tabs = $this->moduleUtil->getModuleData('get_contact_view_tabs');

        $business = Business::where('id','=',$business_id)->first();

        return view('crm::lead.show')
            ->with(compact('contact', 'leads', 'contact_view_tabs','business'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $can_access_all_leads = auth()->user()->can('crm.access_all_leads');
        $can_access_own_leads = auth()->user()->can('crm.access_own_leads');

        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module')) || ! ($can_access_all_leads || $can_access_own_leads)) {
            abort(403, 'Unauthorized action.');
        }

        $query = CrmContact::with('leadUsers')
                    ->where('business_id', $business_id);

        if (! $can_access_all_leads && $can_access_own_leads) {
            $query->OnlyOwnLeads();
        }
        $contact = $query->findOrFail($id);

        $users = User::forDropdown($business_id, false);
        $sources = Category::forDropdown($business_id, 'source');
        $life_stages = Category::forDropdown($business_id, 'life_stage');

        $types = [];
        $types['customer'] = __('crm::lang.customer');
        $types['lead'] = __('crm::lang.lead');
        $update_action = action([\Modules\Crm\Http\Controllers\LeadController::class, 'update'], ['lead' => $id]);

        return view('contact.edit_crm')
            ->with(compact('contact', 'types', 'update_action', 'sources', 'life_stages', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');
        $can_access_all_leads = auth()->user()->can('crm.access_all_leads');
        $can_access_own_leads = auth()->user()->can('crm.access_own_leads');

        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module')) || ! ($can_access_all_leads || $can_access_own_leads)) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['type', 'prefix', 'first_name', 'middle_name', 'last_name', 'tax_number', 'mobile', 'landline', 'alternate_number', 'city', 'state', 
                                    'country', 'landmark', 'contact_id', 'custom_field1', 'custom_field2', 'custom_field3', 'custom_field4', 'custom_field5', 
                                    'custom_field6', 'custom_field7', 'custom_field8', 'custom_field9', 'custom_field10', 'email', 'crm_source', 'crm_life_stage', 'dob', 
                                    'address_line_1', 'address_line_2', 'zip_code', 'supplier_business_name', 'shipping_custom_field_details', 'export_custom_field_1', 
                                    'export_custom_field_2', 'export_custom_field_3', 'export_custom_field_4', 'export_custom_field_5', 'export_custom_field_6',
                                    'title','business_position', 'street','house_nr'
                                    ]);

            $input['name'] = implode(' ', [$input['prefix'], $input['first_name'], $input['middle_name'], $input['last_name']]);

            $input['is_export'] = ! empty($request->input('is_export')) ? 1 : 0;

            if ($request->input('consent') == 1) {
                $input['consent'] = true;
                $input['consent_email'] = true;
                $input['consent_mobile'] = true;
                $input['consent_post'] = true;
                $input['consent_messenger'] = true;
            }else{
                $input['consent'] = false;
                $input['consent_email'] = ! empty($request->input('consent_email')) ? 1 : 0;
                $input['consent_mobile'] = ! empty($request->input('consent_mobile')) ? 1 : 0;
                $input['consent_post'] = ! empty($request->input('consent_post')) ? 1 : 0;
                $input['consent_messenger'] = ! empty($request->input('consent_messenger')) ? 1 : 0;
            }

            // $input['consent'] = ! empty($request->input('consent')) ? 1 : 0;
            // $input['consent_email'] = ! empty($request->input('consent_email')) ? 1 : 0;
            // $input['consent_mobile'] = ! empty($request->input('consent_mobile')) ? 1 : 0;
            // $input['consent_post'] = ! empty($request->input('consent_post')) ? 1 : 0;
            // $input['consent_messenger'] = ! empty($request->input('consent_messenger')) ? 1 : 0;

            if (! $input['is_export']) {
                unset($input['export_custom_field_1'], $input['export_custom_field_2'], $input['export_custom_field_3'], $input['export_custom_field_4'], $input['export_custom_field_5'], $input['export_custom_field_6']);
            }

            if (! empty($input['dob'])) {
                $input['dob'] = $this->commonUtil->uf_date($input['dob']);
            }

            $assigned_to = $request->input('user_id');
           

            $query = CrmContact::where('business_id', $business_id);

            // $contact_person = Contact::where('id','=',$id)->first();

            $contact_person = $query->findOrFail($id);
            if($contact_person->consent != $input['consent'] || $contact_person->consent_email != $input['consent_email'] 
                || $contact_person->consent_mobile != $input['consent_mobile'] || $contact_person->consent_post != $input['consent_post'] 
                || $contact_person->consent_messenger != $input['consent_messenger']
            ){
                $input['last_consent_date'] = Carbon::now();
                $input['last_consent_in'] = 'in der Firma';
                $input['last_consent_from'] = implode(' ', [auth()->user()->first_name, auth()->user()->last_name]);
            }

            $input_historie['business_id'] = $business_id;
            $input_historie['user_id'] = $request->session()->get('user.id');
            $input_historie['change_date'] = date("Y-m-d");
            $input_historie['title'] = "Daten wurden geändert";
            $input_historie['type'] = "Kunde";
            $input_historie['description'] = "Geändert von " .auth()->user()->getUserNameById(auth()->user()->id);
            $input_historie['ip_address'] = request()->ip();
            
            $this->contactInfoHistorie->saveContactInfoHistorie($contact_person->id, $input_historie , json_encode($request->all()));

            $contact = CrmContact::updateLead($id, $input, $assigned_to);            
            
            $output = [
                'success' => true,
                'msg' => __('lang_v1.success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $can_access_all_leads = auth()->user()->can('crm.access_all_leads');
        $can_access_own_leads = auth()->user()->can('crm.access_own_leads');

        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module')) || ! ($can_access_all_leads || $can_access_own_leads)) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $query = CrmContact::where('business_id', $business_id);

                if (! $can_access_all_leads && $can_access_own_leads) {
                    $query->OnlyOwnLeads();
                }
                $contact = $query->findOrFail($id);

                $contact->delete();

                $output = [
                    'success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    public function convertToCustomer($id)
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $contact = CrmContact::where('business_id', $business_id)->findOrFail($id);

                $contact->type = 'customer';
                $contact->converted_by = auth()->user()->id;
                $contact->converted_on = \Carbon::now();
                $contact->save();

                $customer = Contact::find($contact->id);

                $this->commonUtil->activityLog($customer, 'converted', null, ['update_note' => __('crm::lang.converted_from_leads')]);

                $output = [
                    'success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    public function postLifeStage($id)
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $contact = CrmContact::where('business_id', $business_id)->findOrFail($id);

                $contact->crm_life_stage = request()->input('crm_life_stage');
                $contact->save();

                $output = [
                    'success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    public function index_new_customer()
    {
        return view('crm::lead.index_new_customer');

        // return view('crm::lead.index_new_customer')
        //     ->with(compact('contact', 'leads', 'contact_view_tabs'));
    }

    public function saveCrmNewCustomer(Request $request){
        // return view('crm::lead.index_new_customer');
        
        $con =  ContactsRegistration::all();
        dd('ketu jemi'. count($con));
            // if($request->consent_mobile)
            //     dd('aaaa');
            // else
            //     dd('bbbbb');
        try {    
           
            // $input = $request->only(['prefix', 'first_name', 
            //                         'supplier_business_name', 'last_name', 
            //                         'email', 'mobile', 'street', 'house_nr', 'city','country', 
            //                         'zip_code']);
            
            // if ($request->consent) {
            //     $input['consent'] = true;
            //     $input['consent_email'] = true;
            //     $input['consent_mobile'] = true;
            //     $input['consent_post'] = true;
            //     $input['consent_messenger'] = true;
            // }else{
            //     $input['consent'] = false;
            //     $input['consent_email'] = $request->input('consent_email');
            //     $input['consent_mobile'] = $request->input('consent_mobile');
            //     $input['consent_post'] = $request->input('consent_post');
            //     $input['consent_messenger'] = $request->input('consent_messenger');
            // }

            // if (! empty($input['dob'])) {
            //     $input['dob'] = $this->commonUtil->uf_date($input['dob']);
            // }

            
            $input['business_id'] = $request->business_id;
            $input['location_id'] = $request->location_id;
            // $input['created_by'] = $request->session()->get('user.id');
            
            // $contact = new ContactsRegistration();
            $offer_cost_pro_year = new ContactsRegistration();
            dd('eee');
            $contact->createContactsRegistration();
            // dd('aaaa');
            // $contact = CrmContact::createNewLead($input, 3);

            // if (! empty($contact)) {
            //     $this->moduleUtil->getModuleData('after_contact_saved', ['contact' => $contact, 'input' => $request->input()]);
            // }

            // $output = ['success' => true,
            //     'msg' => __('contact.added_success'),
            // ];
            return redirect()->back()->with('message', 'OK');
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            // return redirect()->back()->with('message', __('messages.something_went_wrong'));
            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

     /**
     * Shows import option for contacts
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function getImportCrmContacts()
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $zip_loaded = extension_loaded('zip') ? true : false;

        //Check if zip extension it loaded or not.
        if ($zip_loaded === false) {
            $output = ['success' => 0,
                'msg' => 'Please install/enable PHP Zip archive for import',
            ];

            return view('crm::lead.import')
                ->with('notification', $output);
        } else {
            return view('crm::lead.import');
        }
    }

    /**
     * Imports contacts
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function postImportCrmContacts(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $notAllowed = $this->commonUtil->notAllowedInDemo();
            if (! empty($notAllowed)) {
                return $notAllowed;
            }

            //Set maximum php execution time
            ini_set('max_execution_time', 0);

            if ($request->hasFile('contacts_csv')) {
                $file = $request->file('contacts_csv');
                $parsed_array = Excel::toArray([], $file);
                //Remove header row
                $imported_data = array_splice($parsed_array[0], 1);

                $business_id = $request->session()->get('user.business_id');
                $user_id = $request->session()->get('user.id');

                $formated_data = [];

                $is_valid = true;
                $error_msg = '';

                DB::beginTransaction();
                foreach ($imported_data as $key => $value) {
                    //Check if 27 no. of columns exists
                    if (count($value) != 24) {
                        $is_valid = false;
                        $error_msg = 'Number of columns mismatch'. count($value);
                        break;
                    }

                    $row_no = $key + 1;
                    $contact_array = [];

                    //Check contact type
                    $contact_type = '';
                    $contact_types = [
                        1 => 'customer',
                    ];
                    if (! empty($value[0])) {
                        $contact_type = strtolower(trim($value[0]));
                        if (in_array($contact_type, [1])) {
                            $contact_array['type'] = $contact_types[$contact_type];
                            $contact_type = $contact_types[$contact_type];
                        } else {
                            $is_valid = false;
                            $error_msg = "Invalid contact type $contact_type in row no. $row_no";
                            break;
                        }
                    } else {
                        $is_valid = false;
                        $error_msg = "Contact type is required in row no. $row_no";
                        break;
                    }

                    $contact_array['prefix'] = $value[1];
                    //Check contact name
                    if (! empty($value[2])) {
                        $contact_array['first_name'] = $value[2];
                    } else {
                        $is_valid = false;
                        $error_msg = "First name is required in row no. $row_no";
                        break;
                    }
                    $contact_array['last_name'] = $value[3];
                    $contact_array['name'] = implode(' ', [$contact_array['prefix'], $contact_array['first_name'], $contact_array['last_name']]);
                    $contact_array['title'] = $value[4];
                    
                    //Check business name
                    if (! empty(trim($value[5]))) {
                        $contact_array['supplier_business_name'] = $value[5];
                    }
                    $contact_array['business_position'] = $value[6];
                    
                    //Check contact ID
                    if (! empty(trim($value[7]))) {
                        $count = Contact::where('business_id', $business_id)
                                    ->where('contact_id', $value[7])
                                    ->count();

                        if ($count == 0) {
                            $contact_array['contact_id'] = $value[7];
                        } else {
                            $is_valid = false;
                            $error_msg = "Contact ID already exists in row no. $row_no";
                            break;
                        }
                    }

                    //Check email
                    if (! empty(trim($value[8]))) {
                        if (filter_var(trim($value[8]), FILTER_VALIDATE_EMAIL)) {
                            $contact_array['email'] = $value[8];
                        } else {
                            $is_valid = false;
                            $error_msg = "Invalid email id in row no. $row_no";
                            break;
                        }
                    }

                    //Mobile number
                    // if (! empty(trim($value[13]))) {
                        $contact_array['mobile'] = $value[9];
                    // } else {
                    //     $is_valid = false;
                    //     $error_msg = "Mobile number is required in row no. $row_no";
                    //     break;
                    // }
                    
                    //Check contact type
                    $contact_consent_email = '';
                    $contact_consent_mobile = '';
                    $contact_consent_post = '';
                    $contact_consent_messenger = '';
                    $contact_consent_chooses = [
                        1 => true,
                        2 => false,
                    ];
                    
                    if (! empty($value[10])) {
                        $contact_consent_email = strtolower(trim($value[10]));
                        if (in_array($contact_consent_email, [1,2])) {
                            $contact_array['consent_email'] = $contact_consent_chooses[$contact_consent_email];
                            $contact_consent_email = $contact_consent_chooses[$contact_consent_email];
                        } else {
                            $is_valid = false;
                            $error_msg = "Invalid contact consent_email $contact_consent_email in row no. $row_no";
                            break;
                        }
                    } else {
                        $is_valid = false;
                        $error_msg = "Contact consent_email is required in row no. $row_no";
                        break;
                    }

                    if (! empty($value[11])) {
                        $contact_consent_mobile = strtolower(trim($value[11]));
                        if (in_array($contact_consent_mobile, [1,2])) {
                            $contact_array['consent_mobile'] = $contact_consent_chooses[$contact_consent_mobile];
                            $contact_consent_mobile = $contact_consent_chooses[$contact_consent_mobile];
                        } else {
                            $is_valid = false;
                            $error_msg = "Invalid contact consent_mobile $contact_consent_mobile in row no. $row_no";
                            break;
                        }
                    } else {
                        $is_valid = false;
                        $error_msg = "Contact consent_mobile is required in row no. $row_no";
                        break;
                    }

                    if (! empty($value[12])) {
                        $contact_consent_post = strtolower(trim($value[12]));
                        if (in_array($contact_consent_post, [1,2])) {
                            $contact_array['consent_post'] = $contact_consent_chooses[$contact_consent_post];
                            $contact_consent_post = $contact_consent_chooses[$contact_consent_post];
                        } else {
                            $is_valid = false;
                            $error_msg = "Invalid contact consent_post $contact_consent_post in row no. $row_no";
                            break;
                        }
                    } else {
                        $is_valid = false;
                        $error_msg = "Contact consent_post is required in row no. $row_no";
                        break;
                    }

                    if (! empty($value[13])) {
                        $contact_consent_messenger = strtolower(trim($value[13]));
                        if (in_array($contact_consent_messenger, [1,2])) {
                            $contact_array['consent_messenger'] = $contact_consent_chooses[$contact_consent_messenger];
                            $contact_consent_messenger = $contact_consent_chooses[$contact_consent_messenger];
                        } else {
                            $is_valid = false;
                            $error_msg = "Invalid contact consent_messenger $contact_consent_messenger in row no. $row_no";
                            break;
                        }
                    } else {
                        $is_valid = false;
                        $error_msg = "Contact consent_messenger is required in row no. $row_no";
                        break;
                    }
                    
                    // $contact_array['consent_email'] = $value[10];
                    // $contact_array['consent_mobile'] = $value[11];
                    // $contact_array['consent_post'] = $value[12];
                    // $contact_array['consent_messenger'] = $value[13];
                 

                    //street
                    $contact_array['street'] = $value[14];

                    //house_nr
                    $contact_array['house_nr'] = $value[15];

                    //City
                    $contact_array['city'] = $value[16];

                    //State
                    // $contact_array['state'] = $value[17];

                    //Country
                    $contact_array['country'] = $value[17];
                    $contact_array['zip_code'] = $value[18];
                    //address_line_1
                    // $contact_array['address_line_1'] = $value[19];
                    // $contact_array['address_line_1'] = implode(' ', [$contact_array['street'], $contact_array['street'], $contact_array['zip_code'], $contact_array['city']]);
                    //address_line_2
                    // $contact_array['address_line_2'] = $value[20];
                    
                    $contact_array['dob'] = $value[19];

                    //Cust fields
                    $contact_array['custom_field1'] = $value[20];
                    $contact_array['custom_field2'] = $value[21];
                    $contact_array['custom_field3'] = $value[22];
                    $contact_array['custom_field4'] = $value[23];

                    $formated_data[] = $contact_array;
                }
                if (! $is_valid) {
                    throw new \Exception($error_msg);
                }

                if (! empty($formated_data)) {
                    foreach ($formated_data as $contact_data) {
                        $ref_count = $this->transactionUtil->setAndGetReferenceCount('contacts');
                        //Set contact id if empty
                        if (empty($contact_data['contact_id'])) {
                            $contact_data['contact_id'] = $this->commonUtil->generateReferenceNumber('contacts', $ref_count);
                        }

                        $opening_balance = 0;
                        // if (isset($contact_data['opening_balance'])) {
                        //     $opening_balance = $contact_data['opening_balance'];
                        //     unset($contact_data['opening_balance']);
                        // }

                        $contact_data['business_id'] = $business_id;
                        $contact_data['created_by'] = $user_id;

                        $contact = Contact::create($contact_data);

                        if (! empty($opening_balance)) {
                            $this->transactionUtil->createOpeningBalanceTransaction($business_id, $contact->id, $opening_balance, $user_id, false);
                        }

                        $this->transactionUtil->activityLog($contact, 'imported');
                    }
                }

                $output = ['success' => 1,
                    'msg' => __('product.file_imported_successfully'),
                ];

                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => 0,
                'msg' => $e->getMessage(),
            ];

            return redirect()->route('crm.customer-import')->with('notification', $output);
        }
       
        $business_id = request()->session()->get('user.business_id');
        $can_access_all_leads = auth()->user()->can('crm.access_all_leads');
        $can_access_own_leads = auth()->user()->can('crm.access_own_leads');

        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module')) || ! ($can_access_all_leads || $can_access_own_leads)) {
            abort(403, 'Unauthorized action.');
        }

        $life_stages = Category::forDropdown($business_id, 'life_stage');

        if (is_null(request()->get('lead_view'))) {
            $lead_view = 'list_view';
        } else {
            $lead_view = request()->get('lead_view');
        }

        if (request()->ajax()) {
            $leads = $this->crmUtil->getLeadsListQuery($business_id);

            if (! $can_access_all_leads && $can_access_own_leads) {
                $leads->OnlyOwnLeads();
            }

            if (! empty(request()->get('source'))) {
                $leads->where('crm_source', request()->get('source'));
            }

            if (! empty(request()->get('life_stage'))) {
                $leads->where('crm_life_stage', request()->get('life_stage'));
            }

            if (! empty(request()->get('user_id'))) {
                $user_id = request()->get('user_id');
                $leads->where(function ($query) use ($user_id) {
                    $query->whereHas('leadUsers', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                });
            }

            if ($lead_view == 'list_view') {
                return Datatables::of($leads)
                    ->addColumn('address', '{{implode(", ", array_filter([$address_line_1, $address_line_2, $city, $state, $country, $zip_code]))}}')
                    ->addColumn('action', function ($row) {
                        $html = '<div class="btn-group">
                                    <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                        '.__('messages.action').'
                                        <span class="caret"></span>
                                        <span class="sr-only">'
                                           .__('messages.action').'
                                        </span>
                                    </button>
                                      <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                       <li>
                                            <a href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'show'], ['lead' => $row->id]).'" class="cursor-pointer view_lead">
                                                <i class="fa fa-eye"></i>
                                                '.__('messages.view').'
                                            </a>
                                        </li>
                                        <li>
                                            <a data-href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'edit'], ['lead' => $row->id]).'"class="cursor-pointer edit_lead">
                                                <i class="fa fa-edit"></i>
                                                '.__('messages.edit').'
                                            </a>
                                        </li>
                                      <!--<li>
                                            <a data-href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'convertToCustomer'], ['id' => $row->id]).'" class="cursor-pointer convert_to_customer">
                                                <i class="fas fa-redo"></i>
                                                '.__('crm::lang.convert_to_customer').'
                                            </a>
                                        </li>-->
                                        <li>
                                            <a data-href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'destroy'], ['lead' => $row->id]).'" class="cursor-pointer delete_a_lead">
                                                <i class="fas fa-trash"></i>
                                                '.__('messages.delete').'
                                            </a>
                                        </li>';
                                            if(! empty($row->first_name) && !empty($row->business_id)){
                                                $html .= '<li>
                                                    <a  target="_blank" href="'.action([\App\Http\Controllers\ContactsRegistrationController::class, 'self_change_data'], ['business_id' => $row->business_id, 'contact_id' => $row->id, 'first_name' => $row->first_name ]).'">
                                                        <i class="fa fa-edit"></i>
                                                        '.__('lang_v1.link_self_edit_data').'
                                                    </a>
                                                </li>
                                                <li>
                                                    <a  target="_blank" href="'.action([\App\Http\Controllers\ContactsRegistrationController::class, 'customer_destroy'], ['business_id' => $row->business_id, 'contact_id' => $row->id, 'first_name' => $row->first_name ]).'">
                                                        <i class="fa fa-exclamation-circle"></i>
                                                        '.__('lang_v1.opt_out').'
                                                    </a>
                                                </li>                                        
                                                ';
                                            }

                        $html .= '</ul>
                                </div>';

                        return $html;
                    })
                    ->addColumn('last_follow_up', function ($row) {
                        $html = '';

                        if (! empty($row->last_follow_up)) {
                            $html .= $this->commonUtil->format_date($row->last_follow_up, true);
                            $html .= '<br><a href="#" target="_blank" title="'.__('crm::lang.view_follow_up').'" data-toggle="tooltip">
                                <i class="fas fa-external-link-alt"></i>
                            </a><br>';
                        }

                        $infos = json_decode($row->last_follow_up_additional_info, true);

                        if (! empty($infos)) {
                            foreach ($infos as $key => $value) {
                                $html .= $key.' : '.$value.'<br>';
                            }
                        }

                        return $html;
                    })
                    ->orderColumn('last_follow_up', function ($query, $order) {
                        $query->orderBy('last_follow_up', $order);
                    })
                    ->addColumn('upcoming_follow_up', function ($row) {
                        $html = '';

                        if (! empty($row->upcoming_follow_up)) {
                            $html .= $this->commonUtil->format_date($row->upcoming_follow_up, true);
                            $html .= '<br><a href="#" target="_blank" title="'.__('crm::lang.view_follow_up').'" data-toggle="tooltip">
                                <i class="fas fa-external-link-alt"></i>
                            </a><br>';
                        }

                        $html .= '<a href="#" data-href="'.action([\Modules\Crm\Http\Controllers\ScheduleController::class, 'create'], ['schedule_for' => 'lead', 'contact_id' => $row->id]).'" class="btn-modal btn btn-xs btn-primary" data-container=".schedule">
                            <i class="fas fa-plus"></i>'.
                            __('crm::lang.add_schedule').'
                        </a><br>';

                        $infos = json_decode($row->upcoming_follow_up_additional_info, true);

                        if (! empty($infos)) {
                            foreach ($infos as $key => $value) {
                                $html .= $key.' : '.$value.'<br>';
                            }
                        }

                        return $html;
                    })
                    ->orderColumn('upcoming_follow_up', function ($query, $order) {
                        $query->orderBy('upcoming_follow_up', $order);
                    })
                    ->editColumn('created_at', '
                        {{@format_date($created_at)}}
                    ')
                    ->editColumn('dob', function ($row) {
                        if($row->dob)
                            return $this->commonUtil->format_date($row->dob);
                        else
                            return null;
                    })
                    ->editColumn('crm_source', function ($row) {
                        return $row->Source?->name;
                    })
                    ->editColumn('crm_life_stage', function ($row) {
                        return $row->lifeStage?->name;
                    })                    
                    ->editColumn('name', '@if(!empty($supplier_business_name)) {{$supplier_business_name}},<br>@endif {{$name}}')
                    ->editColumn('business_position', $business_position
                        	
                    )
                    
                    ->editColumn('leadUsers', function ($row) {
                        $html = '&nbsp;';
                        foreach ($row->leadUsers as $leadUser) {
                            if (isset($leadUser->media->display_url)) {
                                $html .= '<img class="user_avatar" src="'.$leadUser->media->display_url.'" data-toggle="tooltip" title="'.$leadUser->user_full_name.'">';
                            } else {
                                $html .= '<img class="user_avatar" src="https://ui-avatars.com/api/?name='.$leadUser->first_name.'" data-toggle="tooltip" title="'.$leadUser->user_full_name.'">';
                            }
                        }

                        return $html;
                    })
                    ->removeColumn('id')
                    ->filterColumn('address', function ($query, $keyword) {
                        $query->where(function ($q) use ($keyword) {
                            $q->where('address_line_1', 'like', "%{$keyword}%")
                            ->orWhere('address_line_2', 'like', "%{$keyword}%")
                            ->orWhere('city', 'like', "%{$keyword}%")
                            ->orWhere('state', 'like', "%{$keyword}%")
                            ->orWhere('country', 'like', "%{$keyword}%")
                            ->orWhere('zip_code', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(COALESCE(address_line_1, ''), ', ', COALESCE(address_line_2, ''), ', ', COALESCE(city, ''), ', ', COALESCE(state, ''), ', ', COALESCE(country, '') ) like ?", ["%{$keyword}%"]);
                        });
                    })
                    ->rawColumns(['action', 'crm_source', 'crm_life_stage', 'leadUsers', 'last_follow_up', 'upcoming_follow_up', 'created_at', 'name','business_position'])
                    ->make(true);
            }             
        }

        $sources = Category::forDropdown($business_id, 'source');

        $users = User::forDropdown($business_id, false, false, false, true);

        // return view('crm::lead.index')
        //     ->with(compact('sources', 'life_stages', 'lead_view', 'users'));

        return redirect('/crm/leads?lead_view=list_view') ->with(compact('sources', 'life_stages', 'lead_view', 'users'));
        // return redirect()->action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => $type])->with('status', $output);
    }

    public function editByShow($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $can_access_all_leads = auth()->user()->can('crm.access_all_leads');
        $can_access_own_leads = auth()->user()->can('crm.access_own_leads');

        if (! (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module')) || ! ($can_access_all_leads || $can_access_own_leads)) {
            abort(403, 'Unauthorized action.');
        }

        $query = CrmContact::with('leadUsers')
                    ->where('business_id', $business_id);

        if (! $can_access_all_leads && $can_access_own_leads) {
            $query->OnlyOwnLeads();
        }
        $contact = $query->findOrFail($id);

        $users = User::forDropdown($business_id, false);
        $sources = Category::forDropdown($business_id, 'source');
        $life_stages = Category::forDropdown($business_id, 'life_stage');

        $types = [];
        $types['customer'] = __('crm::lang.customer');
        $types['lead'] = __('crm::lang.lead');
        $update_action = action([\Modules\Crm\Http\Controllers\LeadController::class, 'update'], ['lead' => $id]);

        return view('contact.edit_crm')
            ->with(compact('contact', 'types', 'update_action', 'sources', 'life_stages', 'users'));
    }
}
