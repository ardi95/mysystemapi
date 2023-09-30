<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('key_name');
            $table->string('name');
            $table->string('url')->nullable();
            $table->unsignedInteger('order_number')->default(1);
            $table->unsignedBigInteger('parent_menu_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['key_name', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
