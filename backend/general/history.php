<?php
// Shared helpers for the "My sessions" dashboard and full history views.
// Expects $conn already created (config.php included by the page)

function fetchMyActivity(mysqli $conn, int $myId): mysqli_result
{
    $sql = "SELECT r.id AS request_id, COALESCE(s.category, r.category) AS category, r.status AS request_status,
                   r.user_id, r.volunteer_id,
                   us.full_name AS student_name, uv.full_name AS volunteer_name,
                   s.id AS session_id, s.session_date, s.start_time, s.support_mode, s.status AS session_status,
                   f.id AS feedback_id, f.rating
            FROM requests r
            JOIN users us ON r.user_id = us.id
            LEFT JOIN users uv ON r.volunteer_id = uv.id
            LEFT JOIN sessions s ON s.id = (SELECT id FROM sessions WHERE request_id = r.id ORDER BY id DESC LIMIT 1)
            LEFT JOIN feedbacks f ON f.session_id = s.id
            WHERE r.user_id = ? OR r.volunteer_id = ?
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $myId, $myId);
    $stmt->execute();
    return $stmt->get_result();
}

function fetchFullHistory(mysqli $conn, int $myId): mysqli_result
{
    $sql = "SELECT COALESCE(s.category, r.category) AS category, r.status AS request_status,
                   us.full_name AS student_name, uv.full_name AS volunteer_name,
                   s.session_date, s.start_time, s.support_mode, s.status AS session_status,
                   f.rating, f.comments
            FROM requests r
            JOIN users us ON r.user_id = us.id
            LEFT JOIN users uv ON r.volunteer_id = uv.id
            LEFT JOIN sessions s ON s.request_id = r.id
            LEFT JOIN feedbacks f ON f.session_id = s.id
            WHERE r.user_id = ? OR r.volunteer_id = ?
            ORDER BY s.session_date DESC, s.start_time DESC, r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $myId, $myId);
    $stmt->execute();
    return $stmt->get_result();
}
