CREATE DATABASE IF NOT EXISTS solarconnect
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE solarconnect;

CREATE TABLE IF NOT EXISTS enquiries (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(120) NOT NULL,
  phone_number VARCHAR(10) NOT NULL,
  monthly_bill DECIMAL(10,2) NOT NULL,
  package_selected ENUM('3kW', '5kW', '10kW') NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_full_name (full_name),
  KEY idx_phone_number (phone_number),
  KEY idx_package_selected (package_selected),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

