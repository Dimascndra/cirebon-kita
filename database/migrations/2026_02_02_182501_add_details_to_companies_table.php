<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->text('description')->nullable()->after('website');
            $table->string('industry')->nullable()->after('description');
            $table->text('address')->nullable()->after('industry');
            $table->string('email')->nullable()->after('address');
            $table->string('phone')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['description', 'industry', 'address', 'email', 'phone']);
        });
    }
};
