<?php
namespace App\Mailer\Preview;

use DebugKit\Mailer\MailPreview;

class SystemMailPreview extends MailPreview
{
    public function preview()
    {
        $config = array();
        $config['from_email'] = 'test@gmail.com';
        $config['from_name'] = 'Time tracker';
        $config['to_email'] = 'fnfsoftbd@gmail.com';
        $config['subject'] = "Email Preview";

        $config['data'] = array('invite_token'=>'sdfdsfsdfsdf');
        return $this->getMailer("System")->preview('invitemail', $config);
    }
}