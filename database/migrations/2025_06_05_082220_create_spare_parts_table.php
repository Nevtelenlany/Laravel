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
    Schema::create('spare_parts', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('part_number'); // cikkszám
        $table->unsignedInteger('stock_quantity');
        $table->decimal('unit_price', 10, 2)->unsigned();
        $table->text('note')->nullable();

        $table->foreignId('device_id')
              ->constrained()
              ->onUpdate('cascade')
              ->onDelete('cascade');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
