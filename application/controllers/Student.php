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
		if ($action == 'list') {
			$user_id = $this->session->userdata('user_id');
			$session_id = active_session();
	
			// Récupérer les sections où l'étudiant est inscrit pour la classe donnée
			$this->db->select('sections.id, sections.name');
			$this->db->from('sections');
			$this->db->join('enrols', 'enrols.section_id = sections.id', 'left');
			$this->db->join('students', 'students.id = enrols.student_id', 'left');
			$this->db->where('sections.class_id', $id);
			$this->db->where('students.user_id', $user_id);
			$this->db->where('enrols.session', $session_id);
			$sections = $this->db->get()->result_array();
	
			$output = '<option value="">' . get_phrase('select_section') . '</option>';
			foreach ($sections as $section) {
				$output .= '<option value="' . $section['id'] . '">' . $section['name'] . '</option>';
			}
			echo $output;
		}
	}
    public function section_course($action = "", $id = "") {
		if ($action == 'list') {
			$user_id = $this->session->userdata('user_id');
			$session_id = active_session();
	
			// Récupérer les sections où l'étudiant est inscrit pour la classe donnée
			$this->db->select('sections.id, sections.name');
			$this->db->from('sections');
			// $this->db->join('enrols', 'enrols.section_id = sections.id', 'left');
			// $this->db->join('students', 'students.id = enrols.student_id', 'left');
			$this->db->where('sections.class_id', $id);
			// $this->db->where('students.user_id', $user_id);
			// $this->db->where('enrols.session', $session_id);
			$sections = $this->db->get()->result_array();
	
			$output = '<option value="">' . get_phrase('select_section') . '</option>';
			foreach ($sections as $section) {
				$output .= '<option value="' . $section['id'] . '">' . $section['name'] . '</option>';
			}
			echo $output;
		}
	}
    
	//	SECTION ENDED
      //START student Create_Join bigbleubutton 
      public function Recording($param1 = '', $param2 = '', $param3 = '')
      {
    
        $school_id = school_id();

        // Récupère les données nécessaires
        $page_data['appointments'] = $this->room_model->get_all_appointments_student();
        $page_data['classes'] = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
        $page_data['rooms'] = $this->db->get_where('rooms', array('school_id' => $school_id, 'Etat' => 1))->result_array();

        // Récupère les enregistrements pour chaque rendez-vous
        foreach ($page_data['appointments'] as &$appointment) {
            $appointment['recordings'] = $this->room_model->get_bbb_recording_by_appointment($appointment['id']);
           
        }
        unset($appointment); // Nettoie la référence
    
  
        $page_data['page_name'] = 'bigbleubutton/Recording';
        $page_data['page_title'] = 'Recording';

        $this->load->view('backend/index', $page_data);

   
      }
      //END student Create_Join bigbleubutton

	  
	  public function filter_recordings()
	  {
		  $this->load->config('bigbluebutton');
		  $bbbUrl = $this->config->item('bbb_url');
		  $bbbSecret = $this->config->item('bbb_secret');
	  
		  $meeting_name = $this->input->post('meeting_name', true);
		  $date_range = $this->input->post('date_range', true);
		  $school_id = $this->input->post('school_id', true);
	  
		  // $this->db->select('*')->from('appointments');
		  $schoolID = school_id();
		  // die($schoolID);
		  // Sélection des colonnes nécessaires

		  $this->db->select('
			  appointments.id, 
			  appointments.title, 
			  appointments.start_date AS start, 
			
			  appointments.description, 
			  appointments.sections_id AS section, 
			  appointments.classe_id, 
			  appointments.room_id, 
			 
			  rooms.name,
			  meeting_id,

		  ');
		  $this->db->from('appointments');
	  
		  // Jointure avec la table rooms pour récupérer les informations des salles
		  $this->db->join('rooms', 'rooms.id = appointments.room_id', 'left');
	  
		  // Filtre : récupérer uniquement les rendez-vous actifs
		  $this->db->where('appointments.Etat', 1);
		  //$this->db->where('rooms.school_id', $schoolID);
		  $this->db->where('appointments.school_id', $school_id);
	
	  
		
	  
		  if (!empty($meeting_name)) {
			  $this->db->like('appointments.title', $meeting_name);
		  }
	  
		  if (!empty($date_range)) {
			  $dates = explode(' to ', $date_range);
			  if (count($dates) === 2) {
				  $this->db->where('appointments.start_date >=', $dates[0] . ' 00:00:00');
				  $this->db->where('appointments.start_date <=', $dates[1] . ' 23:59:59');
			  }
		  }
	  
		  $appointments = $this->db->get()->result_array();
	  
		  foreach ($appointments as &$appointment_rec) {
			  $meetingId = $appointment_rec['meeting_id'] ?? null;
			  $appointment_rec['recordings'] = [];
		
			  if ($meetingId) {
				  // Générer l’URL avec meetingID spécifique
				  // $query = http_build_query(['meetingID' => $meetingId]);
				  // $checksum = sha1('getRecordings' . $query . $bbbSecret);
				  // $url = $bbbUrl . 'api/getRecordings?' . $query . '&checksum=' . $checksum;
				  $params = ['meetingID' => $meetingId];
				  $query = http_build_query($params);
				  $checksum = sha1('getRecordings' . $query . $bbbSecret);
				  $url = $bbbUrl . 'getRecordings?' . $query . '&checksum=' . $checksum;
	  
				  // Appel de l’API
				  $ch = curl_init();
				  curl_setopt($ch, CURLOPT_URL, $url);
				  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				  $response = curl_exec($ch);
				  curl_close($ch);
	  
				  $xml = @simplexml_load_string($response);

				  if ($xml && $xml->returncode == 'SUCCESS') {
					  foreach ($xml->recordings->recording as $rec) {
						  $appointment_rec['recordings'][] = [
							  'meetingID' => (string) $rec->meetingID,
							  'playback_url' => (string) $rec->playback->format->url,
							  'duration' => (string) $rec->playback->format->length,
							  'video_download_url' => (string) $rec->playback->format->url,
							  'endTime' => (string) $rec->endTime
						  ];
					  }
				  }
			  }
		  }
	  
		  // Affichage HTML comme avant
		  foreach ($appointments as $appointment) {
			$classe_name = $this->db->get_where('classes', array('id' => $appointment['classe_id']))->row('name');
			$section = " - ";
			  if (!empty($appointment['section'])) {
						  $section_ids = explode(',', $appointment['section']);
						  $section_names = [];
							  foreach ($section_ids as $id) {
										  $name = $this->db->get_where('sections', array('id' => $id))->row('name');
										  if ($name) $section_names[] = $name;
									  }
									$section =  implode(', ', $section_names);
								  } 
			  echo '<tr>';
			  echo '<td>' . htmlspecialchars($appointment['title']) . '</td>';
			  echo '<td>' . htmlspecialchars($appointment['name']) . '</td>';
			  echo '<td>' . htmlspecialchars($classe_name) . '</td>';
			  echo '<td>' . htmlspecialchars($section) . '</td>';
			  echo '<td>' . date('d-m-Y H:i', strtotime($appointment['start'])) . '</td>';
			  echo '<td>' . (!empty($appointment_rec['recordings']) ? $appointment_rec['recordings'][0]['duration'] : '—') . '</td>';
			  echo '<td>';
	  
			  if (!empty($appointment['recordings'])) {
				  $rec = $appointment_rec['recordings'][0];
				  $endTime = !empty($rec['endTime']) ? (int)$rec['endTime'] / 1000 : null;
				  $isExpired = $endTime ? (time() > ($endTime + (7 * 24 * 60 * 60))) : false;
	  
				  if ($isExpired) {
					  echo '<span class="badge bg-warning text-dark"> Expired</span>';
				  } elseif (!empty($rec['playback_url'])) {
					  echo '<a href="' . htmlspecialchars($rec['playback_url']) . '" target="_blank" class="btn btn-sm btn-success">VIDEO</a>';
				  } else {
					  echo '<span class="badge bg-danger">NOT RECORDED</span>';
				  }
			  } else {
				  echo '<span class="badge bg-danger">NOT RECORDED</span>';
			  }
	  
			  echo '</td><td>';
	  
			  if (!empty($appointment_rec['recordings'])) {
				  $rec = $appointment_rec['recordings'][0];
				  echo '<a href="' . htmlspecialchars($rec['video_download_url']) . '" class="btn btn-sm btn-success">Download</a> ';
			  }
	  
		
			  echo '</td></tr>';
		  }
	  }
		//	SECTION STARTED
		public function exam_class($action = "", $id = "") {
			if ($action == 'list') {
				$user_id = $this->session->userdata('user_id');
				$session_id = active_session();
		
				// Récupérer les classes où l'étudiant est inscrit pour l'examen donné
				$this->db->select('classes.id, classes.name');
				$this->db->from('classes');
				$this->db->join('enrols', 'enrols.class_id = classes.id', 'left');
				$this->db->join('students', 'students.id = enrols.student_id', 'left');
				$this->db->join('exams', 'exams.class_id = classes.id', 'left');
				$this->db->where('exams.id', $id);
				$this->db->where('students.user_id', $user_id);
				$this->db->where('enrols.session', $session_id);
				$classes = $this->db->get()->result_array();
		
				$output = '<option value="">' . get_phrase('select_a_class') . '</option>';
				foreach ($classes as $class) {
					$output .= '<option value="' . $class['id'] . '">' . $class['name'] . '</option>';
				}
				echo $output;
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

	// Récupérer les classes par école pour l'étudiant connecté
	public function get_classes_by_school() {
		try {
			$school_id = $this->input->post('school_id');
			$user_id = $this->session->userdata('user_id');
	
			if (!$school_id || !$user_id) {
				log_message('error', 'school_id ou user_id manquant dans get_classes_by_school');
				echo json_encode(['classes' => [], 'csrf_hash' => $this->security->get_csrf_hash()]);
				return;
			}
	
			$session_id = active_session();
			log_message('debug', 'Session active : ' . $session_id);
	
			$this->db->reset_query();
			$this->db->select('classes.*');
			$this->db->from('classes');
			$this->db->join('enrols', 'enrols.class_id = classes.id', 'left');
			$this->db->join('students', 'students.id = enrols.student_id', 'left');
			$this->db->where('classes.school_id', $school_id);
			$this->db->where('students.user_id', $user_id);
			$this->db->where('enrols.session', $session_id);
	
			$db_temp = clone $this->db;
			$query = $db_temp->get_compiled_select();
			log_message('debug', 'Requête SQL compilée : ' . $query);
	
			$classes = $this->db->get()->result_array();
	
			log_message('debug', 'Requête SQL exécutée : ' . $this->db->last_query());
			log_message('debug', 'Classes trouvées : ' . json_encode($classes));
	
			echo json_encode([
				'classes' => $classes,
				'csrf_hash' => $this->security->get_csrf_hash()
			]);
		} catch (Exception $e) {
			log_message('error', 'Erreur dans get_classes_by_school : ' . $e->getMessage());
			http_response_code(500);
			echo json_encode([
				'error' => 'Erreur serveur interne',
				'csrf_hash' => $this->security->get_csrf_hash()
			]);
		}
	}

// Filtrer les examens en fonction des sélections
public function filter_exams() {
    try {
        log_message('debug', 'Début de filter_exams');
        log_message('debug', 'Données POST reçues : ' . json_encode($this->input->post()));
        log_message('debug', 'Utilisateur connecté : ' . $this->session->userdata('user_id'));

        $school_id = $this->input->post('school_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $date_filter = $this->input->post('date_filter');
        $user_id = $this->session->userdata('user_id');

        if (!$school_id || !$class_id || !$section_id || !$user_id) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Données manquantes',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $session_id = active_session();

        if (!$session_id) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Aucune session active',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Vérification des permissions
        $this->db->select('students.id');
        $this->db->from('students');
        $this->db->join('enrols', 'enrols.student_id = students.id', 'left');
        $this->db->where('students.user_id', $user_id);
        $this->db->where('enrols.school_id', $school_id);
        $this->db->where('enrols.class_id', $class_id);
        $this->db->where('enrols.section_id', $section_id);
        $this->db->where('enrols.session', $session_id);

        $student = $this->db->get()->row_array();

        if (!$student) {
            http_response_code(403);
            echo json_encode([
                'error' => 'Non autorisé',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Construire la requête pour récupérer les examens
        $this->db->reset_query();
        $this->db->select('exams.*, classes.name as class_name, sections.name as section_name, schools.name as school_name');
        $this->db->from('exams');
        $this->db->join('classes', 'exams.class_id = classes.id', 'left');
        $this->db->join('sections', 'exams.section_id = sections.id', 'left');
        $this->db->join('schools', 'exams.school_id = schools.id', 'left');
        $this->db->where('exams.school_id', $school_id);
        $this->db->where('exams.class_id', $class_id);
        $this->db->where('exams.section_id', $section_id);
        $this->db->where('exams.session', $session_id);

        // Gérer le filtre de date
        if (!empty($date_filter)) {
            // Parser le filtre de date
            $dates = explode(' - ', $date_filter);
            if (count($dates) == 1) {
                // Date unique
                $start_date = DateTime::createFromFormat('d-m-Y', trim($dates[0]));
                if ($start_date) {
                    $start_timestamp = $start_date->setTime(0, 0, 0)->getTimestamp();
                    $end_timestamp = $start_date->setTime(23, 59, 59)->getTimestamp();
                    $this->db->where('exams.starting_date >=', $start_timestamp);
                    $this->db->where('exams.starting_date <=', $end_timestamp);
                }
            } elseif (count($dates) == 2) {
                // Plage de dates
                $start_date = DateTime::createFromFormat('d-m-Y', trim($dates[0]));
                $end_date = DateTime::createFromFormat('d-m-Y', trim($dates[1]));
                if ($start_date && $end_date) {
                    $start_timestamp = $start_date->setTime(0, 0, 0)->getTimestamp();
                    $end_timestamp = $end_date->setTime(23, 59, 59)->getTimestamp();
                    $this->db->where('exams.starting_date >=', $start_timestamp);
                    $this->db->where('exams.starting_date <=', $end_timestamp);
                }
            }
        }

        $exams = $this->db->get()->result_array();
        $exam_calendar = [];
        $current_time = time(); // Current timestamp

        foreach ($exams as $exam) {
            $exam_calendar[] = [
                'title' => $exam['name'],
                'start' => date('Y-m-d H:i:s', $exam['starting_date'])
            ];
        }

        // Génération du tableau HTML
        $table_html = '';
        foreach ($exams as $exam) {
            $exam_start_time = $exam['starting_date'];
            $table_html .= '<tr>';
            $table_html .= '<td>' . htmlspecialchars($exam['name']) . '</td>';
            $table_html .= '<td>' . date('D, d-M-Y H:i', $exam_start_time) . '</td>';
            $table_html .= '<td>' . (!empty($exam['class_name']) ? htmlspecialchars($exam['class_name']) : get_phrase('no_class')) . '</td>';
            $table_html .= '<td>' . (!empty($exam['section_name']) ? htmlspecialchars($exam['section_name']) : get_phrase('no_section')) . '</td>';

            // Vérifier si l'examen a déjà été soumis
            $this->db->select('id');
            $this->db->from('exam_responses');
            $this->db->where('exam_id', $exam['id']);
            $this->db->where('user_id', $user_id);
            $has_submitted = $this->db->get()->row_array();

            if ($has_submitted) {
                // Bouton pour afficher les résultats dans un popup
                $table_html .= '<td><button class="btn btn-sm btn-success view-results-btn" data-exam-id="' . $exam['id'] . '" data-student-id="' . $student['id'] . '">' . get_phrase('view_results') . '</button></td>';
            } elseif ($exam_start_time > $current_time) {
                // Afficher le compteur pour les examens futurs
                $table_html .= '<td data-exam-start="' . $exam_start_time . '" data-exam-id="' . $exam['id'] . '" class="exam-countdown">';
                $table_html .= get_phrase('exam_not_yet_available') . '<br>';
                $table_html .= '<span class="countdown-text" style="background-color: #3A87AD; color:white; border-radius: 5px; padding:3px;"></span></td>';
            } else {
                // Afficher le bouton d'accès
                $table_html .= '<td><a href="' . site_url('student/online_exam/' . $exam['id']) . '" target="_blank" class="btn btn-sm btn-primary access-exam-btn">' . get_phrase('access') . '</a></td>';
            }

            $table_html .= '</tr>';
        }
        log_message('debug', 'Tableau HTML généré : ' . $table_html);

        echo json_encode([
            'exam_calendar' => $exam_calendar,
            'table_html' => $table_html,
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    } catch (Exception $e) {
        log_message('error', 'Erreur dans filter_exams : ' . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'error' => 'Erreur serveur interne : ' . $e->getMessage(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }
}

public function get_sections() {
	try {
		$class_id = $this->input->post('classe_id');
		$user_id = $this->session->userdata('user_id');

		if (!$class_id || !$user_id) {
			log_message('error', 'class_id ou user_id manquant dans get_sections');
			echo json_encode(['sections' => [], 'csrf_hash' => $this->security->get_csrf_hash()]);
			return;
		}

		$session_id = active_session();
		log_message('debug', 'Session active : ' . $session_id);

		$this->db->select('sections.*');
		$this->db->from('sections');
		$this->db->join('enrols', 'enrols.section_id = sections.id', 'left');
		$this->db->join('students', 'students.id = enrols.student_id', 'left');
		$this->db->where('sections.class_id', $class_id);
		$this->db->where('students.user_id', $user_id);
		$this->db->where('enrols.session', $session_id);

		$sections = $this->db->get()->result_array();

		log_message('debug', 'Requête SQL exécutée : ' . $this->db->last_query());
		log_message('debug', 'Sections trouvées : ' . json_encode($sections));

		echo json_encode([
			'sections' => $sections,
			'csrf_hash' => $this->security->get_csrf_hash()
		]);
	} catch (Exception $e) {
		log_message('error', 'Erreur dans get_sections : ' . $e->getMessage());
		http_response_code(500);
		echo json_encode([
			'error' => 'Erreur serveur interne',
			'csrf_hash' => $this->security->get_csrf_hash()
		]);
	}
}

// Dans Student.php
public function online_exam($exam_id = "") {
    try {
        if (empty($exam_id)) {
            log_message('error', 'ID de l\'examen manquant dans online_exam');
            redirect(site_url('student/exam'), 'refresh');
        }

        // Ajouter des en-têtes anti-cache
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->output->set_header("Pragma: no-cache");
        $this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");

        $user_id = $this->session->userdata('user_id');
        $session_id = active_session();

        // Vérifier si l'étudiant a accès à cet examen
        $this->db->select('exams.*, classes.name as class_name, sections.name as section_name, schools.name as school_name');
        $this->db->from('exams');
        $this->db->join('classes', 'exams.class_id = classes.id', 'left');
        $this->db->join('sections', 'exams.section_id = sections.id', 'left');
        $this->db->join('schools', 'exams.school_id = schools.id', 'left');
        $this->db->join('enrols', 'enrols.class_id = exams.class_id AND enrols.section_id = exams.section_id', 'left');
        $this->db->join('students', 'students.id = enrols.student_id', 'left');
        $this->db->where('exams.id', $exam_id);
        $this->db->where('students.user_id', $user_id);
        $this->db->where('enrols.session', $session_id);

        $exam = $this->db->get()->row_array();

        if (!$exam) {
            log_message('error', 'Examen non trouvé ou accès non autorisé pour exam_id: ' . $exam_id);
            $this->session->set_flashdata('error_message', get_phrase('exam_not_found_or_unauthorized'));
            redirect(site_url('student/exam'), 'refresh');
        }

        // Vérifier si l'étudiant a déjà soumis l'examen
        $this->db->select('id');
        $this->db->from('exam_responses');
        $this->db->where('exam_id', $exam_id);
        $this->db->where('user_id', $user_id);
        $existing_submission = $this->db->get()->row_array();

        if ($existing_submission) {
            // L'étudiant a déjà passé l'examen, rediriger vers la liste des examens
            $this->session->set_flashdata('success_message', get_phrase('exam_already_submitted'));
            redirect(site_url('student/exam'), 'refresh');
        }

        // Vérifier si l'examen a commencé
        $current_time = time();
        if ($exam['starting_date'] > $current_time) {
            log_message('error', 'L\'examen n\'a pas encore commencé pour exam_id: ' . $exam_id);
            $this->session->set_flashdata('error_message', get_phrase('exam_not_yet_available'));
            redirect(site_url('student/exam'), 'refresh');
        }

        // Récupérer les questions de l'examen
        $questions = $this->get_exam_questions($exam_id);

        // Préparer les données pour la vue
        $page_data['exam_id'] = $exam_id;
        $page_data['exam_details'] = $exam;
        $page_data['questions'] = $questions;
        $page_data['page_title'] = $exam['name'];

        // Charger la vue via online_exams/index.php
        $this->load->view('online_exams/index', $page_data);
    } catch (Exception $e) {
        log_message('error', 'Erreur dans online_exam : ' . $e->getMessage());
        $this->session->set_flashdata('error_message', get_phrase('server_error'));
        redirect(site_url('student/exam'), 'refresh');
    }
}

public function get_exam_questions($exam_id) {
    $this->db->select('exam_questions.*');
    $this->db->from('exam_questions');
    $this->db->where('exam_id', $exam_id);
    return $this->db->get()->result_array();
}

public function submit_exam() {
    try {
        // Vérifier si la requête est POST
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            log_message('error', 'Méthode non autorisée dans submit_exam');
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $exam_id = $this->input->post('exam_id');
        $session_id = active_session();

        if (!$user_id || !$exam_id) {
            log_message('error', 'user_id ou exam_id manquant dans submit_exam');
            http_response_code(400);
            echo json_encode(['error' => 'Données manquantes']);
            return;
        }

        // Vérifier si l'étudiant a le droit de soumettre cet examen
        $this->db->select('exams.*, classes.id as class_id, sections.id as section_id, schools.id as school_id');
        $this->db->from('exams');
        $this->db->join('classes', 'exams.class_id = classes.id', 'left');
        $this->db->join('sections', 'exams.section_id = sections.id', 'left');
        $this->db->join('schools', 'exams.school_id = schools.id', 'left');
        $this->db->join('enrols', 'enrols.class_id = exams.class_id AND enrols.section_id = exams.section_id', 'left');
        $this->db->join('students', 'students.id = enrols.student_id', 'left');
        $this->db->where('exams.id', $exam_id);
        $this->db->where('students.user_id', $user_id);
        $this->db->where('enrols.session', $session_id);

        $exam = $this->db->get()->row_array();

        if (!$exam) {
            log_message('error', 'Examen non trouvé ou accès non autorisé pour exam_id: ' . $exam_id);
            http_response_code(403);
            echo json_encode(['error' => 'Non autorisé']);
            return;
        }

        // Récupérer l'étudiant
        $student_data = $this->db->get_where('students', ['user_id' => $user_id, 'school_id' => $exam['school_id']])->row_array();
        if (!$student_data) {
            log_message('error', 'Étudiant non trouvé pour user_id: ' . $user_id);
            http_response_code(400);
            echo json_encode(['error' => 'Étudiant non trouvé']);
            return;
        }

        // Récupérer les questions de l'examen
        $questions = $this->get_exam_questions($exam_id);
        $total_questions = count($questions);
        $total_correct_answers = 0;
        $submitted_answers = [];

        if ($total_questions == 0) {
            log_message('error', 'Aucune question trouvée pour exam_id: ' . $exam_id);
            http_response_code(400);
            echo json_encode(['error' => 'Aucune question trouvée']);
            return;
        }

        // Étape 1 : Supprimer les anciennes réponses pour cet examen et cet utilisateur
        $this->db->where('exam_id', $exam_id);
        $this->db->where('user_id', $user_id);
        $this->db->delete('exam_responses');
        log_message('debug', 'Anciennes réponses supprimées pour exam_id: ' . $exam_id . ' et user_id: ' . $user_id);

        // Étape 2 : Traiter les réponses soumises
        foreach ($questions as $question) {
            $question_id = $question['id'];

            // Récupérer les options disponibles pour la question
            $options = json_decode($question['options'], true);
            if (!is_array($options) || empty($options)) {
                log_message('error', 'Options mal formatées pour la question ID ' . $question_id . ': ' . $question['options']);
                $options = [];
            }

            // Extraire les réponses correctes
            $correct_answers_data = json_decode($question['correct_answers'], true);
            if (!is_array($correct_answers_data) || empty($correct_answers_data)) {
                $correct_answers_raw = is_array($correct_answers_data) ? $correct_answers_data[0] : $question['correct_answers'];
                if (is_numeric($correct_answers_raw) && isset($options[$correct_answers_raw - 1])) {
                    $correct_answers_index = (int)$correct_answers_raw - 1;
                    $correct_answers = $options[$correct_answers_index];
                    log_message('debug', 'Index base-1 détecté pour question ID ' . $question_id . ': ' . $correct_answers_raw . ' -> ' . $correct_answers);
                } else {
                    log_message('error', 'Réponses correctes mal formatées pour la question ID ' . $question_id . ': ' . $question['correct_answers']);
                    $correct_answers = '';
                }
            } else {
                $correct_answers = trim((string)$correct_answers_data[0]);
                if (is_numeric($correct_answers) && isset($options[$correct_answers - 1])) {
                    $correct_answers_index = (int)$correct_answers - 1;
                    $correct_answers = $options[$correct_answers_index];
                    log_message('debug', 'Index base-1 détecté pour question ID ' . $question_id . ': ' . $correct_answers_data[0] . ' -> ' . $correct_answers);
                }
            }

            // Vérifier si la réponse correcte est dans les options
            if (!in_array($correct_answers, $options) && $correct_answers !== '') {
                log_message('error', 'Réponse correcte "' . $correct_answers . '" pour la question ID ' . $question_id . ' ne correspond à aucune option: ' . json_encode($options));
                $correct_answers = '';
            }

            $submitted_answers_value = $this->input->post('question_' . $question_id);
            $submitted_answers_value = trim((string)$submitted_answers_value);

            $submitted_answer_status = ($submitted_answers_value == $correct_answers) ? 1 : 0;
            log_message('debug', 'Comparaison pour question ID ' . $question_id . ': submitted="' . $submitted_answers_value . '", correct="' . $correct_answers . '", status=' . $submitted_answer_status);

            if ($submitted_answer_status) {
                $total_correct_answers++;
            }

            // Stocker les détails de la réponse dans exam_responses
            $data = [
                'user_id' => $user_id,
                'exam_id' => $exam_id,
                'exam_question_id' => $question_id,
                'submitted_answers' => $submitted_answers_value ?: 'Aucune réponse',
                'correct_answers' => $correct_answers,
                'submitted_answer_status' => $submitted_answer_status,
                'date_submitted' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('exam_responses', $data);

            $submitted_answers[] = [
                'question_id' => $question_id,
                'question_title' => $question['title'],
                'submitted_answer' => $submitted_answers_value ?: 'Aucune réponse',
                'correct_answer' => $correct_answers,
                'status' => $submitted_answer_status,
                'options' => $options
            ];
        }

        // Calculer la note (sur 100, arrondie car mark_obtained est un int)
        $mark_obtained = ($total_questions > 0) ? round(($total_correct_answers / $total_questions) * 100) : 0;

        // Vérifier si une entrée existe déjà dans la table marks
        $this->db->where([
            'student_id' => $student_data['id'],
            'exam_id' => $exam_id,
            'class_id' => $exam['class_id'],
            'section_id' => $exam['section_id'],
            'school_id' => $exam['school_id'],
            'session' => $session_id
        ]);
        $existing_mark = $this->db->get('marks')->row_array();

        if ($existing_mark) {
            // Mettre à jour la note existante
            $this->db->where('id', $existing_mark['id']);
            $this->db->update('marks', [
                'mark_obtained' => $mark_obtained,
            ]);
        } else {
            // Insérer une nouvelle entrée
            $this->db->insert('marks', [
                'student_id' => $student_data['id'],
                'subject_id' => NULL, // Pas de matière pour les examens en ligne
                'exam_id' => $exam_id,
                'class_id' => $exam['class_id'],
                'section_id' => $exam['section_id'],
                'school_id' => $exam['school_id'],
                'session' => $session_id,
                'mark_obtained' => $mark_obtained,
                'comment' => ''
            ]);
        }

        // Réponse JSON pour le front-end
        $response = [
            'message' => get_phrase('exam_submitted_successfully'),
            'total_questions' => $total_questions,
            'total_correct_answers' => $total_correct_answers,
            'mark_obtained' => $mark_obtained,
            'submitted_answers' => $submitted_answers,
            'csrf_hash' => $this->security->get_csrf_hash()
        ];

        log_message('debug', 'Examen soumis avec succès pour exam_id: ' . $exam_id);
        echo json_encode($response);
    } catch (Exception $e) {
        log_message('error', 'Erreur dans submit_exam : ' . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'error' => 'Erreur serveur interne : ' . $e->getMessage(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }
}

public function exam_results($exam_id = "", $student_id = "") {
    try {

        if (!$this->session->userdata('student_login') || $this->session->userdata('user_type') != 'student') {
            redirect(site_url('login'), 'refresh');
        }

        $user_id = $this->session->userdata('user_id');
        $session_id = active_session();

        $this->db->select('exams.*, classes.name as class_name, sections.name as section_name, schools.name as school_name');
        $this->db->from('exams');
        $this->db->join('classes', 'exams.class_id = classes.id', 'left');
        $this->db->join('sections', 'exams.section_id = sections.id', 'left');
        $this->db->join('schools', 'exams.school_id = schools.id', 'left');
        $this->db->join('enrols', 'enrols.class_id = exams.class_id AND enrols.section_id = exams.section_id', 'left');
        $this->db->join('students', 'students.id = enrols.student_id', 'left');
        $this->db->where('exams.id', $exam_id);
        $this->db->where('students.user_id', $user_id);
        $this->db->where('enrols.session', $session_id);

        $exam = $this->db->get()->row_array();

        if (!$exam) {
            redirect(site_url('student/exam'), 'refresh');
        }

        // Récupérer l'ID de l'étudiant à partir de user_id
        $student_data = $this->db->get_where('students', ['user_id' => $user_id, 'school_id' => $exam['school_id']])->row_array();
        if (!$student_data || $student_data['id'] != $student_id) {
            redirect(site_url('student/exam'), 'refresh');
        }

        // Récupérer les réponses soumises
        $this->db->select('er.*, eq.title as question_title');
        $this->db->from('exam_responses er');
        $this->db->join('exam_questions eq', 'er.exam_question_id = eq.id', 'left');
        $this->db->where('er.exam_id', $exam_id);
        $this->db->where('er.user_id', $user_id);
        $submitted_answers = $this->db->get()->result_array();

        // Préparer les données pour la vue
        $page_data['exam_details'] = $exam;
        $page_data['submitted_answers'] = $submitted_answers;
        $page_data['page_title'] = $exam['name'] . ' - ' . get_phrase('results');

        // Charger la vue partielle pour le modal
        $this->load->view('backend/student/mark/exam_results_modal', $page_data);
    } catch (Exception $e) {
        redirect(site_url('student/exam'), 'refresh');
    }
}

public function get_exam_results_popup($exam_id = "", $student_id = "") {
    try {
        if (!$this->session->userdata('student_login') || $this->session->userdata('user_type') != 'student') {
            http_response_code(403);
            echo json_encode(['error' => 'Non autorisé']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $session_id = active_session();

        // Vérifier si l'étudiant a accès à cet examen
        $this->db->select('exams.*, classes.name as class_name, sections.name as section_name, schools.name as school_name');
        $this->db->from('exams');
        $this->db->join('classes', 'exams.class_id = classes.id', 'left');
        $this->db->join('sections', 'exams.section_id = sections.id', 'left');
        $this->db->join('schools', 'exams.school_id = schools.id', 'left');
        $this->db->join('enrols', 'enrols.class_id = exams.class_id AND enrols.section_id = exams.section_id', 'left');
        $this->db->join('students', 'students.id = enrols.student_id', 'left');
        $this->db->where('exams.id', $exam_id);
        $this->db->where('students.user_id', $user_id);
        $this->db->where('enrols.session', $session_id);

        $exam = $this->db->get()->row_array();

        if (!$exam) {
            http_response_code(403);
            echo json_encode(['error' => 'Examen non trouvé ou accès non autorisé']);
            return;
        }

        // Vérifier si l'étudiant correspond
        $student_data = $this->db->get_where('students', ['user_id' => $user_id, 'school_id' => $exam['school_id']])->row_array();
        if (!$student_data || $student_data['id'] != $student_id) {
            http_response_code(403);
            echo json_encode(['error' => 'Étudiant non autorisé']);
            return;
        }

        // Récupérer les réponses soumises
        $this->db->select('er.*, eq.title as question_title');
        $this->db->from('exam_responses er');
        $this->db->join('exam_questions eq', 'er.exam_question_id = eq.id', 'left');
        $this->db->where('er.exam_id', $exam_id);
        $this->db->where('er.user_id', $user_id);
        $submitted_answers = $this->db->get()->result_array();

        // Préparer les données pour la vue
        $page_data['exam_details'] = $exam;
        $page_data['submitted_answers'] = $submitted_answers;
        $page_data['page_title'] = $exam['name'] . ' - ' . get_phrase('results');

        // Rendre la vue partielle et retourner le HTML
        $html_content = $this->load->view('backend/student/mark/exam_results_modal', $page_data, TRUE);

        echo json_encode([
            'html' => $html_content,
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    } catch (Exception $e) {
        log_message('error', 'Erreur dans get_exam_results_popup : ' . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'error' => 'Erreur serveur interne : ' . $e->getMessage(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }
}

public function load_initial_exams() {
    try {
        log_message('debug', 'Début de load_initial_exams');
        log_message('debug', 'Données POST reçues : ' . json_encode($this->input->post()));
        log_message('debug', 'Utilisateur connecté : ' . $this->session->userdata('user_id'));

        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $date_filter = $this->input->post('date_filter');
        $user_id = $this->session->userdata('user_id');

        if (!$class_id || !$section_id || !$user_id) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Données manquantes',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $session_id = active_session();
        if (!$session_id) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Aucune session active',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Récupérer les écoles auxquelles l'étudiant est inscrit
        $this->db->select('schools.id');
        $this->db->from('schools');
        $this->db->join('enrols', 'enrols.school_id = schools.id');
        $this->db->join('students', 'students.id = enrols.student_id');
        $this->db->where('students.user_id', $user_id);
        $this->db->where('enrols.session', $session_id);
        $school_ids = $this->db->get()->result_array();
        $school_ids = array_column($school_ids, 'id');

        if (empty($school_ids)) {
            http_response_code(403);
            echo json_encode([
                'error' => 'Non autorisé',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Vérification des permissions
        $this->db->select('students.id');
        $this->db->from('students');
        $this->db->join('enrols', 'enrols.student_id = students.id', 'left');
        $this->db->where('students.user_id', $user_id);
        $this->db->where_in('enrols.school_id', $school_ids);
        $this->db->where('enrols.class_id', $class_id);
        $this->db->where('enrols.section_id', $section_id);
        $this->db->where('enrols.session', $session_id);

        $student = $this->db->get()->row_array();

        if (!$student) {
            http_response_code(403);
            echo json_encode([
                'error' => 'Non autorisé',
                'csrf_hash' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        // Début de la journée actuelle (00:00:00)
        $today_start = strtotime('today midnight');

        // Construire la requête pour récupérer les examens
        $this->db->reset_query();
        $this->db->select('exams.*, classes.name as class_name, sections.name as section_name, schools.name as school_name');
        $this->db->from('exams');
        $this->db->join('classes', 'exams.class_id = classes.id', 'left');
        $this->db->join('sections', 'exams.section_id = sections.id', 'left');
        $this->db->join('schools', 'exams.school_id = schools.id', 'left');
        $this->db->where_in('exams.school_id', $school_ids);
        $this->db->where('exams.class_id', $class_id);
        $this->db->where('exams.section_id', $section_id);
        $this->db->where('exams.session', $session_id);
        $this->db->where('exams.starting_date >=', $today_start);

        // Gérer le filtre de date
        if (!empty($date_filter)) {
            // Parser le filtre de date
            $dates = explode(' - ', $date_filter);
            if (count($dates) == 1) {
                // Date unique
                $start_date = DateTime::createFromFormat('d-m-Y', trim($dates[0]));
                if ($start_date) {
                    $start_timestamp = $start_date->setTime(0, 0, 0)->getTimestamp();
                    $end_timestamp = $start_date->setTime(23, 59, 59)->getTimestamp();
                    $this->db->where('exams.starting_date >=', $start_timestamp);
                    $this->db->where('exams.starting_date <=', $end_timestamp);
                }
            } elseif (count($dates) == 2) {
                // Plage de dates
                $start_date = DateTime::createFromFormat('d-m-Y', trim($dates[0]));
                $end_date = DateTime::createFromFormat('d-m-Y', trim($dates[1]));
                if ($start_date && $end_date) {
                    $start_timestamp = $start_date->setTime(0, 0, 0)->getTimestamp();
                    $end_timestamp = $end_date->setTime(23, 59, 59)->getTimestamp();
                    $this->db->where('exams.starting_date >=', $start_timestamp);
                    $this->db->where('exams.starting_date <=', $end_timestamp);
                }
            }
        }

        $exams = $this->db->get()->result_array();
        $exam_calendar = [];
        $current_time = time();

        foreach ($exams as $exam) {
            $exam_calendar[] = [
                'title' => $exam['name'],
                'start' => date('Y-m-d H:i:s', $exam['starting_date'])
            ];
        }

        // Génération du tableau HTML
        $table_html = '';
        foreach ($exams as $exam) {
            $exam_start_time = $exam['starting_date'];
            $table_html .= '<tr>';
            $table_html .= '<td>' . htmlspecialchars($exam['name']) . '</td>';
            $table_html .= '<td>' . date('D, d-M-Y H:i', $exam_start_time) . '</td>';
            $table_html .= '<td>' . (!empty($exam['class_name']) ? htmlspecialchars($exam['class_name']) : get_phrase('no_class')) . '</td>';
            $table_html .= '<td>' . (!empty($exam['section_name']) ? htmlspecialchars($exam['section_name']) : get_phrase('no_section')) . '</td>';

            $this->db->select('id');
            $this->db->from('exam_responses');
            $this->db->where('exam_id', $exam['id']);
            $this->db->where('user_id', $user_id);
            $has_submitted = $this->db->get()->row_array();

            if ($has_submitted) {
                $table_html .= '<td><button class="btn btn-sm btn-success view-results-btn" data-exam-id="' . $exam['id'] . '" data-student-id="' . $student['id'] . '">' . get_phrase('view_results') . '</button></td>';
            } elseif ($exam_start_time > $current_time) {
                $table_html .= '<td data-exam-start="' . $exam_start_time . '" data-exam-id="' . $exam['id'] . '" class="exam-countdown">';
                $table_html .= get_phrase('exam_not_yet_available') . '<br>';
                $table_html .= '<span class="countdown-text" style="background-color: #3A87AD; color:white; border-radius: 5px; padding:3px;"></span></td>';
            } else {
                $table_html .= '<td><a href="' . site_url('student/online_exam/' . $exam['id']) . '" target="_blank" class="btn btn-sm btn-primary access-exam-btn">' . get_phrase('access') . '</a></td>';
            }

            $table_html .= '</tr>';
        }
        log_message('debug', 'Tableau HTML généré : ' . $table_html);

        echo json_encode([
            'exam_calendar' => $exam_calendar,
            'table_html' => $table_html,
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    } catch (Exception $e) {
        log_message('error', 'Erreur dans load_initial_exams : ' . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'error' => 'Erreur serveur interne : ' . $e->getMessage(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }
}

public function get_classes_by_student() {
    $user_id = $this->session->userdata('user_id');
    $session_id = active_session();

    $this->db->select('classes.id, classes.name');
    $this->db->from('classes');
    $this->db->join('enrols', 'enrols.class_id = classes.id');
    $this->db->join('students', 'students.id = enrols.student_id');
    $this->db->where('students.user_id', $user_id);
    $this->db->where('enrols.session', $session_id);
    $this->db->group_by('classes.id');

    $classes = $this->db->get()->result_array();
    echo json_encode([
        'classes' => $classes,
        'csrf_hash' => $this->security->get_csrf_hash()
    ]);
}
}
