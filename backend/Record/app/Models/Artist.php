<?php

namespace App\Models;

use App\Models\Record;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Artist extends Model
{
    
    public $table = 'artist';

    public $timestamps = false;

    public $incrementing = true;

    public function records(): BelongsToMany {
        return $this->belongsToMany(Record::class, 'artist_record')->withPivot('role');
    }

    protected $casts = [
        'is_group' => 'boolean',
    ];

    public function getIconUrlAttribute() {
        if($this->icon_path) {
            return asset('storage/'.$this->icon_path);
        } else {
            return null;
        }
    }

    public function getCoverUrlAttribute() {
        if($this->cover_path) {
            return asset('storage/'.$this->cover_path);
        } else {
            return null;
        }
    }

    protected $fillable=[
        'name','active_since','nationality','url','is_group','icon_path','cover_path'
    ];
}
