<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistRecord extends Model
{
    public $table = 'artist_record';

    public $primaryKey=['artist_id','record_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable=['artist_id','record_id'];
}
