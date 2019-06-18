<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\I18n\Time;

/**
 * Common helper
 */
class CommonHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function uploadimage($tmpfile=array()){
    	try{
			$tmpname = $tmpfile['name'];
			$arr = explode(".",$tmpname);
			$ext = $arr[count($arr)-1];
			$name = $arr[0];
			if(!in_array($ext, ['jpg','png','jpeg','gif']))
				return array('status'=>'failed','result'=>'Invalid file format.');
	        $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
	        $filename = $name.'-'.rand(1,9999).'.'.$ext;
	        $upload_path = WWW_ROOT . 'img/uploads/';
			move_uploaded_file($tmpfile['tmp_name'], WWW_ROOT . 'img/uploads/' . $filename);
			$server_url = $protocol.'://'.$_SERVER['SERVER_NAME'];
			$script_url = $this->request->getAttribute('webroot');
			$image_url = $server_url.$script_url.'img/uploads/'.$filename;
			return ['status'=>'success','result'=>['url'=>$image_url,'name'=>$filename]];
		}
		catch(Exception $e)
		{
			return ['status'=>'failed','result'=>$e->getMessage()];
		}
	 }

	 public function generateinvitetoken($email,$companyid)
     {
         $expire = strtotime(Time::now()) + (60*60*24); //expire time 24 hours
         $token = array('email'=>$email,'company'=>$companyid,'exp'=>$expire);
         $token_json = json_encode($token);
         return $this->encryptdecrypt($token_json,'e');
     }

     public function getdatafrominvitetoken($token)
     {
         return json_decode($this->encryptdecrypt($token,'d'));
     }

    private function encryptdecrypt( $string, $action = 'e' ) {

        $secret_key = 'cake_enc_type_time';
        $secret_iv = 'cake_dec_type_time';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
        return $output;
    }

}
