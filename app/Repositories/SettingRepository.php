<?php namespace App\Repositories;

class SettingRepository {

    public static function settingsFormat($settings)
    {
        $output = array();

        foreach ($settings as $setting) {
            $output[$setting->index][$setting->subindex][$setting->parameter] = array(
                'description' => $setting->description,      
                'value'       => $setting->value,
                'type'        => $setting->type
            );
        }
        return $output;
    }
}