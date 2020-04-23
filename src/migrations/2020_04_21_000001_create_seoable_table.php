<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seoable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('seoable_id')->unsigned();
            $table->string('seoable_type');
            $table->string('title')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->json('keywords')->nullable()->default(null);
            $table->string('follow_type')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            // $table->json('sociale')->nullable()->default(null);
            // $table->json('params')->nullable()->default(null);

            $table->unique(['seoable_id', 'seoable_type']);

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
        Schema::dropIfExists('seoable');
    }
}
