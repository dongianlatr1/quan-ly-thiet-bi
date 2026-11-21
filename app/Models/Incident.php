<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'device_id', 'title', 'description', 'image_path', 'status'])]
class Incident extends Model
{
    public const STATUSES = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'resolved' => 'Đã xử lý',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function badgeClass(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'resolved' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}