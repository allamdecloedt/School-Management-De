<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bigbluebutton extends CI_Controller {

    // private $bbb_url = "https://192.168.119.131/bigbluebutton/api"; // Remplace par l'URL de ton serveur BBB
    // private $bbb_secret = "mNPemKRmyLZlmVlQ4AafeUB5IBrHFYtfS5T3HU370"; // Remplace par ta clé secrète BBB



    private $bbb_url;
    private $bbb_secret;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        require_once APPPATH . '../vendor/autoload.php';

        $this->load->config('bigbluebutton');
       
        $this->load->library('session');

            /*LOADING ALL THE MODELS HERE*/
            $this->load->model('Crud_model', 'crud_model');
            $this->load->model('User_model', 'user_model');
            $this->load->model('Settings_model', 'settings_model');
            $this->load->model('Payment_model', 'payment_model');
            $this->load->model('Email_model', 'email_model');
            $this->load->model('Addon_model', 'addon_model');
            $this->load->model('Frontend_model', 'frontend_model');
            $this->load->model('Meeting_model');
            $this->load->model('Participant_model');
            $this->load->model('Room_model','room_model');
        

        // if (!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
        $this->bbb_url = $this->config->item('bbb_url');
        $this->bbb_secret = $this->config->item('bbb_secret');
    }


    public function create_and_join_meeting($role = "attendee")
    {
        if ($this->session->userdata('role') !== 'mentor' && $this->session->userdata('role') !== 'admin' && $this->session->userdata('role') !== 'superadmin') {
            echo json_encode(["status" => "error", "message" => "Accès refusé."]);
            return;
        }

        $schoolID = school_id();
        $user_ID = $this->session->userdata('user_id');
        $meetingID = "metting-" . rand(100000, 999999);
        $meetingName = urlencode($meetingID);
        $attendeePW = "ap";
        $moderatorPW = "mp";
        $voiceBridge = rand(10000, 99999);
        $welcome = urlencode("<br>Welcome to <b>%%CONFNAME%%</b>!");
    
        // Construire la chaîne de paramètres pour la création de la réunion
        $params = "allowStartStopRecording=true&autoStartRecording=false";
        $params .= "&meetingID=$meetingID&name=$meetingName";
        $params .= "&attendeePW=$attendeePW&moderatorPW=$moderatorPW";
        $params .= "&record=false&voiceBridge=$voiceBridge&welcome=$welcome";
    
        // Générer le checksum SHA1
        $checksum = sha1("create" . $params . $this->bbb_secret);
        $api_url = $this->bbb_url . "create?" . $params . "&checksum=" . $checksum;
    
        // Utiliser cURL pour envoyer la requête
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        curl_close($ch);
    
        if (strpos($response, "<returncode>SUCCESS</returncode>") !== false) {
            
            $this->Meeting_model->save_meeting($meetingID, $meetingName, $attendeePW, $moderatorPW, $schoolID, $user_ID);

            $fullName = "User-" . rand(1000, 9999);
            $password = ($role === "moderator") ? $moderatorPW : $attendeePW;
    
            $join_params = "fullName=" . urlencode($fullName) . "&meetingID=$meetingID&password=$password&redirect=true";
            $join_checksum = sha1("join" . $join_params . $this->bbb_secret);
            $join_url = $this->bbb_url . "join?" . $join_params . "&checksum=" . $join_checksum;
    
            header("Location: " . $join_url);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Échec de la création de la réunion"]);
        }
    }

    // public function create_room()
    // {
    //     try {
    //         // die('room_name');
    //         // die($this->input->post('classSelect'));
    //         // Récupération et décodage des données JSON envoyées
    //         $roomName = html_escape($this->input->post('roomName'));
    //         $classID = html_escape($this->input->post('classSelect'));
    //         $description = !empty($this->input->post('description')) ? htmlspecialchars($this->input->post('description')) : null;

    
    //         // Vérification des champs obligatoires
    //         if (empty($roomName) || empty($classID) || empty($description) ) {
    //             echo json_encode(["status" => "error", "message" => "Tous les champs obligatoires doivent être remplis."]);
    //             return;
    //         }
    


    //         $schoolID = school_id(); // Fonction pour récupérer l'ID de l'école
    //         $userID = $this->session->userdata('user_id');
    

    
    //         // Vérifier si la salle existe déjà pour cette école
    //         $exists = $this->db->get_where('rooms', ['name' => $roomName, 'school_id' => $schoolID])->row();
    //         if ($exists) {
    //             echo json_encode(["status" => "error", "message" => "Cette salle existe déjà."]);
    //             return;
    //         }
    
    //         // Préparation des données pour l'insertion
    //         $roomData = [
    //             'name' => $roomName,
    //             'description' => $description,
    //             'school_id' => $schoolID,
    //             'user_id' => $userID,
    //             'class_id' => $classID
    //         ];
    
    //         // Insertion dans la base de données
    //         $this->db->insert('rooms', $roomData);
    
    //         // Vérification de l'insertion
    //         if ($this->db->affected_rows() > 0) {
    //             echo json_encode(["status" => "success", "message" => "Salle créée avec succès"]);

    //             // if ($param1 == 'list') {
    //             $this->load->view('backend/superadmin/bigbleubutton/list');
    //             // }
    //         } else {
    //             echo json_encode(["status" => "error", "message" => "Erreur lors de la création de la salle."]);
    //         }
    //     } catch (Exception $e) {
    //         echo json_encode(["status" => "error", "message" => "Erreur serveur : " . $e->getMessage()]);
    //     }
    // }
    

 
    // Créer une nouvelle session BigBlueButton
    public function create_meeting($roomID,$appointment_id) {


        $schoolID = school_id();

        
        
        $room = $this->db->get_where('rooms', ['id' => $roomID])->row_array();

        $user_ID = $this->session->userdata('user_id');
        $meetingID = "metting-" . rand(100000, 999999);
         $meetingName = urlencode($meetingID);
        $attendeePW = "ap";
        $moderatorPW = "mp";
        $voiceBridge = rand(10000, 99999);
        $welcome = urlencode("<br>Welcome to <b>%%CONFNAME%%</b>!");
        $endWhenNoModerator = "false"; // Ne pas terminer si l'hôte quitte
        $meetingKeepEvents = "true"; // Conserver l'état de la réunion
      
        $params = "allowStartStopRecording=true&autoStartRecording=false";
        $params .= "&meetingID=$meetingID&name=$meetingName";
        $params .= "&attendeePW=$attendeePW&moderatorPW=$moderatorPW";
        $params .= "&record=true&voiceBridge=$voiceBridge&welcome=$welcome";
        $params .= "&endWhenNoModerator=$endWhenNoModerator"; // NE PAS FERMER SI LE MODÉRATEUR QUITTE
        $params .= "&meetingKeepEvents=$meetingKeepEvents"; // GARDER L'ÉTAT DE LA RÉUNION    
        $params .= "&meta_roomID=" . $roomID;
        // Générer le checksum SHA1
        $checksum = sha1("create" . $params . $this->bbb_secret);
        $api_url = $this->bbb_url . "create?" . $params . "&checksum=" . $checksum;
        // echo "URL de création : " . $api_url;
        // die();
        
    
        // Utiliser cURL pour envoyer la requête
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        curl_close($ch);
        // var_dump($response);die;
        if (strpos($response, "<returncode>SUCCESS</returncode>") !== false) {



            $this->Meeting_model->save_meeting($meetingID, $meetingName, $attendeePW, $moderatorPW, $schoolID, $user_ID,$room['classe_id'],$room['id']);

                $data['meeting_id']   = $meetingID;

                $this->db->where('id', $appointment_id);
                $this->db->update('appointments', $data);
                $page_data['folder_name'] = 'bigbleubutton';
              
                $page_data['page_title'] = 'Démarrer Réunion';
                $user_details = $this->user_model->get_user_details($this->session->userdata('user_id'));
                $fullName = $user_details['name'];
                $password = $moderatorPW ;
        
                $join_params = "fullName=" . urlencode($fullName) . "&meetingID=$meetingID&password=$password&redirect=true";
                $join_checksum = sha1("join" . $join_params . $this->bbb_secret);
                $join_url = $this->bbb_url . "join?" . $join_params . "&checksum=" . $join_checksum;
        
                header("Location: " . $join_url);
                exit();
              
        
              
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de la création de la réunion."]);
        }
    }
  
    
    
    

    public function room_has_active_meeting55($roomID)
    {
        // Récupérer l'ID de la dernière réunion créée pour cette Room
        $meeting = $this->db->order_by('id', 'DESC')
                            ->get_where('sessions_meetings', ['class_id' => $roomID])
                            ->row();

        if (!$meeting) {
            return false; // Pas de réunion trouvée
        }

        $meetingID = $meeting->meeting_id;

        // Vérifier si la réunion est active sur BigBlueButton
        $checksum = sha1("getMeetings" . $this->bbb_secret);
        $api_url = $this->bbb_url . "getMeetings?checksum=" . $checksum;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($response);

        if ($xml->returncode == "SUCCESS") {
            foreach ($xml->meetings->meeting as $activeMeeting) {
                if ((string) $activeMeeting->meetingID === $meetingID) {
                    return true; // La réunion existe et est active
                }
            }
        }

        return false; // La réunion n'est pas active
    }

    public function start_meeting($roomID)
    {
        // Récupérer l'ID de l'appointment depuis les paramètres GET
            $appointment_id = $this->input->get('appointment_id');
        // Vérifier si la salle a déjà une réunion active
        if ($this->room_has_active_meeting($roomID)) {
            echo json_encode(["status" => "error", "message" => "Une réunion est déjà en cours pour cette salle."]);
            return;
        }
        

        // Si aucune réunion active, créer une nouvelle réunion
        $this->create_meeting($roomID,$appointment_id);
    }


    public function check_active_meetings()
{
    // Appel de l'API BigBlueButton pour obtenir la liste des réunions actives
    $checksum = sha1("getMeetings" . $this->bbb_secret);
    $api_url = $this->bbb_url . "getMeetings?checksum=" . $checksum;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);

    // Convertir la réponse XML en tableau PHP
    $xml = simplexml_load_string($response);
    $json = json_encode($xml);
    $array = json_decode($json, true);

    // Vérifier si l'API retourne un succès et qu'il y a des réunions actives
    $activeMeetings = [];
    if (isset($array['meetings']['meeting'])) {
        $meetings = is_array($array['meetings']['meeting']) ? $array['meetings']['meeting'] : [$array['meetings']['meeting']];
        foreach ($meetings as $meeting) {
            $activeMeetings[$meeting['meetingID']] = [
                "running" => $meeting['running'] === "true",
                "participantCount" => $meeting['participantCount'] ?? 0
            ];
        }
    }

    // Retourner la liste des réunions actives
    echo json_encode(["status" => "success", "meetings" => $activeMeetings]);
}

    public function is_meeting_running($meetingID)
    {
        $checksum = sha1("isMeetingRunningmeetingID=" . $meetingID . $this->bbb_secret);
        $api_url = $this->bbb_url . "isMeetingRunning?meetingID=" . $meetingID . "&checksum=" . $checksum;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
    
        // ✅ Ajout de logs pour voir la réponse brute
        header("Content-Type: application/json");
        echo json_encode(["meetingID" => $meetingID, "response_raw" => $response]);
        exit();
    }

    


    public function get_meetings()
    {
        $checksum = sha1("getMeetings" . $this->bbb_secret);
        $api_url = $this->bbb_url . "getMeetings?checksum=" . $checksum;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        curl_close($ch);
    
        echo $response;
    }
    
    


    public function join_meeting($meetingID)
    {
        // die($meetingID);

        $meeting = $this->Meeting_model->get_meeting_by_id($meetingID);
    
        
        if (!$meeting) {
            show_error("Réunion introuvable ou supprimée.", 404);
            return;
        }

        // $role = $this->session->userdata('role');die($role);
        $user_details = $this->user_model->get_user_details($this->session->userdata('user_id'));
        $password = ($user_details['role'] === "mentor" || $user_details['role'] === "admin" || $user_details['role'] === "superadmin") ? $meeting['moderator_pw'] : $meeting['attendee_pw'];
        $fullName =  $user_details['name'];
        // die($this->session->userdata('user_id'));
        $params = "fullName=" . urlencode($fullName) . "&meetingID=$meetingID&password=$password&redirect=true";
        $checksum = sha1("join" . $params . $this->bbb_secret);
        $join_url = $this->bbb_url . "join?" . $params . "&checksum=" . $checksum;
        header("Location: " . $join_url);
        exit();
        // Afficher l'URL générée pour tester
        // echo "Rejoindre via : " . $join_url;
        // exit();
    }   
    public function breakout_rooms($meetingID)
    {
        die;
        $data['meeting_id'] = $meetingID;
        $data['breakout_rooms'] = $this->Meeting_model->get_breakout_rooms($meetingID);
        $data['participants'] = $this->Participant_model->get_participants($meetingID);
        
        $this->load->view('breakout_rooms', $data);
    }

    // Exemple d'utilisation
    public function index() {
        $meetingID = "unique_meeting_id_123";
        $meetingName = "Ma réunion de test";
        
        // Créer la réunion
        if ($this->create_meeting($meetingID, $meetingName)) {
            echo "Réunion créée avec succès!<br>";

            // Rejoindre la réunion en tant que modérateur
            $this->join_meeting($meetingID, "Modérateur", "mp");
        } else {
            echo "Erreur lors de la création de la réunion.";
        }
    }






    public function room_has_active_meeting($room_id)
{
    $this->db->where('room_id', $room_id);
    $this->db->order_by('created_at', 'DESC');
    $meeting = $this->db->get('sessions_meetings')->row_array();

    if (!$meeting) {
        return false; // Aucune réunion trouvée pour cette room
    }

    // Vérifier via l'API BigBlueButton si la réunion est en cours
    $meetingID = $meeting['meeting_id'];
    $checksum = sha1("getMeetingInfomeetingID=$meetingID" . $this->bbb_secret);
    $api_url = $this->bbb_url . "getMeetingInfo?meetingID=$meetingID&checksum=" . $checksum;

    $response = file_get_contents($api_url);
    if (strpos($response, "<returncode>SUCCESS</returncode>") !== false && strpos($response, "<running>true</running>") !== false) {
        return true; // La réunion est en cours
    }

    return false; // Réunion non active
}

public function get_active_meetings()
{
    $checksum = sha1("getMeetings" . $this->bbb_secret);
    $api_url = $this->bbb_url . "getMeetings?checksum=" . $checksum;
    // echo "URL de création : " . $api_url;
    // die();


    // $response = file_get_contents($api_url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);
    $xml = simplexml_load_string($response);

    $active_meetings = [];
    if ($xml->returncode == "SUCCESS") {
        foreach ($xml->meetings->meeting as $meeting) {
            $roomID = isset($meeting->metadata->roomid) ? (string)$meeting->metadata->roomid : null;

            $active_meetings[] = [
                "meeting_id" => (string)$meeting->meetingID,
                "room_id" => $roomID,
                "running" => (string)$meeting->running,
                "participant_count" => isset($meeting->participantCount) ? (int)$meeting->participantCount : 0
                // "api_url" => $api_url
            ];
        }
    }

    echo json_encode(["active_meetings" => $active_meetings]);
}


}