<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Business;
use App\Contact;
use \Modules\Superadmin\Entities\Subscription;
use App\Utils\TransactionUtil;

class CashBook extends Model
{
    public function business()
    {
        return $this->belongsTo(\App\Business::class, 'business_id');
    }

    public function business_location()
    {
        return $this->belongsTo(\App\BusinessLocation::class, 'location_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }

    public function cash_register()
    {
        return $this->hasMany(\App\CashRegister::class);
    }

    public function getCashRegisterDetailsByBusinessId($business_id){
        $location_id = auth()->user()->permitted_locations($business_id);
        if($location_id == "all"){
            $query = CashRegisterDetail::where('business_id', $business_id)
                                    ->where('cash_register_active', true)
                                    ->get();
        }
        else{
            $query = CashRegisterDetail::where('business_id', $business_id)
                                    ->where('location_id', $location_id)
                                    ->where('cash_register_active', true)
                                    ->get();
        }
        return $query;
    }

    public function getLastNumberingCash($business_id, $location_id, $cash_register_detail_id, $year){
        $cash_book = CashBook::where('business_id',$business_id)
                                        ->where('location_id', $location_id)
                                        ->where('cash_register_detail_id', $cash_register_detail_id)
                                        // ->where('transaction_year', $year)
                                        ->latest()->first();

        if(is_object($cash_book)){
            return $cash_book->numbering_cash + 1;
        }else{
            return 1;
        }
    }

    public function getLastNoForNumberingYear($business_id, $location_id, $cash_register_detail_id, $year){
        // dd('ketu');
        $cash_book = CashBook::where('business_id',$business_id)
                                        ->where('location_id', $location_id)
                                        ->where('cash_register_detail_id', $cash_register_detail_id)
                                        ->where('transaction_year', $year)
                                        ->latest('id')->first();

        if(is_object($cash_book)){
            $last_no_for_numbering_year = $cash_book->last_no_for_numbering_year; //string length 5
            $last_no_for_numbering_year_as_number = intval($last_no_for_numbering_year) + 1; //convert in number and add +1

            if( strlen($last_no_for_numbering_year_as_number) == 1)
                $return = '0000'.$last_no_for_numbering_year_as_number;
            
            if( strlen($last_no_for_numbering_year_as_number) == 2)
                $return = '000'.$last_no_for_numbering_year_as_number;

            if( strlen($last_no_for_numbering_year_as_number) == 3)
                $return = '00'.$last_no_for_numbering_year_as_number;
            
            if( strlen($last_no_for_numbering_year_as_number) == 4)
                $return = '0'.$last_no_for_numbering_year_as_number;

            if( strlen($last_no_for_numbering_year_as_number) == 5)
                $return = $last_no_for_numbering_year_as_number;

            return $return;
        }else{
            return '00001';
        }
    }

    public function getLastSumAmountCashBook($business_id, $location_id, $cash_register_detail_id){
        $cash_book = CashBook::where('business_id',$business_id)
                                            ->where('location_id', $location_id)
                                            ->where('cash_register_detail_id', $cash_register_detail_id)
                                            ->latest()->first();  
                                            
        return $cash_book->sum_amount;
    }

    public function saveTransactionInCashBook($transaction){

        $contact = new Contact();
        $user = new User();
        $cashRegisterTransaction = new CashRegisterTransaction();
        $transactionPayment = new TransactionPayment();
        $transactionSellLine = new TransactionSellLine();
        $transactionUtil = new TransactionUtil();

        $tax_rates = TaxRate::where('business_id', $transaction->business_id)
                                ->orderBy('amount', 'desc')
                                ->get();

        if($cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->pay_method == "cash"){
            $cash_book = new CashBook();
            $cash_book->business_id = $transaction->business_id;
            $cash_book->location_id = $transaction->location_id;
            $cash_book->transaction_id = $transaction->id;
            $cash_book->cash_register_detail_id = $transaction->cash_register_detail_id;
            $cash_book->cash_register_id = $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->cash_register_id;
            $cash_book->invoice_nr = $transaction->invoice_no;
            $cash_book->transaction_year = $transaction->created_at->format('Y');
            $cash_book->transaction_month = $transaction->created_at->format('m');
            $cash_book->transaction_day = $transaction->created_at->format('d');
            $cash_book->transaction_time = $transaction->created_at->format('H:i:s');
            $cash_book->transaction_date = $transaction->created_at;
            $cash_book->discount_amount = $transaction->discount_amount;
            $cash_book->customer_name = $contact->getFullNameAndTitleById($transaction->contact_id);
            $cash_book->created_by = $transaction->created_by;
            $cash_book->created_by_name = $user->getUserNameById($transaction->created_by);
            $cash_book->amount = $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->amount;
            $cash_book->sum_amount = $this->getLastSumAmountCashBook($transaction->business_id,$transaction->location_id, $transaction->cash_register_detail_id) + $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->amount;
            // $cash_book->sum_amount = $last_close_amount;
            $cash_book->brutto_amount = $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->amount;
            $cash_book->netto_amount = $transactionSellLine->getSumNettoValue($transaction->id);
            $cash_book->payment_ref_no = $transactionPayment->getTransactionPaymentByTransactionId($transaction->id)->payment_ref_no;
            $cash_book->transaction_type = $transaction->type;
            $cash_book->sum_tax_rate = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[0]->id, 'Sum');
            $cash_book->netto_tax_rate = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[0]->id, 'Netto');
            $cash_book->brutto_tax_rate = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[0]->id, 'Brutto');
            $cash_book->sum_tax_rate_1 = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[1]->id, 'Sum');
            $cash_book->netto_tax_rate_1 = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[1]->id, 'Netto');
            $cash_book->brutto_tax_rate_1 = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[1]->id, 'Brutto');
            $cash_book->sum_tax_rate_2 = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[2]->id, 'Sum');
            $cash_book->netto_tax_rate_2 = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[2]->id, 'Netto');
            $cash_book->brutto_tax_rate_2 = $transactionSellLine->getTaxRateValue($transaction->id, $tax_rates[2]->id, 'Brutto');
            $cash_book->sum_all_tax_rate = $transactionSellLine->getSumAllTaxRates($transaction->id);
            $cash_book->pay_method = $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->pay_method;
            $cash_book->pay_method_de = "Barmittel";
            $cash_book->process = "Verkauf";
            $cash_book->description = "<b>Verkauf an Privatkunde</b> <br>";
            $cash_book->invoice_url = $transactionUtil->getInvoiceUrl($transaction->id, $transaction->business_id);
            $cash_book->numbering_year = \Carbon::now()->format('Y-m-d').'-'.$this->getLastNoForNumberingYear($transaction->business_id, $transaction->location_id, $transaction->cash_register_detail_id, \Carbon::now()->format('Y'));
            $cash_book->last_no_for_numbering_year = $this->getLastNoForNumberingYear($transaction->business_id, $transaction->location_id, $transaction->cash_register_detail_id, \Carbon::now()->format('Y'));
            $cash_book->numbering_cash = $this->getLastNumberingCash($transaction->business_id, $transaction->location_id, $transaction->cash_register_detail_id, \Carbon::now()->format('Y'));
            $cash_book->save();
        }

    }

    public function saveExpenseTransactionInCashBook($transaction, $request){
        $contact = new Contact();
        $user = new User();
        $cashRegisterTransaction = new CashRegisterTransaction();
        $transactionPayment = new TransactionPayment();
        $transactionSellLine = new TransactionSellLine();
        $transactionUtil = new TransactionUtil();

        $tax_rates = TaxRate::where('business_id', $transaction->business_id)
                                ->orderBy('amount', 'desc')
                                ->get();

        // if($cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->pay_method == "cash"){
            $cash_book = new CashBook();
            $cash_book->business_id = $transaction->business_id;
            $cash_book->location_id = $transaction->location_id;
            $cash_book->transaction_id = $transaction->id;
            $cash_book->cash_register_detail_id = $transaction->cash_register_detail_id;
            // $cash_book->cash_register_id = $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->cash_register_id;
            $cash_book->invoice_nr = $transaction->invoice_no;
            $cash_book->transaction_year = $transaction->created_at->format('Y');
            $cash_book->transaction_month = $transaction->created_at->format('m');
            $cash_book->transaction_day = $transaction->created_at->format('d');
            $cash_book->transaction_time = $transaction->created_at->format('H:i:s');
            $cash_book->transaction_date = $transaction->created_at;
            $cash_book->discount_amount = $transaction->discount_amount;
            if(!empty($transaction->contact_id))
                // $cash_book->customer_name = $contact->getFullNameAndTitleById($transaction->contact_id);
            $cash_book->created_by = $transaction->created_by;
            $cash_book->created_by_name = $user->getUserNameById($transaction->created_by);
            // $cash_book->amount = $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->amount;
            $cash_book->sum_amount = $this->getLastSumAmountCashBook($transaction->business_id,$transaction->location_id, $transaction->cash_register_detail_id) + $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->amount;
            // $cash_book->sum_amount = $last_close_amount;
            $cash_book->brutto_amount = $transaction->total_before_tax;
            $cash_book->netto_amount = $transactionSellLine->getSumNettoValue($transaction->id);
            $cash_book->payment_ref_no = $transactionPayment->getTransactionPaymentByTransactionId($transaction->id)->payment_ref_no;
            $cash_book->transaction_type = $transaction->type;
            foreach ($tax_rates as $id => $tax_rate) {
                if($id == 0){
                    $cash_book->sum_tax_rate = $request->input('tax_'.$id);
                    $cash_book->netto_tax_rate = $request->input('total_'.$id) - $request->input('tax_'.$id);
                    $cash_book->brutto_tax_rate = $request->input('total_'.$id);
                }
                if($id == 1){
                    $cash_book->sum_tax_rate_1 = $request->input('tax_'.$id);
                    $cash_book->netto_tax_rate_1 = $request->input('total_'.$id) - $request->input('tax_'.$id);
                    $cash_book->brutto_tax_rate_1 = $request->input('total_'.$id);
                }
                if($id == 2){
                    $cash_book->sum_tax_rate_2 = $request->input('tax_'.$id);
                    $cash_book->netto_tax_rate_2 = $request->input('total_'.$id) - $request->input('tax_'.$id);
                    $cash_book->brutto_tax_rate_2 = $request->input('total_'.$id);
                }
            }
            $cash_book->sum_all_tax_rate = $request->input('tax_total');
            // $cash_book->pay_method = $cashRegisterTransaction->getCashRegisterTransactionByTransactionId($transaction->id)->pay_method;
            $cash_book->pay_method_de = "Ausgabe";
            $cash_book->process = "Ausgabe";
            $cash_book->description = "<b>Ausgaben</b> <br>";
            $cash_book->invoice_url = $transactionUtil->getInvoiceUrl($transaction->id, $transaction->business_id);
            $cash_book->numbering_year = \Carbon::now()->format('Y-m-d').'-'.$this->getLastNoForNumberingYear($transaction->business_id, $transaction->location_id, $transaction->cash_register_detail_id, \Carbon::now()->format('Y'));
            $cash_book->last_no_for_numbering_year = $this->getLastNoForNumberingYear($transaction->business_id, $transaction->location_id, $transaction->cash_register_detail_id, \Carbon::now()->format('Y'));
            $cash_book->numbering_cash = $this->getLastNumberingCash($transaction->business_id, $transaction->location_id, $transaction->cash_register_detail_id, \Carbon::now()->format('Y'));
            $cash_book->save();
        // }

    }

    public function saveOpenCashRegisterInCashBook($cash_register, $last_close_amount){
        // dd($cash_register->id.' '.$cash_register->user_id.' '.$cash_register->status);
        $user = new User();
        $user_id = auth()->user()->id;

        $cash_book = new CashBook();
        $cash_book->business_id = $cash_register->business_id;
        $cash_book->location_id = $cash_register->location_id;
        $cash_book->cash_register_detail_id = $cash_register->cash_register_detail_id;
        $cash_book->cash_register_id = $cash_register->id;
        $cash_book->created_by = $user_id;
        $cash_book->transaction_year = \Carbon::now()->format('Y');
        $cash_book->transaction_month = \Carbon::now()->format('m');
        $cash_book->transaction_day = \Carbon::now()->format('d');
        $cash_book->transaction_time = \Carbon::now()->format('H:i:s');
        $cash_book->transaction_date = \Carbon::now()->format('Y-m-d');
        $cash_book->created_by_name = $user->getUserNameById($user_id);
        $cash_book->status_cash_register = $cash_register->status;
        $cash_book->cash_register_date = $cash_register->created_at;
        $cash_book->description = "<b>Kassenöffnung</b> <br>".$cash_register->correction_description;
        $cash_book->amount = $last_close_amount;
        $cash_book->sum_amount = $last_close_amount;
        $cash_book->pay_method_de = __('cash_book.pay_cash_method_de');
        $cash_book->numbering_year = \Carbon::now()->format('Y-m-d').'-'.$this->getLastNoForNumberingYear($cash_register->business_id, $cash_register->location_id, $cash_register->cash_register_detail_id, \Carbon::now()->format('Y'));
        $cash_book->last_no_for_numbering_year = $this->getLastNoForNumberingYear($cash_register->business_id, $cash_register->location_id, $cash_register->cash_register_detail_id, \Carbon::now()->format('Y'));
        $cash_book->process = "Kassen Öffnung";
        $cash_book->numbering_cash = $this->getLastNumberingCash($cash_register->business_id, $cash_register->location_id, $cash_register->cash_register_detail_id, \Carbon::now()->format('Y'));
        // $cash_book->correction_description_cash_register = $cash_register->correction_description;
        // $cash_book->closing_note_cash_register = $cash_register->closing_note;
        $cash_book->save();
    }

    public function saveCloseCashRegisterInCashBook($cash_register, $last_close_amount, $type){
        $user = new User();
        $user_id = auth()->user()->id;

        $cash_book = new CashBook();
        $cash_book->business_id = $cash_register->business_id;
        $cash_book->location_id = $cash_register->location_id;
        $cash_book->cash_register_detail_id = $cash_register->cash_register_detail_id;
        $cash_book->cash_register_id = $cash_register->id;
        $cash_book->created_by = $user_id;
        $cash_book->transaction_year = \Carbon::now()->format('Y');
        $cash_book->transaction_month = \Carbon::now()->format('m');
        $cash_book->transaction_day = \Carbon::now()->format('d');
        $cash_book->transaction_time = \Carbon::now()->format('H:i:s');
        $cash_book->transaction_date = \Carbon::now()->format('Y-m-d');
        $cash_book->created_by_name = $user->getUserNameById($user_id);
        $cash_book->cash_register_date = $cash_register->created_at;
        $cash_book->amount = $last_close_amount;
        $cash_book->pay_method_de = __('cash_book.pay_cash_method_de');
        $cash_book->numbering_year = \Carbon::now()->format('Y-m-d').'-'.$this->getLastNoForNumberingYear($cash_register->business_id, $cash_register->location_id, $cash_register->cash_register_detail_id, \Carbon::now()->format('Y'));
        $cash_book->last_no_for_numbering_year = $this->getLastNoForNumberingYear($cash_register->business_id, $cash_register->location_id, $cash_register->cash_register_detail_id, \Carbon::now()->format('Y'));
        $cash_book->numbering_cash = $this->getLastNumberingCash($cash_register->business_id, $cash_register->location_id, $cash_register->cash_register_detail_id, \Carbon::now()->format('Y'));
        if($type == "close"){
            $cash_book->status_cash_register = $cash_register->status;
            $cash_book->process = __('cash_book.cash_register_close');
            $cash_book->description = '<b>'. __('cash_book.desc_cash_register_close').'</b> <br>'.$cash_register->closing_note;
        }
        if($type == "cash_payout"){
            $cash_book->status_cash_register = "payout";
            $cash_book->process = __('cash_book.cash_payout');
            $cash_book->description = "<b>Abschöpfung</b> <br>";
        }
        // $cash_book->amount = $last_close_amount;
        // $cash_book->correction_description_cash_register = $cash_register->correction_description;
        // $cash_book->closing_note_cash_register = $cash_register->closing_note;
        $cash_book->save();
        
    }
}
