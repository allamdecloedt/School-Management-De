<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Student extends CI_Controller {
	public function __construct(){

		parent::__construct();

		$this->load->database();
		$this->load->library('Humhub_sso');
		$this->load->library('session');
		 require_once APPPATH . '../vendor/autoload.php';

		/*LOADING ALL THE MODELS HERE*/
		$this->load->model('Crud_model',     'crud_model');
		$this->load->model('User_model',     'user_model');
		$this->load->model('Settings_model', 'settings_model');
		$this->load->model('Payment_model',  'payment_model');
		$this->load->model('Email_model',    'email_model');
		$this->load->model('Addon_model',    'addon_model');
		$this->load->model('Frontend_model', 'frontend_model');
		$this->load->model('Room_model','room_model');

		/*cache control*/
		$this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		/*SET DEFAULT TIMEZONE*/
		timezone();

		// CHECK WHETHER student IS LOGGED IN
		if($this->session->userdata('student_login') != 1){
			redirect(site_url('login'), 'refresh');
		}
	}

	// INDEX FUNCTION
	public function index(){
		redirect(site_url('student/dashboard'), 'refresh');
	}
	   //START TEACHER Create_Join bigbleubutton 
	   public function Join_Session($param1 = '', $param2 = '', $param3 = '')
	   {
	 
   
	 
		 if (empty($param1)) {
		   $page_data['folder_name'] = 'bigbleubutton';
		   $page_data['page_title'] = 'Démarrer Réunion';
		   $this->load->view('backend/index', $page_data);
		 }
	   }
	   //END TEACHER Create_Join bigbleubutton 

	//DASHBOARD
	public function dashboard(){
		// $page_data['page_title'] = 'Dashboard';
		// $page_data['folder_name'] = 'dashboard';
		  // 1) Vérifier que c'est bien un admin
			if (! $this->session->userdata('student_login')) {
				show_error('Accès réservé aux superadmins.');
			}
			
			// 2) Récupérer les infos du user (ici : depuis la table Wayo)
			$userId = $this->session->userdata('user_id');
			
			$wUser  = $this->db->get_where('users', ['id' => $userId])->row();
			if (empty($wUser) || ! filter_var($wUser->email, FILTER_VALIDATE_EMAIL)) {
				log_message('error', 'Invalid Wayo user data: ' . print_r($wUser, true));
				show_error('Impossible de retrouver votre compte Wayo pour SSO.');
			}
			log_message('debug', 'Wayo user data: ' . print_r($wUser, true));

			// 3) Stocker temporairement cet objet pour la librairie SSO
			$this->session->set_userdata('user', $wUser);

			// 4) Générer l’URL SSO
			$iframeUrl = $this->humhub_sso->provisionAndGetIframeUrl();
			log_message('debug', 'Generated iframe URL: ' . $iframeUrl);

			if (! $iframeUrl) {
				show_error('Impossible de générer l’URL SSO HumHub.');
			}
			
			// 5) Passer à la vue
			$page_data = [
				'folder_name' => 'dashboard',
				'page_title'  => 'Dashboard',
				//'page_name'   => 'central',
				'iframe_url'  => $iframeUrl,
			];
		$this->load->view('backend/index', $page_data);
	}
	public function get_appointments() {
		$appointments = $this->room_model->get_all_appointments_student();
		echo json_encode($appointments);
	}
	public function get_sections() {
		$classe_id = $this->input->post('classe_id');
	
		if (!empty($classe_id)) {
			$sections = $this->db->get_where('sections', array('class_id' => $classe_id))->result_array();
		} else {
			$sections = [];
		}
	
		echo json_encode($sections);
	}
	//START CLASS secion
	public function manage_class($param1 = '', $param2 = '', $param3 = ''){
		if($param1 == 'section'){
			$response = $this->crud_model->section_update($param2);
			echo $response;
		}

		// show data from database
		if ($param1 == 'list') {
			$this->load->view('backend/student/class/list');
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'class';
			$page_data['page_title'] = 'class';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END CLASS section
	//	SECTION STARTED
	public function section($action = "", $id = "") {

		// PROVIDE A LIST OF SECTION ACCORDING TO CLASS ID
		if ($action == 'list') {
			$page_data['class_id'] = $id;
			$this->load->view('backend/student/section/list', $page_data);
		}
	}
	//	SECTION ENDED

		//	SECTION STARTED
		public function exam_class($action = "", $id = "") {

			// PROVIDE A LIST OF SECTION ACCORDING TO CLASS ID
			if ($action == 'list') {
				$page_data['exam_id'] = $id;
				$this->load->view('backend/student/exam/list_select', $page_data);
			}
		}
		//	SECTION ENDED

	//START SUBJECT section
	public function subject($param1 = '', $param2 = ''){

		if($param1 == 'list'){
			$page_data['class_id'] = $param2;
			$this->load->view('backend/student/subject/list', $page_data);
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'subject';
			$page_data['page_title'] = 'subject';
			$this->load->view('backend/index', $page_data);
		}
	}

	public function class_wise_subject($class_id) {

		// PROVIDE A LIST OF SUBJECT ACCORDING TO CLASS ID
		$page_data['class_id'] = $class_id;
		$this->load->view('backend/student/subject/dropdown', $page_data);
	}
	//END SUBJECT section


	//START SYLLABUS section
	public function syllabus($param1 = '', $param2 = '', $param3 = ''){

		if($param1 == 'list'){
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			$this->load->view('backend/student/syllabus/list', $page_data);
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'syllabus';
			$page_data['page_title'] = 'syllabus';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END SYLLABUS section
  // LANGUAGE SETTINGS
  public function language($param1 = "", $param2 = "")
  {
    // adding language
    // if ($param1 == 'create') {
    //   $response = $this->settings_model->create_language();
    //   // echo $response;
    //   // Préparer la réponse avec un nouveau jeton CSRF
    //   $csrf = array(
    //     'csrfName' => $this->security->get_csrf_token_name(),
    //     'csrfHash' => $this->security->get_csrf_hash(),
    //           );
            
    //   // Renvoyer la réponse avec un nouveau jeton CSRF
    //   echo json_encode(array('status' => $response, 'csrf' => $csrf));
    // }

    // update language
    // if ($param1 == 'update') {
    //   $response = $this->settings_model->update_language($param2);
    //   // echo $response;
    //   // Préparer la réponse avec un nouveau jeton CSRF
    //   $csrf = array(
    //     'csrfName' => $this->security->get_csrf_token_name(),
    //     'csrfHash' => $this->security->get_csrf_hash(),
    //           );
            
    //   // Renvoyer la réponse avec un nouveau jeton CSRF
    //   echo json_encode(array('status' => $response, 'csrf' => $csrf));
    // }

    // deleting language
    // if ($param1 == 'delete') {
    //   $response = $this->settings_model->delete_language($param2);
    //   // echo $response;
    //   // Préparer la réponse avec un nouveau jeton CSRF
    //   $csrf = array(
    //     'csrfName' => $this->security->get_csrf_token_name(),
    //     'csrfHash' => $this->security->get_csrf_hash(),
    //           );
            
    //   // Renvoyer la réponse avec un nouveau jeton CSRF
    //   echo json_encode(array('status' => $response, 'csrf' => $csrf));
    // }

    // // showing the list of language
    // if ($param1 == 'list') {
    //   $this->load->view('backend/superadmin/language/list');
    // }

	if ($param1 == 'active') {
		// 1) Mise à jour de la langue en base et en session
		$user_id = $this->session->userdata('user_id');
		$this->session->set_userdata('language', $param2);
		$this->settings_model->update_system_language($user_id, $param2);
	
		// 2) Retourner à la page appelante
		$referer = $this->input->server('HTTP_REFERER');
		if ($referer) {
			redirect($referer, 'refresh');
		} else {
			// Fallback : renvoyer vers la home du rôle
			$role = $this->session->userdata('user_type');
			redirect(site_url($role), 'refresh');
		}
	}
  

    // showing the list of language
    // if ($param1 == 'update_phrase') {
    //   $current_editing_language = htmlspecialchars($this->input->post('currentEditingLanguage'));
    //   $updatedValue = htmlspecialchars($this->input->post('updatedValue'));
    //   $key = htmlspecialchars($this->input->post('key'));
    //   saveJSONFile($current_editing_language, $key, $updatedValue);
    //   $response =  $current_editing_language . ' ' . $key . ' ' . $updatedValue;
    //   // Préparer la réponse avec un nouveau jeton CSRF
    //   $csrf = array(
    //     'csrfName' => $this->security->get_csrf_token_name(),
    //     'csrfHash' => $this->security->get_csrf_hash(),
    //           );
            
    //   // Renvoyer la réponse avec un nouveau jeton CSRF
    //   echo json_encode(array('response' => $response, 'csrf' => $csrf));
    // }

    // GET THE DROPDOWN OF LANGUAGES
    if ($param1 == 'dropdown') {
      $this->load->view('backend/student/language/dropdown');
    }
    // showing the index file
    if (empty($param1)) {
      $page_data['folder_name'] = 'language';
      $page_data['page_title'] = 'languages';
      $this->load->view('backend/index', $page_data);
    }
  }

	//START TEACHER section
	public function teacher($param1 = '', $param2 = '', $param3 = ''){
		$page_data['folder_name'] = 'teacher';
		$page_data['page_title'] = 'techers';
		$this->load->view('backend/index', $page_data);
	}
	//END TEACHER section

	//START CLASS ROUTINE section
	public function routine($param1 = '', $param2 = '', $param3 = '', $param4 = ''){

		if($param1 == 'filter'){
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			$this->load->view('backend/student/routine/list', $page_data);
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'routine';
			$page_data['page_title'] = 'routine';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END CLASS ROUTINE section


	//START DAILY ATTENDANCE section
	public function attendance($param1 = '', $param2 = '', $param3 = ''){
		if($param1 == 'filter'){
			$date = '01 '.$this->input->post('month').' '.$this->input->post('year');
			$page_data['attendance_date'] = strtotime($date);
			$page_data['class_id'] = htmlspecialchars($this->input->post('class_id'));
			$page_data['section_id'] = htmlspecialchars($this->input->post('section_id'));
			$page_data['school_id'] = htmlspecialchars($this->input->post('school_id'));
			$page_data['month'] = htmlspecialchars($this->input->post('month'));
			$page_data['year'] = htmlspecialchars($this->input->post('year'));
			// $this->load->view('backend/student/attendance/list', $page_data);
			// Charger la vue mise à jour
			$response_html = $this->load->view('backend/student/attendance/list', $page_data, TRUE);
		    // Préparer le nouveau jeton CSRF
			$csrf = array(
					 'csrfName' => $this->security->get_csrf_token_name(),
					 'csrfHash' => $this->security->get_csrf_hash(),
				 );
			
			// Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
			echo json_encode(array('status' => $response_html, 'csrf' => $csrf));
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'attendance';
			$page_data['page_title'] = 'attendance';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END DAILY ATTENDANCE section

  //	academy STARTED
  public function academy($action = "", $id = "") {
		
    // PROVIDE A LIST OF SECTION ACCORDING TO CLASS ID
    if ($action == 'list') {
		// echo $id;
      $page_data['school_id'] = $id;
      $this->load->view('backend/academy/liste_classe', $page_data);
    }
  }
  //	academy ENDED
  public function online_admission($param1 = "", $user_id = "")
  {

	
    if ($param1 == 'assigned') {
		
	 // Stocker les données de l'inscription dans la session pour un accès ultérieur
		$data['student_id'] = htmlspecialchars($this->input->post('student_id'));
		$data['class_id'] = htmlspecialchars($this->input->post('class_id'));
		$data['section_id'] = htmlspecialchars($this->input->post('section_id'));
		$data['school_id'] = htmlspecialchars($this->input->post('school_id'));
		$data['price'] = htmlspecialchars($this->input->post('price'));
		$data['currency'] = htmlspecialchars($this->input->post('currency'));
		$data['session'] = active_session();
	
	  	$this->session->set_userdata('enrolment_data', $data);


		$num_rows_invoices = $this->db->get_where('invoices', array('class_id' => $data['class_id'],'student_id' => $data['student_id']))->num_rows();
		// print_r($num_rows_invoices);die;
		if($num_rows_invoices == 0){
			$name = $this->db->get_where('schools', array('id' => $data['school_id']))->row('name');
			$classe_name = $this->db->get_where('classes', array('id' => $data['class_id']))->row('name');
			$data_invoice['title'] = $name." - ".$classe_name ;
			$data_invoice['total_amount'] = $data['price'];
			$data_invoice['class_id'] = $data['class_id'] ;
			$data_invoice['student_id'] = $data['student_id'];
			$data_invoice['status'] = "unpaid";
			$data_invoice['school_id'] =$data['school_id'];
			$data_invoice['session'] = $data['session'];
			$data_invoice['created_at'] = strtotime(date('d-M-Y'));
			$this->db->insert('invoices', $data_invoice);
			$invoice_id = $this->db->insert_id();
		}else{
			$invoice_id = $this->db->get_where('invoices', array('class_id' => $data['class_id'],'student_id' => $data['student_id']))->row('id');
		}


	  redirect(site_url('Student/payment/' . $invoice_id), 'refresh');

    //   $this->session->set_flashdata('flash_message', get_phrase('admission_request_has_been_updated'));
    //   redirect(site_url('addons/courses'), 'refresh');
    }








    $page_data['folder_name'] = 'academy';
    $page_data['page_title'] = 'academy';
    $this->load->view('backend/index', $page_data);
  }
	//START EVENT CALENDAR section
	public function event_calendar($param1 = '', $param2 = ''){

		if($param1 == 'all_events'){
			echo $this->crud_model->all_events();
		}

		if ($param1 == 'list') {
			$this->load->view('backend/student/event_calendar/list');
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'event_calendar';
			$page_data['page_title'] = 'event_calendar';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END EVENT CALENDAR section

	//START EXAM section
	public function exam($param1 = '', $param2 = ''){

		if ($param1 == 'list') {
			$this->load->view('backend/student/exam/list');
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'exam';
			$page_data['page_title'] = 'exam';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END EXAM section

		//HUMHUB DASHBOARD
		public function central()
		{
			// 1) Vérifier que c'est bien un admin
			if (! $this->session->userdata('student_login')) {
				show_error('Accès réservé aux superadmins.');
			}
			
			// 2) Récupérer les infos du user (ici : depuis la table Wayo)
			$userId = $this->session->userdata('user_id');
			$wUser  = $this->db->get_where('users', ['id' => $userId])->row();
			if (empty($wUser) || ! filter_var($wUser->email, FILTER_VALIDATE_EMAIL)) {
				log_message('error', 'Invalid Wayo user data: ' . print_r($wUser, true));
				show_error('Impossible de retrouver votre compte Wayo pour SSO.');
			}
			log_message('debug', 'Wayo user data: ' . print_r($wUser, true));

			// 3) Stocker temporairement cet objet pour la librairie SSO
			$this->session->set_userdata('user', $wUser);

			// 4) Générer l’URL SSO
			$iframeUrl = $this->humhub_sso->provisionAndGetIframeUrl();
			log_message('debug', 'Generated iframe URL: ' . $iframeUrl);

			if (! $iframeUrl) {
				show_error('Impossible de générer l’URL SSO HumHub.');
			}
			
			// 5) Passer à la vue
			$page_data = [
				'folder_name' => 'humhub',
				'page_title'  => 'Central',
				'page_name'   => 'central',
				'iframe_url'  => $iframeUrl,
			];
			$this->load->view('backend/index', $page_data);
		}
			//HUMHUB PEOPLE
		public function people()
		{
			// 1) Vérifier que c'est bien un admin
			if (! $this->session->userdata('student_login')) {
				show_error('Accès réservé aux superadmins.');
			}
			
			// 2) Récupérer les infos du user (ici : depuis la table Wayo)
			$userId = $this->session->userdata('user_id');
			$wUser  = $this->db->get_where('users', ['id' => $userId])->row();

			if (empty($wUser) || ! filter_var($wUser->email, FILTER_VALIDATE_EMAIL)) {
				log_message('error', 'Invalid Wayo user data: ' . print_r($wUser, true));
				show_error('Impossible de retrouver votre compte Wayo pour SSO.');
			}
			log_message('debug', 'Wayo user data: ' . print_r($wUser, true));

			// 3) Stocker temporairement cet objet pour la librairie SSO
			$this->session->set_userdata('user', $wUser);

			// 4) Générer l’URL SSO
			$iframeUrl = $this->humhub_sso->provisionAndGetIframeUrl();
			log_message('debug', 'Generated iframe URL: ' . $iframeUrl);
		
			if (! $iframeUrl) {
				show_error('Impossible de générer l’URL SSO HumHub.');
			}

			// 5) Passer à la vue
			$page_data = [
				'folder_name' => 'humhub',
				'page_title'  => 'People',
				'page_name'   => 'people',
				'iframe_url'  => $iframeUrl,
			];
			$this->load->view('backend/index', $page_data);
		}

		//HUMHUB SPEACES
		public function spaces() {
			// 1) Vérifier que c'est bien un admin
			if (! $this->session->userdata('student_login')) {
				show_error('Accès réservé aux superadmins.');
			}
			
			// 2) Récupérer les infos du user (ici : depuis la table Wayo)
			$userId = $this->session->userdata('user_id');
			$wUser  = $this->db->get_where('users', ['id' => $userId])->row();
			if (empty($wUser) || ! filter_var($wUser->email, FILTER_VALIDATE_EMAIL)) {
				log_message('error', 'Invalid Wayo user data: ' . print_r($wUser, true));
				show_error('Impossible de retrouver votre compte Wayo pour SSO.');
			}
			log_message('debug', 'Wayo user data: ' . print_r($wUser, true));

			// 3) Stocker temporairement cet objet pour la librairie SSO
			$this->session->set_userdata('user', $wUser);

			// 4) Générer l’URL SSO
			$iframeUrl = $this->humhub_sso->provisionAndGetIframeUrl();
			log_message('debug', 'Generated iframe URL: ' . $iframeUrl);

			if (! $iframeUrl) {
				show_error('Impossible de générer l’URL SSO HumHub.');
			}

			// 5) Passer à la vue
			$page_data = [
				'folder_name' => 'humhub',
				'page_title'  => 'Spaces',
				'page_name'   => 'spaces',
				'iframe_url'  => $iframeUrl,
			];
			$this->load->view('backend/index', $page_data);
		
		}

		//HUMHUB MESSAGING
		public function chat() {
			// 1) Vérifier que c'est bien un admin
			if (! $this->session->userdata('student_login')) {
				show_error('Accès réservé aux superadmins.');
			}
			
			// 2) Récupérer les infos du user (ici : depuis la table Wayo)
			$userId = $this->session->userdata('user_id');
			$wUser  = $this->db->get_where('users', ['id' => $userId])->row();
			if (empty($wUser) || ! filter_var($wUser->email, FILTER_VALIDATE_EMAIL)) {
				log_message('error', 'Invalid Wayo user data: ' . print_r($wUser, true));
				show_error('Impossible de retrouver votre compte Wayo pour SSO.');
			}
			log_message('debug', 'Wayo user data: ' . print_r($wUser, true));

			// 3) Stocker temporairement cet objet pour la librairie SSO
			$this->session->set_userdata('user', $wUser);

			// 4) Générer l’URL SSO
			$iframeUrl = $this->humhub_sso->provisionAndGetIframeUrl();
			log_message('debug', 'Generated iframe URL: ' . $iframeUrl);

			if (! $iframeUrl) {
				show_error('Impossible de générer l’URL SSO HumHub.');
			}

			// 5) Passer à la vue
			$page_data = [
				'folder_name' => 'humhub',
				'page_title'  => 'Messages',
				'page_name'   => 'message',
				'iframe_url'  => $iframeUrl,
			];
			$this->load->view('backend/index', $page_data);
		
		}

	//START MARKS section
	public function mark($param1 = '', $param2 = ''){

		if($param1 == 'list'){
			$page_data['class_id'] = htmlspecialchars($this->input->post('class_id'));
			$page_data['section_id'] = htmlspecialchars($this->input->post('section_id'));
			// $page_data['subject_id'] = htmlspecialchars($this->input->post('subject'));
			$page_data['exam_id'] = htmlspecialchars($this->input->post('exam'));
			// $this->crud_model->mark_insert($page_data['class_id'], $page_data['section_id'], $page_data['subject_id'], $page_data['exam_id']);
			// $this->load->view('backend/student/mark/list', $page_data);
			// Charger la vue mise à jour
			$response_html = $this->load->view('backend/student/mark/list', $page_data, TRUE);
		    // Préparer le nouveau jeton CSRF
			$csrf = array(
					 'csrfName' => $this->security->get_csrf_token_name(),
					 'csrfHash' => $this->security->get_csrf_hash(),
				 );
			
			// Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
			echo json_encode(array('status' => $response_html, 'csrf' => $csrf));
		}

		if($param1 == 'mark_update'){
			$this->crud_model->mark_update();
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'mark';
			$page_data['page_title'] = 'marks';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END MARKS sesction

	// GRADE SECTION STARTS
	public function grade($param1 = "", $param2 = "") {
		$page_data['folder_name'] = 'grade';
		$page_data['page_title'] = 'grades';
		$this->load->view('backend/index', $page_data);
	}
	// GRADE SECTION ENDS

	// ACCOUNT SECTION STARTS
	public function invoice($param1 = "", $param2 = "") {
		// showing the list of invoices
		if ($param1 == 'invoice') {
			$page_data['invoice_id'] = $param2;
			$page_data['folder_name'] = 'invoice';
			$page_data['page_name'] = 'invoice';
			$page_data['page_title']  = 'invoice';
			$this->load->view('backend/index', $page_data);
		}

		// showing the index file
		if(empty($param1)){
			$page_data['folder_name'] = 'invoice';
			$page_data['page_title']  = 'invoice';
			$this->load->view('backend/index', $page_data);
		}
	}

	// PAYPAL CHECKOUT
	public function paypal_checkout() {
		$invoice_id = htmlspecialchars($this->input->post('invoice_id'));
		$invoice_details = $this->crud_model->get_invoice_by_id($invoice_id);

		$page_data['invoice_id']   = $invoice_id;
		$page_data['user_details']    = $this->user_model->get_student_details_by_id('student', $invoice_details['student_id']);
		$page_data['amount_to_pay']   = $invoice_details['total_amount'] - $invoice_details['paid_amount'];
		$page_data['folder_name'] = 'paypal';
		$page_data['page_title']  = 'paypal_checkout';
		$this->load->view('backend/payment_gateway/paypal_checkout', $page_data);
	}
	// STRIPE CHECKOUT
	public function stripe_checkout() {
		$invoice_id = htmlspecialchars($this->input->post('invoice_id'));
		$invoice_details = $this->crud_model->get_invoice_by_id($invoice_id);

		$page_data['invoice_id']   = $invoice_id;
		$page_data['user_details']    = $this->user_model->get_student_details_by_id('student', $invoice_details['student_id']);
		$page_data['amount_to_pay']   = $invoice_details['total_amount'] - $invoice_details['paid_amount'];
		$page_data['folder_name'] = 'paypal';
		$page_data['page_title']  = 'paypal_checkout';
		$this->load->view('backend/payment_gateway/stripe_checkout', $page_data);
	}

	private function add_student_to_class_space($student_id, $class_id)
    {
        // 1. Récupérer les infos de l'étudiant
        $student = $this->user_model->get_student_details_by_id('student', $student_id);
       
		// 2. Récupérer la classe et son humhub_space_id
		$class = $this->db
					->get_where('classes', ['id' => $class_id])
					->row_array();

		if (! $class) {
			log_message('error', "Classe #{$class_id} introuvable");
			return;
		}

		$space_id       = $class['humhub_space_id'];

		// 3. Récupérer l’ID HumHub de l’utilisateur via son email
		$humhubUser = $this->humhub_sso->getUserByEmail($student['email']);
		if (empty($humhubUser['id'])) {
			log_message('error', "Utilisateur HumHub introuvable pour {$student['email']}");
			return;
    }

		$humhub_user_id = $humhubUser['id'];  

		// 4. Appel à l’API HumHub pour ajouter le membre
		if (!empty($space_id) && !empty($humhub_user_id)) {
			$this->humhub_sso->addUserSpace($space_id, $humhub_user_id);
			log_message('debug', "Étudiant HumHub #{$humhub_user_id} ajouté à l’espace #{$space_id}");
		} else {
			log_message('error', "Impossible d’ajouter l’étudiant à l’espace (space_id ou humhub_user_id manquant)");
		}
    }
	
	public function payment_success($payment_method = "", $invoice_id = "", $amount_paid = "", $reference = "") {
		if ($payment_method == 'stripe') {
			$stripe = json_decode(get_payment_settings('stripe_settings'));
			$token_id = $this->input->post('stripeToken');
			$stripe_test_mode = $stripe[0]->stripe_mode;
            if ($stripe_test_mode == 'on') {
                $public_key = $stripe[0]->stripe_test_public_key;
                $secret_key = $stripe[0]->stripe_test_secret_key;
            } else {
                $public_key = $stripe[0]->stripe_live_public_key;
                $secret_key = $stripe[0]->stripe_live_secret_key;
            }
            $payment_status = $this->payment_model->stripe_payment($token_id, $invoice_id, $amount_paid, $secret_key);
		}elseif($payment_method == 'paystack'){
			$this->load->model('addons/paystack_model');
			$payment_status = $this->paystack_model->check_payment($reference);
		}

		$data['payment_method'] = $payment_method;
		$data['invoice_id'] = $invoice_id;
		$data['amount_paid'] = $amount_paid;
		
		//Pour chaque mode de paiement, si succès → marquer facture ET ajouter étudiant
    if ($payment_method === 'stripe'  && $payment_status === true ||
            $payment_method === 'paystack' && $payment_status === true ||
            $payment_method === 'paypal'  && $payment_status === true) {

        // Marquer la facture comme payée
       $this->crud_model->payment_success($data);
        // Récupérer les détails et ajouter l’étudiant à l’espace HumHub
        $details = $this->crud_model->get_invoice_by_id($invoice_id);
        $this->add_student_to_class_space($details['student_id'], $details['class_id']);

    } else {
        log_message('error', "Échec du paiement pour invoice #{$invoice_id} via {$payment_method}");
    }

		// if($payment_status == true && $payment_method == 'stripe'){
		// 	$this->crud_model->payment_success($data);
		// }elseif($payment_method == 'paystack'){
		// 	$this->crud_model->payment_success($data);
		// }elseif($payment_method == 'paypal'){
		// 	$this->crud_model->payment_success($data);
		// }

		redirect(route('invoice'), 'refresh');
	}
	// ACCOUNT SECTION ENDS

	// BACKOFFICE SECTION

	//BOOK LIST MANAGER
	public function book($param1 = "", $param2 = "") {
		$page_data['folder_name'] = 'book';
		$page_data['page_title']  = 'books';
		$this->load->view('backend/index', $page_data);
	}

	// BOOK ISSUED BY THE STUDENT
	public function book_issue($param1 = "", $param2 = "") {
		// showing the index file
		$page_data['folder_name'] = 'book_issue';
		$page_data['page_title']  = 'issued_book';
		$this->load->view('backend/index', $page_data);
	}

	//MANAGE PROFILE STARTS
	public function profile($param1 = "", $param2 = "") {
		if ($param1 == 'update_profile') {
			$response = $this->user_model->update_profile();
			echo $response;
		}
		if ($param1 == 'update_password') {
			$response = $this->user_model->update_password();
			echo $response;
		}

		// showing the Smtp Settings file
		if(empty($param1)){
			$page_data['folder_name'] = 'profile';
			$page_data['page_title']  = 'manage_profile';
			$this->load->view('backend/index', $page_data);
		}
	}
	//MANAGE PROFILE ENDS

	public function payment($invoice_id = ""){
		$page_data['page_title']  = 'payment_gateway';
		$page_data['invoice_details'] = $this->crud_model->get_invoice_by_id($invoice_id);
		$this->load->view('backend/payment_gateway/index', $page_data);
	}
}
