-- Start by creating the database
CREATE DATABASE IF NOT EXISTS ShipmentSchedule;
USE ShipmentSchedule;

-- Transaction block for creating tables
START TRANSACTION;

-- Create schedule_template table
CREATE TABLE IF NOT EXISTS schedule_template (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create schedule_template_item table
CREATE TABLE IF NOT EXISTS schedule_template_item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    templateid INT,
    item VARCHAR(255) NOT NULL,
    FixDurationMinute BIGINT,
    FixStartTime TIME,
    FixEndTime TIME,
    FOREIGN KEY (templateid) REFERENCES schedule_template(id)
);

-- Create schedule_schedules table
CREATE TABLE IF NOT EXISTS schedule_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    scheduleDate DATE,
    templateid INT,
    FOREIGN KEY (templateid) REFERENCES schedule_template(id)
);

-- Create schedule_schedules_item table
CREATE TABLE IF NOT EXISTS schedule_schedules_item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedulesid INT,
    templateitemid INT,
    durationMinute BIGINT,
    startTime TIME,
    endTime TIME,
    FOREIGN KEY (schedulesid) REFERENCES schedule_schedules(id),
    FOREIGN KEY (templateitemid) REFERENCES schedule_template_item(id)
);

-- Create user_users table
CREATE TABLE IF NOT EXISTS user_users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    realname VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    isAdmin BOOLEAN NOT NULL,
    isActive BOOLEAN NOT NULL
);
