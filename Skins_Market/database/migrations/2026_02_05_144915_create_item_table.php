<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('color');
            $table->decimal('precio', 10, 2);
            $table->string('foto')->nullable();
            
            // Relación con calidad
            $table->foreignId('calidad_id')->constrained('calidad');
            
            // Relación con categoría
            $table->foreignId('categoria_id')->constrained('categoria');
            
            // Relación con exterior
            $table->foreignId('exterior_id')->constrained('exterior');
            
            // Tipo de item (arma, guantes, etc.)
            $table->enum('tipo', ['arma', 'guantes', 'agente', 'llavero']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item');
    }
};
