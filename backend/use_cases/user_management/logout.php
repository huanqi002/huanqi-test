<?php
require __DIR__ . '/../../general/config.php';
require __DIR__ . '/../../general/user_profile.php';

logoutUser();
header('Location: select_user.php');
exit;
