<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tinh extends Model
{
    protected $table = "tinh";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
