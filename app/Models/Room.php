<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'building', 'capacity', 'status'])]
class Room extends Model
{
    public const STATUSES = [
        'active' => 'Hoạt động',
        'maintenance' => 'Bảo trì',
        'locked' => 'Đã khóa',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}