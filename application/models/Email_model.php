<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Email_model extends CI_Model {

	protected $school_id;
	protected $active_session;

	public function __construct()
	{
		parent::__construct();
		$this->school_id = school_id();
		$this->active_session = active_session();
	}

	function account_opening_email($account_type = '' , $email = '', $password = '')
	{
		$system_name	=	get_settings('system_name');

		$email_msg		=	"Welcome to ".$system_name."<br />";
		$email_msg		.=	"Your account type : ".$account_type."<br />";
		$email_msg		.=	"Your login password : ". $password ."<br />";
		$email_msg		.=	"Login Here : ".base_url()."<br />";

		$email_sub		=	"Account opening email";
		$email_to		=	$email;

		if (get_smtp('mail_sender') == 'php_mailer') {
			$this->send_mail_using_php_mailer($email_msg , $email_sub , $email_to);
		}else{
			$this->send_mail_using_smtp($email_msg , $email_sub , $email_to);
		}
	}

	function password_reset_email($new_password = '' , $user_id = "")
	{
		$query			=	$this->db->get_where('users' , array('id' => $user_id))->row_array();
		if(sizeof($query) > 0)
		{

			$email_msg	=	"Your account type is : ".ucfirst($query['role'])."<br />";
			$email_msg	.=	"Your password is : ".$new_password."<br />";

			$email_sub	=	"Password reset request";
			$email_to	=	$query['email'];

			if (get_smtp('mail_sender') == 'php_mailer') {
				$this->send_mail_using_php_mailer($email_msg , $email_sub , $email_to);
			}else{
				$this->send_mail_using_smtp($email_msg , $email_sub , $email_to);
			}
			return true;
		}
		else
		{
			return false;
		}
	}
	function password_reset_email_link($link = '' , $user_id = "")
	{
		$query			=	$this->db->get_where('users' , array('id' => $user_id))->row_array();
		if(sizeof($query) > 0)
		{
			$systemEmail = get_settings('system_email');
			$email_msg = <<<HTML
			<!DOCTYPE html>
			<html lang="fr">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>Reset your password</title>
				<style>
					body { 
						margin: 0; 
						padding: 0; 
						font-family: Arial, Helvetica, sans-serif; 
					}
					.container { 
						background: #ECECEC; 
						padding: 30px 15px; 
					}
					.logo { 
						text-align: center; 
						margin-bottom: 20px; 
					}
					.logo img { 
						max-width: 150px; 
						height: auto; 
					}
					.content-table { 
						max-width: 700px; 
						width: 100%; 
						margin: 0 auto; 
						border-collapse: collapse; 
					}
					.header { 
						background: #FC7B30; 
						border-radius: 15px 15px 0 0; 
						padding: 15px; 
						text-align: center; 
					}
					.header span { 
						font-size: 20px; 
						font-weight: bold; 
						color: #fff; 
					}
					.body { 
						background: #f0f7fa; 
						padding: 20px; 
					}
					.body p { 
						font-size: 14px; 
						color: #000; 
						line-height: 1.5; 
						margin: 15px 0; 
					}
					.body a { 
						color: #0047AB; 
						text-decoration: none; 
					}
					.button {
						display: inline-block;
						padding: 15px 25px;
						font-size: 16px;
						color: #fff !important;
						background-color: #FC7B30;
						text-decoration: none;
						border-radius: 5px;
						margin: 20px 0;
						transition: background-color 0.3s ease;
						font-weight: bold;
					}
					.button:hover {
						background-color: #B8701F;
					}
					.button-container {
						text-align: center;
					}
					.footer { 
						background: #FC7B30; 
						border-radius: 0 0 15px 15px; 
						color: #fff; 
						font-size: 14px; 
					}
					.footer table { 
						width: 100%; 
					}
					.footer td { 
						padding: 10px; 
					}
					@media only screen and (max-width: 600px) {
						.content-table { 
							width: 100% !important; 
						}
						.header span { 
							font-size: 18px; 
						}
						.footer td { 
							display: block; 
							text-align: center !important; 
							width: 100% !important; 
						}
					}
				</style>
			</head>
			<body>
				<div class="container">
					<div class="logo">
						<img src="https://wayo.academy/uploads/system/logo/logo-light.png" alt="Wayo Academy Logo">
					</div>
					<table class="content-table">
						<tr>
							<td class="header">
								<span>Password Reset</span>
							</td>
						</tr>
						<tr>
							<td class="body">
								<p><strong>Hello {$query['name']},</strong></p>
								<p>You have requested a password reset. If you initiated this request, please click the link below to create a new password:</p>
								<div class="button-container">
									<a href="{$link}" class="button">Reset My Password</a>
								</div>
								<p><strong>This link is valid for 24 hours.</strong></p>
								<p>If you did not request this password reset, please ignore this email. Your current password will remain unchanged and your account remains secure.</p>
								<p>If you have any questions or need assistance, please feel free to contact our support team at: <a href="mailto:{$systemEmail}">{$systemEmail}</a>.</p>
							</td>
						</tr>
						<tr>
							<td class="footer">
								<table>
									<tr>
										<td style="text-align: left; width: 33%;">
											<a href="https://wayo.academy/home/contact#map" style="color: #fff; text-decoration: none;">R320 Umm Hurair 2 Dubai UAE</a>
										</td>
										<td style="text-align: center; width: 34%;">
											© 2025 Wayo Academy. All rights reserved.
										</td>
										<td style="text-align: right; width: 33%;">
											Tel: <a href="tel:+971501548923" style="color: #fff; text-decoration: none;">+971 50 154 8923</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</body>
			</html>
			HTML;


			$email_sub	=	"Password reset request";
			$email_to	=	$query['email'];

			if (get_smtp('mail_sender') == 'php_mailer') {
				$this->send_mail_using_php_mailer($email_msg , $email_sub , $email_to);
			}else{
				$this->send_mail_using_smtp($email_msg , $email_sub , $email_to);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	function password_send_add_student($link = '', $user_id = "") {
    $query = $this->db->get_where('users', array('id' => $user_id))->row_array();
    
    if (!empty($query)) {
        $systemEmail = get_settings('system_email');
        $email_msg = <<<HTML
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Add Password</title>
			<style>
				body { 
					margin: 0; 
					padding: 0; 
					font-family: Arial, Helvetica, sans-serif; 
				}
				.container { 
					background: #ECECEC; 
					padding: 30px 15px; 
				}
				.logo { 
					text-align: center; 
					margin-bottom: 20px; 
				}
				.logo img { 
					max-width: 150px; 
					height: auto; 
				}
				.content-table { 
					max-width: 700px; 
					width: 100%; 
					margin: 0 auto; 
					border-collapse: collapse; 
				}
				.header { 
					background: #FC7B30; 
					border-radius: 15px 15px 0 0; 
					padding: 15px; 
					text-align: center; 
				}
				.header span { 
					font-size: 20px; 
					font-weight: bold; 
					color: #fff; 
				}
				.body { 
					background: #f0f7fa; 
					padding: 20px; 
				}
				.body p { 
					font-size: 14px; 
					color: #000; 
					line-height: 1.5; 
					margin: 15px 0; 
				}
				.body a { 
					color: #0047AB; 
					text-decoration: none; 
				}
				.button {
					display: inline-block;
					padding: 15px 25px;
					font-size: 16px;
					color: #fff !important;
					background-color: #FC7B30;
					text-decoration: none;
					border-radius: 5px;
					margin: 20px 0;
					transition: background-color 0.3s ease;
					font-weight: bold;
				}
				.button:hover {
					background-color: #B8701F;
				}
				.button-container {
					text-align: center;
				}
				.footer { 
					background: #FC7B30; 
					border-radius: 0 0 15px 15px; 
					color: #fff; 
					font-size: 14px; 
				}
				.footer table { 
					width: 100%; 
				}
				.footer td { 
					padding: 10px; 
				}
				@media only screen and (max-width: 600px) {
					.content-table { 
						width: 100% !important; 
					}
					.header span { 
						font-size: 18px; 
					}
					.footer td { 
						display: block; 
						text-align: center !important; 
						width: 100% !important; 
					}
				}
			</style>
		</head>
		<body>
			<div class="container">
				<div class="logo">
					<img src="https://wayo.academy/uploads/system/logo/logo-light.png" alt="Wayo Academy Logo">
				</div>
				<table class="content-table">
					<tr>
						<td class="header">
							<span>Add Password</span>
						</td>
					</tr>
					<tr>
						<td class="body">
							<p><strong>Hello {$query['name']},</strong></p>
							<p>We received a request to add your password for your account. You can add your password by clicking the link below:</p>
							<div class="button-container">
								<a href="{$link}" class="button">Set Password</a>
							</div>
							<p><strong>This link is valid for 24 hours.</strong></p>
							<p>If you did not request a password reset, please ignore this email. Your current password will remain unchanged and your account remains secure.</p>
							<p>If you have any questions or need assistance, please feel free to contact our support team at: <a href="mailto:{$systemEmail}">{$systemEmail}</a>.</p>
						</td>
					</tr>
					<tr>
						<td class="footer">
							<table>
								<tr>
									<td style="text-align: left; width: 33%;">
										<a href="https://wayo.academy/home/contact#map" style="color: #fff; text-decoration: none;">R320 Umm Hurair 2 Dubai UAE</a>
									</td>
									<td style="text-align: center; width: 34%;">
										© 2025 Wayo Academy. All rights reserved.
									</td>
									<td style="text-align: right; width: 33%;">
										Tel: <a href="tel:+971501548923" style="color: #fff; text-decoration: none;">+971 50 154 8923</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</body>
		</html>
		HTML;

        $email_sub = "Password reset request";
        $email_to = $query['email'];

        // Encode subject to handle special characters
        $email_sub = mb_encode_mimeheader($email_sub, 'UTF-8', 'B');

        if (get_smtp('mail_sender') == 'php_mailer') {
            return $this->send_mail_using_php_mailer($email_msg, $email_sub, $email_to);
        } else {
            return $this->send_mail_using_smtp($email_msg, $email_sub, $email_to);
        }
    } else {
        return false;
    }
}

	function contact_message_email($email_from, $email_to, $email_message) {
		$email_sub = "Message from School Website";

		if (get_smtp('mail_sender') == 'php_mailer') {
			$this->send_mail_using_php_mailer($email_message, $email_sub, $email_to, $email_from);
		}else{
			$this->send_mail_using_smtp($email_message, $email_sub, $email_to, $email_from);
		}
	}

	function personal_message_email($email_from, $email_to, $email_message) {
		$email_sub = "Message from School Website";

		if (get_smtp('mail_sender') == 'php_mailer') {
			$this->send_mail_using_php_mailer($email_message, $email_sub, $email_to, $email_from);
		}else{
			$this->send_mail_using_smtp($email_message, $email_sub, $email_to, $email_from);
		}
	}

	function request_book_email($student_id){
		$student_details = $this->user_model->get_student_details_by_id('student', $student_id);
		$student_name = $student_details['name'];
		$student_code = $student_details['code'];
		$email_message  = '<html><body><p>'.$student_name.' has been requested you, for the book.'.'</p><br><p>Student Code : '.$student_code.'</p></body></html>';
		$email_sub		= 'New book issued';
		$this->db->limit(1);
		$librarians = $this->db->get('librarian')->result_array();
		foreach($librarians as $librarian){
			$email_to = $librarian['email'];
		}
		$this->send_mail_using_smtp($email_message, $email_sub, $email_to);
	}
	function Add_online_admission($email = "", $user_id = "",$name = ""){

		$school_data = $this->settings_model->get_current_school_data();
		$image_url = "http://51.92.7.185/uploads/schools/".$school_data['id'].".jpg"; // URL de l'image à côté des informations
		$systemEmail = get_settings('system_email');
		$email_message = <<<HTML
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Registration Student</title>
			<style>
				body { 
					margin: 0; 
					padding: 0; 
					font-family: Arial, Helvetica, sans-serif; 
				}
				.container { 
					background: #ECECEC; 
					padding: 30px 15px; 
				}
				.logo { 
					text-align: center; 
					margin-bottom: 20px; 
				}
				.logo img { 
					max-width: 150px; 
					height: auto; 
				}
				.content-table { 
					max-width: 700px; 
					width: 100%; 
					margin: 0 auto; 
					border-collapse: collapse; 
				}
				.header { 
					background: #FC7B30; 
					border-radius: 15px 15px 0 0; 
					padding: 15px; 
					text-align: center; 
				}
				.header span { 
					font-size: 20px; 
					font-weight: bold; 
					color: #fff; 
				}
				.body { 
					background: #f0f7fa; 
					padding: 20px; 
				}
				.body p { 
					font-size: 14px; 
					color: #000; 
					line-height: 1.5; 
					margin: 15px 0; 
				}
				.body a { 
					color: #0047AB; 
					text-decoration: none; 
				}
				.button {
					display: inline-block;
					padding: 15px 25px;
					font-size: 16px;
					color: #fff !important;
					background-color: #FC7B30;
					text-decoration: none;
					border-radius: 5px;
					margin: 20px 0;
					transition: background-color 0.3s ease;
					font-weight: bold;
				}
				.button:hover {
					background-color: #B8701F;
				}
				.button-container {
					text-align: center;
				}
				.info-container {
					display: flex;
					align-items: center;
					flex-wrap: wrap;
				}
				.info-image {
					width: 100%;
					max-width: 200px;
					margin-right: 38px;
					margin-bottom: 20px;
				}
				.info-content {
					flex: 1;
					min-width: 300px;
				}
				.footer { 
					background: #FC7B30; 
					border-radius: 0 0 15px 15px; 
					color: #fff; 
					font-size: 14px; 
				}
				.footer table { 
					width: 100%; 
				}
				.footer td { 
					padding: 10px; 
				}
				@media only screen and (max-width: 600px) {
					.content-table { 
						width: 100% !important; 
					}
					.header span { 
						font-size: 18px; 
					}
					.footer td { 
						display: block; 
						text-align: center !important; 
						width: 100% !important; 
					}
					.info-container {
						flex-direction: column;
					}
					.info-image {
						margin-right: 0;
						text-align: center;
					}
				}
			</style>
		</head>
		<body>
			<div class="container">
				<div class="logo">
					<img src="https://wayo.academy/uploads/system/logo/logo-light.png" alt="Wayo Academy Logo">
				</div>
				<table class="content-table">
					<tr>
						<td class="header">
							<span>Registration Student</span>
						</td>
					</tr>
					<tr>
						<td class="body">
							<div class="info-container">
								<div class="info-content">
									<p>Your registration has been made.</p>
									<p><strong>Name:</strong> {$name}</p>
									<p><strong>Email:</strong> {$email}</p>
									<p>If you have any questions or need assistance, please feel free to contact our support team at: <a href="mailto:{$systemEmail}">{$systemEmail}</a>.</p>
									<a href="http://51.92.7.185/home/courses" class="button">Login to Your Account</a>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="footer">
							<table>
								<tr>
									<td style="text-align: left; width: 33%;">
										<a href="https://wayo.academy/home/contact#map" style="color: #fff; text-decoration: none;">R320 Umm Hurair 2 Dubai UAE</a>
									</td>
									<td style="text-align: center; width: 34%;">
										© 2025 Wayo Academy. All rights reserved.
									</td>
									<td style="text-align: right; width: 33%;">
										Tel: <a href="tel:+971501548923" style="color: #fff; text-decoration: none;">+971 50 154 8923</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</body>
		</html>
		HTML;

		$email_sub		= 'Mentor Registration ';
		$email_to = $email;
		

		$this->send_mail_using_smtp($email_message, $email_sub, $email_to ,Null,$school_data['name']);

	}
	function School_online_admission($email = "", $school_name = "", $name = "") {
		$image_url = base_url('uploads/images/decloedt/logo/logo_mail.png');
		$systemEmail = get_settings('system_email');	
		$email_message = <<<HTML
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Registration School</title>
			<style>
				body { 
					margin: 0; 
					padding: 0; 
					font-family: Arial, Helvetica, sans-serif; 
				}
				.container { 
					background: #ECECEC; 
					padding: 30px 15px; 
				}
				.logo { 
					text-align: center; 
					margin-bottom: 20px; 
				}
				.logo img { 
					max-width: 150px; 
					height: auto; 
				}
				.content-table { 
					max-width: 700px; 
					width: 100%; 
					margin: 0 auto; 
					border-collapse: collapse; 
				}
				.header { 
					background: #FC7B30; 
					border-radius: 15px 15px 0 0; 
					padding: 15px; 
					text-align: center; 
				}
				.header span { 
					font-size: 20px; 
					font-weight: bold; 
					color: #fff; 
				}
				.body { 
					background: #f0f7fa; 
					padding: 20px; 
				}
				.body p { 
					font-size: 14px; 
					color: #000; 
					line-height: 1.5; 
					margin: 15px 0; 
				}
				.body a { 
					color: #0047AB; 
					text-decoration: none; 
				}
				.button {
					display: inline-block;
					padding: 15px 25px;
					font-size: 16px;
					color: #fff !important;
					background-color: #FC7B30;
					text-decoration: none;
					border-radius: 5px;
					margin: 20px 0;
					transition: background-color 0.3s ease;
					font-weight: bold;
				}
				.button:hover {
					background-color: #B8701F;
				}
				.info-container {
					display: flex;
					align-items: center;
					flex-wrap: wrap;
					text-align: left;
				}
				.info-image {
					width: 100%;
					max-width: 150px;
					margin-right: 20px;
					margin-bottom: 15px;
					border-radius: 8px;
				}
				.info-content {
					flex: 1;
					min-width: 300px;
				}
				.footer { 
					background: #FC7B30; 
					border-radius: 0 0 15px 15px; 
					color: #fff; 
					font-size: 14px; 
				}
				.footer table { 
					width: 100%; 
				}
				.footer td { 
					padding: 10px; 
				}
				@media only screen and (max-width: 600px) {
					.content-table { 
						width: 100% !important; 
					}
					.header span { 
						font-size: 18px; 
					}
					.footer td { 
						display: block; 
						text-align: center !important; 
						width: 100% !important; 
					}
					.info-container {
						flex-direction: column;
						text-align: center;
					}
					.info-image {
						margin: 0 auto 15px;
					}
					.info-content {
						min-width: auto;
					}
				}
			</style>
		</head>
		<body>
			<div class="container">
			<div class="logo">
				<img src="https://wayo.academy/uploads/system/logo/logo-light.png" alt="Wayo Academy Logo">
			</div>
			<table class="content-table">
				<tr>
					<td class="header">
						<span>School Registration Confirmation</span>
					</td>
				</tr>
				<tr>
					<td class="body">
						<div class="info-container">
							<div class="info-content">
								<p>Dear <strong>{$name}</strong>,</p>
								<p>We are delighted to confirm that your registration with <a href="https://wayo.academy/" style="color: #FC7B30; text-decoration: none;">Wayo Academy</a> has been successfully processed.</p>
								<p><strong>School:</strong> {$school_name}</p>
								<p><strong>Email:</strong> {$email}</p>
								<p>If you have any questions or need assistance, please feel free to contact our support team at: <a href="mailto:{$systemEmail}">{$systemEmail}</a>.</p>
								<p>Please click the button below to access your account and explore our services.</p>
								<div>
									<a href="https://wayo.academy/login" class="button">Login to Your Account</a>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="footer">
						<table>
							<tr>
								<td style="text-align: left; width: 33%;">
									<a href="https://wayo.academy/home/contact#map" style="color: #fff; text-decoration: none;">R320 Umm Hurair 2, Dubai, UAE</a>
								</td>
								<td style="text-align: center; width: 34%;">
									© 2025 Wayo Academy. All rights reserved.
								</td>
								<td style="text-align: right; width: 33%;">
									Tel: <a href="tel:+971501548923" style="color: #fff; text-decoration: none;">+971 50 154 8923</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		</body>
		</html>
		HTML;
	
		$email_sub = 'Registration';
		$email_to = $email;
		$school_name = "Wayo Academy";
	
		$this->send_mail_using_smtp($email_message, $email_sub, $email_to, null, $school_name);
	}
	function School_online_admission_superadmin($email = "", $school_name = "", $name = "") {
		$image_url = base_url('uploads/images/decloedt/logo/logo_mail.png');
	
		$email_message = '
			<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>New School Creation Notification</title>
		<style>
			body { 
				margin: 0; 
				padding: 0; 
				font-family: Arial, Helvetica, sans-serif; 
			}
			.container { 
				background: #ECECEC; 
				padding: 30px 15px; 
			}
			.logo { 
				text-align: center; 
				margin-bottom: 20px; 
			}
			.logo img { 
				max-width: 150px; 
				height: auto; 
			}
			.content-table { 
				max-width: 700px; 
				width: 100%; 
				margin: 0 auto; 
				border-collapse: collapse; 
			}
			.header { 
				background: #FC7B30; 
				border-radius: 15px 15px 0 0; 
				padding: 15px; 
				text-align: center; 
			}
			.header span { 
				font-size: 20px; 
				font-weight: bold; 
				color: #fff; 
			}
			.body { 
				background: #f0f7fa; 
				padding: 20px; 
			}
			.body p { 
				font-size: 14px; 
				color: #000; 
				line-height: 1.5; 
				margin: 15px 0; 
			}
			.body a { 
				color: #0047AB; 
				text-decoration: none; 
			}
			.button {
				display: inline-block;
				padding: 15px 25px;
				font-size: 16px;
				color: #fff !important;
				background-color: #FC7B30;
				text-decoration: none;
				border-radius: 5px;
				margin: 20px 0;
				transition: background-color 0.3s ease;
				font-weight: bold;
			}
			.button:hover {
				background-color: #B8701F;
			}
			.info-container {
				display: flex;
				align-items: center;
				flex-wrap: wrap;
				text-align: left;
			}
			.info-image {
				width: 100%;
				max-width: 150px;
				margin-right: 20px;
				margin-bottom: 15px;
				border-radius: 8px;
			}
			.info-content {
				flex: 1;
				min-width: 300px;
			}
			.footer { 
				background: #FC7B30; 
				border-radius: 0 0 15px 15px; 
				color: #fff; 
				font-size: 14px; 
			}
			.footer table { 
				width: 100%; 
			}
			.footer td { 
				padding: 10px; 
			}
			@media only screen and (max-width: 600px) {
				.content-table { 
					width: 100% !important; 
				}
				.header span { 
					font-size: 18px; 
				}
				.footer td { 
					display: block; 
					text-align: center !important; 
					width: 100% !important; 
				}
				.info-container {
					flex-direction: column;
					text-align: center;
				}
				.info-image {
					margin: 0 auto 15px;
				}
				.info-content {
					min-width: auto;
				}
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="logo">
				<img src="https://wayo.academy/uploads/system/logo/logo-light.png" alt="Wayo Academy Logo">
			</div>
			<table class="content-table">
				<tr>
					<td class="header">
						<span>New School Creation Notification</span>
					</td>
				</tr>
				<tr>
					<td class="body">
						<div class="info-container">
							<div class="info-content">
								<p><strong>New School Registration</strong></p>
								<p>Dear Superadmin,</p>
								<p>We are pleased to inform you that a new school has been successfully registered on the Wayo Academy platform. Below are the details of the new school and its administrator:</p>
								<p><strong>School Name:</strong> '.$school_name.'</p>
								<p><strong>Administrator Name:</strong> '.$name.'</p>
								<p><strong>Administrator Email:</strong> '.$email.'</p>
								<p>Please review the details and take any necessary actions to ensure the onboarding process is completed smoothly.</p>
								<div>
									<a href="https://wayo.academy/login" class="button">Login to Your Account</a>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="footer">
						<table>
							<tr>
								<td style="text-align: left; width: 33%;">
									<a href="https://wayo.academy/home/contact#map" style="color: #fff; text-decoration: none;">R320 Umm Hurair 2, Dubai, UAE</a>
								</td>
								<td style="text-align: center; width: 34%;">
									© '.date("Y").' Wayo Academy. All rights reserved.
								</td>
								<td style="text-align: right; width: 33%;">
									Tel: <a href="tel:+971501548923" style="color: #fff; text-decoration: none;">+971 50 154 8923</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
	</html>';
	
		$email_sub = 'Application for School Admission';
		$email_to = $email;
		$school_name = "Wayo Academy";
	
		$this->send_mail_using_smtp($email_message, $email_sub, $email_to, null, $school_name);
	}
	
	function approved_online_admission($student_id = "", $user_id = "")
	{
				$student_details = $this->user_model->get_student_details_by_id('student', $student_id);
				$school_data = $this->settings_model->get_current_school_data();
				$student_email = $student_details['email'];
				$student_name = $student_details['name'];
				$student_code = $student_details['code'];
				$image_url = "http://51.92.7.185/uploads/schools/".$school_data['id'].".jpg"; // URL de l'image à côté des informations
				$systemEmail = get_settings('system_email');
				$email_message = '
				<html lang="fr">
				<head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<title>Admission Approved</title>
					<style>
						body { 
							margin: 0; 
							padding: 0; 
							font-family: Arial, Helvetica, sans-serif; 
						}
						.container { 
							background: #ECECEC; 
							padding: 30px 15px; 
						}
						.logo { 
							text-align: center; 
							margin-bottom: 20px; 
						}
						.logo img { 
							max-width: 150px; 
							height: auto; 
						}
						.content-table { 
							max-width: 700px; 
							width: 100%; 
							margin: 0 auto; 
							border-collapse: collapse; 
						}
						.header { 
							background: #FC7B30; 
							border-radius: 15px 15px 0 0; 
							padding: 15px; 
							text-align: center; 
						}
						.header span { 
							font-size: 20px; 
							font-weight: bold; 
							color: #fff; 
						}
						.body { 
							background: #f0f7fa; 
							padding: 20px; 
						}
						.body p { 
							font-size: 14px; 
							color: #000; 
							line-height: 1.5; 
							margin: 15px 0; 
						}
						.body a { 
							color: #0047AB; 
							text-decoration: none; 
						}
						.button {
							display: inline-block;
							padding: 15px 25px;
							font-size: 16px;
							color: #fff !important;
							background-color: #FC7B30;
							text-decoration: none;
							border-radius: 5px;
							margin: 20px 0;
							transition: background-color 0.3s ease;
							font-weight: bold;
						}
						.button:hover {
							background-color: #B8701F;
						}
						.info-container {
							display: flex;
							align-items: center;
							flex-wrap: wrap;
							text-align: left;
						}
						.info-image {
							width: 100%;
							max-width: 150px;
							margin-right: 20px;
							margin-bottom: 15px;
							border-radius: 8px;
						}
						.info-content {
							flex: 1;
							min-width: 300px;
						}
						.footer { 
							background: #FC7B30; 
							border-radius: 0 0 15px 15px; 
							color: #fff; 
							font-size: 14px; 
						}
						.footer table { 
							width: 100%; 
						}
						.footer td { 
							padding: 10px; 
						}
						@media only screen and (max-width: 600px) {
							.content-table { 
								width: 100% !important; 
							}
							.header span { 
								font-size: 18px; 
							}
							.footer td { 
								display: block; 
								text-align: center !important; 
								width: 100% !important; 
							}
							.info-container {
								flex-direction: column;
								text-align: center;
							}
							.info-image {
								margin: 0 auto 15px;
							}
							.info-content {
								min-width: auto;
							}
						}
					</style>
				</head>
				<body>
					<div class="container">
						<div class="logo">
							<img src="https://wayo.academy/uploads/system/logo/logo-light.png" alt="Wayo Academy Logo">
						</div>
						<table class="content-table">
							<tr>
								<td class="header">
									<span>Admission Approved</span>
								</td>
							</tr>
							<tr>
								<td class="body">
									<div class="info-container">
										<div class="info-content">
											<p><strong>Confirmation of Admission</strong></p>
											<p>Dear Student,</p>
											<p>We are pleased to inform you that your admission request to ' . $school_data['name'] . ' has been successfully approved. Below are the details of your admission:</p>
											<p><strong>Student Code:</strong> ' . $student_code . '</p>
											<p><strong>Email:</strong> ' . $student_email . '</p>
											<p>Please log in to your account to access course details and complete any additional onboarding steps required.</p>
											<div>
												<a href="http://51.92.7.185/home/course_details/' . $school_data['id'] . '" class="button">Login to Your Account</a>
											</div>
											<p>If you have any questions or need assistance, please feel free to contact our support team at: <a href="mailto:' . $systemEmail . '">' . $systemEmail . '</a></p>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="footer">
									<table>
										<tr>
											<td style="text-align: left; width: 33%;">
												<a href="https://wayo.academy/home/contact#map" style="color: #fff; text-decoration: none;">R320 Umm Hurair 2, Dubai, UAE</a>
											</td>
											<td style="text-align: center; width: 34%;">
											© '.date("Y").' Wayo Academy. All rights reserved.
											</td>
											<td style="text-align: right; width: 33%;">
												Tel: <a href="tel:+971501548923" style="color: #fff; text-decoration: none;">+971 50 154 8923</a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</body>
				</html>';
				// $email_message  = '<html><body><p> Your admission request has been accepted.'.'</p><br><p>Student Code : '.$student_code.'</p><p>Email : '.$student_email.'</p><p>Password : '.$password.'</p></body></html>';
				$email_sub		= 'Admission approval';
				$email_to = $student_email;
				

				$this->send_mail_using_smtp($email_message, $email_sub, $email_to,Null,$school_data['name']);
	}


	function join_student_email($email_student = "", $user_name ,$code_student = "", $name_school = "",$school_id ="")
	{
				$image_url = "http://51.92.7.185/uploads/schools/".$school_id.".jpg"; // URL de l'image à côté des informations
				$systemEmail = get_settings('system_email');
				$email_message = '
				<html lang="fr">
				<head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<title>Join Request Confirmation</title>
					<style>
						body { 
							margin: 0; 
							padding: 0; 
							font-family: Arial, Helvetica, sans-serif; 
						}
						.container { 
							background: #ECECEC; 
							padding: 30px 15px; 
						}
						.logo { 
							text-align: center; 
							margin-bottom: 20px; 
						}
						.logo img { 
							max-width: 150px; 
							height: auto; 
						}
						.content-table { 
							max-width: 700px; 
							width: 100%; 
							margin: 0 auto; 
							border-collapse: collapse; 
						}
						.header { 
							background: #FC7B30; 
							border-radius: 15px 15px 0 0; 
							padding: 15px; 
							text-align: center; 
						}
						.header span { 
							font-size: 20px; 
							font-weight: bold; 
							color: #fff; 
						}
						.body { 
							background: #f0f7fa; 
							padding: 20px; 
						}
						.body p { 
							font-size: 14px; 
							color: #000; 
							line-height: 1.5; 
							margin: 15px 0; 
						}
						.body a { 
							color: #0047AB; 
							text-decoration: none; 
						}
						.button {
							display: inline-block;
							padding: 15px 25px;
							font-size: 16px;
							color: #fff !important;
							background-color: #FC7B30;
							text-decoration: none;
							border-radius: 5px;
							margin: 20px 0;
							transition: background-color 0.3s ease;
							font-weight: bold;
						}
						.button:hover {
							background-color: #B8701F;
						}
						.info-container {
							display: flex;
							align-items: center;
							flex-wrap: wrap;
							text-align: left;
						}
						.info-image {
							width: 100%;
							max-width: 150px;
							margin-right: 20px;
							margin-bottom: 15px;
							border-radius: 8px;
						}
						.info-content {
							flex: 1;
							min-width: 300px;
						}
						.footer { 
							background: #FC7B30; 
							border-radius: 0 0 15px 15px; 
							color: #fff; 
							font-size: 14px; 
						}
						.footer table { 
							width: 100%; 
						}
						.footer td { 
							padding: 10px; 
						}
						@media only screen and (max-width: 600px) {
							.content-table { 
								width: 100% !important; 
							}
							.header span { 
								font-size: 18px; 
							}
							.footer td { 
								display: block; 
								text-align: center !important; 
								width: 100% !important; 
							}
							.info-container {
								flex-direction: column;
								text-align: center;
							}
							.info-image {
								margin: 0 auto 15px;
							}
							.info-content {
								min-width: auto;
							}
						}
					</style>
				</head>
				<body>
					<div class="container">
						<div class="logo">
							<img src="https://wayo.academy/uploads/system/logo/logo-light.png" alt="Wayo Academy Logo">
						</div>
						<table class="content-table">
							<tr>
								<td class="header">
									<span>Join Request Confirmation</span>
								</td>
							</tr>
							<tr>
								<td class="body">
									<div class="info-container">
										<div class="info-content">
											<p><strong>Join Request Confirmation</strong></p>
											<p>Dear ' . $user_name . ',</p>
											<p>Thank you for submitting your request to join ' . $name_school . '. Your application has been successfully received and is currently under review. Below are the details of your request:</p>
											<p><strong>Student Code:</strong> ' . $code_student . '</p>
											<p><strong>Email:</strong> ' . $email_student . '</p>
											<p>You will be notified once your request has been reviewed and approved. In the meantime, you may log in to your account to check the status of your application or access additional information.</p>
											<div>
												<a href="http://51.92.7.185/home/course_details/' . $school_id . '" class="button">Login to Your Account</a>
											</div>
											<p>If you have any questions or need assistance, please feel free to contact our support team at: <a href="mailto:' . $systemEmail . '">' . $systemEmail . '</a></p>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="footer">
									<table>
										<tr>
											<td style="text-align: left; width: 33%;">
												<a href="https://wayo.academy/home/contact#map" style="color: #fff; text-decoration: none;">R320 Umm Hurair 2, Dubai, UAE</a>
											</td>
											<td style="text-align: center; width: 34%;">
												© '.date("Y").' Wayo Academy. All rights reserved.
											</td>
											<td style="text-align: right; width: 33%;">
												Tel: <a href="tel:+971501548923" style="color: #fff; text-decoration: none;">+971 50 154 8923</a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</body>
				</html>';
				$email_sub		= 'Admission approval';
				$email_to = $email_student;
				

				$this->send_mail_using_smtp($email_message, $email_sub, $email_to,Null,$name_school);
	}
	function join_student_email_for_admin($email_student = "", $user_name ,$code_student = "", $name_school = "",$school_id ="")
	{
				$image_url = "http://51.92.7.185/uploads/schools/".$school_id.".jpg"; // URL de l'image à côté des informations
				$systemEmail = get_settings('system_email');
				$email_message = '
				<html lang="fr">
				<head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<title>New Registration Notification</title>
					<style>
						body { 
							margin: 0; 
							padding: 0; 
							font-family: Arial, Helvetica, sans-serif; 
						}
						.container { 
							background: #ECECEC; 
							padding: 30px 15px; 
						}
						.logo { 
							text-align: center; 
							margin-bottom: 20px; 
						}
						.logo img { 
							max-width: 150px; 
							height: auto; 
						}
						.content-table { 
							max-width: 700px; 
							width: 100%; 
							margin: 0 auto; 
							border-collapse: collapse; 
						}
						.header { 
							background: #FC7B30; 
							border-radius: 15px 15px 0 0; 
							padding: 15px; 
							text-align: center; 
						}
						.header span { 
							font-size: 20px; 
							font-weight: bold; 
							color: #fff; 
						}
						.body { 
							background: #f0f7fa; 
							padding: 20px; 
						}
						.body p { 
							font-size: 14px; 
							color: #000; 
							line-height: 1.5; 
							margin: 15px 0; 
						}
						.body a { 
							color: #0047AB; 
							text-decoration: none; 
						}
						.button {
							display: inline-block;
							padding: 15px 25px;
							font-size: 16px;
							color: #fff !important;
							background-color: #FC7B30;
							text-decoration: none;
							border-radius: 5px;
							margin: 20px 0;
							transition: background-color 0.3s ease;
							font-weight: bold;
						}
						.button:hover {
							background-color: #B8701F;
						}
						.info-container {
							display: flex;
							align-items: center;
							flex-wrap: wrap;
							text-align: left;
						}
						.info-image {
							width: 100%;
							max-width: 150px;
							margin-right: 20px;
							margin-bottom: 15px;
							border-radius: 8px;
						}
						.info-content {
							flex: 1;
							min-width: 300px;
						}
						.footer { 
							background: #FC7B30; 
							border-radius: 0 0 15px 15px; 
							color: #fff; 
							font-size: 14px; 
						}
						.footer table { 
							width: 100%; 
						}
						.footer td { 
							padding: 10px; 
						}
						@media only screen and (max-width: 600px) {
							.content-table { 
								width: 100% !important; 
							}
							.header span { 
								font-size: 18px; 
							}
							.footer td { 
								display: block; 
								text-align: center !important; 
								width: 100% !important; 
							}
							.info-container {
								flex-direction: column;
								text-align: center;
							}
							.info-image {
								margin: 0 auto 15px;
							}
							.info-content {
								min-width: auto;
							}
						}
					</style>
				</head>
				<body>
					<div class="container">
						<div class="logo">
							<img src="https://wayo.academy/uploads/system/logo/logo-light.png" alt="Wayo Academy Logo">
						</div>
						<table class="content-table">
							<tr>
								<td class="header">
									<span>New Registration Notification</span>
								</td>
							</tr>
							<tr>
								<td class="body">
									<div class="info-container">
										<div class="info-content">
											<p><strong>New Registration Notification</strong></p>
											<p>Dear Administrator,</p>
											<p>We are pleased to inform you that a new registration has been submitted for ' . $name_school . '. Below are the details of the registrant:</p>
											<p><strong>Name:</strong> ' . $user_name . '</p>
											<p><strong>Student Code:</strong> ' . $code_student . '</p>
											<p><strong>Email:</strong> ' . $email_student . '</p>
											<p>Please log in to your account to review the registration details and take any necessary actions to process the request.</p>
											<div>
												<a href="https://wayo.academy/login" class="button">Access Your Account</a>
											</div>
											<p>If you have any questions or need assistance, please feel free to contact our support team at: <a href="mailto:' . $systemEmail . '">' . $systemEmail . '</a></p>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="footer">
									<table>
										<tr>
											<td style="text-align: left; width: 33%;">
												<a href="https://wayo.academy/home/contact#map" style="color: #fff; text-decoration: none;">R320 Umm Hurair 2, Dubai, UAE</a>
											</td>
											<td style="text-align: center; width: 34%;">
												© ' . date("Y") . ' ' . $name_school . '. All rights reserved.
											</td>
											<td style="text-align: right; width: 33%;">
												Tel: <a href="tel:+971501548923" style="color: #fff; text-decoration: none;">+971 50 154 8923</a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</body>
				</html>';
				$email_sub		= 'Admission approval';
				$email_to = $email_student;
				

				$this->send_mail_using_smtp($email_message, $email_sub, $email_to,Null,$name_school);
	}
	function approved_online_admission_parent_access($user_id = "", $password = ""){
		$parent_details = $this->db->get_where('users', array('id' => $user_id))->row_array();
		$email = $parent_details['email'];
		$email_message  = "<html><body><p> Your son/daughter's admission has been accepted.".'</p><br><p>Your account access-</p><p>Email : '.$email.'</p><p>Password : '.$password.'</p></body></html>';
		$email_sub		= 'Admission approval';
		$email_to = $email;
		

		$this->send_mail_using_smtp($email_message, $email_sub, $email_to);
	}


	//SEND MARKS VIA MAIL
	function send_marks_email($email_msg=NULL, $email_sub=NULL, $email_to=NULL)
	{
		if (get_smtp('mail_sender') == 'php_mailer') {
			$this->send_mail_using_php_mailer($email_msg , $email_sub , $email_to);
		}else{
			$this->send_mail_using_smtp($email_msg , $email_sub , $email_to);
		}
		return true;
	}
	// more stable function
	public function send_mail_using_smtp($msg = NULL, $sub = NULL, $to = NULL, $from = NULL, $school_name = NULL) {
    // Load email library
    $this->load->library('email');

    $smtp_username = get_smtp('smtp_username');
    $smtp_password = get_smtp('smtp_password');
    if ($from == NULL) {
        $from = $smtp_username; // Use SMTP username as the default sender
    }

    // SMTP & mail configuration
    $config = array(
        'protocol'      => get_smtp('smtp_protocol'),
        'smtp_host'     => get_smtp('smtp_host'),
        'smtp_port'     => get_smtp('smtp_port'),
        'smtp_user'     => $smtp_username,
        'smtp_pass'     => $smtp_password,
        'smtp_crypto'   => get_smtp('smtp_crypto'),
        'mailtype'      => 'html',
        'charset'       => 'utf-8',
        'newline'       => "\r\n",
        'smtp_timeout'  => '30',
        'mailpath'      => '/usr/sbin/sendmail',
        'wordwrap'      => TRUE,
        'crlf'          => "\r\n",
        'encoding'      => 'base64' // Explicitly set to base64 to avoid Quoted-Printable issues
    );

    $this->email->initialize($config);
    $this->email->set_mailtype("html");
    $this->email->set_newline("\r\n");

    // Encode the message in base64
    $htmlContent = $msg;

    $this->email->to($to);
    $this->email->from($from, $school_name ?? get_settings('system_name'));
    $this->email->subject($sub);
    $this->email->message($htmlContent);

    // Send email and handle errors
    if (!$this->email->send()) {
        log_message('error', 'CI Email Debugger: ' . $this->email->print_debugger(['headers', 'subject', 'body']));
        return false;
    }

    return true;
}

	public function send_mail_using_php_mailer($message = NULL, $subject = NULL, $to = NULL, $from = NULL) {
    // Load PHPMailer library
    $this->load->library('phpmailer_lib');

    // PHPMailer object
    $mail = $this->phpmailer_lib->load();

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host       = get_smtp('smtp_host');
    $mail->SMTPAuth   = true;
    $mail->Username   = get_smtp('smtp_username');
    $mail->Password   = get_smtp('smtp_password');
    $mail->SMTPSecure = get_smtp('smtp_secure');
    $mail->Port       = get_smtp('smtp_port');

    // Set charset and encoding
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64'; // Use Base64 to avoid Quoted-Printable issues

    // Set sender and reply-to
    $from = $from ?? get_smtp('smtp_username');
    $mail->setFrom($from, get_smtp('smtp_set_from') ?? get_settings('system_name'));
    $mail->addReplyTo(get_settings('system_email'), get_settings('system_name'));

    // Add recipient
    $mail->addAddress($to);

    // Email subject
    $mail->Subject = mb_encode_mimeheader($subject, 'UTF-8', 'B');

    // Set email format to HTML
    $mail->isHTML(true);

    // Disable debug output in production
    $mail->SMTPDebug = false;

    // Email body content
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message); // Plain text fallback for non-HTML clients

    // Send email
    if (!$mail->send()) {
        log_message('error', 'PHPMailer Error: ' . $mail->ErrorInfo);
        return false;
    }

    return true;
}
	function send_email_with_validation_code($email_message, $email_to) {
		$email_sub = "Your Validation Code";
		
		// Sending the email using SMTP or PHPMailer
		if (get_smtp('mail_sender') == 'php_mailer') {
			return $this->send_mail_using_php_mailer($email_message, $email_sub, $email_to);
		} else {
			return $this->send_mail_using_smtp($email_message, $email_sub, $email_to);
		}
	}
	
}
