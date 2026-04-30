<?php

use App\Models\Pengaturan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_usaha');
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->text('catatan_footer')->nullable();
            $table->timestamps();
        });

        // Insert default data
        Pengaturan::create([
            'nama_usaha' => 'Eca-Print',
            'alamat' => 'Jl. Percetakan No. 123',
            'no_hp' => '08123456789',
            'email' => 'contact@eca-print.com',
            'catatan_footer' => 'Terima kasih telah memesan di Eca-Print.',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
