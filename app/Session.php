<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * Get the action record associated with the user.
     */
    public function actions()
    {
        return $this->hasMany('App\Action');
    }
}
