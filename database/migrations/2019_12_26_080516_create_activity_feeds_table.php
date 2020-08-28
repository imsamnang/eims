<?php

use App\Models\Users;
use App\Models\ThemesColor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on((new Users())->getTable())->onDelete('cascade');
            $table->enum('type',['text','link','media','share'])->default('text');
            $table->text('post_message')->nullable();
            $table->bigInteger('theme_color_id')->unsigned()->nullable();
            $table->foreign('theme_color_id')->references('id')->on((new ThemesColor())->getTable())->onDelete('cascade');
            $table->enum('status',['delete','active'])->default('active');
            $table->enum('who_see',['public','only_me'])->default('public');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->nullable();

            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_feeds');
    }
}
