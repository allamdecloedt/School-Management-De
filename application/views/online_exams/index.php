<?php
// Désactivation du cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo $page_title; ?> | <?php echo $this->db->get_where('schools', array('id' => school_id()))->row('name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Creativeitem" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo $this->settings_model->get_favicon(); ?>">
    <?php include APPPATH . 'views/backend/includes_top.php'; ?>
</head>
<body class="gray-bg justify-content-center">
    <?php
        include 'online_exam.php';
        include APPPATH . 'views/backend/includes_bottom.php';
        include APPPATH . 'views/backend/common_scripts.php';
    ?>
</body>
</html>