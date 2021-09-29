<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lotrinh extends Model
{
    protected $table = "lo_trinh";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
