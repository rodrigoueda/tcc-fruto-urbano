<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model {

	protected $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany('App\City');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public static function findOrCreate($data)
    {
        $state = self::where('name', $data['state'])->first();

        if (count($state) <= 0) {
            $state = new State();
            $state->name       = $data['state'];
            $state->country_id = Country::findOrCreate($data)->id;
            $state->save();
        }

        return $state;
    }
}
