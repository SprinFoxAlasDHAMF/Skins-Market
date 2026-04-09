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
    public function up() {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'contraseña')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('contraseña', 'password');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'password')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'contraseña')) {
                    $table->renameColumn('password', 'contraseña');
                }
            });
        }
    }
};
