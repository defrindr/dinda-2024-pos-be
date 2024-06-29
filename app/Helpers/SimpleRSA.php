<?php

namespace App\Helpers;

class SimpleRSA
{
  protected $p;
  protected $q;
  protected $n;
  protected $etN;
  protected $facN;
  protected $publicKey;
  protected $privateKey;

  public function __construct($payload = null)
  {
    if ($payload) {
      list($n, $publicKey, $privateKey) = explode(":", $payload);
      $this->n = $n;
      $this->publicKey = $publicKey;
      $this->privateKey = $privateKey;
    } else {
      $this->p = $p ?? $this->getRandomPrimeFactor(1);
      $this->q = $q ?? $this->getRandomPrimeFactor(3);

      $this
        ->findModN()
        ->findEulerTotient()
        ->findPrimeFactorialOfN()
        ->findPublicKey(1024)
        ->findPrivateKey(512);
    }
  }


  public function encrypt($plainText)
  {
    $bytes = [];
    foreach (str_split($plainText) as $chr) {
      $ordChr = ord($chr);
      $byte = bcpowmod($ordChr, $this->publicKey, $this->n);
      $bytes[] = $byte;
    }
    return implode(":", $bytes);
  }

  public function decrypt($cipherText)
  {
    $bytes = explode(":", $cipherText);
    $text = [];
    foreach ($bytes as $byte) {
      $chr = bcpowmod($byte, $this->privateKey, $this->n);
      $text[] = chr($chr);
    }
    return implode("", $text);
  }

  public function getPublicKey()
  {
    return $this->publicKey;
  }

  public function getPrivateKey()
  {
    return $this->privateKey;
  }

  public function getN()
  {
    return $this->n;
  }

  public function getPayload()
  {
    return implode(":", [$this->n, $this->publicKey, $this->privateKey]);
  }

  private function getRandomPrimeFactor($indexOfPrime = null)
  {
    $currentTimestamp = time() % 100; // prevent number, not larger than 100

    if (!$indexOfPrime) $indexOfPrime = random_int(1, 100);

    $loop = true;
    while ($loop) {
      if ($this->isPrime($currentTimestamp)) {
        $indexOfPrime--;
        if ($indexOfPrime == 0) {
          $loop = false;
        } else {
          $currentTimestamp++;
        }
      } else {
        $currentTimestamp++;
      }
    }


    return $currentTimestamp;
  }

  private function findModN()
  {
    $this->n = $this->p * $this->q;
    return $this;
  }

  private function findEulerTotient()
  {
    $this->etN = ($this->p - 1) * ($this->q - 1);
    return $this;
  }

  private function findPrimeFactorialOfN()
  {
    $this->facN = $this->primeFactors($this->etN);
    return $this;
  }

  private function primeFactors($n)
  {
    $factors = [];
    while ($n % 2 == 0) {
      $factors[] = 2;
      $n /= 2;
    }

    for ($i = 3; $i <= sqrt($n); $i += 2) {
      while ($n % $i == 0) {
        $factors[] = $i;
        $n /= $i;
      }
    }

    if ($n > 2) {
      $factors[] = $n;
    }

    return array_unique($factors);
  }

  private function findPublicKey($indexOfPrime = null)
  {
    if (!$indexOfPrime) $indexOfPrime = random_int(1, 100);
    $loop = true;
    $i = max($this->facN);
    while ($loop) {
      if ($this->isPrime($i)) {
        if (!in_array($i, $this->facN)) {
          $indexOfPrime -= 1;
        }
      }

      if ($indexOfPrime == 0) {
        $loop = false;
      } else {
        $i++;
      }
    }

    $this->publicKey = $i;

    return $this;
  }

  private function findPrivateKey($index = null)
  {

    if ($index == null) $index = random_int(1, 100);
    // 𝑘𝑠 = (nx360+1)/7
    $privateKeys = [];
    $i = 0;
    while (count($privateKeys) < $index) {
      $privateKey = ($i * $this->publicKey) % $this->etN;
      if ($privateKey == 1) {
        $privateKeys[] = $i;
      }

      $i++;
    }

    $this->privateKey = $privateKeys[$index - 1];

    return $this;
  }

  private function isPrime(int $number)
  {
    for ($i = 2; $i <= sqrt($number); $i++) {
      if ($number % $i == 0) {
        return false;
      }
    }

    return true;
  }
}
