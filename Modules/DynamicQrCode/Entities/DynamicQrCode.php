<?php

namespace Modules\DynamicQrCode\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Business;
use \Modules\Superadmin\Entities\Subscription;

class DynamicQrCode extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\DynamicQrCode\Database\factories\DynamicQrCodeFactory::new();
    }

    public function getAllowedNumberOfDynamicQrCode(){
        $business = Business::where('id','=',session('business.id'))->first();

        $subscription = new Subscription();

        $active_subscription = $subscription->active_subscription($business->id);

        return $active_subscription->package->dynamic_qr_code_count;
    }
}
