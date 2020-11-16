<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_version_id')->index();
            $table->unsignedInteger('type_id')->index();
            $table->string('name')->index();
            $table->string('label');
            $table->json('meta')->nullable();
            $table->boolean('required')->default(false);
            $table->unsignedInteger('order');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('form_version_id')
                  ->references('id')
                  ->on('form_versions')
                  ->onDelete('cascade');

            $table->foreign('type_id')
                  ->references('id')
                  ->on('field_types')
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
        Schema::dropIfExists('fields');
        Schema::dropIfExists('field_types');
    }
}
