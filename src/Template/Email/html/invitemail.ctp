<?php
$this->assign('subject',$fields['subject']);
?>
Hi,<br>
You have received an invitation from <?=$fields['from_name']?>
To complete the registration proccess <a target="_blank" href="<?=$this->Url->build('/register',true).'?invite_token='.$fields['data']['invite_token']?>">click here</a> or follow the bellow url.<br><br>
<?=$this->Url->build('/register',true).'?invite_token='.$fields['data']['invite_token']?>
<br>
<br>
Regards,<br>
<strong><?=$fields['from_name']?></strong>
