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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consultation_id')->constrained('consultations')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->string('mode_reglement')->nullable(); // cash, carte, OM, Wave…
            $table->string('statut')->default('en_attente'); // en_attente / paye / annule
            $table->dateTime('date_paiement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
