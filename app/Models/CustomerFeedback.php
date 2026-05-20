<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    protected $table = 'customer_feedback';
    protected $fillable = [
        'user_id',
        'comments',
        'rating',
        'visible',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'visible' => 'boolean',
        'rating' => 'decimal:1',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
