<?php

namespace App\Traits;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

/**
 * EncryptPersonalData
 *
 * You must declare encryptable contains all column you need to encrpyted
 * and you need to declare saltcolumn or keyEncryption
 *
 * @param  array  $encryptable
 * @param  string|null  $saltcolumn
 * @param  string|null  $keyEncryption
 */
trait EncryptPersonalData
{
    public function getAttribute($key)
    {
        if (in_array($key, $this->encryptable)) {
            $keyEncryption = $this->getKeyEncryption();
            $encrypter = new Encrypter(key: $keyEncryption, cipher: config('app.cipher'));

            if (parent::getAttribute($key)) {
                try {
                    $decrypted = $encrypter->decrypt(parent::getAttribute($key));
                } catch (DecryptException $exception) {
                    $decrypted = parent::getAttribute($key);
                }

                return $decrypted;
            }
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $keyEncryption = $this->getKeyEncryption();
            $encrypter = new Encrypter(key: $keyEncryption, cipher: config('app.cipher'));

            $value = $encrypter->encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getKeyEncryption()
    {
        if (property_exists($this, 'saltcolumn')) {
            if (! parent::getAttribute($this->saltcolumn)) {
                $keyEncryption = Str::random(22);
                parent::setAttribute($this->saltcolumn, base64_encode($keyEncryption));
            } else {
                $keyEncryption = parent::getAttribute($this->saltcolumn);
                $keyEncryption = base64_decode($keyEncryption);
            }

            // add prefix
            $keyEncryption .= 'ComeAndDie';
        } else {
            $keyEncryption = $this->keyEncryption;
        }

        return $keyEncryption;
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->encryptable)) {

                $keyEncryption = $this->getKeyEncryption();
                $encrypter = new Encrypter(key: $keyEncryption, cipher: config('app.cipher'));

                try {
                    $decrypted = $encrypter->decrypt($value);
                } catch (DecryptException $exception) {
                    $decrypted = $value;
                }

                $attributes[$key] = $decrypted;
            }
        }

        return $attributes;
    }
}
