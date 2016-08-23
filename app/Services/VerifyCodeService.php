<?php namespace App\Services;

use Cache;

class VerifyCodeService{
    const CACHE_PREFIX = 'verify_code_';
    const CACHE_TTL = 1;

    public function setVerifyCodeForPhoneNumber($phone_number) {
        $code = $this->getRandomCode();
        $this->setVerifyCodeToCache($code, $phone_number);
        return $code;
    }

    public function testingVerifyCodeWithPhoneNumber($phone_number, $verify_code)  {
        try{
            $cached_code = $this->getVerifyCodeFromCache($phone_number);
            return $verify_code == $cached_code;
        }catch (\Exception $e){
            return false;
        }
    }
    
    private function setVerifyCodeToCache($verify_code, $phone_number){
        Cache::put($this->getKey($phone_number), $verify_code, self::CACHE_TTL);
    }

    private function getVerifyCodeFromCache($phone_number)  {
        $code = Cache::get($this->getKey($phone_number));
        if(!$code){
            throw new \Exception('code not found');
        }
        return $code;
    }

    private function getKey($key) {
        return self::CACHE_PREFIX . $key;
    }

    private function getRandomCode(){
        return rand(1000, 9999);
    }
}