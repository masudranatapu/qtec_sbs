<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
}
