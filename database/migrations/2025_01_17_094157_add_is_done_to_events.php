<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table( 'events', function(Blueprint $table) {
            $table->boolean( 'is_done' )->default( false );
        } );
    }

    public function down(): void
    {
        Schema::table( 'events', function(Blueprint $table) {
            if ( Schema::hasColumn( 'events', 'is_done' ) ){
                $table->dropColumn( 'is_done' );
            }
        } );
    }
};
