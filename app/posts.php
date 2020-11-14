<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    public function comments()
    {
        return $this->hasMany('App\comentarios');
    }
    public function user()
    {
        return $this->hasOne('App\User');
    }
    protected $fillable = [
        'titulo','user_id','descripcion','foto'
    ];
}
