<?php

class Alert
{
    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO alerts
                (title, message, severity, target_role, target_department,
                 created_by, recipients_count, sent_count, failed_count, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $data['title'],
            $data['message'],
            $data['severity'],
            $data['target_role'],
            $data['target_department'],
            $data['created_by'],
            $data['recipients_count'],
            $data['sent_count'],
            $data['failed_count'],
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public static function all(): array
    {
        $stmt = Database::connection()->query(
            'SELECT a.*, u.full_name AS sender_name
             FROM alerts a
             JOIN users u ON u.id = a.created_by
             ORDER BY a.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public static function latest(int $limit = 5): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT a.*, u.full_name AS sender_name
             FROM alerts a
             JOIN users u ON u.id = a.created_by
             ORDER BY a.created_at DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function countAll(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM alerts')->fetchColumn();
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM alerts WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findForUser(array $user, ?int $limit = null): array
    {
        $sql = "SELECT * FROM alerts
                WHERE (target_role = 'all' OR target_role = ?)
                  AND (target_department IS NULL OR target_department = ?)
                ORDER BY created_at DESC";
        if ($limit !== null) {
            $sql .= ' LIMIT ?';
        }

        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(1, $user['role']);
        $stmt->bindValue(2, $user['department']);
        if ($limit !== null) {
            $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Count of alerts per severity: ['info' => n, 'warning' => n, 'critical' => n] */
    public static function severityBreakdown(): array
    {
        $stmt = Database::connection()->query('SELECT severity, COUNT(*) AS count FROM alerts GROUP BY severity');
        $result = ['info' => 0, 'warning' => 0, 'critical' => 0];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['severity']] = (int) $row['count'];
        }
        return $result;
    }

    /** Count of alerts per target audience: ['all' => n, 'student' => n, 'staff' => n] */
    public static function targetRoleBreakdown(): array
    {
        $stmt = Database::connection()->query('SELECT target_role, COUNT(*) AS count FROM alerts GROUP BY target_role');
        $result = ['all' => 0, 'student' => 0, 'staff' => 0];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['target_role']] = (int) $row['count'];
        }
        return $result;
    }

    /** Overall delivery success rate across every alert ever sent. */
    public static function deliveryStats(): array
    {
        $row = Database::connection()->query(
            'SELECT SUM(recipients_count) AS total_recipients, SUM(sent_count) AS total_sent, SUM(failed_count) AS total_failed
             FROM alerts'
        )->fetch();

        $totalRecipients = (int) ($row['total_recipients'] ?? 0);
        $totalSent = (int) ($row['total_sent'] ?? 0);
        $totalFailed = (int) ($row['total_failed'] ?? 0);

        return [
            'total_recipients' => $totalRecipients,
            'total_sent'       => $totalSent,
            'total_failed'     => $totalFailed,
            'success_rate'     => $totalRecipients > 0 ? round(($totalSent / $totalRecipients) * 100, 1) : null,
        ];
    }

    /** Alert counts per day for the last $days days, zero-filled for days with none. */
    public static function dailyCounts(int $days = 30): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT DATE(created_at) AS day, COUNT(*) AS count
             FROM alerts
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             GROUP BY DATE(created_at)'
        );
        $stmt->bindValue(1, $days, PDO::PARAM_INT);
        $stmt->execute();

        $counts = [];
        foreach ($stmt->fetchAll() as $row) {
            $counts[$row['day']] = (int) $row['count'];
        }

        $series = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $series[] = ['date' => $date, 'count' => $counts[$date] ?? 0];
        }

        return $series;
    }
}