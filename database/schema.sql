-- ============================================================
-- KompMind — Ma'lumotlar bazasi sxemasi
-- MySQL 8.0+
-- ============================================================

CREATE DATABASE IF NOT EXISTS kfkaiedu_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE kfkaiedu_db;

-- ------------------------------------------------------------
-- 1. FOYDALANUVCHILAR
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)        NOT NULL,
    email         VARCHAR(150)        NOT NULL UNIQUE,
    password_hash VARCHAR(255)        NOT NULL,
    role          ENUM('student','teacher','admin') NOT NULL DEFAULT 'student',
    email_verified_at DATETIME        NULL,
    remember_token VARCHAR(64)        NULL,
    INDEX idx_remember_token (remember_token),
    avatar        VARCHAR(255)        NULL,
    is_active     TINYINT(1)          NOT NULL DEFAULT 1,
    created_at    DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. MODULLAR
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS modules (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(80)     NOT NULL UNIQUE,
    name        VARCHAR(150)    NOT NULL,
    icon        VARCHAR(10)     NOT NULL DEFAULT '📚',
    description TEXT            NULL,
    bloom_level TINYINT         NOT NULL DEFAULT 1 COMMENT '1=bilish,2=tushunish,3=qollash,4=tahlil,5=baholash,6=yaratish',
    sort_order  TINYINT         NOT NULL DEFAULT 0,
    badge       ENUM('','new','hot') NOT NULL DEFAULT '',
    is_active   TINYINT(1)      NOT NULL DEFAULT 1,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. MASHQLAR
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS exercises (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id   INT UNSIGNED    NOT NULL,
    title       VARCHAR(200)    NOT NULL,
    description TEXT            NOT NULL,
    instructions TEXT           NOT NULL,
    type        ENUM('practical','creative') NOT NULL DEFAULT 'practical',
    max_score   TINYINT         NOT NULL DEFAULT 100,
    sort_order  TINYINT         NOT NULL DEFAULT 0,
    is_active   TINYINT(1)      NOT NULL DEFAULT 1,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. IJODIY VAZIFALAR
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS creative_tasks (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exercise_id  INT UNSIGNED   NOT NULL,
    task_type    VARCHAR(60)    NOT NULL COMMENT 'elements_game, broken_composition, one_word, ...',
    elements     JSON           NULL COMMENT 'Tasodifiy elementlar massivi',
    prompt       TEXT           NOT NULL,
    created_at   DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. TOPSHIRILGAN ISHLAR
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS submissions (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED    NOT NULL,
    exercise_id INT UNSIGNED    NOT NULL,
    content     TEXT            NOT NULL,
    score       TINYINT         NULL     COMMENT 'NULL = baholanmagan',
    ai_feedback TEXT            NULL,
    teacher_feedback TEXT       NULL,
    teacher_score TINYINT       NULL,
    status      ENUM('submitted','ai_reviewed','teacher_reviewed') NOT NULL DEFAULT 'submitted',
    submitted_at DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reviewed_at DATETIME        NULL,
    FOREIGN KEY (user_id)     REFERENCES users(id)     ON DELETE CASCADE,
    FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_exercise (user_id, exercise_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. TARAQQIYOT
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS progress (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED    NOT NULL,
    module_id   INT UNSIGNED    NOT NULL,
    total_score INT             NOT NULL DEFAULT 0,
    max_possible INT            NOT NULL DEFAULT 0,
    exercises_done TINYINT      NOT NULL DEFAULT 0,
    exercises_total TINYINT     NOT NULL DEFAULT 0,
    percent     TINYINT         NOT NULL DEFAULT 0,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_module (user_id, module_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. AI LOGI
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS ai_logs (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED    NOT NULL,
    exercise_id INT UNSIGNED    NULL,
    request_type ENUM('help','review','feedback') NOT NULL DEFAULT 'help',
    prompt_text TEXT            NOT NULL,
    response_text TEXT          NOT NULL,
    tokens_used INT             NULL,
    duration_ms INT             NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 8. PAROLNI TIKLASH TOKENLARI
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS password_resets (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(150)    NOT NULL,
    token      VARCHAR(100)    NOT NULL,
    expires_at DATETIME        NOT NULL,
    used       TINYINT(1)      NOT NULL DEFAULT 0,
    created_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_token (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. RATE LIMIT (brute-force himoyasi)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS rate_limits (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address  VARCHAR(45)     NOT NULL,
    action      VARCHAR(60)     NOT NULL DEFAULT 'login',
    attempts    TINYINT         NOT NULL DEFAULT 1,
    blocked_until DATETIME      NULL,
    last_attempt DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ip_action (ip_address, action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
