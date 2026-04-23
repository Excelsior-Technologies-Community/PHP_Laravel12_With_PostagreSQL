<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto py-10">
        <div class="flex flex-col md:flex-row md:justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-4 md:mb-0">Edit Post</h1>
            <a href="{{ route('posts.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded shadow-md transition duration-300">
                ← Back to Posts
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.update', $post->id) }}" method="POST"
            class="bg-white p-8 rounded-lg shadow-md max-w-xl mx-auto">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Title</label>
                <input type="text" name="title"
                    class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ $post->title }}">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="description"
                    class="w-full border border-gray-300 px-4 py-2 rounded h-32 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $post->description }}</textarea>
            </div>

            <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded shadow-md transition duration-300 w-full">
                Update Post
            </button>
        </form>
    </div>

</body>

</html>