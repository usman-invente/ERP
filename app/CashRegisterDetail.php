<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Business;
use \Modules\Superadmin\Entities\Subscription;

class CashRegisterDetail extends Model
{   
    protected $fillable = [
        'name', 'location_id'
    ];

    public function business_location()
    {
        return $this->belongsTo(\App\BusinessLocation::class, 'location_id');
    }

    public function cash_register()
    {
        return $this->hasMany(\App\CashRegister::class);
    }

    public function getCashRegisterDetailsByBusinessId($business_id){
        $location_id = auth()->user()->permitted_locations($business_id);
        
        $query = [];
        if($location_id == "all"){
            $cash_register_details = CashRegisterDetail::where('business_id', $business_id)
                                    ->where('cash_register_active', true)
                                    ->get();
                        
            foreach($cash_register_details as $cash_register_detail){
                $business_location = BusinessLocation::where('id', $cash_register_detail->location_id)
                                                        ->first();
                                            
                if(is_object($business_location) && $business_location->is_active == true){
                    $query[] = $cash_register_detail;
                }
            }
        }
        else{
            $cash_register_details = CashRegisterDetail::where('business_id', $business_id)
                                    ->where('location_id', $location_id)
                                    ->where('cash_register_active', true)
                                    ->get();
                                    
            foreach($cash_register_details as $cash_register_detail){
                $business_location = BusinessLocation::where('id', $cash_register_detail->location_id)
                                                        ->first();
                                            
                if(is_object($business_location) && $business_location->is_active == true){
                    $query[] = $cash_register_detail;
                }
            }
        }
        return $query;
    }
    
    public function getFirstCashRegDetByBusinessId($business_id){
        $location_id = auth()->user()->permitted_locations();
        
        $query = CashRegisterDetail::where('business_id', $business_id)
                                    // ->where('location_id', $location_id)
                                    ->where('cash_register_active', true)
                                    ->first();
        
        // dd('ketu'.  $location_id);
        
        return $query;
    }
    
    public function getAllowedNumberOfCashRegister(){
        $business = Business::where('id','=',session('business.id'))->first();

        $subscription = new Subscription();

        $active_subscription = $subscription->active_subscription($business->id);

        return $active_subscription->package->cash_register_count;
    }
}
