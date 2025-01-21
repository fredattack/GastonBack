<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table( 'event_pet', function(Blueprint $table) {
            $table->string('detail_type')->nullable(); // Type d'événement: "meal", "medical_care"
            $table->string('item')->nullable(); // Nom de la nourriture ou médicament
            $table->string('quantity')->nullable(); // Quantité associée
            $table->text('notes')->nullable(); // Notes supplémentaires
        } );
    }

    public function down(): void
    {
        Schema::table( 'event_pet', function(Blueprint $table) {
            $table->dropColumn('detail_type');
            $table->dropColumn('item');
            $table->dropColumn('quantity');
            $table->dropColumn('notes');
        } );
    }
};
