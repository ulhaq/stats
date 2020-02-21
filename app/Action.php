<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['location', 'action', 'target', 'session_id'];

    /**
     * Get the session that owns the action.
     */
    public function session()
    {
        return $this->belongsTo('App\Session');
    }

    /**
     * Get the variables for the action.
     */
    public function variables()
    {
        return $this->hasMany('App\Variable');
    }
}
