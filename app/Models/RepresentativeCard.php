<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Traits\Auditable;

class RepresentativeCard extends Model
{
    use Translatable, Auditable;

    protected $table = 'representative_cards';

    /** @var list<string> */
    public $translatedAttributes = ['name', 'notes'];

    public $translationModel = RepresentativeCardTranslation::class;

    protected $fillable = [
        'representative_id',
        'code',
        'role',
        'branch',
        'phone',
        'email',
        'commission_rate',
        'commission_method',
        'commission_min',
        'commission_max',
        'status',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
