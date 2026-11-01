<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thiết Bị Giảng Dạy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Danh Sách Thiết Bị Giảng Dạy</h1>
        
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-3 border">Mã thiết bị</th>
                    <th class="p-3 border">Tên thiết bị</th>
                    <th class="p-3 border">Loại</th>
                    <th class="p-3 border">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($devices as $device)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-3 border font-semibold">{{ $device['code'] }}</td>
                        <td class="p-3 border">{{ $device['name'] }}</td>
                        <td class="p-3 border">{{ $device['category'] }}</td>
                        <td class="p-3 border">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $device['status'] == 'Sẵn sàng' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $device['status'] == 'Đang mượn' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $device['status'] == 'Đang bảo trì' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $device['status'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>