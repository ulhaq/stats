<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /**
     * Get the session that owns the comment.
     */
    public function session()
    {
        return $this->belongsTo('App\Session');
    }

    /**
     * Get the variables for the blog post.
     */
    public function variables()
    {
        return $this->hasMany('App\Variable');
    }
}
