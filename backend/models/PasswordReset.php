<?php

class PasswordReset
{
    public static function create(int $userId, int $ttlMinutes): string
    {
        $token   = bin2hex(random_bytes(32));
        $expires = (new DateTime())->modify("+{$ttlMinutes} minutes")->format('Y-m-d H:i:s');

        $stmt = Database::connection()->prepare(
            'INSERT INTO password_resets (user_id, token, expires_at, used) VALUES (?, ?, ?, 0)'
        );
        $stmt->execute([$userId, $token, $expires]);

        return $token;
    }

    /** Returns the reset row only if it exists, is unused, and hasn't expired. */
    public static function findValidByToken(string $token): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM password_resets WHERE token = ?');
        $stmt->execute([$token]);
        $row = $stmt->fetch();

        if ($row && !$row['used'] && strtotime($row['expires_at']) > time()) {
            return $row;
        }
        return null;
    }

    public static function markUsed(int $id): void
    {
        $stmt = Database::connection()->prepare('UPDATE password_resets SET used = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
}
