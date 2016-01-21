<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Spatialpoint extends Model {

	protected $fillable = ['point', 'address', 'comments'];

    public function attributes()
    {
        return $this->hasMany('App\Attribute');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function geom($lat, $lon)
    {
        return DB::select("SELECT ST_GeomFromText('POINT({$lat} {$lon})', 4326) as geom")[0]->geom;
    }

    public static function getByBoundary($data) 
    {
        $extent = [$data['minX'], $data['minY'], $data['maxX'], $data['maxY']];
        $points = DB::select("
            SELECT ST_AsGeoJSON(point) as point, type, id, user_id = {$data['user']} as owner
            FROM spatialpoints
            WHERE ST_Contains(ST_MakeEnvelope(
                {$extent[0]},
                {$extent[1]},
                {$extent[2]},
                {$extent[3]},
                4326
            ), point)");

        $result = [];

        foreach ($points as $point) {
            $geoJson = new static;
            $geoJson->type = 'Feature';

            $geoJson->geometry = json_decode($point->point);

            $geoJson->properties = new static;
            $geoJson->properties->type = $point->type;
            $geoJson->properties->id = $point->id;
            $geoJson->properties->isOwner = $point->owner;

            array_push($result, $geoJson);
        }

        return $result;
    }

}
