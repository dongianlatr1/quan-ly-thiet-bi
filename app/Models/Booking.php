<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id', 'room_id', 'booking_date', 'start_period', 'end_period',
    'purpose', 'status', 'reviewed_by', 'reviewed_at', 'reject_reason',
])]
class Booking extends Model
{
    public const PERIOD_COUNT = 12;

    public const STATUSES = [
        'pending' => 'Chờ duyệt',
        'approved' => 'Đã duyệt',
        'rejected' => 'Từ chối',
        'cancelled' => 'Đã hủy',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function details()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'booking_details', 'booking_id', 'device_id');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function badgeClass(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800',
            'approved' => 'bg-emerald-100 text-emerald-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-slate-200 text-slate-700',
        };
    }

    public function periodText(): string
    {
        return $this->start_period === $this->end_period
            ? "Tiết {$this->start_period}"
            : "Tiết {$this->start_period} - {$this->end_period}";
    }

    /**
     * Trả về danh sách thông báo trùng lịch (mảng rỗng = không trùng).
     * Hai khoảng tiết trùng nhau khi: bắt đầu_mới <= kết_thúc_cũ VÀ kết_thúc_mới >= bắt_đầu_cũ
     */
    public static function conflicts(
        int $roomId,
        array $deviceIds,
        string $date,
        int $start,
        int $end,
        array $statuses,
        ?int $ignoreId = null
    ): array {
        $overlap = function ($query) use ($date, $start, $end, $statuses, $ignoreId) {
            $query->whereDate('booking_date', $date)
                ->whereIn('status', $statuses)
                ->where('start_period', '<=', $end)
                ->where('end_period', '>=', $start);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        };

        $errors = [];

        $roomClash = static::query()->where('room_id', $roomId)->where($overlap)->first();
        if ($roomClash) {
            $errors[] = "Phòng đã có lịch {$roomClash->periodText()} trong ngày này ({$roomClash->statusLabel()}).";
        }

        if (count($deviceIds) > 0) {
            $deviceClashes = BookingDetail::with(['booking', 'device'])
                ->whereIn('device_id', $deviceIds)
                ->whereHas('booking', $overlap)
                ->get();

            foreach ($deviceClashes as $row) {
                $errors[] = "Thiết bị {$row->device->code} đã được mượn {$row->booking->periodText()} trong ngày này ({$row->booking->statusLabel()}).";
            }
        }

        return $errors;
    }
}