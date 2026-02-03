# PHP_Laravel12_With_PostagreSQL

## Introduction

This project is a fully functional CRUD (Create, Read, Update, Delete) application built using Laravel 12 and PostgreSQL, designed for beginners, freshers, and anyone looking to learn modern web application development with PHP frameworks.

The main focus of this project is to demonstrate how to:

- Integrate Laravel 12 with PostgreSQL as the primary database

- Perform database migrations, create models and controllers

- Implement full CRUD operations with clean and maintainable code

- Build responsive UI templates using Tailwind CSS

- Handle form validations, error messages, and user feedback

---

##  Project Overview

PHP_Laravel12_With_PostgreSQL is a CRUD application that allows users to create, read, update, and delete posts using Laravel 12 and PostgreSQL.

The project includes:

- PostgreSQL integration for secure database operations

- Models, controllers, and migrations for structured backend logic

- Manual CRUD routes for index, create, edit, and delete actions

- Responsive Blade templates with Tailwind CSS

- Validation and error handling for clean user input

---

##  Requirements

| Tool | Version |
|------|--------|
| PHP | 8.2+ |
| Composer | Latest |
| Laravel | 12 |
| PostgreSQL | 14+ |
| Node.js | Optional |
| VS Code | Recommended |

---

##  Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_With_PostgreSQL "12.*"
cd PHP_Laravel12_With_PostgreSQL
```

---

## Step 2: Install & Setup PostgreSQL

1. **Download PostgreSQL** (if not installed):  
   [https://www.postgresql.org/download/](https://www.postgresql.org/download/)

2. **Create Database**  
   Open pgAdmin or PostgreSQL CLI and create a database:

```
Database Name: laravel12_pgsql_db
Username: postgres
Password: your_password
Port: 5432
```

---

## Step 3: Configure .env for PostgreSQL

Open .env file and update ONLY database section:

```.env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel12_pgsql_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```


```bash
php artisan migrate
```

Laravel automatically supports PostgreSQL — no extra package needed.
---


## Step 4: Verify PostgreSQL Driver

Run:

```
php -m | findstr pgsql
```

You should see:

```
pgsql
pdo_pgsql
```


If not installed, enable in php.ini:

Search for these lines:

```
;extension=pdo_pgsql
;extension=pgsql
```
Remove the semicolon ; to enable them:

```
extension=pdo_pgsql
extension=pgsql
```
Restart server.

---

##  Step 5: Run Default Migrations

```bash
php artisan migrate
```
If successful, tables like these will appear in PostgreSQL:

users

password_reset_tokens

sessions

cache


This confirms PostgreSQL is connected correctly.

---

## Step 6: Create Model + Migration

Create a Post model with migration:

```bash
php artisan make:model Post -m
```
This creates:

app/Models/Post.php

database/migrations/xxxx_create_posts_table.php

---

## Step 7: Update Migration File

File: database/migrations/xxxx_create_posts_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

Run migration:

```bash
php artisan migrate
```

---

## Step 8: Update Model

File: app/Models/Post.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description'
    ];
}
```

---

##  Step 9: Create Controller

```bash
php artisan make:controller PostController
```

File: app/Http/Controllers/PostController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Display all posts
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    // Show form to create a new post
    public function create()
    {
        return view('posts.create');
    }

    // Store a new post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Post::create($request->all());

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    // Show form to edit a post
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Update a post
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $post->update($request->all());

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    // Delete a post
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
```

## Step 10: Blade Files

Create the Blade templates inside:

```
resources/views/posts/
```

### index.blade.php – List All Posts

```html
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
```

### create.blade.php – Create New Post

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto py-10">
    <div class="flex flex-col md:flex-row md:justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-4 md:mb-0">Create New Post</h1>
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

    <form action="{{ route('posts.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md max-w-xl mx-auto">
        @csrf
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Title</label>
            <input type="text" name="title" 
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   value="{{ old('title') }}">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Description</label>
            <textarea name="description" 
                      class="w-full border border-gray-300 px-4 py-2 rounded h-32 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow-md transition duration-300 w-full">
            Create Post
        </button>
    </form>
</div>

</body>
</html> 
```

### edit.blade.php – Edit Post

```html
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

    <form action="{{ route('posts.update', $post->id) }}" method="POST" class="bg-white p-8 rounded-lg shadow-md max-w-xl mx-auto">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Title</label>
            <input type="text" name="title" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $post->title }}">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Description</label>
            <textarea name="description" class="w-full border border-gray-300 px-4 py-2 rounded h-32 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $post->description }}</textarea>
        </div>

        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded shadow-md transition duration-300 w-full">
            Update Post
        </button>
    </form>
</div>

</body>
</html>
```

---

## Step 11: Define Routes

routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

// ------------------
// POSTS CRUD MANUAL
// ------------------

//  List all posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

//  Show create post form
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

//  Store new post
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

//  Show edit post form
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

//  Update post
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

//  Delete post
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
```

---

## Step 12: Test in Browser

View data:

```bash
http://127.0.0.1:8000/posts
```

---

## Output

### Index Page

<img width="1919" height="1028" alt="Screenshot 2026-02-03 165749" src="https://github.com/user-attachments/assets/42bd4732-6e65-4a95-9bb7-dec1342aa9c0" />

### Create Page

<img width="1919" height="1028" alt="Screenshot 2026-02-03 165852" src="https://github.com/user-attachments/assets/8ace0d93-307a-43a0-bd17-3951981cd073" />

<img width="1919" height="1030" alt="Screenshot 2026-02-03 165944" src="https://github.com/user-attachments/assets/30cb8b68-7df5-42c0-beb8-a81c7bed564c" />

### Edit Page

<img width="1919" height="1030" alt="Screenshot 2026-02-03 170223" src="https://github.com/user-attachments/assets/14a1de31-2f7d-4903-902c-5e4eccea072f" />

<img width="1919" height="1028" alt="Screenshot 2026-02-03 170233" src="https://github.com/user-attachments/assets/22cea510-b15d-4891-a69a-8dbbd7a0ae41" />

### Delete Page

<img width="1919" height="1031" alt="Screenshot 2026-02-03 170527" src="https://github.com/user-attachments/assets/38a1bfda-aa81-40df-aa78-9042bcbbe826" />

<img width="1919" height="1030" alt="Screenshot 2026-02-03 170539" src="https://github.com/user-attachments/assets/70bd1bda-2e89-49fc-a2ed-3b35c0ed6550" />

---

## Project Structure 

```
PHP_Laravel12_With_PostgreSQL
│── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── PostController.php      <- CRUD Controller
│   ├── Models/
│   │   └── Post.php                    <- Model
│
│── bootstrap/
│
│── config/
│
│── database/
│   ├── migrations/
│   │   └── 2026_02_03_000000_create_posts_table.php  <- Posts table migration
│   ├── seeders/
│
│── public/
│
│── resources/
│   ├── views/
│   │   └── posts/
│   │       ├── index.blade.php        <- List all posts
│   │       ├── create.blade.php       <- Create post form
│   │       └── edit.blade.php         <- Edit post form
│
│── routes/
│   └── web.php                        <- Manual CRUD routes
│
│── storage/
│
│── .env                               <- Database & environment settings
│── composer.json
│── artisan
```

---

Your PHP_Laravel12_With_PostgreSQL Project is now ready!

