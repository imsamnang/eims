<?php

use App\Models\Staff;
use App\Models\StaffCertificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_qualifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_id')->unsigned()->nullable();
            $table->foreign('staff_id')->references('id')->on((new Staff())->getTable())->onDelete('cascade');            
            $table->bigInteger('certificate_id')->unsigned()->nullable();
            $table->foreign('certificate_id')->references('id')->on((new StaffCertificate())->getTable())->onDelete('cascade');
            $table->text('extra_info')->nullable();
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
        Schema::dropIfExists('staff_qualifications');
    }
}
