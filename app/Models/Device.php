<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'category', 'room_id', 'status', 'imported_at'])]
class Device extends Model
{
    public const STATUSES = [
        'good' => 'Tốt',
        'broken' => 'Hỏng',
        'maintenance' => 'Bảo trì',
        'locked' => 'Đã khóa',
    ];

    protected function casts(): array
    {
        return [
            'imported_at' => 'date',
        ];
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}