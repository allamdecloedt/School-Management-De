<?php
namespace App\Controllers;
use App\Models\NotificationModel;
use CI_Controller;

class NotificationController extends CI_Controller
{
    public function fetch()
    {
        $userId = session()->get('user_id');
        $model = new NotificationModel();
        $notifications = $model->getUserNotifications($userId);
        $unread = $model->countUnread($userId);

        return $this->response->setJSON([
            'notifications' => $notifications,
            'unread_count' => $unread
        ]);
    }

    public function markAllAsRead()
    {
        $userId = session()->get('user_id');
        $model = new NotificationModel();
        $model->markAllAsRead($userId);
        return $this->response->setJSON(['success' => true]);
    }
}
