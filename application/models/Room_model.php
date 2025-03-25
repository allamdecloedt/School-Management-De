<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

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
            appointments.room_id
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
}
