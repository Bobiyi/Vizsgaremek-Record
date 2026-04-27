<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    public $table='favourite';

    public $timestamps=false;

    public $incrementing=false;

    public $primaryKey=['user_id','record_id'];

    protected $fillable=['user_id','record_id'];
}
