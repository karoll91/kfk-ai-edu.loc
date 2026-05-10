<?php

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    // Barcha yozuvlar
    public static function all(string $orderBy = 'id', string $dir = 'ASC'): array
    {
        $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
        return Database::fetchAll(
            "SELECT * FROM `" . static::$table . "` ORDER BY `{$orderBy}` {$dir}"
        );
    }

    // ID bo'yicha topish
    public static function find(int $id): array|false
    {
        return Database::fetchOne(
            "SELECT * FROM `" . static::$table . "` WHERE `" . static::$primaryKey . "` = ?",
            [$id]
        );
    }

    // Shart bo'yicha bitta topish
    public static function findBy(string $column, mixed $value): array|false
    {
        return Database::fetchOne(
            "SELECT * FROM `" . static::$table . "` WHERE `{$column}` = ? LIMIT 1",
            [$value]
        );
    }

    // Shart bo'yicha barcha topish
    public static function where(string $column, mixed $value, string $orderBy = 'id'): array
    {
        return Database::fetchAll(
            "SELECT * FROM `" . static::$table . "` WHERE `{$column}` = ? ORDER BY `{$orderBy}`",
            [$value]
        );
    }

    // Yangi yozuv qo'shish
    public static function create(array $data): int
    {
        $columns = implode('`, `', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        Database::query(
            "INSERT INTO `" . static::$table . "` (`{$columns}`) VALUES ({$placeholders})",
            array_values($data)
        );
        return (int) Database::lastInsertId();
    }

    // Yangilash
    public static function update(int $id, array $data): bool
    {
        $set = implode(' = ?, ', array_map(fn($c) => "`{$c}`", array_keys($data))) . ' = ?';
        $params = array_values($data);
        $params[] = $id;
        $stmt = Database::query(
            "UPDATE `" . static::$table . "` SET {$set} WHERE `" . static::$primaryKey . "` = ?",
            $params
        );
        return $stmt->rowCount() > 0;
    }

    // O'chirish
    public static function delete(int $id): bool
    {
        $stmt = Database::query(
            "DELETE FROM `" . static::$table . "` WHERE `" . static::$primaryKey . "` = ?",
            [$id]
        );
        return $stmt->rowCount() > 0;
    }

    // Sanoq
    public static function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `" . static::$table . "`";
        if ($where) $sql .= " WHERE {$where}";
        $row = Database::fetchOne($sql, $params);
        return (int) array_values($row)[0];
    }

    // Mavjudligini tekshirish
    public static function exists(string $column, mixed $value): bool
    {
        return (bool) self::findBy($column, $value);
    }
}
