<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Teacher extends CI_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->database();
		$this->load->library('session');

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
		
		if($this->session->userdata('teacher_login') != 1){
			redirect(site_url('login'), 'refresh');
		}
	}
	//dashboard
	public function index(){
		redirect(route('dashboard'), 'refresh');
	}

	public function dashboard(){

		$page_data['page_title'] = 'Dashboard';
		$page_data['folder_name'] = 'dashboard';
		$this->load->view('backend/index', $page_data);
	}
	   //START TEACHER Create_Join bigbleubutton 
	   public function Create_Join($param1 = '', $param2 = '', $param3 = '')
	   {
	 
   
	 
		 if (empty($param1)) {
		   $page_data['folder_name'] = 'bigbleubutton';
		   $page_data['page_title'] = 'Démarrer Réunion';
		   $this->load->view('backend/index', $page_data);
		 }
	   }
	   //END TEACHER Create_Join bigbleubutton 
		//START TEACHER Create_Join bigbleubutton 
		public function Liveclasse($param1 = '', $param2 = '', $param3 = '')
		{
		
			if ($param1 == 'create') {
			$response = $this->room_model->create_room();
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
			}

			if ($param1 == 'update') {
			$response = $this->room_model->update_room($param2);
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
			}
			if ($param1 == 'list') {
				$this->load->view('backend/teacher/bigbleubutton/list');
			}
		
			if (empty($param1)) {
			$page_data['folder_name'] = 'bigbleubutton';
			$page_data['page_title'] = 'Démarrer Réunion';
			$this->load->view('backend/index', $page_data);
			}
		}
		//END TEACHER Create_Join bigbleubutton 
		
	

		public function get_appointments() {
			$appointments = $this->room_model->get_all_appointments();
			echo json_encode($appointments);
		}
			

			
			public function add_appointment() {
			  // Récupération et sécurisation des données
			  $title = $this->input->post('title', true);
			  $start_date = $this->input->post('start', true);
			  $description = $this->input->post('description', true);
			  $classe_id = $this->input->post('classe_id', true);
			  $room_id = $this->input->post('room_id', true);
			  $sections = $this->input->post('sections'); // Tableau de sections sélectionnées
			  
			
			 
			  // Vérification : Les champs obligatoires ne doivent pas être vides
			  if (empty($title) || empty($start_date)) {
				  echo json_encode(["status" => "error", "message" => "Titre et date sont obligatoires"]);
				  return;
			  }
		  
			  // Création du tableau de données à insérer
			  $data = array(
				  'title' => $title,
				  'start_date' => $start_date,
				  'description' => $description,
				  'classe_id' => $classe_id,
				  'sections_id' => $sections, // Stockage sous forme "1,2,3"
				  'room_id' => $room_id
			  );
		  
			  // Insertion dans la base de données avec gestion d'erreur
			  try {
				  $this->db->insert('appointments', $data);
				  echo json_encode(["status" => "success", "message" => "Rendez-vous ajouté avec succès"]);
			  } catch (Exception $e) {
				  echo json_encode(["status" => "error", "message" => "Erreur lors de l'ajout du rendez-vous : " . $e->getMessage()]);
			  }
			}
		  
			public function update_appointment() {
			  $id = $this->input->post('id');
			  // die( $id);
		  
			  $data = array(
				  'title' => $this->input->post('title'),
				  'start_date' => $this->input->post('start'),
				  'description' => $this->input->post('description'),
				  'classe_id' => $this->input->post('classe_id'),
				  'sections_id' => $this->input->post('sections'),
				  'room_id' => $this->input->post('room_id')
			  );
		  
			  $this->db->where('id', $id);
			  $this->db->update('appointments', $data);
			  echo json_encode(["status" => "updated"]);
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
	//START STUDENT ADN ADMISSION section
	public function student($param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = ''){

		if($param1 == 'create'){
			//form view
			if($param2 == 'bulk'){
				$page_data['aria_expand'] = 'bulk';
				$page_data['working_page'] = 'create';
				$page_data['folder_name'] = 'student';
				$page_data['page_title'] = 'add_student';
				$this->load->view('backend/index', $page_data);
			}elseif($param2 == 'excel'){
				$page_data['aria_expand'] = 'excel';
				$page_data['working_page'] = 'create';
				$page_data['folder_name'] = 'student';
				$page_data['page_title'] = 'add_student';
				$this->load->view('backend/index', $page_data);
			}else{
				$page_data['aria_expand'] = 'single';
				$page_data['working_page'] = 'create';
				$page_data['folder_name'] = 'student';
				$page_data['page_title'] = 'add_student';
				$this->load->view('backend/index', $page_data);
			}
		}

		//create to database
		if($param1 == 'create_single_student'){
			$response = $this->user_model->single_student_create();
			// echo $response;
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				  'csrfHash' => $this->security->get_csrf_hash(),
				);
				
		  // Renvoyer la réponse avec un nouveau jeton CSRF
		  echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'create_bulk_student'){
			$response = $this->user_model->bulk_student_create();
			// echo $response;
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				  'csrfHash' => $this->security->get_csrf_hash(),
				);
				
		  // Renvoyer la réponse avec un nouveau jeton CSRF
		  echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'create_excel'){
			$response = $this->user_model->excel_create();
			// echo $response;
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				  'csrfHash' => $this->security->get_csrf_hash(),
				);
				
		  // Renvoyer la réponse avec un nouveau jeton CSRF
		  echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		// form view
		if($param1 == 'edit'){
			$page_data['student_id'] = $param2;
			$page_data['working_page'] = 'edit';
			$page_data['folder_name'] = 'student';
			$page_data['page_title'] = 'update_student_information';
			$this->load->view('backend/index', $page_data);
		}

		//updated to database
		if($param1 == 'updated'){
			$response = $this->user_model->student_update($param2, $param3);
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
				
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'delete'){
			$response = $this->user_model->delete_student($param2, $param3);
			// echo $response;
			       // Préparer la réponse avec un nouveau jeton CSRF
				   $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				 echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'filter'){
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			// $this->load->view('backend/teacher/student/list', $page_data);
			$html_content = $this->load->view('backend/teacher/student/list', $page_data, TRUE);

			// Prepare a new CSRF token for the response
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Return JSON response with the HTML content and new CSRF token
			echo json_encode(array('html' => $html_content, 'csrf' => $csrf));
		}

		if(empty($param1)){
			$page_data['working_page'] = 'filter';
			$page_data['folder_name'] = 'student';
			$page_data['page_title'] = 'student_list';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END STUDENT ADN ADMISSION section
	public function get_sections_by_class()
	{
	  $class_ids = $this->input->post('class_ids');
	  if (empty($class_ids)) {
		echo json_encode([]);
		return;
	  }
  
	  $sections = [];
	  foreach ($class_ids as $class_id) {
		$this->db->where('class_id', $class_id);
		$result = $this->db->get('sections')->result_array();
		$sections = array_merge($sections, $result);
	  }
  
		   // Prepare a new CSRF token for the response
		   $csrf = array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
		);
	
		// Return JSON response with the HTML content and new CSRF token
		echo json_encode(array('sections' => $sections, 'csrf' => $csrf));
	  
	}
	//START TEACHER section
	public function teacher($param1 = '', $param2 = '', $param3 = ''){


		if($param1 == 'create'){
			$response = $this->user_model->create_teacher();
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'update'){
			$response = $this->user_model->update_teacher($param2);
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'delete'){
			$teacher_id = $this->db->get_where('teachers', array('user_id' => $param2))->row('id');
			$response = $this->user_model->delete_teacher($param2, $teacher_id);
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if ($param1 == 'list') {
			$this->load->view('backend/teacher/teacher/list');
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'teacher';
			$page_data['page_title'] = 'techers';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END TEACHER section

	//START CLASS secion
	public function manage_class($param1 = '', $param2 = '', $param3 = ''){

		if($param1 == 'create'){
			$response = $this->crud_model->class_create();
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
				);
	
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'delete'){
			$response = $this->crud_model->class_delete($param2);
			// echo $response;
			       // Préparer la réponse avec un nouveau jeton CSRF
				   $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'update'){
			$response = $this->crud_model->class_update($param2);
			// echo $response;
			       // Préparer la réponse avec un nouveau jeton CSRF
				   $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'section'){
			$response = $this->crud_model->section_update($param2);
			// echo $response;
			       // Préparer la réponse avec un nouveau jeton CSRF
				   $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		// show data from database
		if ($param1 == 'list') {
			$this->load->view('backend/teacher/class/list');
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
			$this->load->view('backend/teacher/section/list', $page_data);
		}
	}
	//	SECTION ENDED

	//START SUBJECT section
	public function subject($param1 = '', $param2 = ''){

		if($param1 == 'create'){
			$response = $this->crud_model->subject_create();
			echo $response;
		}

		if($param1 == 'update'){
			$response = $this->crud_model->subject_update($param2);
			echo $response;
		}

		if($param1 == 'delete'){
			$response = $this->crud_model->subject_delete($param2);
			echo $response;
		}

		if($param1 == 'list'){
			$page_data['class_id'] = $param2;
			$this->load->view('backend/teacher/subject/list', $page_data);
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
		$this->load->view('backend/teacher/subject/dropdown', $page_data);
	}
	//END SUBJECT section

	//START SYLLABUS section
	public function syllabus($param1 = '', $param2 = '', $param3 = ''){

		if($param1 == 'create'){
			$response = $this->crud_model->syllabus_create();
			// echo $response;
			       // Préparer la réponse avec un nouveau jeton CSRF
				   $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'delete'){
			$response = $this->crud_model->syllabus_delete($param2);
			// echo $response;
			       // Préparer la réponse avec un nouveau jeton CSRF
				   $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'list'){
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			$this->load->view('backend/teacher/syllabus/list', $page_data);
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'syllabus';
			$page_data['page_title'] = 'syllabus';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END SYLLABUS section

	//START CLASS ROUTINE section
	public function routine($param1 = '', $param2 = '', $param3 = '', $param4 = ''){

		if($param1 == 'create'){
			$response = $this->crud_model->routine_create();
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'update'){
			$response = $this->crud_model->routine_update($param2);
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'delete'){
			$response = $this->crud_model->routine_delete($param2);
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'filter'){
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			$this->load->view('backend/teacher/routine/list', $page_data);
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

		if($param1 == 'take_attendance'){
			$response = $this->crud_model->take_attendance();
			// echo $response;
		   // Préparer la réponse avec un nouveau jeton CSRF
		   $csrf = array(
		    'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			);
				 
		   // Renvoyer la réponse avec un nouveau jeton CSRF
		   echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'filter'){
			$date = '01 '.$this->input->post('month').' '.$this->input->post('year');
			$page_data['attendance_date'] = strtotime($date);
			$page_data['class_id'] = htmlspecialchars($this->input->post('class_id'));
			$page_data['section_id'] = htmlspecialchars($this->input->post('section_id'));
			$page_data['month'] = htmlspecialchars($this->input->post('month'));
			$page_data['year'] = htmlspecialchars($this->input->post('year'));
			// $this->load->view('backend/teacher/attendance/list', $page_data);

			// Charger la vue mise à jour
			$response_html = $this->load->view('backend/teacher/attendance/list', $page_data, TRUE);
			// Préparer le nouveau jeton CSRF
			$csrf = array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
				);
	
			// Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
			echo json_encode(array('status' => $response_html, 'csrfName' => $csrf['csrfName'], 'csrfHash' => $csrf['csrfHash']));
		}

		if($param1 == 'student'){
			$page_data['attendance_date'] = strtotime($this->input->post('date'));
			$page_data['class_id'] = htmlspecialchars($this->input->post('class_id'));
			$page_data['section_id'] = htmlspecialchars($this->input->post('section_id'));
			// $this->load->view('backend/teacher/attendance/student', $page_data);
			      // Charger la vue mise à jour
			$response_html = $this->load->view('backend/teacher/attendance/student', $page_data, TRUE);
			// Préparer le nouveau jeton CSRF
			$csrf = array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			);
	
		// Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
				echo json_encode(array('status' => $response_html, 'csrfName' => $csrf['csrfName'], 'csrfHash' => $csrf['csrfHash']));
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'attendance';
			$page_data['page_title'] = 'attendance';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END DAILY ATTENDANCE section


	//START EVENT CALENDAR section
	public function event_calendar($param1 = '', $param2 = ''){

		if($param1 == 'create'){
			$response = $this->crud_model->event_calendar_create();
			// echo $response;
                  // Préparer la réponse avec un nouveau jeton CSRF
				  $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'update'){
			$response = $this->crud_model->event_calendar_update($param2);
			// echo $response;
				// Préparer la réponse avec un nouveau jeton CSRF
				$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'delete'){
			$response = $this->crud_model->event_calendar_delete($param2);
			// echo $response;
                  // Préparer la réponse avec un nouveau jeton CSRF
				  $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'all_events'){
			echo $this->crud_model->all_events();
		}

		if ($param1 == 'list') {
			$this->load->view('backend/teacher/event_calendar/list');
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

		if($param1 == 'create'){
			$response = $this->crud_model->exam_create();
			// echo $response;
				// Préparer la réponse avec un nouveau jeton CSRF
				$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'update'){
			$response = $this->crud_model->exam_update($param2);
			// echo $response;
				// Préparer la réponse avec un nouveau jeton CSRF
				$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
		
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if($param1 == 'delete'){
			$response = $this->crud_model->exam_delete($param2);
			// echo $response;
                  // Préparer la réponse avec un nouveau jeton CSRF
				  $csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		if ($param1 == 'list') {
			$this->load->view('backend/teacher/exam/list');
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'exam';
			$page_data['page_title'] = 'exam';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END EXAM section

	//START MARKS section
	public function mark($param1 = '', $param2 = ''){

		if($param1 == 'list'){
			$page_data['class_id'] = htmlspecialchars($this->input->post('class_id'));
			$page_data['section_id'] = htmlspecialchars($this->input->post('section_id'));
			// $page_data['subject_id'] = htmlspecialchars($this->input->post('subject'));
			$page_data['exam_id'] = htmlspecialchars($this->input->post('exam'));
			$this->crud_model->mark_insert($page_data['class_id'], $page_data['section_id'], $page_data['exam_id']);
			// $this->load->view('backend/teacher/mark/list', $page_data);
			// Charger la vue et capturer le contenu
			$html_content = $this->load->view('backend/teacher/mark/list', $page_data, TRUE);
				
			// Préparer le nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
			
			// Renvoyer la réponse JSON avec le HTML et le nouveau jeton CSRF
			echo json_encode(array('html' => $html_content, 'csrf' => $csrf));
		}

		if($param1 == 'mark_update'){
			$this->crud_model->mark_update();
				// Préparer le nouveau jeton CSRF
				$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			);
			
			// Renvoyer la réponse JSON avec le nouveau jeton CSRF
		
			echo json_encode(array('csrf' => $csrf));
		}

		if(empty($param1)){
			$page_data['folder_name'] = 'mark';
			$page_data['page_title'] = 'marks';
			$this->load->view('backend/index', $page_data);
		}
	}

	// GET THE GRADE ACCORDING TO MARK
	public function get_grade($acquired_mark) {
		echo get_grade($acquired_mark);
	}
	//END MARKS sesction


	// BACKOFFICE SECTION

	//BOOK LIST MANAGER
	public function book($param1 = "", $param2 = "") {
		// adding book
		if ($param1 == 'create') {
			$response = $this->crud_model->create_book();
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			);
	
			// Renvoyer la réponse avec un nouveau jeton CSRF
			echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		// update book
		if ($param1 == 'update') {
			$response = $this->crud_model->update_book($param2);
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				);
		
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}

		// deleting book
		if ($param1 == 'delete') {
			$response = $this->crud_model->delete_book($param2);
			// echo $response;
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				);
		
				// Renvoyer la réponse avec un nouveau jeton CSRF
				echo json_encode(array('status' => $response, 'csrf' => $csrf));
		}
		// showing the list of book
		if ($param1 == 'list') {
			$this->load->view('backend/teacher/book/list');
		}

		// showing the index file
		if(empty($param1)){
			$page_data['folder_name'] = 'book';
			$page_data['page_title']  = 'books';
			$this->load->view('backend/index', $page_data);
		}
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
}
