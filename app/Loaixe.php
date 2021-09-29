<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loaixe extends Model
{
    protected $table = "bus_model";
    protected $primaryKey = "Mã";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
