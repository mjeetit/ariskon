<?php

class class_mailmanager extends Zend_Custom{

	public $_senderEmail = NULL;    // Sender of the Email
	public $_receiverEmail = NULL;   // Receiver of the Email
	public $_Sender	  = NULL;   // Receiver of the Email
	public $_receiver	  = NULL;   // Receiver of the Email
	public $_subject = NULL;	 // Subject of Email
	public $_mailObj = NULL;	 // Zend_mail Object
	public $_attachemnt = NULL;  //Attachement of the file
	public $_receiverName = NULL;  //Name Of email Receiver
	public $_reply = NULL;  //Reply Email Address
	public $_MailBody = NULL;
	public $_cc = array();
	public $_DataArray = array();

	public function SetEmailData(){

		$this->_senderEmail 	= $this->_DataArray['SenderEmail'];
		$this->_Sender 			= $this->_DataArray['SenderName'];
		$this->_receiverEmail 	= $this->_DataArray['ReceiverEmail'];
		$this->_receiver 		= $this->_DataArray['ReceiverName'];
		$this->_subject 		= $this->_DataArray['Subject'];
		$this->_MailBody 		= $this->_DataArray['Body'];
		$this->_attachemnt 		= $this->_DataArray['Attachment'];
		$this->_cc 		= $this->_DataArray['CC'];
		$this->Send();
	}

	public function LeaveRequestMail($body,$data){
	
		$this->_senderEmail 	= 'jiterder.maithani@gmail.com';//$data['email'];
		$this->_Sender 			= $data['Sender'];
		$this->_receiverEmail 	= 'jiterder.maithani@gmail.com';//$data['Appemail'];
		$this->_receiver 		= $data['Receiver'];
		$this->_subject 		= $body['subject'];
		$this->_MailBody 		= $body['contents'];
		$this->Send();
	}

	/*
	** Mail Config
	** function : MailConfig()
	** Description : Fetch the Config Detail and Configure The Emal
	**/
	public function MailConfig(){

		$select = $this->_db->select()
			->from('emailconfig',array('*'));
		$result = $this->getAdapter()->fetchRow($select);

		$config = array('auth' => 'login',
					'username' => $result['username'],
					'password' => $result['password'],
					'ssl' => $result['ssl'],
					'port' => $result['port']);
					$this->_noReply = $result['reply'];		   
		$transport = new Zend_Mail_Transport_Smtp($result['host_name'], $config);

		Zend_Mail::setDefaultTransport($transport);

		return;	
	}

	/*
	** Send Email
	** function : Send()
	** Description : This functon Create Object of Zend_mail and send email
	*/
	public function Send(){

		$allowed_ext = array (

			// archives
			'zip' => 'application/zip',

			// documents
			'pdf' => 'application/pdf',
			'doc' => 'application/msword',
			'txt' => 'application/txt',
			'LST' => 'application/txt',
			'csv' => 'application/csv',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',

			// executables
			// images
			'gif' => 'image/gif',
			'png' => 'image/png',
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',

			// audio
			'mp3' => 'audio/mpeg',
			'wav' => 'audio/x-wav',

			// video
			'mpeg' => 'video/mpeg',
			'mpg' => 'video/mpeg',
			'mpe' => 'video/mpeg',
			'mov' => 'video/quicktime',
			'avi' => 'video/x-msvideo'
		);

		try {

			$this->MailConfig();
			$this->_mailObj = new Zend_Mail();
			$this->_mailObj->setType(Zend_Mime::MULTIPART_RELATED);
			$this->_mailObj->setFrom ($this->_senderEmail,$this->_Sender); 
			$this->_mailObj->setReplyTo($this->_noReply,$this->_Sender);
			$this->_mailObj->addTo($this->_receiverEmail, $this->_receiver);

			if(!empty($this->_cc)){
				foreach($this->_cc as $cc){
					//$this->_mailObj->addBcc($cc);
					$this->_mailObj->addCc($cc,$cc);
				}
			}
			$this->_mailObj->addBcc('jiterder.maithani@gmail.com');
			$this->_mailObj->setSubject ($this->_subject);
			$this->_mailObj->setBodyHtml($this->_MailBody);

			if(!empty($this->_attachemnt)){//print_r('HI');die;

				$file_info = pathinfo($this->_attachemnt);
				
				if(array_key_exists($file_info['extension'],$allowed_ext))
				{ 

					$this->_mailObj->createAttachment(file_get_contents($this->_attachemnt), $allowed_ext[$file_info['extension']], Zend_Mime::DISPOSITION_INLINE , Zend_Mime::ENCODING_BASE64,basename($this->_attachemnt)); 
				}else{
		
					$_SESSION[ERROR_MSG] = 'Invalid Attachment Extenion';
				}
			}
			$this->_mailObj->send();
		}catch (Exception $e) {
			$_SESSION[ERROR_MSG] = $e->getMessage();
		} 
	}
}
?>