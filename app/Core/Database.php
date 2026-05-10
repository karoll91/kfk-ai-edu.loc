<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $cfg = config('database');
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $cfg['host'], $cfg['port'], $cfg['dbname'], $cfg['charset']
            );
            try {
                self::$instance = new PDO($dsn, $cfg['user'], $cfg['pass'], $cfg['options']);
            } catch (PDOException $e) {
                // Xavfsizlik: xato tafsilotlarini tashqariga chiqarma
                error_log('DB connection error: ' . $e->getMessage());
                http_response_code(500);
                die(json_encode(['error' => 'Database connection failed']));
            }
        }
        return self::$instance;
    }

    // Qulay yordamchi metodlar
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchOne(string $sql, array $params = []): array|false
    {
        return self::query($sql, $params)->fetch();
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function lastInsertId(): string
    {
        return self::getInstance()->lastInsertId();
    }

    public static function beginTransaction(): void
    {
        self::getInstance()->beginTransaction();
    }

    public static function commit(): void
    {
        self::getInstance()->commit();
    }

    public static function rollback(): void
    {
        self::getInstance()->rollBack();
    }
}
