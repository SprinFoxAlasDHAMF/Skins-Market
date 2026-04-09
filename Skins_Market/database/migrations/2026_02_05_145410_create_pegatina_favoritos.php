<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('item')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['usuario_id', 'item_id']); // evita duplicados
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favoritos');
    }
};