<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Xe extends Model
{
    protected $table = "xe";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
