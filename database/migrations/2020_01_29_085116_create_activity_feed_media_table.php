<?php

use App\Models\ActivityFeed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityFeedMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_feed_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_feed_id')->unsigned();
            $table->foreign('activity_feed_id')->references('id')->on((new ActivityFeed())->getTable())->onDelete('cascade');
            $table->enum('type',['image','video'])->nullable();
            $table->text('source')->nullable();
            $table->string('title')->nullable();
            $table->string('original_name')->nullable();
            $table->text('poster')->nullable();
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
        Schema::dropIfExists('activity_feed_media');
    }
}
