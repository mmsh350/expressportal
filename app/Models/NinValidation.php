<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NinValidation extends Model
{
  protected $fillable = [
    'user_id',
    'tnx_id',
    'refno',
    'nin_number',
    'description',
    'status',
    'reason',
    'refunded_at',
    'email',
    'tag',
    'tracking_no',
    'resp_code',
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
