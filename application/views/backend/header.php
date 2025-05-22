<!-- Topbar Start -->
 <div class="navbar-custom topnav-navbar navbar-color topnav-navbar-dark">
    <div class="container-fluid">

        <!-- LOGO -->
        <a href="<?php echo site_url($this->session->userdata('role')); ?>" class="topnav-logo" style="min-width: unset;">
            <span class="topnav-logo-lg">
                <img src="<?php echo $this->settings_model->get_logo_light(); ?>" alt="" height="40">
            </span>
            <span class="topnav-logo-sm">
                <img src="<?php echo $this->settings_model->get_logo_light('small'); ?>" alt="" height="40">
            </span>
        </a>

        <!-- Topbar Menu -->
        <ul class="list-unstyled topbar-menu float-end mb-0 d-flex align-items-center">

            <?php if ($this->session->userdata('user_type') == 'superadmin' || $this->session->userdata('user_type') == 'admin' || $this->session->userdata('user_type') == 'teacher' || $this->session->userdata('user_type') == 'student'): ?>

                <!-- Notification Bell -->
                <li class="dropdown notification-list topbar-dropdown d-flex align-items-center ms-3">
                    <div class="dropdown">
                        <button class="btn btn-light position-relative" id="notifDropdown" data-bs-toggle="dropdown">
                            <!-- Icône principale de la cloche -->
                            <i class="dripicons-bell"></i>
                            <!-- Badge pour le compteur de notifications -->
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notif-count">0</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;" id="notif-list">
                            <li class="dropdown-header">
                                <!-- Ajout d'une icône dans l'en-tête -->
                                <i class="bi bi-envelope me-2"></i> Notifications
                            </li>
                            <li>
                                <div class="text-center text-muted">Chargement...</div>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Language Dropdown -->
                <li class="dropdown notification-list topbar-dropdown d-none d-lg-block ms-3">
                    <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" onclick="getLanguageList()">
                        <i class="mdi mdi-translate noti-icon"></i> <?php echo ucfirst(get_user_language()); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg">
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0"><?php echo get_phrase('language'); ?></h5>
                        </div>
                        <div class="slimscroll" id="language-list" style="min-height: 150px;"></div>
                    </div>
                </li>

            <?php endif; ?>

            <!-- User Profile -->
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user user-dropdown arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="account-user-avatar">
                        <img src="<?php echo $this->user_model->get_user_image($this->session->userdata('user_id')); ?>" alt="user-image" class="rounded-circle">
                    </span>
                    <span>
                        <span class="account-user-name"><?php echo $user_name; ?></span>
                        <span class="account-position">
                            <?php
                            $role = strtolower($this->db->get_where('users', array('id' => $user_id))->row('role'));
                            echo $role == 'admin' ? get_phrase('school_admin') : ucfirst($role);
                            ?>
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                    <!-- item-->
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0"><?php echo get_phrase('welcome'); ?> !</h6>
                    </div>
                    <a href="<?php echo route('profile'); ?>" class="dropdown-item notify-item">
                        <i class="mdi mdi-account-circle me-1"></i>
                        <span><?php echo get_phrase('my_account'); ?></span>
                    </a>
                    <?php if ($this->session->userdata('user_type') == 'superadmin'): ?>
                        <a href="<?php echo route('system_settings'); ?>" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-edit me-1"></i>
                            <span><?php echo get_phrase('settings'); ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if ($this->session->userdata('user_type') == 'superadmin' || $this->session->userdata('user_type') == 'admin'): ?>
                        <!-- item-->
                        <a href="mailto:info@wayo.cloud?Subject=Help%20On%20This" target="_blank" class="dropdown-item notify-item">
                            <i class="mdi mdi-lifebuoy me-1"></i>
                            <span><?php echo get_phrase('support'); ?></span>
                        </a>
                    <?php endif; ?>

                    <!-- item-->
                    <a href="<?php echo site_url('login/logout'); ?>" class="dropdown-item notify-item">
                        <i class="mdi mdi-logout me-1"></i>
                        <span><?php echo get_phrase('logout'); ?></span>
                    </a>
                </div>
            </li>

        </ul>

        <!-- System Name -->
        <div class="app-search dropdown pt-1 mt-2">
            <h4 style="color: #fff; float: left;" class="d-none d-md-inline-block"><?php echo get_settings('system_name'); ?></h4>
            <a href="<?php echo site_url(); ?>" target="" class="btn btn-outline-light website-button ms-2 d-none d-md-inline-block"><?php echo get_phrase('visit_website'); ?></a>
        </div>

        <!-- Mobile Menu Button -->
        <a class="button-menu-mobile disable-btn">
            <div class="lines">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </a>
    </div>
</div>
<!-- end Topbar -->


<script type="text/javascript">
function getLanguageList() {
    $.ajax({
        url: "<?php echo route('language/dropdown'); ?>",
        success: function(response){
            $('#language-list').html(response);
        }
    });
}


document.addEventListener("DOMContentLoaded", function () {
  fetch('/notificationcontroller/fetch')
    .then(response => response.json())
    .then(data => {
      const notifCount = document.getElementById('notif-count');
      const notifList = document.getElementById('notif-list');
        console.log("fettah : "+data);
      notifCount.textContent = data.unread_count;

      notifList.innerHTML = '<li class="dropdown-header">Notifications</li>';
      data.notifications.forEach(n => {
        notifList.innerHTML += `
          <li><a class="dropdown-item" href="${n.link || '#'}">${n.message}</a></li>
        `;
      });

      notifList.innerHTML += `
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-center" href="#" onclick="markAllRead()">Tout marquer comme lu</a></li>
      `;
    });
});

function markAllRead() {
  fetch('/notificationcontroller/markAllAsRead')
    .then(res => res.json())
    .then(() => location.reload());
}
</script>
