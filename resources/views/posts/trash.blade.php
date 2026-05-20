<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trash Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto py-10 px-4">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-2">
                    🗑 Trash Posts
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Manage deleted posts & restore anytime
                </p>
            </div>

            <a href="{{ route('posts.index') }}"
                class="bg-gray-800 hover:bg-black text-white px-5 py-2 rounded-lg shadow transition">
                ← Back
            </a>
        </div>

        @if(session('success'))
            <div id="msg" class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form id="bulk-trash-form" method="POST" action="{{ route('posts.bulkAction') }}">
            @csrf
            <div class="mb-4 flex gap-2 items-center bg-white p-3 rounded shadow-sm">
                <select name="action" class="border px-3 py-1.5 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="restore">Restore Selected</option>
                    <option value="force_delete">Delete Permanently</option>
                </select>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded text-sm transition">
                    Apply Bulk Action
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-gray-700 to-gray-900 text-white">
                        <tr>
                            <th class="py-4 px-5 text-left width-10"><input type="checkbox" id="select-all-trash"></th>
                            <th class="py-4 px-5 text-left">ID</th>
                            <th class="py-4 px-5 text-left">Title</th>
                            <th class="py-4 px-5 text-left">Description</th>
                            <th class="py-4 px-5 text-center">Status</th>
                            <th class="py-4 px-5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-4 px-5">
                                    <input type="checkbox" name="ids[]" value="{{ $post->id }}" class="trash-checkbox">
                                </td>
                                <td class="py-4 px-5">
                                    <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        #{{ $post->id }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 font-semibold text-gray-800">{{ $post->title }}</td>
                                <td class="py-4 px-5 text-gray-600">{{ Str::limit($post->description, 50) }}</td>
                                <td class="py-4 px-5 text-center">
                                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">Deleted</span>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('posts.restore', $post->id) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                                            Restore
                                        </a>
                                        <a href="{{ route('posts.forceDelete', $post->id) }}"
                                            onclick="return confirm('Delete permanently?')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center text-gray-500">
                                        <div class="text-6xl">🧹</div>
                                        <h2 class="text-xl font-semibold mt-3">Trash is empty</h2>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('select-all-trash').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.trash-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        setTimeout(() => {
            let msg = document.getElementById('msg');
            if (msg) msg.style.display = 'none';
        }, 3000);
    </script>
</body>
</html>