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
        Schema::create('transaksi_sensor_suhu', function (Blueprint $table) {
            $table->id('id')->startingValue(1); // Menetapkan AUTO_INCREMENT dimulai dari 1
            $table->decimal('nilai_temperatur', 8, 2); // decimal untuk temperatur dengan 2 digit desimal
            $table->integer('nilai_humidity'); // integer untuk humidity dalam bentuk presentase
            $table->timestamps(); // Menambahkan created_at dan updated_at
        });
    }
   
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_sensor_suhu');
    }
};