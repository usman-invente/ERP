<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\CashBook;
use App\CashRegister;
use App\CashRegisterDetail;
use App\Contract;
use App\Contact;
use App\User;
use App\Utils\ContactUtil;
use App\Utils\ModuleUtil;
use App\Utils\NotificationUtil;
use App\Utils\TransactionUtil;
use App\Utils\BusinessUtil;
use App\TaxRate;
use App\Transaction;
use App\Utils\Util;
use DB;
use Excel;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;
use Modules\Crm\Utils\CrmUtil;
use Mpdf\Mpdf;

class CashBookController extends Controller
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
    public function index(Request $reques, CashBook $cashBook)
    {
        $business_id = request()->session()->get('user.business_id');
        // if (!(auth()->user()->can('admin') )) {
        //     abort(403, 'Unauthorized action.');
        // }

        $cash_registers_details = CashRegisterDetail::where('business_id', $business_id)
                                    ->where('cash_register_active', true)
                                    ->get();

        // $business_locations = BusinessLocation::forDropdown($business_id, true);
        $business_locations = BusinessLocation::where('business_id', $business_id)
                                                ->where('is_active', true)
                                                ->get();
        $b_locations = new BusinessLocation();

        $business_locations_query = $b_locations->getAllLocationsByBusinessId($business_id);        
        
        $view_values = [
            'test_data' => "aaaa",
            'business_id' => $business_id,
            'cash_registers_details' => $cash_registers_details,
            'business_locations' => $business_locations,
            'business_locations_query' => $business_locations_query,
            // 'cashRegisterDetail' => $cashRegisterDetail,
        ];                                   
        
        return view('cash_book.index')->with(($view_values));
    }

    public function getListCashBookHistorie(Request $request, CashBook $cashBook)
    {
        $business_id = request()->session()->get('user.business_id');

        $cash_books = CashBook::where('business_id', $business_id)
                                    ->select('*');
        
        if (! empty(request()->start_date) && ! empty(request()->end_date)) {
            $start = request()->start_date;
            $end = request()->end_date;
            $cash_books->whereDate('transaction_date', '>=', $start)
                            ->whereDate('transaction_date', '<=', $end);
        }

        $locationId = $request->input('locationId');

        if(! empty($request->locationId) && (!str_contains($request->locationId, 'cash_register_'))){
            $cash_books->where('location_id', $locationId);
        }
        if(! empty($request->cashRegisterId) && (!str_contains($request->cashRegisterId, 'location_'))){
            $cash_books->where('cash_register_detail_id', $request->cashRegisterId);
        }
        // if(! empty($request->cashRegisterId) && is_int($request->cashRegisterId)){
        //     $cash_books->where('cash_register_detail_id', $request->cashRegisterId);
        // }

        return Datatables::of($cash_books)
        ->addColumn('action', 
        '
        <style>
            .span_right {
                text-align: right;
            }
        </style>
        <div class="btn-group">
            <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                '.__('messages.action').'
                <span class="caret"></span>
                <span class="sr-only">'
                   .__('messages.action').'
                </span>
            </button>
            {{-- <ul class="dropdown-menu dropdown-menu-left" role="menu">
                <li>
                    <a href="">
                        <i class="fa fa-eye"></i>
                        '.__('messages.view').'
                    </a>
                </li>
            </ul>--}}
        </div>
        '
         )
        // ->addColumn('location_name', function ($row){
        //     return $row->business_location->getLocationNameById();
        // })
        // ->addColumn('active_tss', function ($row){
        //     if($row->tss_active)
        //         return 'Ja';
        //     else
        //         return 'Nein';
        // })
        ->editColumn(
            'amount',
            '<span class="span_right" data-orig-value="{{$amount}}">
                <div class="span_right">@format_currency($amount)</div>
            </span>'
        )
        ->editColumn(
            'brutto_amount',
            '<span class="brutto-amount" data-orig-value="{{$brutto_amount}}">
                <div class="span_right">@format_currency($brutto_amount)</div>
            </span>'
        )
        ->editColumn(
            'netto_amount',
            '<span class="netto-amount" data-orig-value="{{$netto_amount}}">
                <div class="span_right">@format_currency($netto_amount)</div>
            </span>'
        )
        ->editColumn(
            'sum_tax_rate',
            '<span class="sum_tax_rate" data-orig-value="{{$sum_tax_rate}}">
                <div class="span_right">@format_currency($sum_tax_rate)</div>
            </span>'
        )
        ->editColumn(
            'netto_tax_rate',
            '
            <span  data-orig-value="{{$netto_tax_rate}}">
                <div class="span_right">@format_currency($netto_tax_rate)</div>
            </span>'
        )
        ->editColumn(
            'brutto_tax_rate',
            '<span class="span_right" data-orig-value="{{$brutto_tax_rate}}">
                <div class="span_right">@format_currency($brutto_tax_rate)</div>
            </span>'
        )
        ->editColumn(
            'sum_tax_rate_1',
            '<span class="sum_tax_rate_1" data-orig-value="{{$sum_tax_rate_1}}">
            <div class="span_right">@format_currency($sum_tax_rate_1)</div>
            </span>'
        )
        ->editColumn(
            'netto_tax_rate_1',
            '<span class="netto_tax_rate_1" data-orig-value="{{$netto_tax_rate_1}}">
                <div class="span_right">@format_currency($netto_tax_rate_1)</div>
            </span>'
        )
        ->editColumn(
            'brutto_tax_rate_1',
            '<span class="brutto_tax_rate_1" data-orig-value="{{$brutto_tax_rate_1}}">
                <div class="span_right">@format_currency($brutto_tax_rate_1)</div>
            </span>'
        )
        ->editColumn(
            'netto_tax_rate_2',
            '<span class="netto_tax_rate_2" data-orig-value="{{$netto_tax_rate_2}}">
                <div class="span_right">@format_currency($netto_tax_rate_2)</div>
            </span>'
        )
        ->editColumn(
            'sum_all_tax_rate',
            '<span class="sum_all_tax_rate" data-orig-value="{{$sum_all_tax_rate}}">
                <div class="span_right">@format_currency($sum_all_tax_rate)</div>
            </span>'
        )
        ->editColumn('transaction_date', function ($row) {
            if($row->transaction_date)
                return $this->commonUtil->format_date($row->transaction_date);
            else
                return null;
        })
        ->rawColumns(['action', 'person_id','description','amount','brutto_amount','netto_amount',
                    'sum_tax_rate','netto_tax_rate','brutto_tax_rate','sum_all_tax_rate',
                    'sum_tax_rate_1','netto_tax_rate_1','brutto_tax_rate_1','netto_tax_rate_2'])
        ->make(true);
        
        $view_values = [
            'test_data' => "eeee",
            'cash_registers_details' => $cash_registers_details,
        ];
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

    public function getCashBookOrderPdf(Request $request,)
    {        
        
        // Create an instance of the mPDF class
       /* $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'autoScriptToLang' => true,
        'autoLangToFont' => true,
        'autoVietnamese' => true,
        'autoArabic' => true,
        'margin_top' => 8,
        'margin_bottom' => 8,
        'format' => 'A4',
    ]);

        // Your PDF content goes here
        $html = '<h1>Hello, this is a sample PDF</h1>';

        // Add content to the PDF
        $mpdf->useSubstitutions = true;
        $mpdf->SetWatermarkText("Neoburg", 0.1);
        $mpdf->showWatermarkText = true;
        $mpdf->SetTitle(__('cash_book.cash_book').'.pdf');
        $mpdf->WriteHTML($html);
    
        // Output the PDF
        $mpdf->Output();*/
        $business_id = request()->session()->get('user.business_id');
        $transaction_id = 422;
        $purchase = Transaction::where('business_id', 1)
                    // ->where('id', $transaction_id)
                    ->with(
                        'contact',
                        'purchase_lines',
                        'purchase_lines.product',
                        'purchase_lines.product.brand',
                        'purchase_lines.product.category',
                        'purchase_lines.variations',
                        'purchase_lines.variations.product_variation',
                        'location',
                        'payment_lines'
                    )
                    ->first();
        

        $cash_books = CashBook::where('business_id', $business_id)
                            // ->where('transaction_id', $transaction_id)
                            // ->where('cash_register_detail_id', $cash_register_detail_id)
                            ->get();

        if (! empty(request()->start_date) && ! empty(request()->end_date)) {
            $start = request()->start_date;
            $end = request()->end_date;
            $cash_books = CashBook::where('business_id', $business_id)
                            // ->where('transaction_id', $transaction_id)
                            // ->where('cash_register_detail_id', $cash_register_detail_id)
                            ->whereDate('transaction_date', '>=', $start)
                            ->whereDate('transaction_date', '<=', $end)
                            ->get();
        }
        $business = Business::find($business_id);
        $location_details = BusinessLocation::find($purchase->location_id);
        $businessUtil = new BusinessUtil();
        $invoice_layout = $businessUtil->invoiceLayout(1, $location_details->invoice_layout_id);
        $custom_labels = json_decode(session('business.custom_labels'), true);
        
        //Logo
        $logo = !empty($business->logo) && file_exists(public_path('uploads/business_logos/'.$business->logo)) ? asset('uploads/business_logos/'.$business->logo) : false;
        
        // $word_format = $invoice_layout->common_settings['num_to_word_format'] ? $invoice_layout->common_settings['num_to_word_format'] : 'international';
        // $total_in_words = $this->numToWord($purchase->final_total, null, $word_format);
            
        //Generate pdf
        $body = view('cash_book.receipts.download_pdf')
                    ->with(compact('cash_books', 'purchase', 'logo', 
                    'custom_labels', 'location_details','business'))
                    ->render();
                    
        // $body ="
        // <table class='tpdf'>
		// <tr>
		// 	<td class='width-50'>
		// 		<strong>@lang('cash_book.cash_book'):</strong> #{{ $purchase->ref_no }} <br>
		// 		<strong>@lang('lang_v1.order_date'):</strong> #{{ ($purchase->transaction_date) }}
		// 	</td>
		// 	</tr>
		// 	</table>
        // ";
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoVietnamese' => true,
            'autoArabic' => true,
            'margin_top' => 8,
            'margin_bottom' => 8,
            'format' => 'A4',
        ]);

        $mpdf->useSubstitutions = true;
        // $mpdf->SetWatermarkText($business->name, 0.1);
        $mpdf->showWatermarkText = true;
        $mpdf->SetTitle(__('cash_book.cash_book').'.pdf');
        $mpdf->WriteHTML($body);
        

        /*/ Output the PDF
        $pdfContent = $mpdf->Output('', 'S');

        // Create a response with the PDF content
        $response = response($pdfContent);

        // Set the appropriate headers for PDF download
        $response->header('Content-Type', 'application/pdf');
        $response->header('Content-Disposition', 'inline; filename=example.pdf');

        return $response;
        */
        /**
         * To auto Download
         */ // Get the PDF content as a string
            $pdfContent = $mpdf->Output('', 'S');

            // Set the appropriate headers for a PDF download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="kassenbuch_' . date('d-m-Y') .'.pdf"');

            // Output the PDF content
            echo $pdfContent;
         
    }

}
