<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\CashRegister;
use App\CashRegisterDetail;
use App\Contract;
use App\Contact;
use App\User;
use App\Transaction;
use App\TransactionPayment;
use App\TransactionSellLine;
use App\TaxRate;
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
use App\Services\FccService;
use Illuminate\Http\RedirectResponse;
use GuzzleHttp\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Expr\FuncCall;

class CashRegisterDetailController extends Controller
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
    public function index(Request $reques, CashRegisterDetail $cashRegisterDetail, CashRegister $cashRegister)
    {
        $business_id = request()->session()->get('user.business_id');
        // if (!(auth()->user()->can('admin') )) {
        //     abort(403, 'Unauthorized action.');
        // }

        $cash_registers_details = CashRegisterDetail::where('business_id', $business_id)
                                    ->get();

        $business_locations = BusinessLocation::forDropdown($business_id);
        $b_locations = new BusinessLocation();

        $business_locations_query = $b_locations->getAllLocationsByBusinessId($business_id);        
        
        $view_values = [
            'test_data' => "eeee",
            'cash_registers_details' => $cash_registers_details,
            'business_locations' => $business_locations,
            'business_locations_query' => $business_locations_query,
            'cashRegisterDetail' => $cashRegisterDetail,
        ];                                   
        
        return view('cash_register_detail.index')->with(($view_values));
    }

    public function getListCashRegisterDetail(Request $reques, CashRegisterDetail $cashRegisterDetail, CashRegister $cashRegister)
    {
        $business_id = request()->session()->get('user.business_id');
        // if (!(auth()->user()->can('admin') )) {
        //     abort(403, 'Unauthorized action.');
        // }

        $cash_registers_details = CashRegisterDetail::where('business_id', $business_id)
                                    ->select('*');

        return Datatables::of($cash_registers_details)
        ->addColumn('action', 
        '
        {{-- <button type="button"data-href=""class="btnbtn-xsbtn-primarybtn-modal"data-container=".cash_register_edit_modal"><iclass="glyphiconglyphicon-edit"></i>@lang("messages.edit")</button> --}}
       
        <button type="button" data-href="{{action(\'App\Http\Controllers\CashRegisterDetailController@activateDeactivateCashRegisterDetail\', [$id])}}" class="btn btn-xs 
            activate-deactivate-cash-register @if($cash_register_active) btn-danger @else btn-success @endif">
            <i class="fa fa-power-off"></i> 
            @if($cash_register_active) @lang("cash_register.deactivate_cash_register") @else @lang("cash_register.activate_cash_register") @endif 
        </button>
        <a href="{{route(\'tse_auth\', [$id])}}" class="btn btn-success btn-xs"><i class="fa fa-wrench"></i> TSE-Auth</a>
        '
        // @if($cash_register_active)
        //     <button type="button" data-href="{{action(\'App\Http\Controllers\CashRegisterDetailController@activateDeactivateTSS\', [$id])}}" class="btn btn-xs 
        //         activate-deactivate-cash-register @if($tss_active) btn-danger @else btn-success @endif">
        //         <i class="fa fa-power-off"></i> 
        //         @if($tss_active) @lang("cash_register.deactivate_tss") @else @lang("cash_register.activate_tss") @endif 
        //     </button>
        // @endif
        
        )
        ->addColumn('location_name', function ($row){
            return $row->business_location->getLocationNameById();
        })
        ->addColumn('active_tss', function ($row){
            if($row->tss_active)
                return 'Ja';
            else
                return 'Nein';
        })
        ->rawColumns(['action', 'person_id'])
        ->make(true);
        
        $view_values = [
            'test_data' => "eeee",
            'cash_registers_details' => $cash_registers_details,
        ];                                   
        
        // return view('cash_register_detail.index')->with(($view_values));
    }
    public function getTseAuth($id){
        $business_id = request()->session()->get('user.business_id');

        if(request()->session()->get('user.id') == 2 || $business_id == 7){
            $eas = CashRegisterDetail::where('id', $id)->first();

            $location = BusinessLocation::where('id', 1)->first();

            $fccServide = new FccService();
            $transaction_id = 922;
            // $this->tseFinishTransaction($transaction_id);
            $this->tseStartTransaction($transaction_id);
            // $response = $fccServide->authenticate($cashRegisterDetail);

            // $cashRegisterDetail->fcc_access_token = $response->json('access_token');
            // $cashRegisterDetail->save();
        }
        return back();
    }

    public function tseStartTransaction($transaction_id)
    {
        $fccServide = new FccService();
        // $eas = CashRegisterDetail::where('id', $transaction->cash_register_detail_id)->first();
        $eas = CashRegisterDetail::where('id', 20)->first();
        $transaction = Transaction::where('id', $transaction_id)->first();
        // $response = $fccServide->authenticate($eas);
        // dd($response->json());
        $business_location = BusinessLocation::where('id', 8)->first();

        // $authResponse = $fccServide->authenticate($eas);
       
        // dd($fccServide->startFccConnector($business_location));
        // dd(base64_decode($business_location->fcc_field1));
            
        // $authResponse = $fccServide->authenticate($eas);
        // if ($authResponse->ok()) {
        //     $eas->fcc_access_token = $authResponse->json('access_token');
        //     $eas->save();

        //     $tseResponseCertifikat = Http::withToken($eas->fcc_access_token)
        //     ->get('http://localhost:' . $business_location->fcc_port . '/export/certificates');
        // $business_location->fcc_field1 = $tseResponseCertifikat;
        // $business_location->save();
        // }
       
        $array_test = "generalRate = ".$transaction->total_before_tax .",
                reducedRate =0 ,
                averageRate10_7 =0,
                averageRate5_5 = 0,
                zeroRate = 0,
                amount= ".$transaction->total_before_tax .",
                paymentType = Bar";
                // dd(base64_encode($array_test));
                // dd($array_test);

                $base_code_test = "Z2VuZXJhbFJhdGUgPSAxMC4wMCwKICAgICAgICAgICAgICAgIHJlZHVjZWRSYXRlID0wICwKICAgICAgICAgICAgICAgIGF2ZXJhZ2VSYXRlMTBfNyA9MCwKICAgICAgICAgICAgICAgIGF2ZXJhZ2VSYXRlNV81ID0gMCwKICAgICAgICAgICAgICAgIHplcm9SYXRlID0gMCwKICAgICAgICAgICAgICAgIGFtb3VudD0gMTAuMDAsCiAgICAgICAgICAgICAgICBwYXltZW50VHlwZSA9IEJhcg==";
                dd(base64_decode($base_code_test));
                /*
            Test Data for processData
        $array_test = "generalRate = 55.0 ,
                reducedRate =0 ,
                averageRate10_7 =0,
                averageRate5_5 = 0,
                zeroRate = 0,
                amount= 55.0,
                paymentType = Bar";

        $base_code ="Z2VuZXJhbFJhdGUgPSA1NS4wICwKICAgICAgICAgICAgICAgIHJlZHVjZWRSYXRlID0wICwKICAgICAgICAgICAgICAgIGF2ZXJhZ2VSYXRlMTBfNyA9MCwKICAgICAgICAgICAgICAgIGF2ZXJhZ2VSYXRlNV81ID0gMCwKICAgICAgICAgICAgICAgIHplcm9SYXRlID0gMCwKICAgICAgICAgICAgICAgIGFtb3VudD0gNTUuMCwKICAgICAgICAgICAgICAgIHBheW1lbnRUeXBlID0gQmFy ";
        dd(base64_decode($base_code));

                dd(base64_encode($array_test));*/
        
        // dd($eas->seriennumber.' '.$transaction->total_before_tax.'-'.$transaction->id.'-'.$eas->fcc_access_token) ;
        // $transaction->save();

        /*if ($eas->fcc_access_token == null) {
            $response = $fccServide->authenticate($eas);
            $response = Http::acceptJson()->withBasicAuth($eas->seriennumber, $business_location->eas_code)
                ->post('http://localhost:' . $business_location->fcc_port . '/oauth/token?grant_type=client_credentials');

            if ($response->ok()) {
                $eas->fcc_access_token = $response->json('access_token');
                $eas->save();
            }
        }*/
        $authResponse = $fccServide->authenticate($eas);
        if ($authResponse->ok()) {
            $eas->fcc_access_token = $authResponse->json('access_token');
            $eas->save();
        // }
        // if ($authResponse->ok()){
            // dd($authResponse->json('access_token').'<br>' . $eas->fcc_access_token);
            $tseResponse = Http::acceptJson()->withToken($eas->fcc_access_token)
                ->post('http://localhost:' . $business_location->fcc_port . '/transaction', [
                    'clientId' => "NEOBKS-0000100-0001-02",
                    'processType' => "Kassenbeleg-V1",
                    "processData"=> "",
                    'externalTransactionId' => $transaction->id,
                    // "grossSales" => array(
                    //     'generalRate' => "55.0",
                    //     'reducedRate' => "0",
                    //     'averageRate10_7' => "0",
                    //     'averageRate5_5' => "0",
                    //     'zeroRate' => "0"
                    // ),
                    // 'payments' => array(                    
                    //     'amount'=> "55.0",
                    //     'paymentType' => "Bar",
                    //     'Currency' => "€"                        
                    // ),                    
                ]);


                dd($tseResponse);
            if ($tseResponse->created()) {
                $transaction->fcc_transaction_response = $tseResponse->json();
                $transaction->save();
            }
        }

    }

    public function tseFinishTransaction($transaction_id)
    {
        $fccServide = new FccService();
        // $eas = CashRegisterDetail::where('id', $transaction->cash_register_detail_id)->first();
        $eas = CashRegisterDetail::where('id', 20)->first();
        $transaction = Transaction::where('id', $transaction_id)->first();
        // $response = $fccServide->authenticate($eas);
        // dd($response->json());
        $business_location = BusinessLocation::where('id', 8)->first();
        $transaction_payments = TransactionPayment::where('transaction_id', $transaction_id)->get();
        $transaction_sell_lines = TransactionSellLine::where('transaction_id', $transaction_id)
                                ->orderBy('tax_id')->get();

        //Get Info from Start_trasanction_response
        // $data_start_transaction = json_decode($transaction->fcc_transaction_response, true);
        $data_start_transaction = json_decode($transaction->fcc_transaction_response,true);
        // dd($data_start_transaction);
        // $str = 'VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==';
        // dd(base64_decode($str));

        /*if ($eas->fcc_access_token == null) {
            $response = $fccServide->authenticate($eas);
            $response = Http::acceptJson()->withBasicAuth($eas->seriennumber, $business_location->eas_code)
                ->post('http://localhost:' . $business_location->fcc_port . '/oauth/token?grant_type=client_credentials');

            if ($response->ok()) {
                $eas->fcc_access_token = $response->json('access_token');
                $eas->save();
            }
        }*/
        $authResponse = $fccServide->authenticate($eas);
        if ($authResponse->ok()) {
            $eas->fcc_access_token = $authResponse->json('access_token');
            $eas->save();
        }
        // if ($authResponse->ok()){
            // dd($eas->fcc_access_token);

        

        $payments = array();
        
        foreach($transaction_payments as $key => $transaction_payment){
            $payment = array();
            if($transaction_payment->method == "cash"){
                $payment_type = "Bar";
            }
            else{
                $payment_type = "Unbar";
            }

            $payment = (object) array(
                "amount" => floatval($transaction_payment->amount),
                "paymentType" => $payment_type,
            );

            $payments[] = $payment;
        }
        
        $summe_tax_19 = 0;
        $summe_tax_7 = 0;
        $summe_tax_10_7 = 0;
        $summe_tax_5_5 = 0;
        $summe_tax_0 = 0;
        $tax_rate = new TaxRate();
        foreach($transaction_sell_lines as $key => $transaction_sell_line){
            if($tax_rate->getTaxRateAmount($transaction_sell_line->tax_id) == 19)
                $summe_tax_19 = $summe_tax_19 + $transaction_sell_line->unit_price_inc_tax* $transaction_sell_line->quantity;
            elseif($tax_rate->getTaxRateAmount($transaction_sell_line->tax_id) == 7)
                $summe_tax_7 = $summe_tax_7 + $transaction_sell_line->unit_price_inc_tax* $transaction_sell_line->quantity;
            elseif($tax_rate->getTaxRateAmount($transaction_sell_line->tax_id) == 10.7)
                $summe_tax_10_7 = $summe_tax_10_7 + $transaction_sell_line->unit_price_inc_tax* $transaction_sell_line->quantity;
            elseif($tax_rate->getTaxRateAmount($transaction_sell_line->tax_id) == 5.5)
                $summe_tax_5_5 = $summe_tax_5_5 + $transaction_sell_line->unit_price_inc_tax* $transaction_sell_line->quantity;
                elseif($tax_rate->getTaxRateAmount($transaction_sell_line->tax_id) == 0)
                $summe_tax_0 = $summe_tax_0 + ($transaction_sell_line->unit_price_inc_tax* $transaction_sell_line->quantity);
        }

        $grossSales = (object) array(
            "generalRate"=> $summe_tax_19, 
            "reducedRate"=> $summe_tax_7, 
            "averageRate10_7"=> $summe_tax_10_7, 
            "averageRate5_5"=> $summe_tax_5_5, 
            "zeroRate"=> $summe_tax_0 
        );

            $tseFinishResponse = Http::acceptJson()->withToken($eas->fcc_access_token)
                ->put('http://localhost:' . $business_location->fcc_port . '/transaction/'.$data_start_transaction['transactionNumber'].'/sales', [
                    "clientId" => "NEOBKS-0000100-0001-02",
                    "processType" => "Kassenbeleg-V1",    
                    "transactionType" => "Beleg",
                    "grossSales" => $grossSales,
                    "payments" => $payments,                
                ]);
                
            /*if (!$tseFinishResponse->created()){
                $authResponse = $fccServide->authenticate($eas);
                if ($authResponse->ok()) {
                    $eas->fcc_access_token = $authResponse->json('access_token');
                    $eas->save();
                }
                
                $tseFinishResponse = Http::acceptJson()->withToken($eas->fcc_access_token)
                ->put('http://localhost:' . $business_location->fcc_port . '/transaction/'.$data_start_transaction['transactionNumber'].'/sales', [
                    'clientId' => "NEOBKS-0000100-0001-02",
                    'transactionType' => "Beleg",
                    'externalTransactionId' => $transaction->id,
                    "grossSales" => json_encode($grossSales),
                    'payments' => json_encode($payments),
                    'processType' => "Kassenbeleg-V1",                    
                ]);
            }*/
            

                // dd($tseFinishResponse);
            if ($tseFinishResponse->created()) {
                $transaction->fcc_finish_transaction_response = $tseFinishResponse->json();
                $transaction->save();
            }
        // }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');

        $business_locations = BusinessLocation::forDropdown($business_id);
        $b_locations = new BusinessLocation();

        $business_locations_query = $b_locations->getAllLocationsByBusinessId($business_id); 

        return view('cash_register_detail.create')
            ->with(compact(
                'business_id',
                'business_locations',
                'business_locations_query')
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
            $business_id = request()->session()->get('user.business_id');
            
            $cash_register_detail = new CashRegisterDetail();
            $cash_register_detail->business_id = $business_id;
            $cash_register_detail->location_id = $request->location_id;
            $cash_register_detail->name = $request->name;
            $cash_register_detail->description = $request->description;
            $cash_register_detail->cash_register_active = true;
            if($request->tss_active)
                $cash_register_detail->tss_active = true;
            else
                $cash_register_detail->tss_active = false;
            $cash_register_detail->save();

            

        return back();
        // dd($request->name.' '.$request->description.' '.$request->tss_active.' '.$request->location_id);
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
        return view('cash_register_detail.edit');
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

     /**
     * Checks if the given cash register details id already exist for the current business, location.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
     public function activateDeactivateLocation($cash_reg_detail_id)     
    {
        return null;
    }

    public function getAllCashRegisters(Request $request){

        $data['cash_registers'] = CashRegisterDetail::where("location_id", $request->location_id)
                                                    ->get(["name", "id"]);

        return response()->json($data);
    }    

    public function activateDeactivateCashRegisterDetail($id)
    {
        try {
            $business_id = request()->session()->get('user.business_id');

            $cash_register_detail = CashRegisterDetail::where('id', $id)
                            ->first();

            $cash_register_detail->cash_register_active = ! $cash_register_detail->cash_register_active;
            if(! $cash_register_detail->cash_register_active){
                $cash_register_detail->tss_active = false;
            }
            $cash_register_detail->save();

            $msg = $cash_register_detail->cash_register_active ? __('cash_register.cash_register_activated_successfully') : __('cash_register.cash_register_deactivated_successfully');

            $output = ['success' => true,
                'msg' => $msg,
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    public function activateDeactivateTSS($id)
    {

        try {
            $business_id = request()->session()->get('user.business_id');

            $cash_register_detail = CashRegisterDetail::where('id', $id)
                            ->first();

            $cash_register_detail->tss_active = ! $cash_register_detail->tss_active;            
            $cash_register_detail->save();

            $msg = $cash_register_detail->tss_active ? __('cash_register.tss_activated_successfully') : __('cash_register.tss_deactivated_successfully');

            $output = ['success' => true,
                'msg' => $msg,
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    public function checkCashRegisterDetailId(Request $request)
    {
        $cash_register_detail_id = $request->input('cash_register_detail_id');

        $valid = 'true';
        if (! empty($cash_register_detail_id)) {
            $business_id = $request->session()->get('user.business_id');
            $hidden_id = $request->input('hidden_id');

            $query = CashRegisterDetail::where('business_id', $business_id)
                            ->where('cash_register_detail_id', $cash_register_detail_id);
            if (! empty($hidden_id)) {
                $query->where('id', '!=', $hidden_id);
            }
            $count = $query->count();
            if ($count > 0) {
                $valid = 'false';
            }
        }
        echo $valid;
        exit;
    }
}
