<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDsoftFieldItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsoft_field_items', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->default(0);
            $table->string('field_id')->nullable();
            $table->string('item_type')->nullable();
            $table->integer('item_id')->default(0);
            $table->string('value')->nullable();
            $table->string('option_id')->nullable();
            $table->string('field_alias')->nullable();
            $table->string('search_values')->nullable();
            $table->string('search_ids')->nullable();

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
        Schema::dropIfExists('dsoft_field_items');
    }
}
