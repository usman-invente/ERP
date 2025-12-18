<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class BusinessLocation extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'featured_products' => 'array',
    ];

    /**
     * Return list of locations for a business
     *
     * @param  int  $business_id
     * @param  bool  $show_all = false
     * @param  array  $receipt_printer_type_attribute =
     * @return array
     */
    public static function forDropdown($business_id, $show_all = false, $receipt_printer_type_attribute = false, $append_id = true, $check_permission = true)
    {
        $query = BusinessLocation::where('business_id', $business_id)->Active();

        if ($check_permission) {
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('id', $permitted_locations);
            }
        }

        if ($append_id) {
            $query->select(
                DB::raw("IF(location_id IS NULL OR location_id='', name, CONCAT(name, ' (', location_id, ')')) AS name"),
                'id',
                'receipt_printer_type',
                'selling_price_group_id',
                'default_payment_accounts',
                'invoice_scheme_id',
                'invoice_layout_id'
            );
        }

        $result = $query->get();

        $locations = $result->pluck('name', 'id');

        $price_groups = SellingPriceGroup::forDropdown($business_id);

        if ($show_all) {
            $locations->prepend(__('report.all_locations'), '');
        }

        if ($receipt_printer_type_attribute) {
            $attributes = collect($result)->mapWithKeys(function ($item) use ($price_groups) {
                $default_payment_accounts = json_decode($item->default_payment_accounts, true);
                $default_payment_accounts['advance'] = [
                    'is_enabled' => 1,
                    'account' => null,
                ];

                return [$item->id => [
                    'data-receipt_printer_type' => $item->receipt_printer_type,
                    'data-default_price_group' => ! empty($item->selling_price_group_id) && array_key_exists($item->selling_price_group_id, $price_groups) ? $item->selling_price_group_id : null,
                    'data-default_payment_accounts' => json_encode($default_payment_accounts),
                    'data-default_invoice_scheme_id' => $item->invoice_scheme_id,
                    'data-default_invoice_layout_id' => $item->invoice_layout_id,
                ],
                ];
            })->all();

            return ['locations' => $locations, 'attributes' => $attributes];
        } else {
            return $locations;
        }
    }

    public function business()
    {
        return $this->belongsTo(\App\Business::class, 'business_id');
    }
    
    public function price_group()
    {
        return $this->belongsTo(\App\SellingPriceGroup::class, 'selling_price_group_id');
    }

    /**
     * Scope a query to only include active location.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Get the featured products.
     *
     * @return array/object
     */
    public function getFeaturedProducts($is_array = false, $check_location = true)
    {
        if (empty($this->featured_products)) {
            return [];
        }
        $query = Variation::whereIn('variations.id', $this->featured_products)
                                    ->join('product_locations as pl', 'pl.product_id', '=', 'variations.product_id')
                                    ->join('products as p', 'p.id', '=', 'variations.product_id')
                                    ->where('p.not_for_selling', 0)
                                    ->with(['product_variation', 'product', 'media'])
                                    ->select('variations.*');

        if ($check_location) {
            $query->where('pl.location_id', $this->id);
        }
        $featured_products = $query->get();
        if ($is_array) {
            $array = [];
            foreach ($featured_products as $featured_product) {
                $array[$featured_product->id] = $featured_product->full_name;
            }

            return $array;
        }

        return $featured_products;
    }

    public function getLocationAddressAttribute()
    {
        $location = $this;
        $address_line_1 = [];
        if (! empty($location->landmark)) {
            $address_line_1[] = $location->landmark;
        }
        if (! empty($location->city)) {
            $address_line_1[] = $location->city;
        }
        if (! empty($location->state)) {
            $address_line_1[] = $location->state;
        }
        if (! empty($location->zip_code)) {
            $address_line_1[] = $location->zip_code;
        }
        $address = implode(', ', $address_line_1);
        $address_line_2 = [];
        if (! empty($location->country)) {
            $address_line_2[] = $location->country;
        }
        $address .= '<br>';
        $address .= implode(', ', $address_line_2);

        return $address;
    }

    public function getBusinessLocationAddress()
    {
        $location = $this;
        $address_line_1 = [];
        if (! empty($location->street)) {
            $address_line_1[] = $location->street;
        }
        if (! empty($location->house_nr)) {
            $address_line_1[] = $location->house_nr;
        }
        if (! empty($location->zip_code)) {
            $address_line_1[] ="<br>" .$location->zip_code;
        }
        if (! empty($location->city)) {
            $address_line_1[] = $location->city;
        }
        if (! empty($location->state)) {
            $address_line_1[] = "<br>" .$location->state;
        }
        if (! empty($location->country)) {
            $address_line_1[] = "<br>".$location->country;
        }
        $address = implode(', ', $address_line_1);       
        
        return $address;
    }

    public function getBusinessLocationContact()
    {
        $location = $this;
        $contact_date = [];
        if (! empty($location->mobile)) {
            $contact_date[] = "Handy: ".$location->mobile;
        }
        if (! empty($location->email)) {
            $contact_date[] = "<br> E-Mail: ".$location->email;
        }
        if (! empty($location->website)) {
            $contact_date[] = "<br> Website: ".$location->website;
        }
        $address = implode(', ', $contact_date);       
        
        return $address;
    }

    public function getAllLocationsByBusinessId($business_id)
    {
        $locations = BusinessLocation::where('business_id', $business_id)
                                        ->where('is_active', true)
                                        ->get();        

        return $locations;
        
    }

    public function getLocationNameById(){
        $location = BusinessLocation::where('id', $this->id)->first();

        return $location->name;
    }
}
