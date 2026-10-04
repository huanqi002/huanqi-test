<?php
require __DIR__ . '/../../general/config.php';
require __DIR__ . '/../../general/user_profile.php';
require __DIR__ . '/../../general/session.php';

requireLogin();

$sessionId = (int)($_POST['session_id'] ?? 0);
$date      = $_POST['session_date'] ?? '';
$time      = $_POST['session_time'] ?? '';
$mode      = $_POST['mode'] ?? '';
$myId      = currentUser()['id'];

if (!$sessionId || !$date || !$time || !in_array($mode, ['Online','Face-to-face'], true)) {
    header('Location: schedule.php?session_id=' . $sessionId . '&err=' . urlencode('Please fill in every field.'));
    exit;
}

$session = findSessionWithStatus($conn, $sessionId, 'Pending');

if (!$session || ($session['user_id'] != $myId && $session['volunteer_id'] != $myId)) {
    header('Location: index.php?err=' . urlencode('This session is not ready to be scheduled.'));
    exit;
}

// Make sure the slot is still free (someone else may have booked it in the meantime)
if (!findAvailableSlot($conn, (int)$session['volunteer_id'], $date, $time)) {
    header('Location: schedule.php?session_id=' . $sessionId . '&err=' . urlencode('That time is no longer available. Please choose another.'));
    exit;
}

scheduleSession($conn, $sessionId, $date, $time, $mode);
header('Location: index.php?msg=' . urlencode('Session booked successfully.'));
exit;
