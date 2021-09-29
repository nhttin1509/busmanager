<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chuyenxe extends Model
{
    protected $table = "chuyen_xe";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
