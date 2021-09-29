<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nhanvien extends Model
{
    protected $table = "employee";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
