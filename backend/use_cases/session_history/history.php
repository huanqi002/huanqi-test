<?php
require __DIR__ . '/../../general/config.php';
require __DIR__ . '/../../general/user_profile.php';
require __DIR__ . '/../../general/history.php';

requireLogin();

$myId = currentUser()['id'];
$rows = fetchFullHistory($conn, $myId);

include __DIR__ . '/../../general/header.php';
?>

<h1>Support history</h1>
<p class="subtitle">All of your past and current support requests and sessions.</p>

<?php if ($rows->num_rows === 0): ?>
<p>Nothing to show yet.</p>
<?php endif; ?>

<?php while ($r = $rows->fetch_assoc()): ?>
<div class="card">
    <?php $status = $r['session_status'] ?? $r['request_status']; // a session's status once the request has one ?>
    <span class="status status-<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></span>
    <h3><?php echo htmlspecialchars($r['category']); ?></h3>
    <p class="meta">Student: <?php echo htmlspecialchars($r['student_name']); ?> &nbsp;|&nbsp; Volunteer: <?php echo htmlspecialchars($r['volunteer_name'] ?? 'Not assigned yet'); ?></p>

    <?php if ($r['session_date']): ?>
        <p class="meta">Session: <?php echo htmlspecialchars($r['session_date']); ?> at <?php echo substr($r['start_time'],0,5); ?>
           (<?php echo htmlspecialchars($r['support_mode']); ?>) &mdash; <?php echo htmlspecialchars($r['session_status']); ?></p>
    <?php endif; ?>

    <?php if ($r['rating']): ?>
        <p class="meta">Feedback: <strong><?php echo (int)$r['rating']; ?> / 5</strong>
           <?php if (!empty($r['comments'])): ?> &mdash; "<?php echo htmlspecialchars($r['comments']); ?>"<?php endif; ?>
        </p>
    <?php endif; ?>
</div>
<?php endwhile; ?>

<?php include __DIR__ . '/../../general/footer.php'; ?>
