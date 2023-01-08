<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKabanCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kaban_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kaban_column_id');
            $table->string('title')->max(191);
            $table->boolean('status')->default(true);
            $table->longText('description')->max(2000);
            $table->softDeletes();
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
        Schema::dropIfExists('kaban_cards');
    }
}
