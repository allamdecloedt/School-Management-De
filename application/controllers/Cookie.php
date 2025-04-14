<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cookie extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
    }

    // Enregistrer la préférence via AJAX
    public function save_preference() {
        $user_id = $this->session->userdata('user_id');
        $session_id = $this->input->post('session_id'); // Envoyé par le client
        $preference = $this->input->post('preference');

        if (in_array($preference, ['accepted', 'rejected'])) {
            if ($user_id) {
                // Utilisateur connecté
                $this->User_model->set_cookie_preference($user_id, null, $preference);
            } else {
                // Utilisateur non connecté : utiliser session_id
                if (!$session_id) {
                    $session_id = uniqid('sess_', true); // Générer un ID unique si non fourni
                }
                $this->User_model->set_cookie_preference(null, $session_id, $preference);
                setcookie('session_id', $session_id, time() + (365 * 24 * 60 * 60), '/'); // Stocker session_id côté client
            }
            echo json_encode(['status' => 'success', 'session_id' => $session_id ?? null]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid preference']);
        }
    }

    // Vérifier si la barre doit être affichée
    public function check_preference() {
        $user_id = $this->session->userdata('user_id');
        $session_id = isset($_COOKIE['session_id']) ? $_COOKIE['session_id'] : null;

        if ($user_id) {
            $preference = $this->User_model->get_cookie_preference($user_id, null);
        } elseif ($session_id) {
            $preference = $this->User_model->get_cookie_preference(null, $session_id);
        } else {
            $preference = null;
        }

        echo json_encode(['has_preference' => !is_null($preference), 'session_id' => $session_id]);
    }
}
