<?php
namespace App\Models;

use CI_Model;
use CodeIgniter\Model;

class NotificationModel extends CI_Model
{
    protected $table = 'notifications';
    protected $allowedFields = ['user_id', 'message', 'link', 'is_read', 'created_at'];
    protected $useTimestamps = false;

    public function getUserNotifications($userId, $limit = 10)
    {
        return $this->where('user_id', $userId)->orderBy('created_at', 'DESC')->limit($limit)->findAll();
    }

    public function countUnread($userId)
    {
        return $this->where(['user_id' => $userId, 'is_read' => 0])->countAllResults();
    }

    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)->set('is_read', 1)->update();
    }
}
