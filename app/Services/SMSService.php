<?php namespace App\Services;

class SMSService
{
    public function sendVerifyCode(string $code, string $phone_number, string $phone_country)
    {
        if ($phone_country == 'china') {
            $smsClient = new \MESSAGEXsend(config('sms_service'));
            $smsClient->SetProject(config('sms_service.verify_code_template_id'));
        } else {
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

            $smsClient = new \INTERNATIONALSMSXsend(config('sms_service_int'));

            $smsClient->SetProject(config('sms_service_int.verify_code_template_id'));
        }

        $smsClient->SetTo($phone_number);

        $smsClient->AddVar('code', $code);
        $xsend = $smsClient->xsend();

        echo "<pre>";
        print_r($xsend);
        echo "</pre>";

        return $xsend;
    }
}
