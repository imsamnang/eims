<?php

use App\Models\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocailsMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socails_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('app_id')->unsigned()->nullable();
            $table->foreign('app_id')->references('id')->on((new App())->getTable())->onDelete('cascade');
            $table->text('name')->nullable();
            $table->text('link')->nullable();
            $table->text('icon')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();            
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
        Schema::dropIfExists('socails_media');
    }
}
