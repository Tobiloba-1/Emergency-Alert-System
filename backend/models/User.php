<?php

class User
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO users (full_name, email, password_hash, role, department, badge_id, phone, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['password_hash'],
            $data['role'],
            $data['department'],
            $data['badge_id'] ?? null,
            $data['phone'] ?? null,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public static function updatePassword(int $userId, string $hash): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $stmt->execute([$hash, $userId]);
    }

    public static function findEmailsByTarget(string $targetRole, ?string $targetDepartment, string $severity): array
    {
        $column = match ($severity) {
            'critical' => 'notify_critical',
            'warning'  => 'notify_warning',
            default    => 'notify_info',
        };

        $sql = "SELECT email, full_name FROM users
                WHERE role NOT IN ('admin','security') AND is_active = 1 AND $column = 1";
        $params = [];

        if ($targetRole !== 'all') {
            $sql .= ' AND role = ?';
            $params[] = $targetRole;
        }

        if ($targetDepartment !== null && $targetDepartment !== '') {
            $sql .= ' AND department = ?';
            $params[] = $targetDepartment;
        }

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function updateProfile(int $userId, string $fullName, string $department, string $phone): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users SET full_name = ?, department = ?, phone = ? WHERE id = ?'
        );
        $stmt->execute([$fullName, $department, $phone, $userId]);
    }

    public static function updatePreferences(int $userId, array $prefs): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users SET notify_info = ?, notify_warning = ?, notify_critical = ? WHERE id = ?'
        );
        $stmt->execute([$prefs['notify_info'], $prefs['notify_warning'], $prefs['notify_critical'], $userId]);
    }

    public static function countStudents(): int
    {
        return self::countByRole('student');
    }

    public static function countStaff(): int
    {
        return self::countByRole('staff');
    }

    private static function countByRole(string $role): int
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM users WHERE role = ?');
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }

    public static function setOnDuty(int $userId, bool $onDuty, ?float $lat = null, ?float $lng = null, ?float $accuracy = null): void
    {
        if ($onDuty && $lat !== null && $lng !== null) {
            $stmt = Database::connection()->prepare(
                'UPDATE users SET on_duty = 1, last_lat = ?, last_lng = ?, last_accuracy = ?, last_location_at = NOW() WHERE id = ?'
            );
            $stmt->execute([$lat, $lng, $accuracy, $userId]);
        } else {
            $stmt = Database::connection()->prepare('UPDATE users SET on_duty = ? WHERE id = ?');
            $stmt->execute([$onDuty ? 1 : 0, $userId]);
        }
    }

    public static function updateLocation(int $userId, float $lat, float $lng, ?float $accuracy = null): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users SET last_lat = ?, last_lng = ?, last_accuracy = ?, last_location_at = NOW() WHERE id = ?'
        );
        $stmt->execute([$lat, $lng, $accuracy, $userId]);
    }

    public static function findOnDutyOfficials(): array
    {
        $stmt = Database::connection()->query(
            "SELECT id, full_name, email, badge_id, phone, last_lat, last_lng
             FROM users WHERE role = 'security' AND on_duty = 1 AND is_active = 1"
        );
        return $stmt->fetchAll();
    }

    public static function search(?string $role, ?string $q): array
    {
        $sql = 'SELECT * FROM users WHERE 1=1';
        $params = [];

        if ($role) {
            $sql .= ' AND role = ?';
            $params[] = $role;
        }
        if ($q) {
            $sql .= ' AND (full_name LIKE ? OR email LIKE ?)';
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        $sql .= ' ORDER BY created_at DESC';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function adminUpdate(int $userId, string $role, string $department, int $isActive, ?string $phone, ?string $badgeId): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users SET role = ?, department = ?, is_active = ?, phone = ?, badge_id = ? WHERE id = ?'
        );
        $stmt->execute([$role, $department, $isActive, $phone, $badgeId, $userId]);
    }
}