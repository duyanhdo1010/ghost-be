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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->index('slug');
            $table->text('description');
            $table->decimal('price', 19, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->unsignedTinyInteger('active_flg')->default(1)->comment('0: deleted, 1: active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
