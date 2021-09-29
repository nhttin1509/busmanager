<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ve extends Model
{
    protected $table = "ve";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
