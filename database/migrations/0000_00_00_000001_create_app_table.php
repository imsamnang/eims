<?php

use App\Models\ThemesColor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->text('en')->nullable();
            $table->text('km')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('location')->nullable();
            $table->string('website')->nullable();
            $table->text('logo')->nullable();
            $table->text('favicon')->nullable();
            $table->bigInteger('theme_color_id')->unsigned()->nullable();
            $table->foreign('theme_color_id')->references('id')->on((new ThemesColor())->getTable())->onDelete('cascade');
            $table->text('description')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('apps');
    }
}
