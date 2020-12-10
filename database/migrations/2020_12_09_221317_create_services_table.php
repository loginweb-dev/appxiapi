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
            $table->foreignId('customer_location_id')->constrained('customer_locations');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->decimal('suggested_amount', 10, 2)->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->string('observations')->nullable();
            $table->integer('status')->nullable()->default(0);
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
