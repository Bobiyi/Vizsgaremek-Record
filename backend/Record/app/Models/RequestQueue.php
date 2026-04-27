<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQueue extends Model
{
    public $table='request';

    public $incrementing=true;

    public $timestamps=false;

    protected $fillable= 
    [
        'user_id','type','payload','status','admin_note','reviewed_at'
    ];

    protected $casts=
    [
        'payload'=>'array',
        'reviewed_at'=>'datetime',
        'created_at'=>'datetime'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
