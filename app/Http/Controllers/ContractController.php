<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\Contact;
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
use App\ContactInfoHistorie;

class ContractController extends Controller
{
    protected $commonUtil;

    protected $contactUtil;

    protected $transactionUtil;

    protected $moduleUtil;

    protected $notificationUtil;

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
        ModuleUtil $moduleUtil,
        TransactionUtil $transactionUtil,
        NotificationUtil $notificationUtil,
        ContactUtil $contactUtil,
        CrmUtil $crmUtil,
        ContactInfoHistorie $contactInfoHistorie
    ) {
        $this->commonUtil = $commonUtil;
        $this->contactUtil = $contactUtil;
        $this->moduleUtil = $moduleUtil;
        $this->transactionUtil = $transactionUtil;
        $this->notificationUtil = $notificationUtil;
        $this->crmUtil = $crmUtil;
        $this->contactInfoHistorie = $contactInfoHistorie;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Contact $contact, Contract $contract)
    {
        
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        
        $contact_id = $request->get('lead_id');
        // $contract = Contract::all();
        
        $test_data = "eeee";
        $contract = Contract::where('business_id', $business_id)
        ->select('*');

        return Datatables::of($contract)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <li>
                                    
                                        <i class="fa fa-eye"></i>
                                        ' . __('crm::lang.view_follow_up') . '
                                    
                                </li>
                                <li>
                                    <a data-href="' . action([\Modules\Crm\Http\Controllers\ScheduleController::class, 'edit'], ['follow_up' => $row->id]) . '?schedule_for=lead"class="cursor-pointer schedule_edit">
                                        <i class="fa fa-edit"></i>
                                        ' . __('messages.edit') . '
                                    </a>
                                </li>
                                <li>
                                    <a data-href="' . action([\Modules\Crm\Http\Controllers\ScheduleController::class, 'destroy'], ['follow_up' => $row->id]) . '" class="cursor-pointer schedule_delete">
                                        <i class="fas fa-trash"></i>
                                        ' . __('messages.delete') . '
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                $html = '<div class="btn-group">
                        <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                        . __('messages.action') . '
                                </span>
                        </button>
                        </div>';
                return $html;
            })
            ->rawColumns(['action', 'person_id'])
            ->make(true);

           
        $view_values = [
            'test_data' => "eeee",
            'contract' => $contract,
            'contact_id' => $contact_id,
        ];
            return view('contract.index')->with(($view_values));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $contact_id = 18;

        $view_values = [
            'contact_id' => 'eeead',
        ];

        $users = User::forDropdown($business_id, false);
        return view('contract.create')->with($view_values);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Contract $contract, Request $request )
    {
        // dd($this->commonUtil->convertInputNumber($request->input('fee_monthly')) - $this->commonUtil->convertInputNumber($request->input('discount')));
        $url_with_contact_id = $request->url_previous;
        /*            
            $contact_id = substr(strrchr($url_with_contact_id, "/"), 1 );
            OR next COde
        */ 
        $parts = explode('/', $url_with_contact_id);
        $contact_id = $parts[count($parts)-1];
        // dd($contact_id);
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }
        $business_location = BusinessLocation::where('business_id','=',  $business_id)
                            ->first();

        $contract->contact_id = $contact_id;
        $contract->business_id = $business_id;
        $contract->location_id = $business_location->id;
        $contract->created_by = $request->session()->get('user.id');
        $contract->number = $contract->getMaxNrForContract($contact_id) + 1;
        $contract->connected_to_number = $request->input('connected_to_number');
        $contract->contract_feld1 = $request->input('contract_feld1');
        $contract->contract_feld2 = $request->input('contract_feld2');
        $contract->contract_feld3 = $request->input('contract_feld3');
        $contract->contract_feld4 = $request->input('contract_feld4');
        $contract->contract_feld5 = $request->input('contract_feld5');
        $contract->contract_start_date = $request->input('PMDate');
        $contract->contract_end_date = $request->input('NPMDate');
        $contract->fee_monthly = $this->commonUtil->convertInputNumber($request->input('fee_monthly'));
        $contract->discount = $this->commonUtil->convertInputNumber($request->input('discount'));
        $contract->contract_feld6 = $request->input('contract_feld6');
        $contract->discount_duraction = $request->input('discount_duraction');
        // $contract->price_total = $request->input('price_total');
        $contract->price_total = $this->commonUtil->convertInputNumber($request->input('fee_monthly')) - $this->commonUtil->convertInputNumber($request->input('discount'));
        $contract->contract_completion = $request->input('contract_completion');
        $contract->contract_duraction = $request->input('contract_duraction');
        $contract->contact_before_end_of_contract = $request->input('contact_before_end_of_contract');
        $contract->date_to_contact = $request->input('ConDate');
        $contract->contract_info = $request->input('contract_info');
        $contract->save();

        $input_historie['business_id'] = $contract->business_id;
        $input_historie['user_id'] = $request->session()->get('user.id');
        $input_historie['title'] = "Neu Vertrag erstellen";
        $input_historie['type'] = "Vertrag";
        $input_historie['details'] = "Vertragsart: <b>".$contract->contract_feld1."</b>";
        $input_historie['description'] = "Erstellt von " .auth()->user()->getUserNameById(auth()->user()->id);
        $input_historie['ip_address'] = request()->ip();
        
        $this->contactInfoHistorie->saveContractContactInfoHistorie($contract->contact_id, $input_historie , json_encode($request->all()));


        if (request()->ajax()) {
            return $output;
        } else {
        return redirect()->back();}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contract = Contract::where('id','=', $id)->first();
        return view('contract.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contract = Contract::where('id','=', $id)->first();

        return view('contract.edit')->with(compact('contract'));
        // return view('contract.edit');
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
        // dd($request->input('PMDate').'start-ende'.$request->input('NPMDate'));
        $contract = Contract::where('id','=', $id)->first();
        $contract->connected_to_number = $request->input('connected_to_number');
        $contract->contract_feld1 = $request->input('contract_feld1');
        $contract->contract_feld2 = $request->input('contract_feld2');
        $contract->contract_feld3 = $request->input('contract_feld3');
        $contract->contract_feld4 = $request->input('contract_feld4');
        $contract->contract_feld5 = $request->input('contract_feld5');
        $contract->contract_start_date = $request->input('PMDate');
        $contract->contract_end_date = $request->input('NPMDate');
        $contract->fee_monthly = $this->commonUtil->convertInputNumber($request->input('fee_monthly'));
        $contract->discount = $this->commonUtil->convertInputNumber($request->input('discount'));
        $contract->contract_feld6 = $request->input('contract_feld6');
        $contract->discount_duraction = $request->input('discount_duraction');
        // $contract->price_total = $request->input('price_total');
        $contract->price_total = $this->commonUtil->convertInputNumber($request->input('fee_monthly')) - $this->commonUtil->convertInputNumber($request->input('discount'));
        $contract->contract_completion = $request->input('contract_completion');
        $contract->contract_duraction = $request->input('contract_duraction');
        $contract->contact_before_end_of_contract = $request->input('contact_before_end_of_contract');
        $contract->date_to_contact = $request->input('ConDate');
        $contract->contract_info = $request->input('contract_info');

        $contract->save();
        
        return redirect()->back();
        // dd('update hier, beabeitet!!' . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $contract = Contract::where('id', $id)
                                ->first();

                $contract->delete();

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

    public function getCustomerContract(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'crm_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $contact_id = $request->get('contact_id');
        // $contract = Contract::all();
        
        $contract = Contract::where('contact_id', $contact_id)
                    // ->orderBy('number')
                    ->select('*');

        return Datatables::of($contract)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <!--<li>
                                    <a data-href="'.action([\App\Http\Controllers\ContractController::class, 'show'], ['contract' => $row->id]).'" class="cursor-pointer schedule_edit">
                                        <i class="fa fa-edit"></i>
                                        ' . __('contract.show_contract') . '
                                    </a>                                    
                                </li>-->
                                <li>
                                    <a data-href="'.action([\App\Http\Controllers\ContractController::class, 'edit'], ['contract' => $row->id]).'" class="cursor-pointer schedule_edit">
                                        <i class="fa fa-edit"></i>
                                        ' . __('messages.edit') . '
                                    </a>
                                </li>
                                <li>
                                <a data-href="'.action([\App\Http\Controllers\ContractController::class, 'destroy'], ['contract' => $row->id]).'" class="cursor-pointer delete_a_contract">
                                        <i class="fas fa-trash"></i>
                                        ' . __('messages.delete') . '
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                
                return $html;
            })
            ->addColumn('is_date_in_this_week', function ($row) {                
                $FirstDay = date("Y-m-d", strtotime('monday this week')); 
                $LastDay = date("Y-m-d", strtotime('sunday this week'));

                if($row->date_to_contact != null && $row->date_to_contact >= $FirstDay &&  $row->date_to_contact <= $LastDay){
                    return true;
                }
                else
                    return false;
            })
            ->addColumn('is_date_in_this_month', function ($row) {                
                $today =  \Carbon::now()->toDateString();

                if($row->date_to_contact != null && date('m', strtotime($row->date_to_contact)) === date('m', strtotime($today))  && date('Y', strtotime($row->date_to_contact)) === date('Y', strtotime($today))) {
                    return true;
                }
                else
                    return false;
            })
            ->addColumn('is_date_in_next_month', function ($row) {                
                $today =  \Carbon::now()->toDateString();

                if($row->date_to_contact != null && date('m', strtotime($row->date_to_contact))+0 === (date('m', strtotime($today))+1 ) && date('Y', strtotime($row->date_to_contact)) === date('Y', strtotime($today))) {
                    return true;
                }
                else
                    return false;
            })
            ->addColumn('is_date_overdue', function ($row) {                
                $today =  date("Y-m-d");

                if($row->date_to_contact != null && $row->date_to_contact < $today){
                    return true;
                }
                else
                    return false;
            })
            ->addColumn('difference', function ($row) {
                $now = date("Y-m-d");
               return $this->commonUtil->func_diff_date($now, $row->date_to_contact);
                // return date("Y-m-d");
            })
            ->addColumn('difference_end_date', function ($row) {
                $now = date("Y-m-d");
               return $this->commonUtil->func_diff_date($now, $row->contract_end_date);
            })
            ->addColumn('between_contact_date_and_enddate', function ($row) {
                $now = date("Y-m-d");
                return $this->commonUtil->is_date_between_two_other_date($now, $row->date_to_contact,  $row->contract_end_date);
            })
            ->editColumn(
                'fee_monthly',
                '<span class="fee-monthly" data-orig-value="{{$fee_monthly}}">@format_currency($fee_monthly)</span>'
            )
            ->editColumn(
                'discount',
                '<span class="discount" data-orig-value="{{$discount}}">@format_currency($discount)</span>'
            )
            ->editColumn(
                'price_total',
                '<span class="price-total" data-orig-value="{{$price_total}}">@format_currency($price_total)</span>'
            )
            // ->editColumn('price_total', function ($row) {
            //     if($row->price_total)
            //         return number_format($row->price_total, 2, ',', ' '). ' €';
            //     else
            //         return '0,00 €';
            // })
            ->editColumn('contract_start_date', function ($row) {
                if($row->contract_start_date)
                    return $this->commonUtil->format_date($row->contract_start_date);
                else
                    return null;
            })
            ->editColumn('contract_end_date', function ($row) {
                if($row->contract_end_date)
                    return $this->commonUtil->format_date($row->contract_end_date);
                else
                    return null;
            })
            ->editColumn('date_to_contact', function ($row) {
                if($row->date_to_contact)
                    return $this->commonUtil->format_date($row->date_to_contact);
                else
                    return null;
            })
            // ->editColumn('users', function ($row) {
            //     $html = '&nbsp;';
            //     foreach ($row->users as $user) {
            //         if (isset($user->media->display_url)) {
            //             $html .= '<img class="user_avatar" src="' . $user->media->display_url . '" data-toggle="tooltip" title="' . $user->user_full_name . '">';
            //         } else {
            //             $html .= '<img class="user_avatar" src="https://ui-avatars.com/api/?name=' . $user->first_name . '" data-toggle="tooltip" title="' . $user->user_full_name . '">';
            //         }
            //     }

            //     return $html;
            // })
            // ->addColumn('number', function ($row) {
            //     return 1;
            // })
            ->removeColumn('id')
            ->rawColumns(['fee_monthly','discount','price_total','action', 'person_id'
            ])
            ->make(true);
    }

    public function getNewThisWeek(){
        // dd('getContractExtensionThisWeek hier!!');
    }
    public function getContractExtensionThisWeek(){
        // dd('getContractExtensionThisWeek hier!!');
        $business_id = request()->session()->get('user.business_id');
        
        $class_contract = new Contract();

        $view_values = [
            'class_contract' => $class_contract,
        ];

        $contracts = $class_contract->getContractsByCondition( $business_id, 'this_week');

        if (request()->ajax()) {
            $datatable = Datatables::of($contracts)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <li>
                                    <a href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'show'], ['lead' => $row->contact_id]).'" class="cursor-pointer view_lead" target="_blank">
                                        <i class="fa fa-eye"></i>
                                        ' . __('contract.to_customer_show') . '
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                
                return $html;
                }
            )
            ->addColumn('customer_name', function ($row) {
               return $row->customer->name;
            })
            ->editColumn(
                'fee_monthly',
                '<span class="fee-monthly" data-orig-value="{{$fee_monthly}}">@format_currency($fee_monthly)</span>'
            )
            ->editColumn(
                'discount',
                '<span class="discount" data-orig-value="{{$discount}}">@format_currency($discount)</span>'
            )
            ->editColumn(
                'price_total',
                '<span class="price-total" data-orig-value="{{$price_total}}">@format_currency($price_total)</span>'
            )
            ->editColumn('contract_start_date', function ($row) {
                if($row->contract_start_date)
                    return $this->commonUtil->format_date($row->contract_start_date);
                else
                    return null;
            })
            ->editColumn('contract_end_date', function ($row) {
                if($row->contract_end_date)
                    return $this->commonUtil->format_date($row->contract_end_date);
                else
                    return null;
            })
            ->editColumn('date_to_contact', function ($row) {
                if($row->date_to_contact)
                    return $this->commonUtil->format_date($row->date_to_contact);
                else
                    return null;
            })
            ;


            $rawColumns = ['fee_monthly', 'action', 'discount', 'price_total', 'payment_status', 'invoice_no', 'discount_amount', 'tax_amount', 'total_before_tax', 
            'shipping_status', 'types_of_service_name', 'payment_methods', 'return_due', 'conatct_name', 'status'];

            return $datatable->rawColumns($rawColumns)
                      ->make(true);
        
        }
        
        return view('contract.this_week')->with($view_values);
    }

    public function getContractExtensionThisMonth(){
        // dd('getContractExtensionThisMonth hier!!');
        $business_id = request()->session()->get('user.business_id');
        
        $class_contract = new Contract();

        $view_values = [
            'class_contract' => $class_contract,
        ];

        $contracts = $class_contract->getContractsByCondition( $business_id, 'this_month');

        if (request()->ajax()) {
            $datatable = Datatables::of($contracts)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <li>
                                    <a href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'show'], ['lead' => $row->contact_id]).'" class="cursor-pointer view_lead">
                                        <i class="fa fa-eye"></i>
                                        ' . __('contract.to_customer_show') . '
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                
                return $html;
                }
            )
            ->addColumn('customer_name', function ($row) {
               return $row->customer->name;
            })
            ->editColumn(
                'fee_monthly',
                '<span class="fee-monthly" data-orig-value="{{$fee_monthly}}">@format_currency($fee_monthly)</span>'
            )
            ->editColumn(
                'discount',
                '<span class="discount" data-orig-value="{{$discount}}">@format_currency($discount)</span>'
            )
            ->editColumn(
                'price_total',
                '<span class="price-total" data-orig-value="{{$price_total}}">@format_currency($price_total)</span>'
            )
            ->editColumn('contract_start_date', function ($row) {
                if($row->contract_start_date)
                    return $this->commonUtil->format_date($row->contract_start_date);
                else
                    return null;
            })
            ->editColumn('contract_end_date', function ($row) {
                if($row->contract_end_date)
                    return $this->commonUtil->format_date($row->contract_end_date);
                else
                    return null;
            })
            ->editColumn('date_to_contact', function ($row) {
                if($row->date_to_contact)
                    return $this->commonUtil->format_date($row->date_to_contact);
                else
                    return null;
            })
            ;


            $rawColumns = ['fee_monthly', 'action', 'discount', 'price_total', 'payment_status', 'invoice_no', 'discount_amount', 'tax_amount', 'total_before_tax', 'shipping_status', 'types_of_service_name', 'payment_methods', 'return_due', 'conatct_name', 'status'];

            return $datatable->rawColumns($rawColumns)
                      ->make(true);
        
        }
        
        return view('contract.this_month')->with($view_values);
    } 

    public function getContractExtensionNextMonth(){
        // dd('getContractExtensionNextMonth hier!!');
        $business_id = request()->session()->get('user.business_id');
        
        $class_contract = new Contract();

        $view_values = [
            'class_contract' => $class_contract,
        ];

        $contracts = $class_contract->getContractsByCondition( $business_id, 'next_month');

        if (request()->ajax()) {
            $datatable = Datatables::of($contracts)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <li>
                                    <a href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'show'], ['lead' => $row->contact_id]).'" class="cursor-pointer view_lead">
                                        <i class="fa fa-eye"></i>
                                        ' . __('contract.to_customer_show') . '
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                
                return $html;
                }
            )
            ->addColumn('customer_name', function ($row) {
               return $row->customer->name;
            })
            ->editColumn(
                'fee_monthly',
                '<span class="fee-monthly" data-orig-value="{{$fee_monthly}}">@format_currency($fee_monthly)</span>'
            )
            ->editColumn(
                'discount',
                '<span class="discount" data-orig-value="{{$discount}}">@format_currency($discount)</span>'
            )
            ->editColumn(
                'price_total',
                '<span class="price-total" data-orig-value="{{$price_total}}">@format_currency($price_total)</span>'
            )
            ->editColumn('contract_start_date', function ($row) {
                if($row->contract_start_date)
                    return $this->commonUtil->format_date($row->contract_start_date);
                else
                    return null;
            })
            ->editColumn('contract_end_date', function ($row) {
                if($row->contract_end_date)
                    return $this->commonUtil->format_date($row->contract_end_date);
                else
                    return null;
            })
            ->editColumn('date_to_contact', function ($row) {
                if($row->date_to_contact)
                    return $this->commonUtil->format_date($row->date_to_contact);
                else
                    return null;
            })
            ;


            $rawColumns = ['fee_monthly', 'action', 'discount', 'price_total', 'payment_status', 'invoice_no', 'discount_amount', 'tax_amount', 'total_before_tax', 'shipping_status', 'types_of_service_name', 'payment_methods', 'return_due', 'conatct_name', 'status'];

            return $datatable->rawColumns($rawColumns)
                      ->make(true);
        
        }
        
        return view('contract.next_month')->with($view_values);
    } 

    public function getContractExtensionOverdue(){
        // dd('getContractExtensionOverdue hier!!');
        $business_id = request()->session()->get('user.business_id');
        
        $class_contract = new Contract();

        $view_values = [
            'class_contract' => $class_contract,
        ];

        $contracts = $class_contract->getContractsByCondition( $business_id, 'overdue');

        if (request()->ajax()) {
            $datatable = Datatables::of($contracts)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <li>
                                    <a href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'show'], ['lead' => $row->contact_id]).'" class="cursor-pointer view_lead">
                                        <i class="fa fa-eye"></i>
                                        ' . __('contract.to_customer_show') . '
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                
                return $html;
                }
            )
            ->addColumn('customer_name', function ($row) {
               return $row->customer->name;
            })
            ->editColumn(
                'fee_monthly',
                '<span class="fee-monthly" data-orig-value="{{$fee_monthly}}">@format_currency($fee_monthly)</span>'
            )
            ->editColumn(
                'discount',
                '<span class="discount" data-orig-value="{{$discount}}">@format_currency($discount)</span>'
            )
            ->editColumn(
                'price_total',
                '<span class="price-total" data-orig-value="{{$price_total}}">@format_currency($price_total)</span>'
            )
            ->editColumn('contract_start_date', function ($row) {
                if($row->contract_start_date)
                    return $this->commonUtil->format_date($row->contract_start_date);
                else
                    return null;
            })
            ->editColumn('contract_end_date', function ($row) {
                if($row->contract_end_date)
                    return $this->commonUtil->format_date($row->contract_end_date);
                else
                    return null;
            })
            ->editColumn('date_to_contact', function ($row) {
                if($row->date_to_contact)
                    return $this->commonUtil->format_date($row->date_to_contact);
                else
                    return null;
            })
            ;


            $rawColumns = ['fee_monthly', 'action', 'discount', 'price_total', 'payment_status', 'invoice_no', 'discount_amount', 'tax_amount', 'total_before_tax', 'shipping_status', 'types_of_service_name', 'payment_methods', 'return_due', 'conatct_name', 'status'];

            return $datatable->rawColumns($rawColumns)
                      ->make(true);
        
        }
        
        return view('contract.overdue')->with($view_values);
    } 

    public function getContractsdetails(){
        $business_id = request()->session()->get('user.business_id');
        $current_user_id = request()->session()->get('user.id');
        
        $class_contract = new Contract();

        $users = User::forDropdown($business_id, false, false, false, true);
        $is_admin = $this->contactUtil->is_admin(auth()->user());
        
        $contracts = Contract::with(['creator', 'customer'])
                    ->where('business_id', $business_id);

        if (!empty(request()->start_date) && !empty(request()->end_date)) {
            $contracts->whereDate('created_at', '>=', request()->start_date)
                      ->whereDate('created_at', '<=', request()->end_date);
        }
        
        // Korrekte Filterung für Admin:
        if ($is_admin && !empty(request()->user_id)) {
            $contracts->where('created_by', request()->user_id);
        }

        if(!$is_admin){
            $contracts->where('created_by', $current_user_id);
        }

        $view_values = [
            'class_contract' => $class_contract,
            'users' => $users,
            'is_admin' => $is_admin,
        ];

        // $contracts = $class_contract->getContractsByCondition( $business_id, 'next_month');

        if (request()->ajax()) {
            
            $datatable = Datatables::of($contracts)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                ' . __('messages.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">'
                    . __('messages.action') . '
                                </span>
                            </button>
                              <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                <li>
                                    <a href="'.action([\Modules\Crm\Http\Controllers\LeadController::class, 'show'], ['lead' => $row->contact_id]).'" class="cursor-pointer view_lead">
                                        <i class="fa fa-eye"></i>
                                        ' . __('contract.to_customer_show') . '
                                    </a>
                                </li>
                            </ul>
                        </div>'; 
                
                return $html;
                }
            )
            ->addColumn('customer_name', function ($row) {
               return $row->customer->name;
            })
            ->addColumn('user_name', function ($row) {
                return $row->creator 
                    ? trim("{$row->creator->surname} {$row->creator->first_name} {$row->creator->last_name}")
                    : null;
            })
            ->editColumn(
                'fee_monthly',
                '<span class="fee-monthly" data-orig-value="{{$fee_monthly}}">@format_currency($fee_monthly)</span>'
            )
            ->editColumn(
                'discount',
                '<span class="discount" data-orig-value="{{$discount}}">@format_currency($discount)</span>'
            )
            ->editColumn(
                'price_total',
                '<span class="price-total" data-orig-value="{{$price_total}}">@format_currency($price_total)</span>'
            )
            ->editColumn('contract_start_date', function ($row) {
                return $row->contract_start_date
                    ? $this->commonUtil->format_date($row->contract_start_date)
                    : null;
            })
            ->editColumn('contract_end_date', function ($row) {
                return $row->contract_end_date
                    ? $this->commonUtil->format_date($row->contract_end_date)
                    : null;
            })
            ->editColumn('date_to_contact', function ($row) {
                return $row->date_to_contact
                    ? $this->commonUtil->format_date($row->date_to_contact)
                    : null;
            })
            ->editColumn('create_contract_date', function ($row) {
                return $row->created_at
                    ? $this->commonUtil->format_date($row->created_at)
                    : null;
            })
            ;


            $rawColumns = ['fee_monthly', 'action', 'discount', 
                    'price_total', 'payment_status', 'invoice_no',
                    'discount_amount', 'tax_amount', 'total_before_tax', 
                    'shipping_status', 'types_of_service_name', 
                    'payment_methods', 'return_due', 'conatct_name', 
                    'status','user_name','create_contract_date'];

            return $datatable->rawColumns($rawColumns)
                      ->make(true);
        
        }
        
        return view('contract.details')->with($view_values);
        
    }
}
