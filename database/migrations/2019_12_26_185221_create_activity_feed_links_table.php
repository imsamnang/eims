<?php

use App\Models\ActivityFeed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityFeedLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_feed_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_feed_id')->unsigned();            
            $table->foreign('activity_feed_id')->references('id')->on((new ActivityFeed())->getTable())->onDelete('cascade');
            $table->text('title')->nullable();
            $table->string('type')->nullable();
            $table->enum('view', ['1', '2', '3'])->default(1);
            $table->text('link')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('code')->nullable();
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
        Schema::dropIfExists('activity_feed_links');
    }
}
