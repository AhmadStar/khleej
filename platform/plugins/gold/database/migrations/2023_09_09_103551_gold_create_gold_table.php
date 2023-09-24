<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gold', function (Blueprint $table) {
            $table->id();
            $table->string('ounce', 255);
            $table->string('country', 255);
            $table->string('chart', 255);
            $table->string('label1', 512);
            $table->string('label2', 1024);
            $table->string('24_karat', 255);
            $table->string('22_karat', 255);
            $table->string('21_karat', 255);
            $table->string('18_karat', 255);
            $table->string('14_karat', 255);
            $table->text('html_table');
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('gold_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->integer('gold_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'gold_id'], 'gold_translations_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gold');
        Schema::dropIfExists('gold_translations');
    }
};
