-- ========================================
-- UPDATE SUPERADMIN PASSWORD
-- ========================================
-- 
-- INSTRUCTIONS:
-- 1. Run this SQL query in phpMyAdmin or your MySQL client
-- 2. Replace 'YourNewPassword@123' with your desired password
-- 3. The password will be hashed using bcrypt
--
-- NOTE: The bcrypt hash below is for the password: SuperAdmin@2025
-- You can use this or generate a new one using Laravel Tinker:
--    php artisan tinker
--    echo bcrypt('YourNewPassword');
-- ========================================

-- Update superadmin password
-- Current hash is for password: SuperAdmin@2025
UPDATE users 
SET password = '$2y$12$3ksTPSJxvxyAsc2sUNtobuMTtrmWsuCIAH5bgEee01OfnionsmO3i'
WHERE email = 'superadmin@jvprophecy.com';

-- Verify the update
SELECT id, name, email, created_at, updated_at 
FROM users 
WHERE email = 'superadmin@jvprophecy.com';

-- ========================================
-- ALTERNATIVE: Update to a custom password
-- ========================================
-- 
-- To set a custom password, generate a bcrypt hash:
-- 1. Run this in your terminal:
--    php artisan tinker
-- 2. Run this command:
--    echo bcrypt('YourDesiredPassword');
-- 3. Copy the hash output
-- 4. Replace the password value in the query below:
--
-- UPDATE users 
-- SET password = 'YOUR_BCRYPT_HASH_HERE'
-- WHERE email = 'superadmin@jvprophecy.com';
--
-- ========================================

-- Additional useful queries:

-- Check all admin users
SELECT u.id, u.name, u.email, u.status, r.name as role
FROM users u
LEFT JOIN user_roles ur ON u.id = ur.user_id
LEFT JOIN roles r ON ur.role_id = r.id
WHERE r.name IN ('super_admin', 'admin', 'editor')
ORDER BY r.name, u.name;

-- Activate superadmin if inactive
UPDATE users 
SET status = 'active', 
    email_verified_at = NOW(),
    is_active = 1
WHERE email = 'superadmin@jvprophecy.com';

