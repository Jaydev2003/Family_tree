<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data extends Model
{
    protected $fillable = [
        'name','email', 'phone','address', 'status','parent_id','gender','relation','kutumb_no'
    ];

  

    public function parent()
    {
        return $this->belongsTo(Data::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Data::class, 'parent_id');
    }

    public function wife()
    {
        return $this->hasOne(Data::class, 'parent_id')->where('relation', 'wife');
    }
    public function newchildren()
{
    return $this->hasMany(data::class, 'parent_id')->where('status', 'Unmarried')->where('gender', 'Male');
}

    protected $table = 'data';
}
