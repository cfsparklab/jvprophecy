-- AWS RDS MySQL Database Initialization Script
-- JV Prophecy Manager
-- Run this after creating RDS instance

-- Verify MySQL version
SELECT VERSION();

-- Show current databases
SHOW DATABASES;

-- Use the application database
USE jvprophecy_db;

-- Create application user (optional, for better security)
CREATE USER IF NOT EXISTS 'jvprophecy'@'%' IDENTIFIED BY 'AnotherStrongPassword123!';
GRANT ALL PRIVILEGES ON jvprophecy_db.* TO 'jvprophecy'@'%';
FLUSH PRIVILEGES;

-- Set timezone
SET GLOBAL time_zone = '+00:00';
SET time_zone = '+00:00';

-- Configure MySQL for better performance
SET GLOBAL max_connections = 150;
SET GLOBAL wait_timeout = 28800;
SET GLOBAL interactive_timeout = 28800;
SET GLOBAL max_allowed_packet = 67108864; -- 64MB

-- Show character set
SHOW VARIABLES LIKE 'character_set%';
SHOW VARIABLES LIKE 'collation%';

-- Verify configuration
SHOW VARIABLES LIKE 'max_connections';
SHOW VARIABLES LIKE 'max_allowed_packet';

-- Show grants for application user
SHOW GRANTS FOR 'jvprophecy'@'%';

-- Test connection
SELECT 'AWS RDS MySQL configured successfully!' AS status;

