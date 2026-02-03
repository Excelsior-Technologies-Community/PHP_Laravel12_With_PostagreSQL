<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto py-10">
    <div class="flex flex-col md:flex-row md:justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-4 md:mb-0">All Posts</h1>
        <a href="{{ route('posts.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow-md transition duration-300">
            + Create New Post
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">ID</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Title</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Description</th>
                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($posts as $post)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 px-4">{{ $post->id }}</td>
                    <td class="py-3 px-4 font-medium">{{ $post->title }}</td>
                    <td class="py-3 px-4">{{ Str::limit($post->description, 60) }}</td>
                    <td class="py-3 px-4 flex justify-center gap-2">
                        <a href="{{ route('posts.edit', $post->id) }}" 
                           class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded transition duration-200">
                           Edit
                        </a>

                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition duration-200">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($posts->isEmpty())
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">No posts found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

</body>
</html>