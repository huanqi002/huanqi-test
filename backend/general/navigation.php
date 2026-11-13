<?php
// Expects $conn and session already started (config.php included by the page)
$currentUserId = $_SESSION['user_id'] ?? null;
$currentRole   = $_SESSION['role'] ?? null;
$currentName   = $_SESSION['name'] ?? null;
?>
<header class="topbar">
    <a href="../support_request/index.php" class="brand">Support Session Manager</a>
    <?php if ($currentUserId): ?>
    <nav class="topnav">
        <span class="whoami">Signed in as <strong><?php echo htmlspecialchars($currentName); ?></strong> (<?php echo htmlspecialchars(ucfirst($currentRole)); ?>)</span>
        <a href="../support_request/index.php">My sessions</a>
        <a href="../session_history/history.php">History</a>
        <a href="../user_management/logout.php">Switch user</a>
    </nav>
    <?php endif; ?>
</header>
