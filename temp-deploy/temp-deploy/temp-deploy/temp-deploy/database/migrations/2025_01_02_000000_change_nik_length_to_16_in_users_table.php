<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Langkah 1: Hapus unique constraint sementara untuk menghindari konflik
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nik']);
        });
        
        // Langkah 2: Potong NIK yang lebih dari 16 digit menjadi 16 digit pertama
        DB::statement('UPDATE users SET nik = SUBSTRING(nik, 1, 16) WHERE LENGTH(nik) > 16');
        
        // Langkah 3: Handle duplikasi jika ada (ambil 15 digit + suffix)
        // Cek apakah ada duplikasi setelah dipotong
        $duplicates = DB::table('users')
            ->select('nik', DB::raw('COUNT(*) as count'))
            ->groupBy('nik')
            ->having('count', '>', 1)
            ->get();
        
        foreach ($duplicates as $duplicate) {
            $users = DB::table('users')->where('nik', $duplicate->nik)->orderBy('id')->get();
            
            // User pertama tetap menggunakan NIK yang dipotong
            // User berikutnya tambahkan suffix
            for ($i = 1; $i < count($users); $i++) {
                $user = $users[$i];
                $baseNik = substr($user->nik, 0, 15);
                $suffix = str_pad($i, 1, '0', STR_PAD_LEFT);
                $newNik = $baseNik . $suffix;
                
                // Pastikan NIK baru tidak duplikat
                while (DB::table('users')->where('nik', $newNik)->where('id', '!=', $user->id)->exists()) {
                    $suffix = str_pad(($i + 1), 2, '0', STR_PAD_LEFT);
                    $newNik = substr($user->nik, 0, 14) . $suffix;
                }
                
                DB::table('users')->where('id', $user->id)->update(['nik' => $newNik]);
            }
        }
        
        // Langkah 4: Ubah ukuran kolom menjadi 16
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->change();
        });
        
        // Langkah 5: Tambahkan kembali unique constraint
        Schema::table('users', function (Blueprint $table) {
            $table->unique('nik');
        });
    }

    public function down(): void
    {
        // Hapus unique constraint
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nik']);
        });
        
        // Kembalikan ukuran kolom ke 18
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 18)->change();
        });
        
        // Tambahkan kembali unique constraint
        Schema::table('users', function (Blueprint $table) {
            $table->unique('nik');
        });
    }
};
