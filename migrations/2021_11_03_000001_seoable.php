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
        if (!Schema::hasColumn('seoable', 'hide_in_sitemap')) {
            Schema::table('seoable', function (Blueprint $table) {
                $table->boolean('hide_in_sitemap')->after('page_type')->default(false);
            });
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('seoable', 'hide_in_sitemap')) {
            Schema::table('seoable', function (Blueprint $table) {
                $table->dropColumn('hide_in_sitemap');
            });
        }
    }
};
