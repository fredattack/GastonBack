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
        Schema::create('famillies', function (Blueprint $table) {
            $table->id();
            $table->string( 'name' )->nullable();
            $table->string( 'local' )->nullable();
            $table->string( 'street' )->nullable();
            $table->string( 'phone' )->nullable();
            $table->string( 'country' )->nullable();
            $table->string( 'city' )->nullable();
            $table->string( 'zip' )->nullable();
            $table->string( 'email' )->nullable();
            $table->foreignId('master_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table( 'users', function(Blueprint $table) {
            $table->foreignId( 'familly_id' )->constrained( 'famillies' )->onDelete( 'cascade' );
        } );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('famillies');
    }
};
