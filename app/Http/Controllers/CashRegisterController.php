<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\CashBook;
use App\CashRegister;
use App\CashRegisterDetail;
use App\Utils\CashRegisterUtil;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $cashRegisterUtil;

    protected $moduleUtil;
    
    protected $cashBook;

    /**
     * Constructor
     *
     * @param  CashRegisterUtil  $cashRegisterUtil
     * @return void
     */
    public function __construct(
        CashRegisterUtil $cashRegisterUtil, 
        ModuleUtil $moduleUtil,
        CashBook $cashBook
        )
    {
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->moduleUtil = $moduleUtil;
        $this->cashBook = $cashBook;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cash_register.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //like:repair
        $business_id = request()->session()->get('user.business_id');
        $sub_type = request()->get('sub_type');

        //Check if there is a open register, if yes then redirect to POS screen.
        if ($this->cashRegisterUtil->countOpenedCashRegister($business_id, $request->input('location_id'), $request->input('cash_register_id')) != 0) {
            return redirect()->action([\App\Http\Controllers\SellPosController::class, 'create'], ['sub_type' => $sub_type,'cash_register_detail_id' => $request->input('cash_register_id'),
                                                                                                    'location_id' => $request->input('location_id')
                                                                                                    ]);
        }
        
        $business_locations = BusinessLocation::forDropdown($business_id);

        $b_locations = new BusinessLocation();

        $business_locations_query = $b_locations->getAllLocationsByBusinessId($business_id);

        $last_close_amount = number_format($this->getCloseAmountLatest($request), 0, '.', ' ');

        $cash_register_d = new CashRegisterDetail();
        $cash_register_details = $cash_register_d->getCashRegisterDetailsByBusinessId($business_id);
        $first_cash_register_detail = $cash_register_d->getFirstCashRegDetByBusinessId($business_id);

        // dd($first_cash_register_detail->id);

        $close_amount_first_cach_register = number_format($this->getCloseAmountByCashRegisterDetailId($first_cash_register_detail->id), 2, ',', '.');

        $cashRegisterUtil = $this->cashRegisterUtil;
        
        return view('cash_register.create')->with(compact('business_id', 'business_locations','business_locations_query', 
                                                            'cash_register_details', 'first_cash_register_detail',
                                                            'sub_type','last_close_amount','close_amount_first_cach_register',
                                                            'cashRegisterUtil'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($request->correction_last_cash_amount)){
            $request->validate([
                'correction_description' => 'required',
            ]);            
        }
        
        //like:repair
        // dd($this->cashRegisterUtil->num_uf($request->input('amount')).' '. $this->cashRegisterUtil->num_uf($request->input('correction_last_cash_amount')));
        $sub_type = request()->get('sub_type');

        try {
            $initial_amount = 0;
            if (! empty($request->input('amount'))) {
                $initial_amount = $this->cashRegisterUtil->num_uf($request->input('amount'));
            }
            $user_id = $request->session()->get('user.id');
            $business_id = $request->session()->get('user.business_id');

            $cash_register = CashRegister::where('business_id',$business_id)
                                            ->where('location_id', $request->input('location_id'))
                                            ->where('cash_register_detail_id', $request->input('cash_register_id'))
                                            ->latest()->first();                        
                                            
            if(!is_object($cash_register) || $cash_register->status == 'close'){
                $register = CashRegister::create([
                    'business_id' => $business_id,
                    'user_id' => $user_id,
                    'status' => 'open',
                    'location_id' => $request->input('location_id'),
                    'cash_register_detail_id' => $request->input('cash_register_id'),
                    'correction_last_cash_amount' => $this->cashRegisterUtil->num_uf($request->input('correction_last_cash_amount')),
                    'correction_description' => $request->input('correction_description'),
                    'created_at' => \Carbon::now()->format('Y-m-d H:i:00'),
                ]);
                
              
                /**
                 * Save Open Cash Register in CashBook
                 */
                if(!is_object($cash_register)){
                    $last_close_amount = $this->cashRegisterUtil->num_uf($request->input('correction_last_cash_amount'));
                    // else
                    //     $last_close_amount = $cash_register->closing_amount;                    
                    $this->cashBook->saveOpenCashRegisterInCashBook($register, $last_close_amount);
                }else{
                        if( $request->input('correction_last_cash_amount') != null){
                            $last_close_amount = $this->cashRegisterUtil->num_uf($request->input('correction_last_cash_amount'));
                            $last_closing_amount_cash_reg = $cash_register->closing_amount;
                            if( $last_close_amount != $last_closing_amount_cash_reg)
                                $this->cashBook->saveOpenCashRegisterInCashBook($register, $last_close_amount);
                        }
                }

                if (! empty($initial_amount)) {
                    $register->cash_register_transactions()->create([
                        'amount' => $initial_amount,
                        'pay_method' => 'cash',
                        'type' => 'credit',
                        'transaction_type' => 'initial',
                    ]);
                }
            }else{
                return redirect()->action([\App\Http\Controllers\SellPosController::class, 'create'], ['sub_type' => $sub_type,
                                                                                                        'cash_register_detail_id' => $request->input('cash_register_id'),
                                                                                                        'location_id' => $request->input('location_id')
                                                                                                        ]);
            }

            
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
        }

        return redirect()->action([\App\Http\Controllers\SellPosController::class, 'create'], ['sub_type' => $sub_type,
                                                                                                'cash_register_detail_id' => $request->input('cash_register_id'),
                                                                                                'location_id' => $request->input('location_id')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! auth()->user()->can('view_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $register_details = $this->cashRegisterUtil->getRegisterDetails($id);

        $cash_register_detail_id = $register_details->cash_register_detail_id;
        $location_id = $register_details->location_id;
        $user_id = $register_details->user_id;
        $open_time = $register_details['open_time'];
        $close_time = ! empty($register_details['closed_at']) ? $register_details['closed_at'] : \Carbon::now()->toDateTimeString();
        // $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time);
        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled, $location_id, $cash_register_detail_id);

        $payment_types = $this->cashRegisterUtil->payment_types(null, false, $business_id);
        $sum_sells_return = $this->cashRegisterUtil->getTransactionSellReturnSums($business_id, $location_id, $cash_register_detail_id, $open_time, $close_time);
        $test = "test text";

        return view('cash_register.register_details')
                    ->with(compact('register_details', 'details', 'payment_types', 'close_time', 'sum_sells_return', 'test'));
    }

    /**
     * Shows register details modal.
     *
     * @param  void
     * @return \Illuminate\Http\Response
     */
    public function getRegisterDetails($id = null)
    {
        if (! auth()->user()->can('view_cash_register')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');

        $register_details = $this->cashRegisterUtil->getRegisterDetails($id);

        $cash_register_detail_id = $register_details->cash_register_detail_id;
        $location_id = $register_details->location_id;
        $user_id = auth()->user()->id;
        $open_time = $register_details['open_time'];
        $close_time = \Carbon::now()->toDateTimeString();

        $is_types_of_service_enabled = $this->moduleUtil->isModuleEnabled('types_of_service');

        // $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled);
        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled, $location_id, $cash_register_detail_id);

        $sum_sells_return = $this->cashRegisterUtil->getTransactionSellReturnSums($business_id, $location_id, $cash_register_detail_id, $open_time, $close_time);

        $payment_types = $this->cashRegisterUtil->payment_types($register_details->location_id, true, $business_id);

        $cashRegisterUtil = $this->cashRegisterUtil;

        return view('cash_register.register_details')
                ->with(compact('register_details', 'details', 'payment_types', 'close_time', 'sum_sells_return', 'cashRegisterUtil'));
    }

    /**
     * Shows close register form.
     *
     * @param  void
     * @return \Illuminate\Http\Response
     */
    public function getCloseRegister($id = null)
    {
        if (! auth()->user()->can('close_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $register_details = $this->cashRegisterUtil->getRegisterDetails($id);

        $cash_register_detail_id = $register_details->cash_register_detail_id;
        $location_id = $register_details->location_id;

        $user_id = $register_details->user_id;
        $open_time = $register_details['open_time'];
        $close_time = \Carbon::now()->toDateTimeString();

        $is_types_of_service_enabled = $this->moduleUtil->isModuleEnabled('types_of_service');

        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled, $location_id, $cash_register_detail_id);

        $payment_types = $this->cashRegisterUtil->payment_types($register_details->location_id, true, $business_id);

        $pos_settings = ! empty(request()->session()->get('business.pos_settings')) ? json_decode(request()->session()->get('business.pos_settings'), true) : [];

        $sum_sells_return = $this->cashRegisterUtil->getTransactionSellReturnSums($business_id, $location_id, $cash_register_detail_id, $open_time, $close_time);
        
        $cashRegisterUtil = $this->cashRegisterUtil;

        return view('cash_register.close_register_modal')
                    ->with(compact('register_details', 'details', 'payment_types', 'pos_settings', 'sum_sells_return', 'cashRegisterUtil'));
    }

    /**
     * Closes currently opened register.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCloseRegister(Request $request)
    {
        if (! auth()->user()->can('close_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        if( $this->cashRegisterUtil->num_uf($request->cash_payout) > $this->cashRegisterUtil->num_uf($request->total_cash_money) )
            return redirect()->back() ->with('msg-alert', 'Abschöpfung darf nicht größer als Gesamt sein!');
        // dd('ketu');

        try {
            //Disable in demo
            // if (config('app.env') == 'demo') {
            //     $output = ['success' => 0,
            //         'msg' => 'Feature disabled in demo!!',
            //     ];

            //     return redirect()->action([\App\Http\Controllers\HomeController::class, 'index'])->with('status', $output);
            // }

            $input = $request->only(['closing_amount', 'total_card_slips', 'total_cheques', 'closing_note', 'cash_payout']);
            $input['closing_amount'] = $this->cashRegisterUtil->num_uf($request->total_cash_money) - $this->cashRegisterUtil->num_uf($input['cash_payout']);
            $input['cash_payout'] = $this->cashRegisterUtil->num_uf($input['cash_payout']);
            $user_id = $request->input('user_id');
            $location_id = $request->input('location_id');
            $cash_register_detail_id = $request->input('cash_register_detail_id');
            $input['closed_at'] = \Carbon::now()->format('Y-m-d H:i:s');
            $input['status'] = 'close';
            $input['denominations'] = ! empty(request()->input('denominations')) ? json_encode(request()->input('denominations')) : null;
            $input['last_cash_amount'] = $this->cashRegisterUtil->num_uf($request['closing_amount']) - $this->cashRegisterUtil->num_uf($request['cash_payout']);

            CashRegister::where('location_id', $location_id)
                                ->where('cash_register_detail_id', $cash_register_detail_id)
                                // ->where('user_id', $user_id)
                                ->where('status', 'open')
                                ->update($input);

            /**
             * Save Open Cash Register in CashBook
             */
            
            $business_id = $request->session()->get('user.business_id');
            $cash_register = CashRegister::where('business_id',$business_id)
                                        ->where('location_id', $request->input('location_id'))
                                        ->where('cash_register_detail_id', $request->input('cash_register_detail_id'))
                                        ->latest('id')->first(); 

            if($request->input('cash_payout') != null && $request->input('cash_payout')){
                $cash_payout_amount = $cash_register->cash_payout;
                if($cash_payout_amount > 0)
                    $this->cashBook->saveCloseCashRegisterInCashBook($cash_register, $cash_payout_amount, "cash_payout");
            }            
            /*$last_close_amount = $cash_register->closing_amount;
                $this->cashBook->saveCloseCashRegisterInCashBook($cash_register, $last_close_amount, "close");*/

            $output = ['success' => 1,
                'msg' => __('cash_register.close_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    public function getCloseAmountLatest(Request $request){

        $data = CashRegister::where("cash_register_detail_id", $request->cash_register_detail_id)
                                ->where('status', 'close')
                                ->latest()->first();

        if(is_object($data))
            return $data->closing_amount;
        else 
            {
                $data = CashRegister::where("cash_register_detail_id", $request->cash_register_detail_id)
                                ->where('status', 'open')
                                ->latest()->first(); 

                if(is_object($data))
                    return $data->correction_last_cash_amount;
                else
                    return ' ';
            }
        return ' '; 
    }

    public function getCloseAmountByCashRegisterDetailId($cash_register_detail_id){

        $cash_register_data = CashRegister::where("cash_register_detail_id", $cash_register_detail_id)
                                ->where('status', 'close')
                                ->latest()->first();

        $open_cash_register_data = CashRegister::where("cash_register_detail_id", $cash_register_detail_id)
                                ->where('status', 'open')
                                ->latest()->first();

        if(is_object($cash_register_data) || is_object($open_cash_register_data)){
            if(is_object($open_cash_register_data) && $open_cash_register_data->correction_last_cash_amount != null)
                return $open_cash_register_data->correction_last_cash_amount;
            elseif(is_object($cash_register_data))
                return $cash_register_data->closing_amount;
            else 
                return null;
        }            
        else 
            return null;
    }
}
