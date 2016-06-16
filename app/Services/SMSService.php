<?php namespace App\Services;

class SMSService{
    public function sendVerifyCode(string $code, string $phone_number){
        $smsClient = new \MESSAGEXsend(config('sms_service'));
        $smsClient->SetTo($phone_number);
        $smsClient->SetProject(config('sms_service.verify_code_template_id'));
        $smsClient->AddVar('code', $code);
        return $smsClient->xsend();
    }
}