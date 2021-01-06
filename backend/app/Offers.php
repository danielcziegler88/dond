<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    // use Authenticatable, Authorizable;

    public $table = 'offers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'board', 'boxesremaining', 'boxesopened', 'average', 'offer', 'percentofaverage', 'gameoverview', 'roundnum'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
    ];
}
