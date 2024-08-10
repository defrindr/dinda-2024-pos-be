<?php

namespace Tests\Unit;

use App\Helpers\SimpleRSA;
use PHPUnit\Framework\TestCase;

class RSATest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $plaintext = [
            'Bisma Nasrul Falahi',
            'Afifah Nurul',
            'Winarsih',
            '6287758842063',
            '3502085804820004',
            'Perempuan',
            'Dukuh Bajang Mlarak',
            'TRX20240805004543',
            'TRX20240805005217'
        ];
        $encrypted = [
            "326:365:145:8:202:280:39:202:145:166:65:147:280:281:202:147:202:13:365",
            "234:41:365:41:202:13:280:39:65:166:65:147",
            "87:365:384:202:166:145:365:13",
            "401:379:56:3:3:300:56:56:104:379:74:401:142",
            "142:300:74:379:74:56:300:56:74:104:56:379:74:74:74:104",
            "102:374:166:374:8:317:65:202:384",
            "68:65:81:65:13:280:326:202:115:202:384:51:280:116:147:202:166:202:81",
            "176:173:88:379:74:379:104:74:56:74:300:74:74:104:300:104:142",
            "176:173:88:379:74:379:104:74:56:74:300:74:74:300:379:257:3"
        ];
        $keys = [
            [403,    7,    103],
            [403,    7,    103],
            [403,    7,    103],
            [403,    7,    103],
            [403,    7,    103],
            [403,    7,    103],
            [403,    7,    103],
            [403,    7,    103],
            [403,    7,    103]
        ];

        $benar = 0;
        for ($i = 0; $i < count($keys); $i++) {
            $n = $keys[$i][0];
            $d = $keys[$i][1];
            $e = $keys[$i][2];
            $rsaInstance = new SimpleRSA("$n:$d:$e");

            if ($rsaInstance->encrypt($plaintext[$i]) == $encrypted[$i]) {
                $benar += 1;
                // echo "Data ke-$i valid";
            }
        }

        echo "Jumlah Testing data: " . count($keys) . " \n";
        echo "Akurasi: " . (($benar / count($keys)) * 100) . "% \n";
        $this->assertTrue($benar == count($keys), "RSA Benar Semua.");
    }
}
