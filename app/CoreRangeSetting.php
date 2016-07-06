<?php

namespace App;

class CoreRangeSetting{
    private $setting;

    public function __construct()
    {
        $this->loadSetting();
    }

    public function reload(){
        $this->loadSetting();
    }

    private function loadSetting(){
        $setting = Setting::get('core_range');
        if(is_null($setting)){
            $setting = $this->newCoreRange();
        }

        $this->setting = $setting;
    }

    public function getSetting(){
        return $this->setting;
    }

    public function getCoreRange($country_id, $degree_id){
        return $this->getCountryDegreeSetting($country_id, $degree_id)['core']['range'];
    }

    public function getCoreCount($country_id, $degree_id){
        return $this->getCountryDegreeSetting($country_id, $degree_id)['core']['count'];
    }

    public function getSprintCount($country_id, $degree_id){
        return $this->getCountryDegreeSetting($country_id, $degree_id)['sprint']['count'];
    }

    public function getSprintRange($country_id, $degree_id){
        return $this->getCountryDegreeSetting($country_id, $degree_id)['sprint']['range'];
    }

    private function getCountryDegreeSetting($country_id, $degree_id){
        foreach ($this->getSetting() as $country){
            if($country['country_id'] == $country_id){
                foreach ($country['degrees'] as $degree) {
                    if($degree['degree_id'] == $degree_id){
                        return $degree;
                    }
                };
            }
        }
        return null;
    }

    private function newCoreRange(){
        $countries = \App\AdministrativeArea::countries()->get();
        $degrees = \App\Degree::estimatable()->get();

        return $countries->map(function ($country) use ($degrees){
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
            })->toArray();

            return $obj;
        });
    }
}