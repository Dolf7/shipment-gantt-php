-- Create the database
CREATE DATABASE ShipmentSchedule;

-- Use the database
USE ShipmentSchedule;

-- Create the schedule schema
CREATE SCHEMA schedule;

-- Create the user schema
CREATE SCHEMA
user;

-- Create tables in the schedule schema
CREATE TABLE schedule.template
(
    id INT IDENTITY(1,1) PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE schedule.template_item
(
    id INT IDENTITY(1,1) PRIMARY KEY,
    templateid INT,
    item VARCHAR(255) NOT NULL,
    FixDurationMinute BIGINT,
    FixStartTime TIME,
    FixEndTime TIME,
    FOREIGN KEY (templateid) REFERENCES schedule.schedule_template(id)
);

CREATE TABLE schedule.schedules
(
    id INT IDENTITY(1,1) PRIMARY KEY,
    scheduleDate DATE,
    templateid INT,
    FOREIGN KEY (templateid) REFERENCES schedule.schedule_template(id)
);

CREATE TABLE schedule.schedules_item
(
    id INT IDENTITY(1,1) PRIMARY KEY,
    schedulesid INT,
    templateitemid INT,
    durationMinute BIGINT,
    startTime TIME,
    endTime TIME,
    FOREIGN KEY (schedulesid) REFERENCES schedule.schedule_schedules(id),
    FOREIGN KEY (templateitemid) REFERENCES schedule.schedule_template_item(id)
);

-- Create the user table in the user schema
CREATE TABLE user.users
(
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    realname VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    isAdmin BIT NOT NULL,
    isActive BIT NOT NULL
);

-- Commit the transaction
COMMIT TRANSACTION;