<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_id')->index();
            $table->boolean('read')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onDelete('cascade');
        });

        Schema::create('enquiry_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('enquiry_id')->index();
            $table->unsignedInteger('field_id')->index();
            $table->json('value')->nullable();

            $table->foreign('enquiry_id')
                  ->references('id')
                  ->on('enquiries')
                  ->onDelete('cascade');

            $table->foreign('field_id')
                  ->references('id')
                  ->on('fields')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiry_contents');
        Schema::dropIfExists('enquiries');
    }
}
