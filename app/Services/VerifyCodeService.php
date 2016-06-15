<?php namespace App\Services;

use Cache;

class VerifyCodeService{
    const CACHE_PREFIX = 'verify_code_';
    const CACHE_TTL = 1;

    public function setVerifyCodeForPhoneNumber(string $phone_number) : string{
        $code = $this->getRandomCode();
        $this->setVerifyCodeToCache($code, $phone_number);
        return $code;
    }

    public function testingVerifyCodeWithPhoneNumber(string $verify_code, string $phone_number) : bool {
        $cached_code = $this->getVerifyCodeFromCache($phone_number);
        return $verify_code == $cached_code;
    }

    private function cacheExist(string $phone_number) : bool {
        return !!$this->getVerifyCodeFromCache($phone_number);
    }

    private function setVerifyCodeToCache(string $verify_code, string $phone_number){
        Cache::put($this->getKey($phone_number), $verify_code, self::CACHE_TTL);
    }

    private function getVerifyCodeFromCache(string $phone_number) : string {
        return Cache::get($this->getKey($phone_number));
    }

    private function getKey(string $key) : string{
        return self::CACHE_PREFIX . $key;
    }

    private function getRandomCode(){
        return rand(1000, 9999);
    }
}