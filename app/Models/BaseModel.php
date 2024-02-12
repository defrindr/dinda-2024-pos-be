<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Mendapatkan nama tabel
     *
     * @return string
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
