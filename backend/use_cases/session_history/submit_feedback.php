<?php
require __DIR__ . '/../../general/config.php';
require __DIR__ . '/../../general/session.php';

if (empty($_SESSION['user_id'])) {
    header('Location: ../user_management/select_user.php');
    exit;
}

$sessionId = (int)($_POST['session_id'] ?? 0);
$rating    = (int)($_POST['rating'] ?? 0);
$comments  = trim($_POST['comments'] ?? '');

// Validate rating
if ($rating < 1 || $rating > 5) {
    header('Location: feedback.php?session_id=' . $sessionId . '&err=' . urlencode('Please choose a rating from 1 to 5.'));
    exit;
}

// Re-check the session belongs to this student and is completed (alt. course 9a)
$session = findSessionForFeedback($conn, $sessionId);

// Only the session's student can leave feedback
if (!$session || $session['user_id'] != $_SESSION['user_id']) {
    header('Location: ../support_request/index.php?err=' . urlencode('Only the student can leave feedback.'));
    exit;
}
if ($session['status'] !== 'Completed') {
    header('Location: feedback.php?session_id=' . $sessionId . '&err=' . urlencode('You can only rate a session after it is completed.'));
    exit;
}

if (hasFeedback($conn, $sessionId)) {
    header('Location: ../support_request/index.php?err=' . urlencode('Feedback has already been submitted for this session.'));
    exit;
}

$conn->begin_transaction();
try {
    $feedbackId = insertFeedback($conn, $session, $rating, $comments);
    attachFeedbackToHistory($conn, $sessionId, $feedbackId);
    $conn->commit();
    header('Location: ../support_request/index.php?msg=' . urlencode('Thank you! Your feedback has been saved.'));
} catch (Exception $e) {
    $conn->rollback();
    header('Location: feedback.php?session_id=' . $sessionId . '&err=' . urlencode('Something went wrong. Please try again.'));
}
exit;
