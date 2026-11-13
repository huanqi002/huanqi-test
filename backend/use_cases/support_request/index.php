<?php
require __DIR__ . '/../../general/config.php';
require __DIR__ . '/../../general/user_profile.php';
require __DIR__ . '/../../general/history.php';
require __DIR__ . '/../../general/session.php';

requireLogin();

$myId = currentUser()['id'];

$rows = fetchMyActivity($conn, $myId);

include __DIR__ . '/../../general/header.php';
?>

<h1>My sessions</h1>
<p class="subtitle">Here are your support requests and sessions. Choose an action below to continue.</p>

<?php if (isset($_GET['msg'])): ?>
<div class="message message-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>
<?php if (isset($_GET['err'])): ?>
<div class="message message-error"><?php echo htmlspecialchars($_GET['err']); ?></div>
<?php endif; ?>

<?php if ($rows->num_rows === 0): ?>
<p>You have no support requests yet.</p>
<?php endif; ?>

<?php while ($r = $rows->fetch_assoc()):
    // A user can be the student on one request and the volunteer on another
    $isStudentHere    = ($r['user_id'] == $myId);
    $otherPersonName  = $isStudentHere ? ($r['volunteer_name'] ?? 'Not assigned yet') : $r['student_name'];
    $otherPersonLabel = $isStudentHere ? 'Volunteer' : 'Student';

    // Once a volunteer accepts, the request is Completed and its latest session drives the card
    $session = $r['session_id'] ? [
        'status'       => $r['session_status'],
        'session_date' => $r['session_date'],
        'start_time'   => $r['start_time'],
    ] : null;
    $status = $session ? sessionDisplayStatus($session) : $r['request_status'];
?>
<div class="card">
    <span class="status status-<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></span>
    <h3><?php echo htmlspecialchars($r['category']); ?></h3>
    <p class="meta"><?php echo $otherPersonLabel; ?>: <?php echo htmlspecialchars($otherPersonName); ?></p>

    <?php if (!$session): ?>
        <p class="meta">Waiting for a volunteer to accept this request.</p>

    <?php elseif ($status === 'Pending'): ?>
        <p class="meta">No date and time booked yet.</p>
        <div class="btn-row">
            <a class="btn btn-primary" href="schedule.php?session_id=<?php echo (int)$r['session_id']; ?>">Book a session</a>
        </div>

    <?php elseif ($status === 'Scheduled' || $status === 'Upcoming'): ?>
        <p class="meta">Date: <strong><?php echo htmlspecialchars($r['session_date']); ?></strong>
           at <strong><?php echo substr($r['start_time'],0,5); ?></strong>
           &mdash; <?php echo htmlspecialchars($r['support_mode']); ?></p>
        <div class="btn-row">
            <?php if (!$isStudentHere && hasSessionStarted($session)): ?>
            <form method="post" action="../session_history/mark_completed.php" style="display:inline">
                <input type="hidden" name="session_id" value="<?php echo (int)$r['session_id']; ?>">
                <button type="submit" class="btn btn-primary">Mark as complete</button>
            </form>
            <?php endif; ?>
            <form method="post" action="../session_history/cancel_session.php" onsubmit="return confirm('Cancel this session?');" style="display:inline">
                <input type="hidden" name="session_id" value="<?php echo (int)$r['session_id']; ?>">
                <button type="submit" class="btn btn-secondary">Cancel session</button>
            </form>
        </div>

    <?php elseif ($status === 'Completed'): ?>
        <p class="meta">Session held on <strong><?php echo htmlspecialchars($r['session_date']); ?></strong>
           at <strong><?php echo substr($r['start_time'],0,5); ?></strong>
           &mdash; <?php echo htmlspecialchars($r['support_mode']); ?></p>
        <?php if ($r['feedback_id']): ?>
            <p class="meta">Feedback given: <strong><?php echo (int)$r['rating']; ?> / 5</strong></p>
        <?php elseif ($isStudentHere): ?>
            <div class="btn-row">
                <a class="btn btn-primary" href="../session_history/feedback.php?session_id=<?php echo (int)$r['session_id']; ?>">Rate this session</a>
            </div>
        <?php else: ?>
            <p class="meta">Waiting for the student to leave feedback.</p>
        <?php endif; ?>

    <?php elseif ($status === 'Cancelled'): ?>
        <p class="meta">This session was cancelled.</p>
    <?php endif; ?>
</div>
<?php endwhile; ?>

<?php include __DIR__ . '/../../general/footer.php'; ?>
