<?php


use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Matakuliah;
use App\Models\Dosen;
use App\Models\Ruangan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('code_token');
            $table->string('Kode_Kelas');
            $table->integer('jumlah_pertemuan')->nullable()->default(0);
            $table->foreignId('prodi_id')->references('id')->on('prodi')->constrained()->cascadeOnDelete();
            $table->foreignId('matakuliah_id')->references('id')->on('matakuliah')->constrained()->cascadeOnDelete();
            $table->foreignId('dosen_id')->references('id')->on('dosen')->constrained()->cascadeOnDelete();
            $table->foreignId('ruangan_id')->references('id')->on('ruangan')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
