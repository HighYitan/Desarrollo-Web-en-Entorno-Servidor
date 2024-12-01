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
        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('regNumber', 10)->unique();
            $table->string('observation_CA', 5000)->nullable();
            $table->string('observation_ES', 5000)->nullable();
            $table->string('observation_EN', 5000)->nullable();
            $table->string('email', 100);
            $table->string("phone", 100);
            $table->string("website", 100);
            $table->string("accessType", 1);
            /*  
            20 digitos en total, 2 decimales
            utilizo default(0) en ambos para inicializar totalScore y countScore a 0 para hacer el cálculo de la media con el trigger
            de la migracion Comment al insertar un comentario en el Seeder y así no tener que insertar totalScore y countScore en el SpaceSeeder a 0.
            */
            $table->decimal('totalScore', 20, 2)->default(0); 
            $table->bigInteger('countScore')->default(0); //Número de puntuaciones, decidimos que fuera integer en clase
            $table->foreignId('address_id')->constrained();
            $table->foreignId('space_type_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spaces');
    }
};
