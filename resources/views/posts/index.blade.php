<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto py-10 px-4">

        <!-- Header -->
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

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Box -->
        <form method="GET" action="{{ route('posts.index') }}" class="mb-6 flex gap-2">
            <input type="text" name="search" placeholder="🔍 Search posts..."
                class="w-full border px-4 py-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400"
                value="{{ request('search') }}">

            <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                Search
            </button>
        </form>

        <!-- Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">

                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Title</th>
                        <th class="py-3 px-4 text-left">Description</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($posts as $post)
                        <tr class="border-b hover:bg-gray-50">

                            <td class="py-3 px-4">{{ $post->id }}</td>

                            <td class="py-3 px-4 font-semibold">
                                {{ $post->title }}
                            </td>

                            <td class="py-3 px-4">
                                {{ \Illuminate\Support\Str::limit($post->description, 60) }}
                            </td>

                            <td class="py-3 px-4 flex justify-center gap-2">

                                <a href="{{ route('posts.edit', $post->id) }}"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                                    Edit
                                </a>

                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                        Delete
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">
                                No posts found 🚫
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

</body>

</html>