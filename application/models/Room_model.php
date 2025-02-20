<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

    public function save_room($roomID, $roomName, $attendeePW, $moderatorPW, $classID, $userID) {
        $data = [
            'room_id' => $roomID,
            'name' => $roomName,
            'attendee_pw' => $attendeePW,
            'moderator_pw' => $moderatorPW,
            'class_id' => $classID,
            'user_id' => $userID,
            'created_at' => date("Y-m-d H:i:s")
        ];
        return $this->db->insert('bbb_rooms', $data);
    }

    public function get_all_rooms() {
        return $this->db->get('bbb_rooms')->result_array();
    }

    public function get_room_by_id($roomID) {
        return $this->db->where('room_id', $roomID)->get('bbb_rooms')->row_array();
    }

    public function update_room_by_id($roomID) {
               	// Update Admin User Status
		$roome['Etat'] = 0;
		$this->db->where('id', $roomID);
		return $this->db->update('rooms', $roome);
    }
}
