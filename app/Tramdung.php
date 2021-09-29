<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tramdung extends Model
{
    protected $table = "tram_dung";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
