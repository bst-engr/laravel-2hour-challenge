<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Booking extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bookings';

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
    protected $fillable = ['date', 'customer_id', 'cleaner_id','city_id'];

    public static $rules = array(
                              'date'=>'required|date', 
                              'customer_id'=>'required|numeric', 
                              'cleaner_id'=>'required|numeric'
                            );

    
}
