<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

	protected $fillable = ['name'];


    public function states()
    {
        return $this->hasMany('App\State');
    }

    public static function findOrCreate($data)
    {
        $country = self::where('name', $data['country'])->first();

        if (count($country) <= 0) {
            $country = new Country();
            $country->name = $data['country'];
            $country->save();
        }

        return $country;
    }
}
