<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Drop index gabungan dulu baru ubah tipe
            // Nama index default dari Sanctum biasanya:
            // personal_access_tokens_tokenable_type_tokenable_id_index
            if (Schema::hasColumn('personal_access_tokens', 'tokenable_type')) {
                $table->dropIndex('personal_access_tokens_tokenable_type_tokenable_id_index');
            } else {
                // fallback (kalau nama index berbeda)
                $table->dropIndex(['tokenable_type', 'tokenable_id']);
            }

            // Ubah kolom jadi string (sesuaikan panjangnya; 16 cukup untuk USER001)
            $table->string('tokenable_id', 64)->change();

            // Recreate index gabungan
            $table->index(['tokenable_type', 'tokenable_id'], 'pat_tokenable_type_tokenable_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropIndex('pat_tokenable_type_tokenable_id_index');
            // Kembalikan ke BIGINT UNSIGNED (tipe default Sanctum)
            $table->unsignedBigInteger('tokenable_id')->change();
            $table->index(['tokenable_type', 'tokenable_id'], 'personal_access_tokens_tokenable_type_tokenable_id_index');
        });
    }
};
