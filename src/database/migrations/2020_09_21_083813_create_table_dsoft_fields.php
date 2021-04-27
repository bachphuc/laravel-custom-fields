<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDsoftFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsoft_fields', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->default(0);
            $table->string('field_type')->nullable();
            $table->string('title')->nullable();
            $table->string('alias')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('category_alias')->nullable();
            $table->string('object_type')->nullable();
            $table->tinyInteger('is_disabled')->default(0);
            $table->string('options')->nullable();
            $table->string('data_type')->nullable();

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
        Schema::dropIfExists('dsoft_fields');
    }
}
