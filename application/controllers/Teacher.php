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
		$this->load->library('Humhub_sso');
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

		
		// 1) Vérifier que c'est bien un admin
			if (! $this->session->userdata('teacher_login')) {
				show_error('Accès réservé aux enseignants.');
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
		
	
		public function Calendar($class_id = null , $room_id = null)
		 {
				if ($class_id === null || $room_id === null ) {
					show_404(); // Erreur 404 si aucun ID n'est fourni
				}
					$page_data['page_name'] = 'bigbleubutton/calendar';
					$page_data['page_title'] = 'Calendar';
					$page_data['classe_id'] = $class_id;
					$page_data['room_id'] = $room_id;
					$this->load->view('backend/index', $page_data);
	
		 }
	
		  public function get_appointments() 
		  {
			$appointments = $this->room_model->get_all_appointments();
			echo json_encode($appointments);
		  }
		  
		public function add_appointment() 
		{
			$schoolID = school_id();
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
				'room_id' => $room_id,
				'school_id' => $schoolID
			);
		
			// Insertion dans la base de données avec gestion d'erreur
			try {
				$this->db->insert('appointments', $data);
				echo json_encode(["status" => "success", "message" => "Rendez-vous ajouté avec succès"]);
			} catch (Exception $e) {
				echo json_encode(["status" => "error", "message" => "Erreur lors de l'ajout du rendez-vous : " . $e->getMessage()]);
			}
		}
		
		  public function update_appointment()
		   {
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
		
		  public function delete_appointment() {
			  $id = $this->input->post('id');
	
			  $appointments ['Etat'] = 0;
	
			  $this->db->where('id', $id);
			  $this->db->update('appointments', $appointments);
		  
		  
			  // $this->db->where('id', $id);
			  // $this->db->delete('appointments');
			  echo json_encode(["status" => "deleted"]);
		  }
		  public function get_sections() 
		  {
			$classe_id = $this->input->post('classe_id');
		
			if (!empty($classe_id)) {
				$sections = $this->db->get_where('sections', array('class_id' => $classe_id))->result_array();
			} else {
				$sections = [];
			}
		
			echo json_encode($sections);
		  }
		  public function delete_room()
		  {
			  $data = json_decode(file_get_contents("php://input"), true);
			  $roomID = $data['selectedRoomID'];
			  // $this->db->delete("rooms", ["id" => $roomID]);
			  $this->room_model->update_room_by_id($roomID);
			  echo json_encode(["status" => "success", "message" => "Room supprimée avec succès !"]);
		  }
	
	
		  //START TEACHER Create_Join bigbleubutton 
		  public function Recording($param1 = '', $param2 = '', $param3 = '')
		  {
		
			$school_id = school_id();
	
			// Récupère les données nécessaires
			$page_data['appointments'] = $this->room_model->get_all_appointments();
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
		  //END TEACHER Create_Join bigbleubutton
	
		  public function filter_recordings()
		  {
			  $this->load->config('bigbluebutton');
			  $bbbUrl = rtrim($this->config->item('bbb_url'), '/') . '/';
			  $bbbSecret = $this->config->item('bbb_secret');
		  
			  $meeting_name = trim($this->input->post('meeting_name', true));
			  $date_range = $this->input->post('date_range', true);
			  $schoolID = school_id();
		  
			  // Préparation de la requête principale
			  $this->db->select('
				  appointments.id,
				  appointments.title,
				  appointments.start_date AS start,
				  appointments.description,
				  appointments.sections_id AS section,
				  appointments.classe_id,
				  classes.name AS class_name,
				  appointments.room_id,
				  rooms.name AS room_name,
				  appointments.meeting_id
			  ');
			  $this->db->from('appointments');
			  $this->db->join('rooms', 'rooms.id = appointments.room_id', 'left');
			  $this->db->join('classes', 'classes.id = appointments.classe_id', 'left');
			  $this->db->where('appointments.Etat', 1);
			  $this->db->where('appointments.school_id', $schoolID);
		  
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
				  $appointment['recordings'] = [];
				  $meetingId = $appointment_rec['meeting_id'] ?? null;
		  
				  if ($meetingId) {
					  $params = ['meetingID' => $meetingId];
					  $query = http_build_query($params);
					  $checksum = sha1('getRecordings' . $query . $bbbSecret);
					  $url = $bbbUrl . 'getRecordings?' . $query . '&checksum=' . $checksum;
		  
					  $ch = curl_init($url);
					  curl_setopt_array($ch, [
						  CURLOPT_RETURNTRANSFER => 1,
						  CURLOPT_SSL_VERIFYPEER => false,
						  CURLOPT_SSL_VERIFYHOST => false,
					  ]);
					  $response = curl_exec($ch);
					  curl_close($ch);
		  
					  $xml = @simplexml_load_string($response);
					  if ($xml && $xml->returncode == 'SUCCESS') {
						  foreach ($xml->recordings->recording as $rec) {
							  $appointment_rec['recordings'][] = [
								  'meetingID' => (string)$rec->meetingID,
								  'playback_url' => (string)$rec->playback->format->url,
								  'duration' => (string)$rec->playback->format->length,
								  'video_download_url' => (string)$rec->playback->format->url,
								  'endTime' => (string)$rec->endTime,
							  ];
						  }
					  }
				  }
			  }
	   
			  // 🔽 Affichage HTML
			  foreach ($appointments as $appointment) {
				  // Traitement des sections
				  $section_label = '—';
				  if (!empty($appointment['section'])) {
					  $section_ids = explode(',', $appointment['section']);
					  $section_names = [];
					  foreach ($section_ids as $id) {
						  $name = $this->db->get_where('sections', ['id' => $id])->row('name');
						  if ($name) $section_names[] = $name;
					  }
					  $section_label = implode(', ', $section_names);
				  }
		  
				  // Affichage ligne
				  echo '<tr>';
				  echo '<td>' . htmlspecialchars($appointment['title']) . '</td>';
				  echo '<td>' . htmlspecialchars($appointment['room_name']) . '</td>';
				  echo '<td>' . htmlspecialchars($appointment['class_name']) . '</td>';
				  echo '<td>' . htmlspecialchars($section_label) . '</td>';
				  echo '<td>' . date('d-m-Y H:i', strtotime($appointment['start'])) . '</td>';
				  echo '<td>' . (!empty($appointment_rec['recordings']) ? $appointment_rec['recordings'][0]['duration'] : '—') . '</td>';
		  
				  echo '<td>';
				  if (!empty($appointment_rec['recordings'])) {
					  $rec = $appointment_rec['recordings'][0];
					  $endTime = !empty($rec['endTime']) ? (int)$rec['endTime'] / 1000 : null;
					  $isExpired = $endTime ? (time() > ($endTime + 7 * 24 * 3600)) : false;
		  
					  if ($isExpired) {
						  echo '<span class="badge bg-warning text-dark">Expired</span>';
					  } elseif (!empty($rec['playback_url'])) {
						  echo '<a href="' . htmlspecialchars($rec['playback_url']) . '" target="_blank" class="btn btn-sm btn-success">VIDEO</a>';
					  } else {
						  echo '<span class="badge bg-danger">NOT RECORDED</span>';
					  }
				  } else {
					  echo '<span class="badge bg-danger">NOT RECORDED</span>';
				  }
				  echo '</td>';
		  
				  echo '<td>';
				  if (!empty($appointment_rec['recordings'])) {
					  $rec = $appointment_rec['recordings'][0];
					  echo '<a href="' . htmlspecialchars($rec['video_download_url']) . '" class="btn btn-sm btn-success">Download</a> ';
				  }
		  
				  echo '<a href="' . site_url('superadmin/delete_appointment_and_recording/' . $appointment['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'❗ Cette action supprimera le rendez-vous et l’enregistrement associé. Continuer ?\')">🗑️ Supprimer</a>';
				  echo '</td></tr>';
			  }
		  }
		  
	
		  
		  public function export_recordings_csv() {
			  $this->load->config('bigbluebutton');
			  $bbbUrl = $this->config->item('bbb_url');
			  $bbbSecret = $this->config->item('bbb_secret');
			  $meeting_name = $this->input->get('meeting_name', true);
			  $date_range = $this->input->get('date_range', true);
			  $schoolID = school_id();
			  // $this->db->select('*')->from('appointments');
	
			  $this->db->select('
				  appointments.id,
				  appointments.title,
				  appointments.start_date AS start,
				  appointments.description,
				  appointments.sections_id AS section,
				  appointments.classe_id,
				  classes.name AS class_name,
				  appointments.room_id,
				  rooms.name AS room_name,
				  appointments.meeting_id
			  ');
			  $this->db->from('appointments');
			  $this->db->join('rooms', 'rooms.id = appointments.room_id', 'left');
			  $this->db->join('classes', 'classes.id = appointments.classe_id', 'left');
			  $this->db->where('appointments.Etat', 1);
			  $this->db->where('appointments.school_id', $schoolID);
			  // Filtrer par nom de réunion
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
			  
	
			  // Préparer le fichier CSV
			  header('Content-Type: text/csv');
			  header('Content-Disposition: attachment;filename="recordings.csv"');
	
	
	
			  $output = fopen('php://output', 'w');
			  fputcsv($output, ['Name', 'Room', 'Class', 'Section', 'Creation Date', 'Duration', 'Recording']);
	
			  foreach ($appointments as &$appointment_reco) {
				$meetingId = $appointment_reco['meeting_id'] ?? null;
				$appointment_reco['recordings'] = [];
		   
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
							$appointment_reco['recordings'][] = [
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
	
			  foreach ($appointments as $appointment) {
				  $recording_url = !empty($appointment_reco['recordings']) && isset($appointment_reco['recordings'][0]['playback_url']) 
					  ? $appointment_reco['recordings'][0]['playback_url'] 
					  : 'NOT RECORDED';
	
						 // Traitement des sections
				  $section_label = '—';
				  if (!empty($appointment['section'])) {
					  $section_ids = explode(',', $appointment['section']);
					  $section_names = [];
					  foreach ($section_ids as $id) {
						  $name = $this->db->get_where('sections', ['id' => $id])->row('name');
						  if ($name) $section_names[] = $name;
					  }
					  $section_label = implode(', ', $section_names);
				  }
			   
	
				  fputcsv($output, [
					  $appointment['title'],
					  $appointment['room_name'],
					  $appointment['class_name'],
					  $section_label,
					  date('d-m-Y H:i', strtotime($appointment['start'])),
					  !empty($appointment_reco['recordings']) && isset($appointment_reco['recordings'][0]['duration']) 
						  ? $appointment_reco['recordings'][0]['duration'] 
						  : '—',
					  $recording_url
				  ]);
			  }
	
			  fclose($output);
			  exit;
		  }
		
		  public function delete_appointment_and_recording($appointment_id)
		  {
		
		
			  if (empty($appointment_id)) {
				  $this->session->set_flashdata('error_message', "ID de l'appointment invalide.");
				  redirect(site_url('teacher/Recording'), 'refresh');
				  return;
			  }
	
			  // Appel à la méthode de suppression dans le modèle
			  $success = $this->room_model->delete_bbb_recording_by_appointment($appointment_id);
			  die($success);
			  // Vérification du succès de la suppression
			  if ($success) {
				  $this->session->set_flashdata('success_message', 'Appointment et enregistrements supprimés avec succès.');
			  } else {
				  $this->session->set_flashdata('error_message', "Erreur lors de la suppression de l'enregistrement ou de l'appointment.");
			  }
	
			  // Redirection vers la liste des enregistrements
			  redirect(site_url('teacher/Recording'), 'refresh');
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
      $this->load->view('backend/teacher/language/dropdown');
    }
    // showing the index file
    if (empty($param1)) {
      $page_data['folder_name'] = 'language';
      $page_data['page_title'] = 'languages';
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
			$modelResponse = $this->crud_model->syllabus_create();
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
			  'name' => $this->security->get_csrf_token_name(),
			  'hash' => $this->security->get_csrf_hash()
		  );
		  
		  // Fusionner la réponse du modèle avec le CSRF
		  $response = array(
			  'status' => $modelResponse['status'],
			  'notification' => $modelResponse['notification'],
			  'csrf' => $csrf
		  );
		  
		  echo json_encode($response);
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
			$modelResponse = $this->crud_model->take_attendance();
			// Préparer la réponse avec un nouveau jeton CSRF
			$csrf = array(
			  'name' => $this->security->get_csrf_token_name(),
			  'hash' => $this->security->get_csrf_hash()
		  );
		  
		  // Fusionner la réponse du modèle avec le CSRF
		  $response = array(
			  'status' => $modelResponse['status'],
			  'notification' => $modelResponse['notification'],
			  'csrf' => $csrf
		  );
		  
		  echo json_encode($response);
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

		//HUMHUB DASHBOARD
		public function central()
		{
			// 1) Vérifier que c'est bien un admin
			if (! $this->session->userdata('teacher_login')) {
				show_error('Accès réservé aux enseignants.');
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
			if (! $this->session->userdata('teacher_login')) {
				show_error('Accès réservé aux enseignants.');
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
			if (! $this->session->userdata('teacher_login')) {
				show_error('Accès réservé aux enseignants.');
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
			if (! $this->session->userdata('teacher_login')) {
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
