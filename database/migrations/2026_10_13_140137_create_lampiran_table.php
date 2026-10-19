<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lampiran')) {
            return;
        }

        Schema::create('lampiran', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable');
            $table->string('nama_file')->nullable();
            $table->string('nama_asli')->nullable();
            $table->string('path')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('ukuran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran');
    }
};
