<?php namespace App\Services;

class SMSService{
    public function sendVerifyCode(string $code, string $phone_number, string $phone_country){
        if($phone_country == 'china') {
          $smsClient = new \MESSAGEXsend(config('sms_service'));
        }else {
          $smsClient = new \INTERNATIONALSMSXsend(config('sms_service'));
          switch ($phone_country) {
            case 'nzl':
              $phone_number = '+64'.$phone_number;
              break;
            case 'aus':
              $phone_number = '+61'.$phone_number;
              break;
            default:
              return;
          }
        }

        $smsClient->SetTo($phone_number);
        $smsClient->SetProject(config('sms_service.verify_code_template_id'));
        $smsClient->AddVar('code', $code);
        return $smsClient->xsend();
    }
}
