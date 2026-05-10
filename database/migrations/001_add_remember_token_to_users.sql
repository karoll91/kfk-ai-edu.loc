-- ============================================================
-- Migration 001: users jadvaliga remember_token ustuni qo'shish
-- MySQL 8.0+ / MariaDB 10.3+
-- Xavfsiz: ustun yoki index allaqachon mavjud bo'lsa xato bermaydi
-- ============================================================

USE kfkaiedu_db;

DROP PROCEDURE IF EXISTS migration_001_remember_token;

DELIMITER $$

CREATE PROCEDURE migration_001_remember_token()
BEGIN
    -- 1. Ustun yo'q bo'lsa qo'shish
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'users'
          AND COLUMN_NAME  = 'remember_token'
    ) THEN
        ALTER TABLE users
            ADD COLUMN remember_token VARCHAR(64) NULL DEFAULT NULL
            AFTER email_verified_at;

        SELECT 'remember_token ustuni qo''shildi.' AS result;
    ELSE
        SELECT 'remember_token ustuni allaqachon mavjud, o''tkazib yuborildi.' AS result;
    END IF;

    -- 2. Index yo'q bo'lsa qo'shish (WHERE remember_token = ? so'rovlari uchun)
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'users'
          AND INDEX_NAME   = 'idx_remember_token'
    ) THEN
        ALTER TABLE users
            ADD INDEX idx_remember_token (remember_token);

        SELECT 'idx_remember_token indeksi qo''shildi.' AS result;
    ELSE
        SELECT 'idx_remember_token indeksi allaqachon mavjud, o''tkazib yuborildi.' AS result;
    END IF;
END$$

DELIMITER ;

CALL migration_001_remember_token();

DROP PROCEDURE IF EXISTS migration_001_remember_token;
