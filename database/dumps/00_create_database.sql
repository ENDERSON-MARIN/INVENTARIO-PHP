-- ============================================================================
-- Database Creation Script with Correct UTF-8 Encoding
-- ============================================================================
--
-- Purpose: Creates the 'inventas' database with proper UTF-8 encoding
--          to ensure correct character handling for Spanish text
--
-- Execution Order: 00_ prefix ensures this runs before other dump files
--
-- Usage:
--   mysql -u root -p < database/dumps/00_create_database.sql
--
-- ============================================================================

-- Drop database if exists (use with caution in production!)
DROP DATABASE IF EXISTS `inventas`;

-- Create database with UTF-8 encoding
CREATE DATABASE `inventas`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

-- Verify database creation
USE `inventas`;

SELECT
  SCHEMA_NAME as 'Database',
  DEFAULT_CHARACTER_SET_NAME as 'Charset',
  DEFAULT_COLLATION_NAME as 'Collation'
FROM information_schema.SCHEMATA
WHERE SCHEMA_NAME = 'inventas';

-- ============================================================================
-- Next Steps:
-- 1. Run: mysql -u root -p < database/dumps/01_Dump20220121.sql
-- 2. Run: mysql -u root -p < database/dumps/02_triggers_PRODUCTOS.sql
-- ============================================================================
