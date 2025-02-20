<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Meeting_model', 'meeting_model');
    }

    public function save_meeting($meetingID, $meetingName, $attendeePW, $moderatorPW, $schoolID, $user_ID,$classID,$room_id)
    {
        $data = [
            'meeting_id' => $meetingID,
            'name' => $meetingName,
            'attendee_pw' => $attendeePW,
            'moderator_pw' => $moderatorPW,
            'school_id' => $schoolID,
            'user_id' => $user_ID,
            'class_id' => $classID,
            'room_id' => $room_id
        ];
        
        // 'start_time' => $start_TIME,
        // 'end_time' => $end_TIME,
        // 'description' => $description

        return $this->db->insert('sessions_meetings', $data);
    }

    public function get_meetings_by_school($schoolID)
    {
        return $this->db->get_where('meetings', ['school_id' => $schoolID])->result_array();
    }

    public function get_meeting_by_id($meetingID)
    {
        return $this->db->get_where('sessions_meetings', ['meeting_id' => $meetingID])->row_array();
    }
    public function save_participant($meetingID, $userID, $username, $role) {
        $data = [
            'meeting_id' => $meetingID,
            'user_id' => $userID,
            'username' => $username,
            'role' => $role,
            'created_at' => date("Y-m-d H:i:s")
        ];
        return $this->db->insert('participants', $data);
    }
    
}
