CREATE DATABASE IF NOT EXISTS flowfinder_local;

CREATE USER IF NOT EXISTS 'flowfinder_local_user'@'localhost' IDENTIFIED BY 'flowfinder_local_password';

GRANT ALL PRIVILEGES ON flowfinder_local.* TO 'flowfinder_local_user'@'localhost';

FLUSH PRIVILEGES;