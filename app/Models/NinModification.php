<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NinModification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tnx_id',
        'refno',
        'type',
        'nin',
        'phone_number',
        'surname',
        'first_name',
        'middle_name',
        'dob',
        'address',
        'town',
        'lga_origin',
        'state_origin',
        'lga_residence',
        'state_residence',
        'gender',
        'modification_type_detail',
        'clear_picture',
        'email',
        'password',
        'status',
        'reason',
        'refunded_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'tnx_id');
    }
}
