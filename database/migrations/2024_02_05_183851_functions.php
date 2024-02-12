<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS LaravelAES');
        DB::statement("
            CREATE FUNCTION LaravelAES(
                encryption blob, 
                salt blob, 
                suffix VARCHAR(255)
            ) RETURNS TEXT RETURN AES_DECRYPT(
                FROM_BASE64(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                    CONVERT(
                        FROM_BASE64(encryption) USING utf8
                    ), 
                    '$.value'
                    )
                )
                ), 
                CONCAT(
                FROM_BASE64(salt), 
                suffix
                ), 
                FROM_BASE64(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                    CONVERT(
                        FROM_BASE64(encryption) USING utf8
                    ), 
                    '$.iv'
                    )
                )
                )
            );          
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
