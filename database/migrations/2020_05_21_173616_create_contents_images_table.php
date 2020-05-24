<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('banner_message')->default('Welcome to karma stores');
            $table->string('banner_type')->default('success');
            $table->text('banner_images')->nullable();
            $table->text('promo_image')->nullable();
            $table->text('latest_feature')->nullable();
            $table->text('feature_images');
            $table->text('sponsors')->nullable();
            $table->integer('countdown_time')->nullable();
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
        Schema::drop('contents');
    }
}
