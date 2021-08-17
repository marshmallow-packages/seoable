<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pretty_urls', function (Blueprint $table) {
            $table->id();
            $table->text('original_url');
            $table->text('pretty_url');
            $table->boolean('is_canonical')->default(false);
            $table->boolean('should_redirect')->default(false);
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
        Schema::dropIfExists('pretty_urls');
    }
};
