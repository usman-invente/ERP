<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function income_tac_details()
    {
        return $this->hasMany(\App\IncomeTaxDetail::class);
    }

    public function income_cash_book()
    {
        return $this->hasOne(\App\CashBook::class);
    }
}
