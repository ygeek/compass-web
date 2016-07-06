<?php

namespace App;

class CoreRangeSetting{
    private $setting;

    public function __construct()
    {
        $setting = Setting::get('core_range');
        if(is_null($setting)){
            $setting = $this->newCoreRange();
        }

        $this->setting = $setting;
    }

    public function getSetting(){
        return $this->setting;
    }

    private function newCoreRange(){
        $countries = \App\AdministrativeArea::countries()->get();
        $degrees = \App\Degree::estimatable()->get();

        $countries->map(function ($country) use ($degrees){
            $obj = [];
            $obj['country_id'] = $country->id;
            $obj['country_name'] = $country->name;
            $obj['degrees'] = $degrees->map(function ($degree){
                $core_degree = [];
                $core_degree['degree_name'] = $degree->name;
                $core_degree['degree_id'] = $degree->id;
                $core_degree['core'] = [
                    'range' => null,
                    'count' => null
                ];
                $core_degree['sprint'] = [
                    'range' => null,
                    'count' => null
                ];
                return $core_degree;
            });

            return $obj;
        });
    }
}