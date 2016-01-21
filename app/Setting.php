<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

	protected $fillable = [
        'index',
        'subindex',
        'parameter',
        'description',
        'value',
        'type'
    ];

    public static function get($key)
    {
        $key = explode('.', $key);

        if (!is_array($key) || count($key) != 3) {
            return;
        }

        return self::where('index', $key[0])
            ->where('subindex', $key[1])
            ->where('parameter', $key[2])
            ->firstOrFail()->value;
    }

}
