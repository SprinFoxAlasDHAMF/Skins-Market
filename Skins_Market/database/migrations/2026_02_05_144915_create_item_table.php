<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            

            $table->decimal('precio', 10, 2);
            $table->string('foto')->nullable();
            
            // Relaciones
            $table->foreignId('calidad_id')->constrained('calidad');
            $table->foreignId('categoria_id')->constrained('categoria');
            $table->foreignId('exterior_id')->constrained('exterior');
            $table->foreignId('color_id')->constrained('colors')->cascadeOnDelete();

            // Tipo de item
            $table->enum('tipo', ['arma','guantes','agente','llavero','cuchillo']);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('item');
    }
};