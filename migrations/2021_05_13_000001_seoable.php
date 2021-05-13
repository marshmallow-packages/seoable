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
        Schema::table('pretty_urls', function (Blueprint $table) {
            $table->string('name')->after('id')->nullable()->default(null);
            $table->json('seoable_content')->after('should_redirect')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pretty_urls', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('seoable_content');
        });
    }
};
