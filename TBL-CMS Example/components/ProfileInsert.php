<?php

class ProfileInsert extends CApplicationComponent
{
	public function insertRiderData($user, $userProfile)
	{
		$rider = new Riders;
		$rider->user_id = $user->primaryKey;
		$rider->full_name = $userProfile->firstName.' '.$userProfile->lastName;
		if($userProfile->age) $rider->age = $userProfile->age;
		else $rider->age = $this->findAge($userProfile->birthMonth .'/'. $userProfile->birthDay .'/'. $userProfile->birthYear);
		$rider->gender = $userProfile->gender;
		$rider->facebook = $userProfile->profileURL;
		//$userProfile->photoURL;
		
		if(!$rider->save())
			throw new Exception("Error, while saving data into Riders model:\n\n" . var_export($rider->errors, true));
			
		//Create password for user and email
		$newPassword = genRandomString();
		$user->setScenario('changePassword');
		$user->new_password = $newPassword;
		$user->new_password_repeat = $newPassword;
		if($user->save())
			$this->sendNewPassword($user, $newPassword);
		else
			throw new Exception("Error, while creating password for user:\n\n" . var_export($user->errors, true));
	}
	
	private function findAge($birthDate)
	{
		//date in mm/dd/yyyy format; or it can be in other formats as well
         //$birthDate = "12/17/1983";
         //explode the date to get month, day and year
         $birthDate = explode("/", $birthDate);
         //get age from date or birthdate
         $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y")-$birthDate[2])-1):(date("Y")-$birthDate[2]));
         return $age;
	}
	
	private function sendNewPassword($user, $newPassword)
	{
		$subject='=?UTF-8?B?'.base64_encode('New Password at '.gl('site_name')).'?=';
		//use 'contact' view from views/mail
		$mail = new YiiMailer('newPassword', array('user' => $user, 'newPassword' => $newPassword));
		//render HTML mail, layout is set from config file or with $mail->setLayout('layoutName')
		$mail->render();
		//set properties as usually with PHPMailer
		$mail->From = gl('admin_email');
		$mail->FromName = gl('site_name');
		$mail->Subject = $subject;
		$mail->AddAddress($user->email);
		//send
		if ($mail->Send())
			return true;
	}
}