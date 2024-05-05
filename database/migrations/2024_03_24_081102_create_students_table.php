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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string("nrp", 20)->nullable(false)->unique("users_nrp_unique");
            $table->string("name", 100)->nullable(false);
            $table->string("address", 300)->nullable(false);
            $table->string("phone", 20)->nullable(false)->unique("users_phone_unique");
            $table->boolean("is_proposal_available")->nullable()->default(0);
           
            $table->foreignId("user_id")->nullable(false);
            $table->foreignId("invitation_id")->nullable();
            $table->foreignId("head_study_program_id")->nullable(false);
            $table->foreignId("proposal_id")->nullable();
            $table->foreignId("seminar_room_id")->nullable();

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
        Schema::dropIfExists('students');
    }
};