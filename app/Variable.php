<?php

namespace App;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    use Filterable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['variable', 'value', 'action_id'];

    /**
     * Get the action that owns the variable.
     */
    public function action()
    {
        return $this->belongsTo('App\Action');
    }
}
