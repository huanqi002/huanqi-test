<?php
require __DIR__ . '/../../general/config.php';
require __DIR__ . '/../../general/session.php';

if (empty($_SESSION['user_id'])) {
    header('Location: ../user_management/select_user.php');
    exit;
}

$sessionId = (int)($_POST['session_id'] ?? 0);
$session   = findScheduledSession($conn, $sessionId);

// Only the session's volunteer can complete it (a user may be a student elsewhere)
if (!$session || $session['volunteer_id'] != $_SESSION['user_id']) {
    header('Location: ../support_request/index.php?err=' . urlencode('Only the volunteer can mark a session as completed.'));
    exit;
}

if (!hasSessionStarted($session)) {
    header('Location: ../support_request/index.php?err=' . urlencode('A session can only be marked as completed once its start time has arrived.'));
    exit;
}

$conn->begin_transaction();
try {
    updateSessionStatus($conn, $sessionId, 'Completed');
    recordHistory($conn, $session, 'Completed');
    $conn->commit();
    header('Location: ../support_request/index.php?msg=' . urlencode('Session marked as completed. The student can now leave feedback.'));
} catch (Exception $e) {
    $conn->rollback();
    header('Location: ../support_request/index.php?err=' . urlencode('Something went wrong. Please try again.'));
}
exit;
