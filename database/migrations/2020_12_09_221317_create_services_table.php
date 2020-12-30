<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types');
            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignId('service_location_id')->nullable()->constrained('service_locations');
            $table->decimal('suggested_amount', 10, 2)->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->string('observations')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->string('platform')->nullable();
            $table->string('details')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
