<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDsoftFieldOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsoft_field_options', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->default(0);
            $table->string('field_id')->nullable();
            $table->string('title')->nullable();
            $table->string('value')->nullable();

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
        Schema::dropIfExists('dsoft_field_options');
    }
}
