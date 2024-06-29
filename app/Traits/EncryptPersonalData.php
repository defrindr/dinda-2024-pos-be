<?php

namespace App\Traits;

use App\Helpers\SimpleRSA;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;

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
            $encrypter = new SimpleRSA($keyEncryption);

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
            $encrypter = new SimpleRSA($keyEncryption);

            $value = $encrypter->encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getKeyEncryption()
    {
        if (property_exists($this, 'saltcolumn')) {
            if (!parent::getAttribute($this->saltcolumn)) {
                $keyEncryption = (new SimpleRSA())->getPayload();
                parent::setAttribute($this->saltcolumn, $keyEncryption);
            } else {
                $keyEncryption = parent::getAttribute($this->saltcolumn);
            }
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
                $encrypter = new SimpleRSA($keyEncryption);

                try {
                    $decrypted = $encrypter->decrypt($value);
                } catch (Exception $exception) {
                    $decrypted = $value;
                }

                $attributes[$key] = $decrypted;
            }
        }

        return $attributes;
    }
}
