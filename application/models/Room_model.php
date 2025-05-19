<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->config('bigbluebutton');
        $this->bbb_url = $this->config->item('bbb_url');
        $this->bbb_secret = $this->config->item('bbb_secret');
        $this->bbb_url_old = $this->config->item('bbb_url_old');
        $this->bbb_url_play = $this->config->item('bbb_url_play');
    }

    public function create_room()
    {
        try {
            // die('room_name');
            // die($this->input->post('classSelect'));
            // Récupération et décodage des données JSON envoyées
            $roomName = html_escape($this->input->post('roomName'));
            $classID = html_escape($this->input->post('classSelect'));
            $description = !empty($this->input->post('description')) ? htmlspecialchars($this->input->post('description')) : null;

    
            // Vérification des champs obligatoires
            if (empty($roomName) || empty($classID) || empty($description) ) {
                echo json_encode(["status" => "error", "message" => "Tous les champs obligatoires doivent être remplis."]);
                return;
            }
    


            $schoolID = school_id(); // Fonction pour récupérer l'ID de l'école
            $userID = $this->session->userdata('user_id');
    

    
            // Vérifier si la salle existe déjà pour cette école
            $exists = $this->db->get_where('rooms', ['name' => $roomName, 'school_id' => $schoolID])->row();
            if ($exists) {
                echo json_encode(["status" => "error", "message" => "Cette salle existe déjà."]);
                return;
            }
    
            // Préparation des données pour l'insertion
            $roomData = [
                'name' => $roomName,
                'description' => $description,
                'school_id' => $schoolID,
                'user_id' => $userID,
                'class_id' => $classID
            ];
    
            // Insertion dans la base de données
            $this->db->insert('rooms', $roomData);
    
            // Vérification de l'insertion
            if ($this->db->affected_rows() > 0) {
                // $response =  json_encode(["status" => "success", "message" => "Salle créée avec succès"]);
                $response = array(
                    'status' => true,
                    'notification' => get_phrase('Salle_créée_avec_succès')
                );

            } else {
                $response =  json_encode(["status" => "error", "message" => "Erreur lors de la création de la salle."]);
                $response = array(
                    'status' => false,
                    'notification' => "Erreur lors de la création de la salle."
                );
            }
        } catch (Exception $e) {
            $response =  json_encode(["status" => "error", "message" => "Erreur serveur : " . $e->getMessage()]);
        }
        return json_encode($response);
    }
    public function update_room($param1 = '')
	{

        $data['name'] = html_escape($this->input->post('roomName'));
		$data['description'] = html_escape($this->input->post('description'));
		// check email duplication

			$this->db->where('id', $param1);
			$this->db->update('rooms', $data);

			$response = array(
				'status' => true,
				'notification' => get_phrase('room_has_been_updated_successfully')
			);

		

		return json_encode($response);
	}

    public function get_all_appointments() {

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

        ');
        $this->db->from('appointments');
    
        // Jointure avec la table rooms pour récupérer les informations des salles
        $this->db->join('rooms', 'rooms.id = appointments.room_id', 'left');
    
        // Filtre : récupérer uniquement les rendez-vous actifs
        $this->db->where('appointments.Etat', 1);
        $this->db->where('rooms.school_id', $schoolID );
    
        // Exécution de la requête
        $query = $this->db->get();
        
        // Vérification si des résultats existent
        if ($query->num_rows() > 0) {
            return $query->result_array(); // Retourne les résultats sous forme de tableau
        } else {
            return []; // Retourne un tableau vide si aucun rendez-vous trouvé
        }
    }
    
       

    public function get_all_appointments_student() {
        $user_id = $this->session->userdata('user_id');
    
        // 🔹 Vérifie les entrées student liées à l'utilisateur
        $this->db->where('user_id', $user_id);
        $students = $this->db->get('students')->result_array();
    
        if (empty($students)) return [];
    
        // 🔹 Récupère tous les student_id liés à l'utilisateur
        $student_ids = array_column($students, 'id');
    
        // 🔹 Récupère toutes les inscriptions (enrols)
        $this->db->where_in('student_id', $student_ids);
        $enrolled_classes = $this->db->get('enrols')->result_array();
    
        if (empty($enrolled_classes)) return [];
    
        // 🔹 Extract class, section, school IDs
        $class_ids   = array_column($enrolled_classes, 'class_id');
        $school_ids  = array_column($enrolled_classes, 'school_id');
        $section_ids = array_unique(array_column($enrolled_classes, 'sections_id'));
    
        // 🔍 Récupération des rendez-vous liés à ces classes et sections
        $this->db->select('
            appointments.id, 
            appointments.title, 
            appointments.start_date AS start, 
            appointments.description, 
            appointments.sections_id AS section, 
            appointments.classe_id, 
            appointments.room_id,
            rooms.name ,
        ');
        $this->db->from('appointments');
        $this->db->join('rooms', 'rooms.id = appointments.room_id', 'left');
    
        // 🔒 Filtres de sécurité
        $this->db->where('appointments.Etat', 1);
        $this->db->where('rooms.Etat', 1);
        $this->db->where_in('appointments.classe_id', $class_ids);
        $this->db->where_in('rooms.school_id', $school_ids);
    
        // 🔁 Ajoute une condition `OR FIND_IN_SET` pour chaque section
        if (!empty($section_ids)) {
            $this->db->group_start();
            foreach ($section_ids as $sid) {
                $this->db->or_where("FIND_IN_SET('$sid', appointments.sections_id) !=", 0);
            }
            $this->db->group_end();
        }
    
        $query = $this->db->get();
    
        return $query->num_rows() > 0 ? $query->result_array() : [];
    }
    


    
    public function get_all_rooms() {
        return $this->db->get('bbb_rooms')->result_array();
    }

    public function get_room_by_id($roomID) {
        return $this->db->where('room_id', $roomID)->get('bbb_rooms')->row_array();
    }

    public function update_room_by_id($roomID) {
               	// Update Admin User Status
		$rooms['Etat'] = 0;
		$appointments ['Etat'] = 0;

		$this->db->where('room_id', $roomID);
		 $this->db->update('appointments', $appointments);

		$this->db->where('id', $roomID);
		return $this->db->update('rooms', $rooms);
    }



        public function get_bbb_recording_by_appointment($appointment_id)
        {
            if (empty($appointment_id)) {
                return null;
            }

            // 🔎 Récupération de l'appointment et du meetingID
            $appointment = $this->db->get_where('appointments', ['id' => $appointment_id])->row_array();
            if (!$appointment || empty($appointment['meeting_id'])) {
                return null;
            }

            $meetingID = $appointment['meeting_id'];

            // 📡 Construction de la requête getRecordings
            $params = ['meetingID' => $meetingID];
            $query = http_build_query($params);
            $checksum = sha1('getRecordings' . $query . $this->bbb_secret);
            $url = $this->bbb_url . 'getRecordings?' . $query . '&checksum=' . $checksum;

            // 🔁 Appel de l'API BBB
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);
            curl_close($ch);

            $xml = @simplexml_load_string($response);
            if ($xml === false || (string)$xml->returncode !== 'SUCCESS') {
                return null;
            }

            if (!isset($xml->recordings->recording)) {
                return null;
            }

            $recordings = [];

            foreach ($xml->recordings->recording as $rec) {
                if ((string)$rec->published !== 'true') {
                    continue;
                }

                $recordID = (string)$rec->recordID;
                $playback_url = '';
                $download_url = '';

                // 🔍 Recherche du format de playback
                if (isset($rec->playback->format)) {
                    foreach ($rec->playback->format as $format) {
                        $url = (string)$format->url;
                        $type = (string)$format->type;

                        // Préférence pour "presentation"
                        if ($type === 'presentation' && empty($playback_url)) {
                            $playback_url = $url;
                        }

                        // Si un format vidéo téléchargeable est détecté
                        if ($type === 'video' || str_ends_with($url, '.mp4')) {
                            $download_url = $url;
                        }
                    }
                }

                // 🛠 Correction du playback_url si mal généré
                // $parsed = parse_url($playback_url);
                // if (isset($parsed['host']) && ($parsed['host'] === 'http' || $parsed['host'] === 'playback')) {
                //     $playback_url = str_replace('http://'.$parsed['host'], $this->bbb_url_play, $playback_url);
                // }
                $parsed = parse_url($playback_url);
                if (isset($parsed['host']) && strpos($parsed['host'], '192.168.') !== false) {
                    $playback_url = str_replace($parsed['host'], $this->bbb_url_play, $playback_url);
                }

                // 📥 Fallback download_url si aucune URL MP4 trouvée
                if (empty($download_url)) {
                    $download_url = str_replace('/playback/', '/download/', $playback_url);
                }

                // 🔗 Liens HTML prêts à afficher
                $playback_link = '<a href="' . $playback_url . '" target="_blank" class="btn btn-success">📹 Voir la vidéo</a>';
                $download_link = '<a href="' . $download_url . '" target="_blank" class="btn btn-primary">⬇️ Télécharger</a>';

                $recordings[] = [
                    'recordID'            => $recordID,
                    'meetingID'           => (string)$rec->meetingID,
                    'playback_url'        => $playback_url,
                    'download_url'        => $download_url,
                    'playback_html'       => $playback_link,
                    'download_html'       => $download_link,
                    'duration'            => isset($rec->startTime, $rec->endTime)
                        ? round(((int)$rec->endTime - (int)$rec->startTime) / 60000) . ' min'
                        : '—',
                    'endTime'             => (string)$rec->endTime
                ];
            }

            return !empty($recordings) ? $recordings : null;
        }

  
          
       

            public function delete_bbb_recording_by_appointment($appointment_id)
                {
                    // if (empty($appointment_id)) {
                    //     return false;
                    // }
                   
                    // Récupération de l'appointment
                    $appointment = $this->db->get_where('appointments', ['id' => $appointment_id])->row_array();
                    if (!$appointment || empty($appointment['meeting_id'])) {
                        return false;
                    }
                    die('true');
                    $meetingID = $appointment['meeting_id'];

                    // Récupération des enregistrements liés via l'API BBB
                    $params = ['meetingID' => $meetingID];
                    $query = http_build_query($params);
                    $checksum = sha1('getRecordings' . $query . $this->bbb_secret);
                    $url = $this->bbb_url . 'getRecordings?' . $query . '&checksum=' . $checksum;
                    // 🔁 Appel de l'API BBB
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        $response = curl_exec($ch);
                        curl_close($ch);
                    // $response = file_get_contents($url);
                    $xml = @simplexml_load_string($response);
                   
                    if ($xml && $xml->returncode == 'SUCCESS' && isset($xml->recordings->recording)) {
                        foreach ($xml->recordings->recording as $rec) {
                            $recordID = (string)$rec->recordID;
                            //  die($recordID);
                            // Suppression via API
                            $deleteParams = ['recordID' => $recordID];
                            $deleteQuery = http_build_query($deleteParams);
                            $deleteChecksum = sha1('deleteRecordings' . $deleteQuery . $this->bbb_secret);
                            $deleteUrl = $this->bbb_url . 'deleteRecordings?' . $deleteQuery . '&checksum=' . $deleteChecksum;

                            // file_get_contents($deleteUrl);
                                 // 🔁 Appel de l'API BBB
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $deleteUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                                $response01 = curl_exec($ch);
                                curl_close($ch);
                                $xml01 = @simplexml_load_string($response01);
                                if ($xml && $xml01->returncode == 'SUCCESS') {
                                    echo "Les métadonnées de l'enregistrement {$recordID} ont été supprimées avec succès.\n";
                                } else {
                                    echo "Erreur lors de la suppression des métadonnées : " . ($xml01->messageKey ?? 'Inconnue') . "\n";
                                }
                                // var_dump($xml01);
                                // die;
                                // die($xml01->returncode);
                           
                        }
                    }

                    // Suppression de l'appointment dans la base de données
                    $this->db->where('id', $appointment_id);
                    $this->db->delete('appointments');
               
                    return true;
                }


}
