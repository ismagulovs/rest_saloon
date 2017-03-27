<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $table = 'salon';

    public $timestamps = false;

    public function bid()
    {
        return $this->hasMany('App\Bid', 'salon_id', 'id');
    }
}
