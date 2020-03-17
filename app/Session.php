<?php

namespace App;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['visitor', 'client', 'platform'];

    /**
     * Get the actions for the session.
     */
    public function actions()
    {
        return $this->hasMany('App\Action');
    }

    /**
     * Get all of the variable for the session.
     */
    public function variables()
    {
        return $this->hasManyThrough('App\Variable', 'App\Action');
    }
}
