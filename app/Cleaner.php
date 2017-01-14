<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cleaner extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cleaners';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'quality_score','city_list', 'email'];

    public static $rules = array(
                              'first_name'=>'required|string', 
                              'last_name'=>'required|string', 
                              'quality_score'=>'required|numeric',
                              'email'=>'required|unique:cleaners',
                            );
}
