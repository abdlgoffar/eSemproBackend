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
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string("number", 100)->nullable(false);
            $table->time('implementation_hours', $precision = 0)->nullable(false);
            $table->date('implementation_date')->nullable(false);

            $table->foreignId("seminar_id")->nullable(false);
            $table->foreignId("coordinator_id")->nullable(false);
    
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
};