<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class SupplierCard extends Model
{
    use Translatable, Auditable;

    protected $table = 'supplier_cards';

    /** @var list<string> */
    public $translatedAttributes = ['name', 'trade_name', 'notes'];

    public $translationModel = SupplierCardTranslation::class;

    protected $fillable = [
        'supplier_id',
        'city_id',
        'village_id',
        'phone',
        'fax',
        'tax_number',
        'registration_number',
        'supplier_type',
        'status',
        'default_currency_id',
        'credit_limit',
        'bank_name',
        'bank_account_number',
        'iban',
        'beneficiary_name',
        'bank_account_currency_id',
        'attachments',
        'created_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function defaultCurrency()
    {
        return $this->belongsTo(Currency::class, 'default_currency_id');
    }

    public function bankAccountCurrency()
    {
        return $this->belongsTo(Currency::class, 'bank_account_currency_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

