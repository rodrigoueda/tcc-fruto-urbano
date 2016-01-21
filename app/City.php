<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\State;

class City extends Model {

	protected $fillable = ['name'];

    public function spatialPoints()
    {
        return $this->hasMany('App\Spatialpoint');
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }

    public static function findOrCreate($data)
    {
        $city = self::where('name', $data['city'])->first();

        if (count($city) <= 0) {
            $city = new City();
            $city->name     = $data['city'];
            $city->state_id = State::findOrCreate($data)->id;
            $city->save();
        }

        return $city;
    }

}
