<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class OmsyPHPMailer {
	private $mail;
	function __construct() {
		define('__OmsyMailer', $this);
	}

	private function getTemplate($obj) {
		$path = __Root . __OmsyMain->getTemplatePath() . 'OmsyMailerTemplate.html';
		if (!file_exists($path)) return '';

		$file = file_get_contents($path);
		preg_match_all('/{{([A-z0-9]+)}}/', $file, $output_array);

		$replaceBy = [];
		foreach ($output_array[1] as $k => $value) {
			$replaceBy[] = $obj[$value] ?? $output_array[0][$k];
			$file = str_replace($output_array[0][$k], ($obj[$value] ?? $output_array[0][$k]), $file);
		}
		echo $file;
		return $file;
	}

	public function send($adress, $subject, $body) {
		$this->mail = new PHPMailer(true);
		try {
			// Configuration serveur
			$this->mail->isSMTP();
			// $this->mail->SMTPDebug = SMTP::DEBUG_SERVER; // ou juste 2
			// $this->mail->Debugoutput = 'echo';
			$this->mail->Host = __OmsyMain->__Applications->smtp->host;
			$this->mail->SMTPAuth = true;
			$this->mail->Username = __OmsyMain->__Applications->smtp->username;
			$this->mail->Password = __OmsyMain->__Applications->smtp->password;
			$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$this->mail->Port = intval(__OmsyMain->__Applications->smtp->port);
			$this->mail->CharSet = 'UTF-8';
			$this->mail->Encoding = 'base64';

			$mail = $this->getTemplate($body);
			// Destinataires
			$this->mail->setFrom(__OmsyMain->__Applications->smtp->mail, 'Floagg Not Reply');
			$this->mail->addAddress($adress, 'Destinataire');
			$this->mail->isHTML(true);
			$this->mail->Subject = $subject;
			$this->mail->Body = $mail;
			$this->mail->AltBody =  $mail;

			$this->mail->send();
			return true;
		} catch (Exception $e) {
			return false;
		} 
	}
}