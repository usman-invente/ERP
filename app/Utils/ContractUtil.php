<?php

namespace App\Utils;

use App\Business;
use App\Contact;
use App\Contract;
use App\Transaction;
use DB;
use App\Utils\BusinessUtil;

class ContractUtil extends Util
{
    /**
     * All Utils instance.
     */
    protected $businessUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        BusinessUtil $businessUtil
    ) {
        $this->businessUtil = $businessUtil;
    }

    /**
     * Returns the contract info
     *
     * @param  int  $business_id
     * @param  int  $contact_id
     * @return array
     */
    public function getContractInfo($business_id, $start_date = null, $end_date = null, $location_id = null)
    {       
        $is_admin = $this->businessUtil->is_admin(auth()->user());
        $current_user_id = request()->session()->get('user.id');

        $query = Contract::where('contracts.business_id', $business_id)
                    ->select(
                        DB::raw('SUM(fee_monthly) as total_fee_monthly'),
                        DB::raw('SUM(discount) as total_discount'),
                        DB::raw('SUM(price_total) as total_price_total'),
                        DB::raw('COUNT(*) as contracts_count')
                    );

        //Check for permitted locations of a user
        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $query->whereIn('contracts.location_id', $permitted_locations);
        }

        if (! empty($start_date) && ! empty($end_date)) {
            $query->whereDate('contracts.created_at', '>=', $start_date)
                ->whereDate('contracts.created_at', '<=', $end_date);
        }

        if (empty($start_date) && ! empty($end_date)) {
            $query->whereDate('contracts.created_at', '<=', $end_date);
        }

        // //Filter by the location
        // if (! empty($location_id)) {
        //     $query->where('contracts.location_id', $location_id);
        // }

        if (!$is_admin) {
            $query->where('contracts.created_by', $current_user_id);
        }

        $contracts = $query->first();

        $output['total_contracts_fee_monthly'] = $contracts->total_fee_monthly;
        $output['total_contracts_discount'] = $contracts->total_discount;
        $output['total_contracts_price_total'] = $contracts->total_price_total;
        $output['total_contracts_count'] = $contracts->contracts_count;

        return $output;
    }   

    /**
     * Returns the contact info
     *
     * @param  int  $business_id
     * @param  int  $contact_id
     * @return array
     */
    public function getContactInfo($business_id, $contact_id)
    {
        $contact = Contact::where('contacts.id', $contact_id)
                    ->where('contacts.business_id', $business_id)
                    ->leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                    ->with(['business'])
                    ->select(
                        DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                        DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                        DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
                        DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
                        DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                        DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid"),
                        'contacts.*'
                    )->first();

        return $contact;
    }   
    
}
