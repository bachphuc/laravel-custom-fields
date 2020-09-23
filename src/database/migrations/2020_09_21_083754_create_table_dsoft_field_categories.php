<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDsoftFieldCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsoft_field_categories', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->default(0);
            $table->string('title')->nullable();
            $table->string('alias')->nullable();
            $table->string('object_type')->nullable();

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
        Schema::dropIfExists('dsoft_field_categories');
    }
}
