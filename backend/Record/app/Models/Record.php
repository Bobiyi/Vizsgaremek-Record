<?php

namespace App\Models;

use App\Models\Artist;
use App\Models\RecordType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Record extends Model
{
    
    public $table = 'record';

    public $timestamps = false;

    public $incrementing = true;
    
    protected $appends = ['image_url'];

    public function getImageUrlAttribute() {
        if($this->file_path) {
            return asset('storage/'.$this->file_path);
        } else {
            return null;
        }
    }


   public function type(): BelongsTo {
    return $this->BelongsTo(RecordType::class,"type_id");
   }
    
    public function artists(): BelongsToMany {
        return $this->belongsToMany(Artist::class,'artist_record')->withPivot('role');
    }

    public function favourites(): BelongsToMany {
        return $this->belongsToMany(User::class,'favourite','record_id','user_id');
    }

    public function getTypeNameAttribute() {
        return $this->type ? $this->type->type_name : null;
    }

    protected $fillable = [
        'name','type_id','release_year','length','file_path'
    ];
}
