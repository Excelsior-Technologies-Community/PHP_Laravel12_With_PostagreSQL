<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto py-10 px-4">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
                📄 All Posts
            </h1>

            <div class="flex gap-2">
                <a href="{{ route('posts.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
                    + Create
                </a>

                <a href="{{ route('posts.trash') }}"
                    class="bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded shadow">
                    🗑 Trash
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('posts.index') }}" class="mb-6 flex gap-2">
            <input type="text" name="search" placeholder="🔍 PostgreSQL Full-Text Search..."
                class="w-full border px-4 py-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400"
                value="{{ request('search') }}">

            <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                Search
            </button>
        </form>

        <form id="bulk-form" method="POST" action="{{ route('posts.bulkAction') }}">
            @csrf
            <div class="mb-4 flex gap-2 items-center bg-white p-3 rounded shadow-sm">
                <select name="action" class="border px-3 py-1.5 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="delete">Move to Trash</option>
                </select>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded text-sm transition">
                    Apply Bulk Action
                </button>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-left width-10"><input type="checkbox" id="select-all"></th>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Image</th>
                            <th class="py-3 px-4 text-left">Title</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Tags</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <input type="checkbox" name="ids[]" value="{{ $post->id }}" class="post-checkbox">
                                </td>
                                <td class="py-3 px-4">{{ $post->id }}</td>
                                <td class="py-3 px-4">
                                    @if($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" class="w-12 h-12 object-cover rounded">
                                    @else
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-semibold">{{ $post->title }}</td>
                                <td class="py-3 px-4">{{ \Illuminate\Support\Str::limit($post->description, 40) }}</td>
                                <td class="py-3 px-4">
                                    @if($post->tags)
                                        @foreach($post->tags as $tag)
                                            <a href="{{ route('posts.index', ['tag' => $tag]) }}" class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full mr-1 hover:bg-blue-200">
                                                #{{ $tag }}
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="py-3 px-4 flex justify-center gap-2">
                                    <a href="{{ route('posts.edit', $post->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm">
                                        Edit
                                    </a>
                                    <button type="button" onclick="document.getElementById('delete-form-{{ $post->id }}').submit();" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500">
                                    No posts found 🚫
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @foreach($posts as $post)
            <form id="delete-form-{{ $post->id }}" action="{{ route('posts.destroy', $post->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.post-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>