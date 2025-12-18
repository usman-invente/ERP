<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountTransaction;
use App\BusinessLocation;
use App\CashBook;
use App\CashRegisterTransaction;
use App\CashRegister;
use App\Contact;
use App\ExpenseCategory;
use App\Income;
use App\IncomeCategorie;
use App\IncomeTaxDetail;
use App\TaxRate;
use App\Transaction;
use App\User;
use App\Utils\CashRegisterUtil;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class IncomeController extends Controller
{
    /**
     * Constructor
     *
     * @param  TransactionUtil  $transactionUtil
     * @return void
     */
    public function __construct(
                TransactionUtil $transactionUtil, 
                Util $util,
                ModuleUtil $moduleUtil, 
                CashRegisterUtil $cashRegisterUtil,
                CashBook $cashBook
            )
    {
        $this->transactionUtil = $transactionUtil;
        $this->util = $util;
        $this->moduleUtil = $moduleUtil;
        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '',
            'is_return' => 0, 'transaction_no' => '', ];
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->cashBook = $cashBook;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $incomes = Income::leftJoin('income_categories AS ic', 'incomes.categorie', '=', 'ic.id')
                        ->leftJoin('income_categories AS isc', 'incomes.sub_categorie', '=', 'isc.id')
                        ->join(
                            'business_locations AS bl',
                            'incomes.location_id',
                            '=',
                            'bl.id'
                        )
                        // ->leftJoin('tax_rates as tr', 'incomes.tax_id', '=', 'tr.id')
                        ->leftJoin('income_tax_details as itd', 'incomes.id', '=', 'itd.income_id')
                        ->leftJoin('users AS usr', 'incomes.created_by', '=', 'usr.id')                        
                        ->where('incomes.business_id', $business_id)
                        ->select(
                            'incomes.id',
                            'incomes.document',
                            'incomes.transaction_date',
                            'incomes.ref_no',
                            'ic.name as categorie',
                            'isc.name as sub_categorie',
                            'incomes.additional_notes',
                            'incomes.final_total',
                            'incomes.tax_total',
                            'bl.name as location_name',                           
                            DB::raw("CONCAT(COALESCE(usr.surname, ''),' ',COALESCE(usr.first_name, ''),' ',COALESCE(usr.last_name,'')) as added_by")                         
                        )                        
                        ->groupBy('incomes.id');                      

            //Add condition for location,used in sales representative expense report & list of expense
            if (request()->has('location_id')) {
                $location_id = request()->get('location_id');
                if (! empty($location_id)) {
                    $incomes->where('incomes.location_id', $location_id);
                }
            }

            //Add condition for expense category, used in list of expense,
            if (request()->has('income_category_id')) {
                $income_category_id = request()->get('income_category_id');
                if (! empty($income_category_id)) {
                    $incomes->where('incomes.categorie', $income_category_id);
                }
            }

            //Add condition for expense sub category, used in list of expense,
            if (request()->has('income_sub_category_id')) {
                $income_sub_category_id = request()->get('income_sub_category_id');
                if (! empty($income_sub_category_id)) {
                    $incomes->where('incomes.sub_categorie', $income_sub_category_id);
                }
            }
            //Add condition for start and end date filter, uses in sales representative expense report & list of expense
            if (! empty(request()->start_date) && ! empty(request()->end_date)) {
                $start = request()->start_date;
                $end = request()->end_date;
                $incomes->whereDate('incomes.transaction_date', '>=', $start)
                        ->whereDate('incomes.transaction_date', '<=', $end);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $incomes->whereIn('incomes.location_id', $permitted_locations);
            }

            $is_admin = $this->util->is_admin(auth()->user(), $business_id);
            if (! $is_admin /*&& ! auth()->user()->can('all_income.access')*/) {
                $user_id = auth()->user()->id;
                $incomes->where('incomes.created_by', $user_id);
            }
            
            return Datatables::of($incomes)
                ->addColumn(
                    'action',
                    '<div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                            data-toggle="dropdown" aria-expanded="false"> @lang("messages.actions")<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                </span>
                        </button>
                    <ul class="dropdown-menu dropdown-menu-left" role="menu">
                    
                    @if($document)
                        <li><a href="{{ url(\'uploads/documents/\' . $document)}}" 
                        download=""><i class="fa fa-download" aria-hidden="true"></i> @lang("purchase.download_document")</a></li>
                        @if(isFileImage($document))
                            <li><a href="#" data-href="{{ url(\'uploads/documents/\' . $document)}}" class="view_uploaded_document"><i class="fas fa-file-image" aria-hidden="true"></i>@lang("lang_v1.view_document")</a></li>
                        @endif
                    @endif
                    @if(auth()->user()->can("expense.delete"))
                        <li>
                        <a href="#" data-href="{{action(\'App\Http\Controllers\IncomeController@destroy\', [$id])}}" class="delete_income"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</a></li>
                    @endif 
                    </ul></div>'
                )

                ->removeColumn('id')
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final-total" data-currency_symbol="true" data-orig-value="{{$final_total}}">
                        <div class="span_right">@format_currency($final_total)</div>
                    </span>'
                )
                ->editColumn(
                    'tax_total',
                    '<span class="display_currency tax-total" data-currency_symbol="true" data-orig-value="{{$tax_total}}">
                        <div class="span_right">@format_currency($tax_total)</div>
                    </span>'
                )

                ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')              
                
                ->rawColumns(['final_total', 'action', 'tax_total', 'transaction_date', 'ref_no'])
                ->make(true);
        }
        $business_id = request()->session()->get('user.business_id');

        $categories = IncomeCategorie::where('business_id', $business_id)
                            ->whereNull('parent_id')
                            ->pluck('name', 'id');

        $users = User::forDropdown($business_id, false, true, true);

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        $contacts = Contact::contactDropdown($business_id, false, false);

        $sub_categories = IncomeCategorie::where('business_id', $business_id)
                        ->whereNotNull('parent_id')
                        ->pluck('name', 'id')
                        ->toArray();

        return view('income.index')
            ->with(compact('categories', 'users', 'business_locations', 'contacts', 'sub_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('expense.add')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        //Check if subscribed or not
        // if (! $this->moduleUtil->isSubscribed($business_id)) {
        //     return $this->moduleUtil->expiredResponse(action([\App\Http\Controllers\IncomeController::class, 'index']));
        // }

        $business_locations = BusinessLocation::forDropdown($business_id, false, true);

        $bl_attributes = $business_locations['attributes'];
        $business_locations = $business_locations['locations'];

        $income_categories = IncomeCategorie::where('business_id', $business_id)
                                ->whereNull('parent_id')
                                ->pluck('name', 'id');
        $users = User::forDropdown($business_id, true, true);

        $taxes = TaxRate::forBusinessDropdown($business_id, true, true);

        $tax_rates = TaxRate::where('business_id', $business_id)
                                ->orderBy('amount', 'desc')
                                ->get();

        $payment_line = $this->dummyPaymentLine;

        $payment_types = $this->transactionUtil->payment_types(null, false, $business_id);

        $contacts = Contact::contactDropdown($business_id, false, false);

        //Accounts
        $accounts = [];
        if ($this->moduleUtil->isModuleEnabled('account')) {
            $accounts = Account::forDropdown($business_id, true, false, true);
        }

        if (request()->ajax()) {
            return view('income.add_income_modal')
                ->with(compact('income_categories', 'business_locations', 'users', 
                            'taxes', 'payment_line', 'payment_types', 'accounts', 
                            'bl_attributes', 'contacts','tax_rates'));
        }

        // return view('income.create')
        //     ->with(compact('income_categories', 'business_locations', 'users', 'taxes', 'payment_line', 'payment_types', 'accounts', 'bl_attributes', 'contacts','tax_rates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request)
    {
     
        $business_id = $request->session()->get('user.business_id');
        $user_id = $request->session()->get('user.id');

        $cash_register = CashRegister::where('business_id',$business_id)
                                            ->where('location_id', $request->input('location_id'))
                                            ->where('cash_register_detail_id', $request->input('cash_register_detail_id'))
                                            ->where('status', 'open')
                                            ->latest()->first();    
        if (empty($cash_register)) {
            return redirect()->back()->with('error', __('Cash register is not open'));
        }
        
        $request->validate([
                'document' => 'file|max:'.(config('constants.document_size_limit') / 1000),
            ]);

        if($request->input('final_total') == 0){
            return response()->json([
                'success' => false,
                'msg' => __('income.income_not_add'),
            ]);            
        }
        $income = new Income();
        $income->business_id = $business_id;
        $income->transaction_date = $this->util->uf_date($request->input('transaction_date'), true);
        $income->location_id = $request->input('location_id');
        $income->final_total = $this->transactionUtil->num_uf($request->input('final_total'));
        $income->tax_total = $this->transactionUtil->num_uf($request->input('tax_total'));
        $income->additional_notes = $request->input('additional_notes');
        $income->categorie = $request->input('income_category_id');
        $income->sub_categorie = $request->input('income_sub_category_id');
        $income->cash_register_detail_id = $request->input('cash_register_detail_id');
        $income->cash_register_id = $cash_register->id;
        $income->created_by = $user_id;
        $income->document = $request->file('document') ? $this->transactionUtil->uploadFile($request->file('document'), 'documents', 'income') : null;
        $income->type = 'cash';
        // $income->ref_no = $request->input('ref_no');

        $ref_count = $this->util->setAndGetReferenceCount('income', $business_id);
        // //Generate reference number
        $income->ref_no = $request->input('ref_no') ?: $this->util->generateReferenceNumber('income', $ref_count, $business_id);

        $income->save();
        
        
        $tax_rates = TaxRate::where('business_id', $business_id)
                                ->orderBy('amount', 'desc')
                                ->get();

        foreach ($tax_rates as $key => $tax_rate) {
            $net_amount = $this->transactionUtil->num_uf($request->input('total_'.$key));
            $tax_amount = $this->transactionUtil->num_uf($request->input('tax_'.$key));
            $tax_rate_amount = $this->transactionUtil->num_uf($request->input('tax_rate_amount_'.$key));
            if ($net_amount > 0) {
                $incomeTaxDetail = new IncomeTaxDetail();
                $incomeTaxDetail->income_id = $income->id;
                $incomeTaxDetail->tax_rate_id = $tax_rate->id;
                $incomeTaxDetail->tax_rate = $tax_rate_amount;
                $incomeTaxDetail->net_amount = $net_amount;
                $incomeTaxDetail->tax_amount = $tax_amount;
                $incomeTaxDetail->save();
            }
        }

        $user = new User();
        
        $cash_book = new CashBook();
        $cash_book->business_id = $income->business_id;
        $cash_book->location_id = $income->location_id;
        $cash_book->income_id = $income->id;
        $cash_book->created_by = $income->created_by;
        $cash_book->created_by_name = $user->getUserNameById($income->created_by);
        $cash_book->cash_register_detail_id = $income->cash_register_detail_id;
        $cash_book->cash_register_id = $income->cash_register_id;
        $cash_book->transaction_date = $income->transaction_date;
        $cash_book->transaction_year = $income->created_at->format('Y');
        $cash_book->transaction_month = $income->created_at->format('m');
        $cash_book->transaction_day = $income->created_at->format('d');
        $cash_book->transaction_time = $income->created_at->format('H:i:s');
        $cash_book->amount = $income->final_total;
        $cash_book->sum_amount = $cash_book->getLastSumAmountCashBook($income->business_id, $income->location_id, $income->cash_register_detail_id) + $income->final_total;
        $cash_book->status_cash_register = 'deposit';
        $cash_book->pay_method_de = "Einnahme";
        $cash_book->process = "Einnahme";
        $cash_book->description = "<b>Einnahmen</b> <br>";
        foreach ($tax_rates as $id => $tax_rate) {
                if($id == 0){
                    $tax =  $this->transactionUtil->num_uf($request->input('tax_'.$id));
                    $total =  $this->transactionUtil->num_uf($request->input('total_'.$id));
                    $cash_book->sum_tax_rate = $tax;
                    $cash_book->netto_tax_rate = $total - $tax;
                    $cash_book->brutto_tax_rate = $total;
                }
                if($id == 1){
                    $tax =  $this->transactionUtil->num_uf($request->input('tax_'.$id));
                    $total =  $this->transactionUtil->num_uf($request->input('total_'.$id));
                    $cash_book->sum_tax_rate_1 = $tax;
                    $cash_book->netto_tax_rate_1 = $total - $tax;
                    $cash_book->brutto_tax_rate_1 = $total;
                }
                if($id == 2){
                    $tax =  $this->transactionUtil->num_uf($request->input('tax_'.$id));
                    $total =  $this->transactionUtil->num_uf($request->input('total_'.$id));
                    $cash_book->sum_tax_rate_2 = $tax;
                    $cash_book->netto_tax_rate_2 = $total - $tax;
                    $cash_book->brutto_tax_rate_2 = $total;
                }
            }
        
        $cash_book->sum_all_tax_rate = $income->tax_total;
        $cash_book->numbering_year = \Carbon::now()->format('Y-m-d').'-'.$cash_book->getLastNoForNumberingYear($income->business_id, $income->location_id, $income->cash_register_detail_id, \Carbon::now()->format('Y'));
        $cash_book->last_no_for_numbering_year = $cash_book->getLastNoForNumberingYear($income->business_id, $income->location_id, $income->cash_register_detail_id, \Carbon::now()->format('Y'));
        $cash_book->numbering_cash = $cash_book->getLastNumberingCash($income->business_id, $income->location_id, $income->cash_register_detail_id, \Carbon::now()->format('Y'));
        $cash_book->save();

        return response()->json([
            'success' => true,
            'msg' => __('income.income_add_success'),
        ]);
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
        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $income = Income::where('id', $id)
                                        ->first();

                //Delete Cash register transactions
                $income->income_tac_details()->delete();
                $income->income_cash_book()->delete();

                $income->delete();

                //Delete cash book


                $output = ['success' => true,
                    'msg' => __('expense.expense_delete_success'),
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
    
    public function getSubcategories($parent_id)
    {
        $subcategories = IncomeCategorie::where('parent_id', $parent_id)
            ->pluck('name', 'id');

        return response()->json($subcategories);
    }    
    

}
