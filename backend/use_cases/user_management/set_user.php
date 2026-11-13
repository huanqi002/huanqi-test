<?php
require __DIR__ . '/../../general/config.php';
require __DIR__ . '/../../general/user_profile.php';

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = findUserById($conn, $id);

if (!$user) {
    header('Location: select_user.php');
    exit;
}

loginAsUser($user);

header('Location: ../support_request/index.php');
exit;
