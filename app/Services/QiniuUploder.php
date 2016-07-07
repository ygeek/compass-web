<?php
/**
 * Created by PhpStorm.
 * User: chareice
 * Date: 16-7-7
 * Time: 16:12
 */

namespace App\Services;

use Qiniu\Client;

class QiniuUploder{

    protected $client;

    protected $bucket;

    protected $params;

    protected $domain;

    public function __construct(){
        $params = [
            'access_key' => config('qiniu.access_key'),
            'secret_key' => config('qiniu.secret_key')
        ];
        $this->client = new Client($params);
        $this->bucket = config('qiniu.bucket');
        $this->domain = config('qiniu.domain');

        $this->params = [
            'scope' => $this->bucket,
            'returnBody' => '{
                                "key": $(key),
                                "name": $(fname),
                                "size": $(fsize),
                                "type": $(mimeType),
                                "hash": $(etag)
                             }',
            'expires' => 3600
        ];
    }

    public function upload_file($file){
        $uuid = $this->gen_uuid();

        list($return, $error) = $this->client->putFile($this->bucket, $uuid.".".$file->guessClientExtension(), [
            'file' => $file->getRealPath()
        ], $this->params);

        if ($error !== null) {
            return false;
        }else{
            $key = $return['key'];
            $return['path'] = $this->client->getPublicUrl($this->domain, $key);
            return $return;
        }
    }

    public function pathOfKey($key){
        return $this->client->getPublicUrl($this->domain, $key);
    }

    private function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}