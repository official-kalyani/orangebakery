<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCustomizeCakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_customize_cakes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('is_photocake')->nullable();
            $table->string('product_type')->nullable();
            $table->string('image')->nullable();
            $table->string('message')->nullable();
            $table->integer('flavor_id')->nullable();
            $table->integer('gallery_id')->nullable();
            
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
        Schema::dropIfExists('user_customize_cakes');
    }
}
