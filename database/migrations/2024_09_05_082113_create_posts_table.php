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
            $table->unsignedBigInteger('author_id');
            // $table->unsignedBigInteger('category_id');
            // $table->unsignedBigInteger('tag_id');
            $table->text('content');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->foreign('author_id')->references('id')->on('users');
            // $table->foreign('category_id')->references('id')->on('categories');
            // $table->foreign('tag_id')->references('id')->on('tags');
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
