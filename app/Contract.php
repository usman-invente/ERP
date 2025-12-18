<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

class Contract extends Model
{
    use SoftDeletes;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contracts';

     /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(\App\Contact::class, 'contact_id');
    }

    public function getMaxNrForContract($contact_id){
        $contract_nr = Contract::where('contact_id', $contact_id)
                    ->max('number');

        return $contract_nr;
    }

    public function getNumberOfContracts($business_id, $condition){
        
        $contracts = Contract::where('business_id', '=', $business_id)
                                ->whereNotNull('date_to_contact')
                                ->get();

        $nr_this_week = 0;
        $nr_this_month = 0;
        $nr_next_month = 0;
        $nr_overdue = 0;
        if($condition == "this_week"){
            $FirstDay = date("Y-m-d", strtotime('monday this week'));  
            $LastDay = date("Y-m-d", strtotime('sunday this week'));
            foreach ($contracts as $key => $contract) {
                if(is_object($contract->customer) && $contract->date_to_contact >= $FirstDay &&  $contract->date_to_contact <= $LastDay)
                $nr_this_week++;
            }
            // dd($nr_this_week);
            return $nr_this_week;
        }
        elseif($condition == "this_month"){
            
            $today =  \Carbon::now()->toDateString();

            foreach ($contracts as $key => $contract) {                
                if( is_object($contract->customer) && date('m', strtotime($contract->date_to_contact)) === date('m', strtotime($today))  && date('Y', strtotime($contract->date_to_contact)) === date('Y', strtotime($today))) {
                    $nr_this_month++;
                }
            }
            return $nr_this_month;
        }
        elseif($condition == "next_month"){
            $today =  \Carbon::now()->toDateString();
           
            foreach ($contracts as $key => $contract) {                
                if( is_object($contract->customer) && date('m', strtotime($contract->date_to_contact))+0 === (date('m', strtotime($today))+1 ) && date('Y', strtotime($contract->date_to_contact)) === date('Y', strtotime($today))) {
                    $nr_next_month++;
                }
            }            
            return $nr_next_month;
        }
        elseif($condition == "overdue"){
            $today = date("Y-m-d"); 
            foreach ($contracts as $key => $contract) {
                if(is_object($contract->customer) && $contract->date_to_contact < $today){
                    $nr_overdue++;
                }
            }  
            return $nr_overdue;
        }   
        else{
            return count($contracts);
        }

    }

    public function getContractsByCondition($business_id, $condition){
        
        $contracts = Contract::where('business_id', '=', $business_id)
                                ->whereNotNull('date_to_contact')
                                ->get();

        $array_this_week = array();
        $array_this_month = array();
        $array_next_month = array();
        $array_overdue = array();

        if($condition == "this_week"){
            $FirstDay = date("Y-m-d", strtotime('monday this week'));  
            $LastDay = date("Y-m-d", strtotime('sunday this week'));
            foreach ($contracts as $key => $contract) {
                if( is_object($contract->customer) && $contract->date_to_contact >= $FirstDay &&  $contract->date_to_contact <= $LastDay)
                    if (!in_array($contract, $array_this_week))
                        $array_this_week[] = $contract;
            }
            // dd($nr_this_week);
            return $array_this_week;
        }
        elseif($condition == "this_month"){
            
            $today =  \Carbon::now()->toDateString();

            foreach ($contracts as $key => $contract) {                
                if(is_object($contract->customer) && date('m', strtotime($contract->date_to_contact)) === date('m', strtotime($today))  && date('Y', strtotime($contract->date_to_contact)) === date('Y', strtotime($today))) {
                    if (!in_array($contract, $array_this_month))
                        $array_this_month[] = $contract;
                }
            }
            return $array_this_month;
        }
        elseif($condition == "next_month"){
            $today =  \Carbon::now()->toDateString();
           
            foreach ($contracts as $key => $contract) {                
                if(is_object($contract->customer) && date('m', strtotime($contract->date_to_contact))+0 === (date('m', strtotime($today))+1 ) && date('Y', strtotime($contract->date_to_contact)) === date('Y', strtotime($today))) {
                    if (!in_array($contract, $array_next_month))
                        $array_next_month[] = $contract;
                }
            }            
            return $array_next_month;
        }
        elseif($condition == "overdue"){
            $today = date("Y-m-d"); 
            foreach ($contracts as $key => $contract) {
                if(is_object($contract->customer) && $contract->date_to_contact < $today){
                    if (!in_array($contract, $array_overdue))
                        $array_overdue[] = $contract;
                }
            }  
            return $array_overdue;
        }   
        else{
            return $contracts;
        }

    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
