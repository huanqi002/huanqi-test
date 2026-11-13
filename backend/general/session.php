<?php
// Shared helpers for the sessions, volunteers, feedbacks, and history entities.
// Expects $conn already created (config.php included by the page)

// Volunteer slots are weekly (preferred_day + preferred_time). A slot is free on a
// date when it is offered on that weekday and no Scheduled session already uses it.
const FREE_SLOT_SQL = "FROM volunteers v
                       WHERE v.user_id = ? AND v.preferred_day = DAYNAME(?) AND v.is_available = 'Y'
                         AND NOT EXISTS (SELECT 1 FROM sessions s
                                         WHERE s.volunteer_id = v.user_id AND s.session_date = ?
                                           AND s.start_time = v.preferred_time AND s.status = 'Scheduled')";

function getAvailableTimes(mysqli $conn, int $volunteerId, string $date): array
{
    $stmt = $conn->prepare("SELECT v.preferred_time " . FREE_SLOT_SQL . " ORDER BY v.preferred_time");
    $stmt->bind_param('iss', $volunteerId, $date, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $times = [];
    while ($row = $result->fetch_assoc()) {
        $times[] = substr($row['preferred_time'], 0, 5); // HH:MM
    }
    return $times;
}

function findAvailableSlot(mysqli $conn, int $volunteerId, string $date, string $time): ?array
{
    $stmt = $conn->prepare("SELECT v.id " . FREE_SLOT_SQL . " AND v.preferred_time = ?");
    $stmt->bind_param('isss', $volunteerId, $date, $date, $time);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

// Called when a volunteer accepts a request: the session starts Pending (no date yet)
// with its own copy of the request's category and description.
function createPendingSession(mysqli $conn, array $request): void
{
    $requestId   = (int)$request['id'];
    $userId      = (int)$request['user_id'];
    $volunteerId = (int)$request['volunteer_id'];
    $category    = $request['category'];
    $description = $request['description'];
    $mode        = $request['support_mode'];

    $stmt = $conn->prepare("INSERT INTO sessions (request_id, user_id, volunteer_id, category, description, support_mode, status)
                             VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param('iiisss', $requestId, $userId, $volunteerId, $category, $description, $mode);
    $stmt->execute();
}

function scheduleSession(mysqli $conn, int $sessionId, string $date, string $time, string $mode): void
{
    $stmt = $conn->prepare("UPDATE sessions SET session_date = ?, start_time = ?, support_mode = ?, status = 'Scheduled'
                             WHERE id = ? AND status = 'Pending'");
    $stmt->bind_param('sssi', $date, $time, $mode, $sessionId);
    $stmt->execute();
}

function findSessionWithStatus(mysqli $conn, int $sessionId, string $status): ?array
{
    $stmt = $conn->prepare("SELECT s.*, uv.full_name AS volunteer_name
                             FROM sessions s
                             JOIN users uv ON s.volunteer_id = uv.id
                             WHERE s.id = ? AND s.status = ?");
    $stmt->bind_param('is', $sessionId, $status);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function findScheduledSession(mysqli $conn, int $sessionId): ?array
{
    return findSessionWithStatus($conn, $sessionId, 'Scheduled');
}

// The volunteer can mark a Scheduled session complete once its start time has arrived
function hasSessionStarted(array $session): bool
{
    return strtotime($session['session_date'] . ' ' . $session['start_time']) <= time();
}

// "Upcoming" is not stored: it is a Scheduled session happening today that has not started yet
function sessionDisplayStatus(array $session): string
{
    if ($session['status'] === 'Scheduled'
        && $session['session_date'] === date('Y-m-d')
        && !hasSessionStarted($session)) {
        return 'Upcoming';
    }
    return $session['status'];
}

function findSessionForFeedback(mysqli $conn, int $sessionId): ?array
{
    $stmt = $conn->prepare("SELECT s.*, uv.full_name AS volunteer_name
                             FROM sessions s
                             JOIN users uv ON s.volunteer_id = uv.id
                             WHERE s.id = ?");
    $stmt->bind_param('i', $sessionId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function updateSessionStatus(mysqli $conn, int $sessionId, string $status): void
{
    $stmt = $conn->prepare("UPDATE sessions SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $sessionId);
    $stmt->execute();
}

function hasFeedback(mysqli $conn, int $sessionId): bool
{
    $stmt = $conn->prepare("SELECT id FROM feedbacks WHERE session_id = ?");
    $stmt->bind_param('i', $sessionId);
    $stmt->execute();
    return (bool) $stmt->get_result()->fetch_assoc();
}

function insertFeedback(mysqli $conn, array $session, int $rating, string $comments): int
{
    $sessionId   = (int)$session['id'];
    $userId      = (int)$session['user_id'];
    $volunteerId = (int)$session['volunteer_id'];

    $stmt = $conn->prepare("INSERT INTO feedbacks (session_id, user_id, volunteer_id, rating, comments)
                             VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('iiiis', $sessionId, $userId, $volunteerId, $rating, $comments);
    $stmt->execute();
    return $conn->insert_id;
}

function recordHistory(mysqli $conn, array $session, string $finalStatus, ?string $cancellationReason = null): void
{
    $sessionId   = (int)$session['id'];
    $userId      = (int)$session['user_id'];
    $volunteerId = (int)$session['volunteer_id'];

    $stmt = $conn->prepare("INSERT INTO history (session_id, user_id, volunteer_id, final_status, cancellation_reason)
                             VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('iiiss', $sessionId, $userId, $volunteerId, $finalStatus, $cancellationReason);
    $stmt->execute();
}

function attachFeedbackToHistory(mysqli $conn, int $sessionId, int $feedbackId): void
{
    $stmt = $conn->prepare("UPDATE history SET feedback_id = ? WHERE session_id = ?");
    $stmt->bind_param('ii', $feedbackId, $sessionId);
    $stmt->execute();
}
