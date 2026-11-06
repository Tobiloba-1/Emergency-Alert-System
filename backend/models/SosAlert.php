<?php

class SosAlert
{
    public static function create(int $studentId, float $lat, float $lng, ?float $accuracy = null, ?int $notifiedOfficialId = null): int
    {
        $stmt = Database::connection()->prepare(
            "INSERT INTO sos_alerts (student_id, latitude, longitude, accuracy, notified_official_id, status, created_at)
             VALUES (?, ?, ?, ?, ?, 'active', NOW())"
        );
        $stmt->execute([$studentId, $lat, $lng, $accuracy, $notifiedOfficialId]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT sa.*, u.full_name AS student_name, u.email AS student_email,
                    u.department AS student_department, u.phone AS student_phone
             FROM sos_alerts sa JOIN users u ON u.id = sa.student_id
             WHERE sa.id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function allOpen(): array
    {
        $stmt = Database::connection()->query(
            "SELECT sa.*, u.full_name AS student_name, u.department AS student_department, u.phone AS student_phone
             FROM sos_alerts sa JOIN users u ON u.id = sa.student_id
             WHERE sa.status != 'resolved'
             ORDER BY sa.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    /** Every SOS this student has sent, with the notified and responding officials attached. */
    public static function findByStudent(int $studentId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT sa.*,
                    n.full_name AS notified_name, n.badge_id AS notified_badge, n.phone AS notified_phone,
                    r.full_name AS responder_name, r.badge_id AS responder_badge, r.phone AS responder_phone
             FROM sos_alerts sa
             LEFT JOIN users n ON n.id = sa.notified_official_id
             LEFT JOIN users r ON r.id = sa.acknowledged_by
             WHERE sa.student_id = ?
             ORDER BY sa.created_at DESC'
        );
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    /** Every SOS ever sent, for the admin view. */
    public static function findAllDetailed(): array
    {
        $stmt = Database::connection()->query(
            'SELECT sa.*,
                    s.full_name AS student_name, s.department AS student_department, s.phone AS student_phone,
                    n.full_name AS notified_name, n.badge_id AS notified_badge,
                    r.full_name AS responder_name, r.badge_id AS responder_badge
             FROM sos_alerts sa
             JOIN users s ON s.id = sa.student_id
             LEFT JOIN users n ON n.id = sa.notified_official_id
             LEFT JOIN users r ON r.id = sa.acknowledged_by
             ORDER BY sa.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /** Returns true only if this call actually moved the alert from active to acknowledged. */
    public static function acknowledge(int $id, int $officialId): bool
    {
        $stmt = Database::connection()->prepare(
            "UPDATE sos_alerts SET status = 'acknowledged', acknowledged_by = ?, acknowledged_at = NOW()
             WHERE id = ? AND status = 'active'"
        );
        $stmt->execute([$officialId, $id]);
        return $stmt->rowCount() > 0;
    }

    public static function resolve(int $id): void
    {
        $stmt = Database::connection()->prepare(
            "UPDATE sos_alerts SET status = 'resolved', resolved_at = NOW() WHERE id = ?"
        );
        $stmt->execute([$id]);
    }

    public static function countAll(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM sos_alerts')->fetchColumn();
    }

    public static function countActive(): int
    {
        $stmt = Database::connection()->query("SELECT COUNT(*) FROM sos_alerts WHERE status != 'resolved'");
        return (int) $stmt->fetchColumn();
    }

    public static function responseStats(): array
    {
        $row = Database::connection()->query(
            "SELECT
                AVG(TIMESTAMPDIFF(SECOND, created_at, acknowledged_at)) AS avg_ack_seconds,
                AVG(TIMESTAMPDIFF(SECOND, created_at, resolved_at)) AS avg_resolve_seconds
             FROM sos_alerts
             WHERE acknowledged_at IS NOT NULL"
        )->fetch();

        return [
            'avg_ack_minutes'     => $row['avg_ack_seconds'] !== null ? round($row['avg_ack_seconds'] / 60, 1) : null,
            'avg_resolve_minutes' => $row['avg_resolve_seconds'] !== null ? round($row['avg_resolve_seconds'] / 60, 1) : null,
        ];
    }
}