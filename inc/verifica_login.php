<?php
if (!isset($_SESSION['id_login'])) {
    echo "<script>
        window.location.href = '../login.php'
        </script>";
    exit;
}
?>