<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Courses extends CI_Controller {
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
    $this->load->model('addons/Lms_model','lms_model');
    $this->load->model('addons/Video_model','video_model');

    $superadmin_login = $this->session->userdata('superadmin_login');
    $admin_login = $this->session->userdata('admin_login');
    $teacher_login = $this->session->userdata('teacher_login');
    $student_login = $this->session->userdata('student_login');
    if($teacher_login == 1 || $superadmin_login == 1 || $admin_login == 1 || $student_login == 1){

    }else{
      redirect(site_url('login'), 'refresh');
    }
  }
  //dashboard
  public function index($param1 = '', $param2 = ''){
    $this->teacher_access($param2);
    if($param1 == 'create'){
      $this->student_access_denied();
      $this->lms_model->course_add();
      $this->session->set_flashdata('flash_message', get_phrase('course_added_successfully'));
      redirect(site_url('addons/courses'));
    }

    if($param1 == 'update'){
      $this->student_access_denied();
      $this->lms_model->course_edit($param2);
      $this->session->set_flashdata('flash_message', get_phrase('course_updated_successfully'));
      redirect(site_url('addons/courses'));
    }

    if($param1 == 'activity'){
      $this->student_access_denied();
      $this->lms_model->course_activity($param2);
                  // Préparer le nouveau jeton CSRF
                  $csrf = array(
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                );
                          // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
          echo json_encode(array('csrf' => $csrf));
    }

    if($param1 == 'delete'){
      $this->student_access_denied();
      $response = $this->lms_model->delete_course($param2);
      // Préparer le nouveau jeton CSRF
      $csrf = array(
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                    );
      // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
      echo json_encode(array('status' => $response, 'csrf' => $csrf));
      // echo $response;
    }

    if(empty($param1)){
      $page_data['selected_class_id']   = isset($_GET['class_id']) ? $_GET['class_id'] : "all";
      $page_data['selected_user_id'] = isset($_GET['user_id']) ? $_GET['user_id'] : "all";
      $page_data['selected_status']     = isset($_GET['status']) ? $_GET['status'] : "all";
      // $page_data['selected_subject']     = isset($_GET['subject_id']) ? $_GET['subject_id'] : "all";
      $page_data['selected_school_id']     = isset($_GET['school_id']) ? $_GET['school_id'] : "all";
      $only_list = isset($_GET['only_list']) ? $_GET['only_list'] : "false";

      // Courses query is used for deciding if there is any course or not. Check the view you will get it
      $page_data['courses']                = $this->lms_model->filter_course_for_backend($page_data['selected_class_id'], $page_data['selected_user_id'], $page_data['selected_status'], $page_data['selected_school_id']);

      $page_data['status_wise_courses']    = $this->lms_model->get_status_wise_courses();
      $page_data['all_teachers']           = $this->user_model->get_all_teachers();
      $page_data['classes']             = $this->crud_model->get_classes();
      $page_data['folder_name'] = 'academy';
      $page_data['page_title'] = 'all_courses';



      if($only_list == 'true'):
        if($this->session->userdata('student_login') == 1){
          $this->load->view('backend/academy/grid_view_for_student', $page_data);
        }else{
          $this->load->view('backend/academy/list', $page_data);
        }
      else:
        $this->load->view('backend/index', $page_data);
      endif;
    }
  }

  public function get_subject_by_class($class_id = ""){
    echo $this->lms_model->get_subject_by_class($class_id);
  }

  public function course_add(){
    $this->student_access_denied();
    $page_data['all_teachers']    = $this->user_model->get_all_teachers();
    $page_data['classes']     = $this->crud_model->get_classes();
    $page_data['folder_name'] = 'academy';
    $page_data['page_title']  = 'courses_add';
    $page_data['page_name']   = 'create';

    $this->load->view('backend/index', $page_data);
  }

  public function course_edit($course_id = ""){
    $this->student_access_denied();
    $this->teacher_access($course_id);

    $page_data['course']          = $this->lms_model->get_course_by_id($course_id);
    $page_data['all_teachers']        = $this->user_model->get_all_teachers();
    $page_data['classes']         = $this->crud_model->get_classes();
    $page_data['course_sections'] = $this->lms_model->get_section('course', $course_id)->result_array();
    $page_data['subjects']        = $this->db->get_where('subjects', array('class_id' => $page_data['course']['class_id']))->result_array();
    $page_data['first_lesson_id']  = $this->db->get_where('lesson', array('course_id' => $course_id))->row_array();
    $page_data['folder_name']     = 'academy';
    $page_data['page_title']      = 'courses_edit';
    $page_data['page_name']       = 'edit';

    $this->load->view('backend/index', $page_data);
  }

  public function course_sections($param1 = "", $param2 = "", $param3 = "") {
    $this->student_access_denied();
    $this->teacher_access($param1);
    if ($param2 == 'add') {
      $this->lms_model->add_course_section($param1);
      $this->session->set_flashdata('flash_message', get_phrase('section_has_been_added_successfully'));
    }
    elseif ($param2 == 'edit') {
      $this->lms_model->edit_course_section($param3);
      $this->session->set_flashdata('flash_message', get_phrase('section_has_been_updated_successfully'));
    }
    elseif ($param2 == 'delete') {
      $response = $this->lms_model->delete_course_section($param1, $param3);
      // echo $response;
            // Préparer le nouveau jeton CSRF
            $csrf = array(
              'csrfName' => $this->security->get_csrf_token_name(),
              'csrfHash' => $this->security->get_csrf_hash(),
                  );
    // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
    echo json_encode(array('status' => $response, 'csrf' => $csrf));
    }
    redirect(site_url('addons/courses/course_edit/'.$param1), 'refresh');
  }

  public function quizes($course_id = "", $action = "", $quiz_id = "") {
    $this->student_access_denied();
    $this->teacher_access($course_id);

    if ($action == 'add') {
      $this->lms_model->add_quiz($course_id);
      $this->session->set_flashdata('flash_message', get_phrase('quiz_has_been_added_successfully'));
    }
    elseif ($action == 'edit') {
      $this->lms_model->edit_quiz($quiz_id);
      $this->session->set_flashdata('flash_message', get_phrase('quiz_has_been_updated_successfully'));
    }
    elseif ($action == 'delete') {
      $response = $this->lms_model->delete_course_section($course_id, $quiz_id);
      echo $response;
    }
    redirect(site_url('addons/courses/course_edit/'.$course_id));
  }

  public function lessons($course_id = "", $param1 = "", $param2 = "") {
    $this->teacher_access($course_id);
    if ($param1 == 'add') {
      $this->student_access_denied();
      $this->lms_model->add_lesson();
      $this->session->set_flashdata('flash_message', get_phrase('lesson_has_been_added_successfully'));
      redirect('addons/courses/course_edit/'.$course_id);
    }
    elseif ($param1 == 'edit') {
      $this->student_access_denied();
      $this->lms_model->edit_lesson($param2);
      $this->session->set_flashdata('flash_message', get_phrase('lesson_has_been_updated_successfully'));
      redirect('addons/courses/course_edit/'.$course_id);
    }
    elseif ($param1 == 'delete') {
      $this->student_access_denied();
      $response = $this->lms_model->delete_lesson($param2);
      // echo $response;
      			// Préparer la réponse avec un nouveau jeton CSRF
        $csrf = array(
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash(),
          );
        
        // Renvoyer la réponse avec un nouveau jeton CSRF
        echo json_encode(array('status' => $response, 'csrf' => $csrf));
    }
    if(empty($param1)){
      $page_data['page_name'] = 'lessons';
      $page_data['lessons'] = $this->lms_model->get_lessons('course', $course_id);
      $page_data['course_id'] = $course_id;
      $page_data['page_title'] = get_phrase('lessons');
      $this->load->view('backend/index', $page_data);
    }
  }

  public function ajax_get_video_details() {
    $video_details = $this->video_model->getVideoDetails($_POST['video_url']);
    // echo $video_details['duration'];

      // Préparer le nouveau jeton CSRF
          $csrf = array(
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
                     );
      // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
      echo json_encode(array('duration' => $video_details['duration'], 'csrf' => $csrf));
  }

  public function ajax_sort_section() {
    $section_json = $this->input->post('itemJSON');
    $this->lms_model->sort_section($section_json);
      // Préparer le nouveau jeton CSRF
      $csrf = array(
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
              );
          
      // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
      echo json_encode(array('csrf' => $csrf));
  }



  public function ajax_get_section($course_id){
    $page_data['course_sections'] = $this->lms_model->get_section('course', $course_id)->result_array();
    return $this->load->view('backend/academy/ajax_get_section', $page_data);
  }

  public function ajax_sort_lesson() {
    $lesson_json = $this->input->post('itemJSON');
    $this->lms_model->sort_lesson($lesson_json);
    // Préparer le nouveau jeton CSRF
    $csrf = array(
              'csrfName' => $this->security->get_csrf_token_name(),
              'csrfHash' => $this->security->get_csrf_hash(),
          );
      
    // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
    echo json_encode(array('csrf' => $csrf));
  }
  public function ajax_sort_question() {
    $question_json = $this->input->post('itemJSON');
    $this->lms_model->sort_question($question_json);
              // Préparer le nouveau jeton CSRF
              $csrf = array(
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash(),
            );
      
            // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
            echo json_encode(array('csrf' => $csrf));
  }
  // this function is responsible for managing multiple choice question
  public function manage_multiple_choices_options() {
    $page_data['number_of_options'] = $this->input->post('number_of_options');
    // $this->load->view('backend/academy/manage_multiple_choices_options', $page_data);

          // Charger la vue mise à jour
          $response_html = $this->load->view('backend/academy/manage_multiple_choices_options', $page_data, TRUE);

          // Préparer le nouveau jeton CSRF
          $csrf = array(
              'csrfName' => $this->security->get_csrf_token_name(),
              'csrfHash' => $this->security->get_csrf_hash(),
          );
    
          // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
          echo json_encode(array('html' => $response_html, 'csrf' => $csrf));
  }
  // Manage Quize Questions
  public function quiz_questions($quiz_id = "", $action = "", $question_id = "") {
    $this->student_access_denied();
    $quiz_details = $this->lms_model->get_lessons('lesson', $quiz_id)->row_array();

    if ($action == 'add') {
      $response = $this->lms_model->add_quiz_questions($quiz_id);
      // echo $response;
      // Préparer le nouveau jeton CSRF
      $csrf = array(
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash(),
      );

      // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
      echo json_encode(array('html' => $response, 'csrf' => $csrf));
    }
    elseif ($action == 'edit') {
      $response = $this->lms_model->update_quiz_questions($question_id);
      // echo $response;
            // Préparer le nouveau jeton CSRF
            $csrf = array(
              'csrfName' => $this->security->get_csrf_token_name(),
              'csrfHash' => $this->security->get_csrf_hash(),
          );
    
          // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
          echo json_encode(array('html' => $response, 'csrf' => $csrf));
    }
    elseif ($action == 'delete') {
      $response = $this->lms_model->delete_quiz_question($question_id);
      // echo $response;
            // Préparer le nouveau jeton CSRF
            $csrf = array(
              'csrfName' => $this->security->get_csrf_token_name(),
              'csrfHash' => $this->security->get_csrf_hash(),
           );
    
          // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
          echo json_encode(array('html' => $response, 'csrf' => $csrf));
    }
  }

  // Mark this lesson as completed codes
  public function save_course_progress() {
    $response = $this->lms_model->save_course_progress();
    // Préparer le nouveau jeton CSRF
    $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash(),
          );
        
    // Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
    echo json_encode(array('html' => $response, 'csrf' => $csrf));
  }

  public function teacher_access($course_id = ""){
    if($course_id != ""){
      $course_data = $this->lms_model->get_course_by_id($course_id);
      if($this->session->userdata('teacher_login') == 1 && $course_data['user_id'] != $this->session->userdata('user_id')){
        $this->session->set_flashdata('error_message', get_phrase('you_do_not_have_access_to_this_course'));
        redirect(site_url('addons/courses'), 'refresh');
      }
    }
  }
  public function student_access_denied(){
    if($this->session->userdata('student_login') == 1){
      $this->session->set_flashdata('error_message', get_phrase('you_do_not_have_access_to_this_course'));
      redirect(site_url('addons/courses'), 'refresh');
    }
  }

  public function course_preview($course_id = ""){
    $page_data['course'] = $this->lms_model->get_course_by_id($course_id);
    $this->load->view('backend/academy/course_preview', $page_data);
  }
  public function course_information($course_id = ""){
    $page_data['course'] = $this->lms_model->get_course_by_id($course_id);
    $this->load->view('backend/academy/course_information', $page_data);
  }

}
