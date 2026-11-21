<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    private const ATTRIBUTES = [
        'device_id' => 'Thiết bị',
        'title' => 'Tiêu đề',
        'description' => 'Mô tả sự cố',
        'image' => 'Hình ảnh',
    ];

    private const MESSAGES = [
        'required' => 'Vui lòng nhập :attribute.',
        'exists' => ':attribute không tồn tại.',
        'title.max' => 'Tiêu đề quá dài (tối đa :max ký tự).',
        'description.max' => 'Mô tả quá dài (tối đa :max ký tự).',
        'image.mimes' => 'Hình ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
        'image.max' => 'Hình ảnh không được vượt quá 4MB.',
        'image.uploaded' => 'Không tải được hình ảnh lên. Hãy thử ảnh nhỏ hơn.',
    ];

    public function index()
    {
        $isAdmin = auth()->user()->isAdmin();

        $incidents = Incident::with(['user', 'device'])
            ->when(! $isAdmin, fn ($query) => $query->where('user_id', auth()->id()))
            ->latest()
            ->paginate(10);

        return view('incidents.index', compact('incidents', 'isAdmin'));
    }

    public function create()
    {
        $this->denyAdmin();

        return view('incidents.create', [
            'devices' => Device::with('room')->orderBy('code')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->denyAdmin();

        $data = $request->validate([
            'device_id' => ['required', 'exists:devices,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], self::MESSAGES, self::ATTRIBUTES);

        // Lưu ảnh vào storage/app/public/incidents
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('incidents', 'public')
            : null;

        Incident::create([
            'user_id' => auth()->id(),
            'device_id' => $data['device_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('issues.index')
            ->with('success', 'Đã gửi báo cáo sự cố. Quản trị viên sẽ kiểm tra và xử lý.');
    }

    private function denyAdmin(): void
    {
        abort_if(auth()->user()->isAdmin(), 403, 'Chỉ giảng viên mới được gửi báo cáo sự cố.');
    }
}