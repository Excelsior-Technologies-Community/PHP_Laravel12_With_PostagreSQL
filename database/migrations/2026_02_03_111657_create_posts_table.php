<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('tags')->nullable(); // jsonb is PostgreSQL-only, use json for MySQL
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // PostgreSQL-only: full-text search vector (not supported in MySQL)
        // DB::statement('ALTER TABLE posts ADD COLUMN search_vector tsvector');
        // DB::statement('CREATE INDEX posts_search_vector_idx ON posts USING gin(search_vector)');
        // DB::statement('
        //     CREATE TRIGGER posts_search_vector_update BEFORE INSERT OR UPDATE ON posts
        //     FOR EACH ROW EXECUTE FUNCTION tsvector_update_trigger(search_vector, \'pg_catalog.english\', title, description);
        // ');
    }

    public function down(): void
    {
        // PostgreSQL-only cleanup
        // DB::statement('DROP TRIGGER IF EXISTS posts_search_vector_update ON posts');
        
        Schema::dropIfExists('posts');
    }
}; 