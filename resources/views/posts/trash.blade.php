<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trash Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto py-10 px-4">

        <!-- Header -->
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

        <!-- Success Message -->
        @if(session('success'))
            <div id="msg" class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            <table class="w-full text-sm">

                <!-- Head -->
                <thead class="bg-gradient-to-r from-gray-700 to-gray-900 text-white">
                    <tr>
                        <th class="py-4 px-5 text-left">ID</th>
                        <th class="py-4 px-5 text-left">Title</th>
                        <th class="py-4 px-5 text-left">Description</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-left">Created</th>
                        <th class="py-4 px-5 text-left">Deleted</th>
                        <th class="py-4 px-5 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($posts as $post)

                        <tr class="border-b hover:bg-gray-50 transition">

                            <!-- ID -->
                            <td class="py-4 px-5">
                                <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    #{{ $post->id }}
                                </span>
                            </td>

                            <!-- Title -->
                            <td class="py-4 px-5 font-semibold text-gray-800">
                                {{ $post->title }}
                            </td>

                            <!-- Description -->
                            <td class="py-4 px-5 text-gray-600">
                                {{ Str::limit($post->description, 50) }}
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-5 text-center">
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">
                                    Deleted
                                </span>
                            </td>

                            <!-- Created -->
                            <td class="py-4 px-5 text-gray-500">
                                {{ $post->created_at->format('d M Y') }}
                            </td>

                            <!-- Deleted -->
                            <td class="py-4 px-5 text-gray-500">
                                {{ $post->deleted_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5">
                                <div class="flex justify-center gap-2">

                                    <!-- Restore -->
                                    <a href="{{ route('posts.restore', $post->id) }}"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                                        Restore
                                    </a>

                                    <!-- Delete -->
                                    <a href="{{ route('posts.forceDelete', $post->id) }}"
                                        onclick="return confirm('Delete permanently?')"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                                        Delete
                                    </a>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <!-- Empty -->
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center text-gray-500">
                                    <div class="text-6xl">🧹</div>
                                    <h2 class="text-xl font-semibold mt-3">Trash is empty</h2>
                                    <p class="text-sm">No deleted posts found</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <!-- Auto hide -->
    <script>
        setTimeout(() => {
            let msg = document.getElementById('msg');
            if (msg) msg.style.display = 'none';
        }, 3000);
    </script>

</body>

</html>