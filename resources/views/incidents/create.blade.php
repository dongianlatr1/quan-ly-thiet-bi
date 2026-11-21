@extends('layouts.panel')

@section('title', 'Báo cáo sự cố')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Báo cáo sự cố thiết bị</h1>
    <p class="text-sm text-gray-500 mt-1">Chọn thiết bị gặp sự cố, mô tả tình trạng và đính kèm hình ảnh hỏng hóc nếu có.</p>
</div>

<form method="POST" action="{{ route('issues.store') }}" enctype="multipart/form-data"
      class="bg-white rounded-xl border border-gray-200 p-6 max-w-2xl space-y-5">
    @csrf

    <div>
        <label class="text-sm font-medium text-gray-700">Thiết bị gặp sự cố</label>
        <select name="device_id" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
            <option value="">-- Chọn thiết bị --</option>
            @foreach ($devices as $d)
                <option value="{{ $d->id }}" @selected((string) old('device_id', request('device_id')) === (string) $d->id)>
                    {{ $d->code }} - {{ $d->name }} ({{ $d->room ? $d->room->code : 'Kho' }})
                </option>
            @endforeach
        </select>
        @error('device_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">Tiêu đề</label>
        <input name="title" value="{{ old('title') }}" placeholder="Ví dụ: Máy chiếu không lên hình"
               class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
        @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">Mô tả sự cố</label>
        <textarea name="description" rows="5" placeholder="Mô tả tình trạng hỏng hóc, thời điểm xảy ra, đã thử cách xử lý nào..."
                  class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">{{ old('description') }}</textarea>
        @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">Hình ảnh hỏng hóc (không bắt buộc)</label>
        <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/webp"
               class="mt-1 block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer file:mr-4 file:py-2.5 file:px-4 file:border-0 file:bg-gray-100 file:text-sm file:font-medium hover:file:bg-gray-200">
        <p class="text-xs text-gray-400 mt-1">Định dạng jpg, png, webp. Tối đa 4MB.</p>
        @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

        <img id="preview" class="hidden mt-3 max-h-56 rounded-lg border border-gray-200" alt="Xem trước ảnh">
    </div>

    <div class="flex gap-3">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium">Gửi báo cáo</button>
        <a href="{{ route('issues.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm">Hủy</a>
    </div>
</form>

<script>
    document.getElementById('image').addEventListener('change', function (e) {
        var preview = document.getElementById('preview');
        var file = e.target.files[0];

        if (!file) {
            preview.classList.add('hidden');
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    });
</script>
@endsection