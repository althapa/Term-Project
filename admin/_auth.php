<?php
require __DIR__ . '/../includes/init.php';

if (!is_admin()) {
    header("Location: ../login.php?msg=Admin+access+only");
    exit;
}
?>
