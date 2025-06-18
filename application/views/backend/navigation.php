<?php
$controller = "";
if ($user_type == 'parent') {
    $controller = 'parents';
} else {
    $controller = $user_type;
}

// Récupérer le nombre total d'examens non passés pour l'étudiant connecté
$user_id = $this->session->userdata('user_id');
$session = active_session();
$this->db->select('exams.id');
$this->db->from('exams');
$this->db->join('enrols', 'enrols.class_id = exams.class_id AND enrols.section_id = exams.section_id', 'left');
$this->db->join('students', 'students.id = enrols.student_id', 'left');
$this->db->where('students.user_id', $user_id);
$this->db->where('enrols.session', $session);
// Exclure les examens déjà soumis
$this->db->where('exams.id NOT IN (SELECT exam_id FROM exam_responses WHERE user_id = ' . $this->db->escape($user_id) . ')', NULL, FALSE);
$total_exams = $this->db->count_all_results();
log_message('debug', 'Total exams not yet taken calculated: ' . $total_exams);



$unread_messages = $this->user_model->get_unread_messages_count($this->session->userdata('user_id'));

?>

<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu leftside-menu-detached" style="min-width: 280px; max-width: 280px;">
    <div class="leftbar-user">
        <a href="<?php echo route('profile'); ?>">
            <img src="<?php echo $this->user_model->get_user_image($this->session->userdata('user_id')); ?>" alt="user-image" height="42" class="rounded-circle shadow-sm">
            <?php
            $user_details = $this->user_model->get_user_details($this->session->userdata('user_id'));
            ?>
            <span class="leftbar-user-name"><?php echo $user_details['name']; ?></span>
        </a>
    </div>
    <!--- Sidemenu -->
    <ul class="side-nav">
        <li class="side-nav-title side-nav-item py-2"><?php echo get_phrase('navigation'); ?></li>
        <li class="side-nav-item">
            <a href="<?php echo site_url($controller . '/dashboard'); ?>" class="side-nav-link py-2">
                <i class="dripicons-meter"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>

        <?php
        $this->db->order_by('sort_order', 'asc');
        $main_menus = $this->db->get_where('menus', array('parent' => 0, 'status' => 1, $this->session->userdata('user_type') . '_access' => 1))->result_array();
        foreach ($main_menus as $main_menu) {
            log_message('debug', 'Processing menu: ' . $main_menu['unique_identifier']);
        ?>
            <li class="side-nav-item">
                <?php
                $this->db->order_by('sort_order', 'asc');
                $check_menus = $this->db->get_where('menus', array('parent' => $main_menu['id'], 'status' => 1, $this->session->userdata('user_type') . '_access' => 1));
                if ($check_menus->num_rows() > 0) {
                ?>
                    <a data-bs-toggle="collapse" href="#<?php echo $main_menu['unique_identifier']; ?>" aria-expanded="false" aria-controls="<?php echo $main_menu['unique_identifier']; ?>" class="side-nav-link py-2">
                        <i class="<?php echo $main_menu['icon']; ?>"></i>
                        <span><?php echo get_phrase($main_menu['displayed_name']); ?></span>
                        <?php if ($main_menu['unique_identifier'] == 'exam_parent' && $total_exams > 0) : ?>
                            <span class="badge bg-danger" style="margin-left: 5px;"><?php echo $total_exams; ?></span>
                        <?php endif; ?>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="<?php echo $main_menu['unique_identifier']; ?>">
                        <ul class="side-nav-second-level">
                            <?php
                            $this->db->order_by('sort_order', 'asc');
                            $menus = $this->db->get_where('menus', array('parent' => $main_menu['id'], 'status' => 1, $this->session->userdata('user_type') . '_access' => 1))->result_array();
                            foreach ($menus as $menu) {
                                log_message('debug', 'Processing submenu: ' . $menu['unique_identifier']);
                                $this->db->order_by('sort_order', 'asc');
                                $check_sub_menus = $this->db->get_where('menus', array('parent' => $menu['id'], 'status' => 1, $this->session->userdata('user_type') . '_access' => 1));
                                if ($check_sub_menus->num_rows() > 0) {
                            ?>
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#<?php echo $menu['unique_identifier']; ?>" aria-expanded="false" aria-controls="<?php echo $menu['unique_identifier']; ?>">
                                            <?php echo get_phrase($menu['displayed_name']); ?>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="<?php echo $menu['unique_identifier']; ?>">
                                            <ul class="side-nav-third-level">
                                                <?php
                                                $this->db->order_by('sort_order', 'asc');
                                                $sub_menus = $this->db->get_where('menus', array('parent' => $menu['id'], $this->session->userdata('user_type') . '_access' => 1))->result_array();
                                                foreach ($sub_menus as $sub_menu) {
                                                ?>
                                                    <li>
                                                        <?php
                                                        if ($menu['is_addon']) {
                                                            $route = 'addons/' . $sub_menu['route_name'];
                                                        } else {
                                                            $route = $controller . '/' . $sub_menu['route_name'];
                                                        }
                                                        ?>
                                                        <a href="<?php echo site_url($route); ?>"><?php echo get_phrase($sub_menu['displayed_name']); ?></a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </li>
                                <?php } else { ?>
                                    <li>
                                        <?php
                                        if ($menu['is_addon']) {
                                            $route = 'addons/' . $menu['route_name'];
                                        } else {
                                            $route = $controller . '/' . $menu['route_name'];
                                        }
                                        ?>
                                        <a href="<?php echo site_url($route); ?>">
                                            <?php echo get_phrase($menu['displayed_name']); ?>
                                            <?php if ($menu['unique_identifier'] == 'exam' && $total_exams > 0) : ?>
                                                <span class="badge bg-danger float-end" style="padding: 0.4em .4em;"><?php echo $total_exams; ?></span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } else {
                    if ($main_menu['is_addon']) {
                        $route = 'addons/' . $main_menu['route_name'];
                    } else {
                        if ($main_menu['unique_identifier'] == 'online_courses') {
                            $route = 'addons/' . $main_menu['route_name'];
                        } else {
                            $route = $controller . '/' . $main_menu['route_name'];
                        }
                    }
                ?>
                    <a href="<?php echo site_url($route); ?>" class="side-nav-link">
                        <i class="<?php echo $main_menu['icon']; ?>"></i>
                        <span><?php echo get_phrase($main_menu['displayed_name']); ?></span>
                        <?php if ($main_menu['unique_identifier'] == 'online_admission') : ?>
                            <span class="badge bg-danger float-end"><?php echo $this->db->get_where('students', array('status' => 0, 'school_id' => school_id()))->num_rows(); ?></span>
                        <?php endif; ?>
                        <?php if ($main_menu['unique_identifier'] == 'online_admission_school') : ?>
                            <span class="badge bg-danger float-end"><?php echo $this->db->get_where('schools', array('status' => 0, 'Etat' => 1))->num_rows(); ?></span>
                        <?php endif; ?>
                        <?php if ($main_menu['unique_identifier'] == 'exam' && $total_exams > 0) : ?>
                            <span class="badge bg-primary float-end"><?php echo $total_exams; ?></span>
                        <?php endif; ?>
<?php if ($main_menu['unique_identifier'] == 'chat') : ?>
    <span class="badge bg-danger float-end" id="chat-badge">
        <?= $unread_messages > 0 ? $unread_messages : '0' ?>
    </span>
<?php endif; ?>
                    </a>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    <!-- End Sidebar -->

    <div class="clearfix"></div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->
 
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBadge = document.getElementById('chat-badge');
    
    function updateChatBadge() {
        fetch('<?= site_url('user/get_unread_count'); ?>')
            .then(response => response.json())
            .then(data => {
                chatBadge.textContent = data.count;
                chatBadge.classList.toggle('bg-danger', data.count > 0);
                chatBadge.classList.toggle('bg-secondary', data.count == 0);
            });
    }

    // Écoutez les messages de HumHub
    window.addEventListener('message', (event) => {
        if (event.data.type === 'MESSAGE_READ') {
            updateChatBadge();
        }
    });

    // Actualiser périodiquement
    setInterval(updateChatBadge, 30000);
});
</script>