<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model {

	public class spatialPoint() 
    {
        return $this->belongsTo('App\SpatialPoint');
    }

}
