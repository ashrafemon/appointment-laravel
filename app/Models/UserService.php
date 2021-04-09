<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'services' => 'array',
    ];

    protected $visible = [
        'services'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}