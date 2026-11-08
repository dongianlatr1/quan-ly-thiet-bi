<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['booking_id', 'device_id'])]
class BookingDetail extends Model
{
    public $timestamps = false;

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}