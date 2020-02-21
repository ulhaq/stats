<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    /**
     * Get the action that owns the comment.
     */
    public function action()
    {
        return $this->belongsTo('App\Action');
    }
}
