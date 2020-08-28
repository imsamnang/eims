<?php

use App\Models\Gender;
use App\Models\Marital;
use App\Models\Communes;
use App\Models\Villages;
use App\Models\Districts;
use App\Models\Institute;
use App\Models\Provinces;
use App\Models\BloodGroup;
use App\Models\MotherTong;
use App\Models\Nationality;
use App\Models\StaffStatus;
use App\Models\StaffDesignations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name_km')->nullable();
            $table->string('last_name_km')->nullable();
            $table->string('first_name_en')->nullable();
            $table->string('last_name_en')->nullable();
            $table->bigInteger('nationality_id')->unsigned()->nullable();
            $table->foreign('nationality_id')->references('id')->on((new Nationality())->getTable())->onDelete('cascade');
            $table->bigInteger('mother_tong_id')->unsigned()->nullable();
            $table->foreign('mother_tong_id')->references('id')->on((new MotherTong())->getTable())->onDelete('cascade');
            $table->integer('national_id')->nullable(); 
            $table->bigInteger('gender_id')->unsigned()->nullable();
            $table->foreign('gender_id')->references('id')->on((new Gender())->getTable())->onDelete('cascade');
            $table->date('date_of_birth')->nullable();
            $table->bigInteger('marital_id')->unsigned()->nullable();
            $table->foreign('marital_id')->references('id')->on((new Marital())->getTable())->onDelete('cascade');
            $table->bigInteger('blood_group_id')->unsigned()->nullable();
            $table->foreign('blood_group_id')->references('id')->on((new BloodGroup())->getTable())->onDelete('cascade');
            $table->bigInteger('pob_province_id')->unsigned()->nullable();
            $table->foreign('pob_province_id')->references('id')->on((new Provinces)->getTable())->onDelete('cascade');
            $table->bigInteger('pob_district_id')->unsigned()->nullable();
            $table->foreign('pob_district_id')->references('id')->on((new Districts)->getTable())->onDelete('cascade');
            $table->bigInteger('pob_commune_id')->unsigned()->nullable();
            $table->foreign('pob_commune_id')->references('id')->on((new Communes)->getTable())->onDelete('cascade');
            $table->bigInteger('pob_village_id')->unsigned()->nullable();
            $table->foreign('pob_village_id')->references('id')->on((new Villages)->getTable())->onDelete('cascade');

            $table->bigInteger('curr_province_id')->unsigned()->nullable();
            $table->foreign('curr_province_id')->references('id')->on((new Provinces)->getTable())->onDelete('cascade');
            $table->bigInteger('curr_district_id')->unsigned()->nullable();
            $table->foreign('curr_district_id')->references('id')->on((new Districts)->getTable())->onDelete('cascade');
            $table->bigInteger('curr_commune_id')->unsigned()->nullable();
            $table->foreign('curr_commune_id')->references('id')->on((new Communes)->getTable())->onDelete('cascade');
            $table->bigInteger('curr_village_id')->unsigned()->nullable();
            $table->foreign('curr_village_id')->references('id')->on((new Villages)->getTable())->onDelete('cascade');
            $table->bigInteger('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on((new Institute())->getTable())->onDelete('cascade');
            $table->bigInteger('designation_id')->unsigned()->nullable();
            $table->foreign('designation_id')->references('id')->on((new StaffDesignations())->getTable())->onDelete('cascade');
            $table->string('permanent_address')->nullable();
            $table->string('temporaray_address')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('extra_info')->nullable();
            $table->text('photo')->nullable();
            $table->bigInteger('staff_status_id')->unsigned()->nullable();
            $table->foreign('staff_status_id')->references('id')->on((new StaffStatus())->getTable())->onDelete('cascade');
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
        Schema::dropIfExists('staff');
    }
}
