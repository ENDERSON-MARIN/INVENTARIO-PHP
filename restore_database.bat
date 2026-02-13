@echo off
REM ============================================================================
REM Database Restore Script for Windows
REM ============================================================================
REM
REM Purpose: Automates the complete database restoration process
REM
REM Prerequisites:
REM   - MySQL client installed and in PATH
REM   - MySQL server running
REM   - Valid credentials
REM
REM Usage: restore_database.bat
REM
REM ============================================================================

echo ============================================================================
echo Database Restore Script - Inventas
echo ============================================================================
echo.

REM Configuration
set DB_NAME=inventas
set DB_USER=root
set DB_HOST=localhost
set DB_PORT=3306

REM Prompt for password
set /p DB_PASS="Enter MySQL root password: "

echo.
echo Starting database restoration...
echo.

REM Step 1: Create database with correct encoding
echo [1/3] Creating database with UTF-8 encoding...
mysql -h %DB_HOST% -P %DB_PORT% -u %DB_USER% -p%DB_PASS% < database\dumps\00_create_database.sql
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to create database
    pause
    exit /b 1
)
echo Database created successfully!
echo.

REM Step 2: Restore main dump
echo [2/3] Restoring main database dump...
mysql -h %DB_HOST% -P %DB_PORT% -u %DB_USER% -p%DB_PASS% %DB_NAME% < database\dumps\01_Dump20220121.sql
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to restore main dump
    pause
    exit /b 1
)
echo Main dump restored successfully!
echo.

REM Step 3: Create triggers
echo [3/3] Creating database triggers...
mysql -h %DB_HOST% -P %DB_PORT% -u %DB_USER% -p%DB_PASS% %DB_NAME% < database\dumps\02_triggers_PRODUCTOS.sql
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to create triggers
    pause
    exit /b 1
)
echo Triggers created successfully!
echo.

echo ============================================================================
echo Database restoration completed successfully!
echo ============================================================================
echo.
echo Database: %DB_NAME%
echo Host: %DB_HOST%:%DB_PORT%
echo.
echo Next steps:
echo 1. Update your .env file with the correct database name
echo 2. Run: php artisan config:clear
echo 3. Run: php artisan migrate:status
echo.

pause
