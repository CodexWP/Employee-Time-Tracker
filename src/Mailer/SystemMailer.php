<?php

namespace App\Mailer;
use Cake\Mailer\Mailer;
use Cake\Mailer\Email;
use Cake\Http\Exception\NotFoundException;


class SystemMailer extends Mailer
{
    private function setCustomSmtp($from_email,$from_name)
    {
        $model = $this->loadModel('Settings');
        $email_config = $model->find('all')->where(['name'=>'email_config'])->first();
        if($email_config && $email_config->value=='smtp')
        {
            $smtp_config = $model->find('all')->where(['name'=>'smtp_config'])->first();
            if($smtp_config)
            {
                $smtp = explode(',',$smtp_config->value);
                if($smtp[4]=='ssl' || $smtp[4]=='tls'){$tls=true;}else{$tls=false;}
                Email::setConfigTransport('smtp',[
                    'host' => $smtp[0],
                    'port' => $smtp[3],
                    'username' => $smtp[1],
                    'password' => $smtp[2],
                    'className' => 'Smtp',
                    'tls' => $tls
                ]);
                $this
                    ->setTransport('smtp')
                    ->setFrom($from_email,$from_name);
            }
            else
            {
                throw new NotFoundException(__('No smtp configuration is found. Go to settings menu and update email configuration.'));
            }
        }
        else
        {
            $this->setFrom($from_email,$from_name);
        }
    }

    public function preview($tpl, $config){
        return $this
            ->to($config['to_email'])
            ->setSubject(sprintf("Sample Subject"))
            ->setEmailFormat('html')
            ->setTemplate($tpl)
            ->setViewVars(['fields' => $config]);
    }

    public function testmail($config)
    {
        $this->setCustomSmtp($config['from_email'],$config['from_name']);
        $this
            ->setTo($config['to_email'])
            ->setEmailFormat('html')
            ->setSubject($config['subject'])
            ->setViewVars(['fields' => $config]);
    }

    public function invitemail($config)
    {
        $this->setCustomSmtp($config['from_email'],$config['from_name']);
        $this
            ->setTo($config['to_email'])
            ->setEmailFormat('html')
            ->setSubject($config['subject'])
            ->setViewVars(['fields' => $config]);
    }

}