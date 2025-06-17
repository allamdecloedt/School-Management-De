<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Participant_model extends CI_Model {



    public function get_meeting_participants($meetingID) {
        return $this->db->where('meeting_id', $meetingID)->get('participants')->result_array();
    }

    public function assign_to_breakout($userID, $roomID) {
        $data = [
            'user_id' => $userID,
            'breakout_room_id' => $roomID,
            'assigned_at' => date("Y-m-d H:i:s")
        ];
        return $this->db->insert('breakout_assignments', $data);
    }
}

