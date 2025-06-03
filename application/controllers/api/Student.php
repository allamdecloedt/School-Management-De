<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load session library
        $this->load->library('session');
        // Load models
        $this->load->model('user_model', 'user_model', TRUE);
        $this->load->model('crud_model', 'crud_model', TRUE);
        // Log constructor call
        log_message('debug', 'Student controller constructor called');
        // Ensure user is logged in for non-API methods
        if (!in_array($this->router->fetch_method(), ['delete_expired_accounts'])) {
            if (!$this->session->userdata('user_id')) {
                log_message('debug', 'No user_id in session, redirecting to login');
                redirect('login', 'refresh');
            }
        }
    }

    public function profile($param1 = "") {
        log_message('debug', 'Profile method called with param: ' . $param1);
        if ($param1 == 'update_profile') {
            $response = $this->user_model->update_profile();
            echo $response;
        }
        if ($param1 == 'update_password') {
            $response = $this->user_model->update_password();
            echo $response;
        }
        if ($param1 == 'delete_request') {
            $response = $this->user_model->delete_request();
            echo json_encode($response);
        }
        if ($param1 == 'undo_delete_request') {
            $response = $this->user_model->undo_delete_request();
            echo json_encode($response);
        }
        if (empty($param1)) {
            $page_data['folder_name'] = 'profile';
            $page_data['page_title'] = 'manage_profile';
            $this->load->view('backend/index', $page_data);
        }
    }

   public function delete_expired_accounts() {

    try {
        $this->db->trans_start();

        // Calculer la date limite (30 jours avant)
        $current_date = new DateTime();
        $cutoff_date = $current_date->modify('-30 days')->format('Y-m-d H:i:s');
        log_message('debug', 'Cutoff date: ' . $cutoff_date);

        // Trouver les utilisateurs avec une demande de suppression expirée
        $this->db->where('delete_request IS NOT NULL');
        $this->db->where('delete_request <=', $cutoff_date);
        $users = $this->db->get('users')->result_array();

        $deleted_count = 0;
        foreach ($users as $user) {
            $user_id = $user['id'];

            // Récupérer l'étudiant associé
            $student = $this->db->get_where('students', ['user_id' => $user_id])->row_array();
            $student_id = $student ? $student['id'] : null;

            // Liste des tables liées avec leurs clés de suppression
            $related_tables = [
                ['table' => 'exam_responses', 'key' => 'user_id', 'value' => $user_id],
                ['table' => 'quiz_responses', 'key' => 'user_id', 'value' => $user_id],
                ['table' => 'enrols', 'key' => 'student_id', 'value' => $student_id],
                ['table' => 'invoices', 'key' => 'student_id', 'value' => $student_id],
                ['table' => 'marks', 'key' => 'student_id', 'value' => $student_id],
                ['table' => 'book_issues', 'key' => 'student_id', 'value' => $student_id],
                ['table' => 'students', 'key' => 'user_id', 'value' => $user_id],
            ];

            // Supprimer les enregistrements des tables liées
            foreach ($related_tables as $table_info) {
                if ($table_info['value']) { // Vérifier si la clé (user_id ou student_id) existe
                    $this->db->where($table_info['key'], $table_info['value']);
                    if (!$this->db->delete($table_info['table'])) {
                        log_message('error', 'Failed to delete from ' . $table_info['table'] . ' for ' . $table_info['key'] . ': ' . $table_info['value']);
                        throw new Exception('Failed to delete from ' . $table_info['table']);
                    }
                }
            }

            // Supprimer les sessions utilisateur (si sessions en base de données)
            if ($this->config->item('sess_driver') === 'database') {
                $this->db->like('data', 'user_id|s:' . strlen($user_id) . ':"' . $user_id . '"');
                $this->db->delete('ci_sessions');
                log_message('debug', 'Deleted ' . $this->db->affected_rows() . ' sessions for user_id: ' . $user_id);
            }

            // Supprimer l'utilisateur
            $this->db->where('id', $user_id);
            if (!$this->db->delete('users')) {
                log_message('error', 'Failed to delete user_id: ' . $user_id);
                throw new Exception('Failed to delete user record');
            }

            $deleted_count++;
        }

        // Valider la transaction
        $this->db->trans_complete();

        // Vérifier l'état de la transaction
        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Transaction failed');
        }

        // Réponse en cas de succès
        $response = [
            'status' => true,
            'message' => "$deleted_count account(s) deleted successfully",
            'deleted_count' => $deleted_count
        ];
        log_message('debug', 'Deletion response: ' . json_encode($response));
        echo json_encode($response);
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $this->db->trans_rollback();
        log_message('error', 'Error in delete_expired_accounts: ' . $e->getMessage());
        $response = [
            'status' => false,
            'message' => 'Internal server error: ' . $e->getMessage()
        ];
        echo json_encode($response);
    }
}
}
?>