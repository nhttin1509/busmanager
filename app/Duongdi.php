<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Duongdi extends Model
{
    protected $table = "duong_di";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
