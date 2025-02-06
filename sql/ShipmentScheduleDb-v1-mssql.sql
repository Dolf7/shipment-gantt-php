-- Start by creating the database
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'ShipmentOnDelivery')
BEGIN
    CREATE DATABASE ShipmentOnDelivery;
END
GO

USE ShipmentOnDelivery;
GO

-- Transaction block for creating tables
BEGIN TRANSACTION;

-- Create schedule_template table
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'schedule_template')
BEGIN
    CREATE TABLE schedule_template (
        id INT IDENTITY(1,1) PRIMARY KEY,
        name NVARCHAR(255) NOT NULL
    );
END
GO

-- Create schedule_template_item table
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'schedule_template_item')
BEGIN
    CREATE TABLE schedule_template_item (
        id INT IDENTITY(1,1) PRIMARY KEY,
        templateid INT,
        item NVARCHAR(255) NOT NULL,
        FixDurationMinute BIGINT,
        FixStartTime TIME,
        FixEndTime TIME,
        FOREIGN KEY (templateid) REFERENCES schedule_template(id)
    );
END
GO

-- Create schedule_schedules table
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'schedule_schedules')
BEGIN
    CREATE TABLE schedule_schedules (
        id INT IDENTITY(1,1) PRIMARY KEY,
        name NVARCHAR(255),
        scheduleDate DATE,
        templateid INT,
        FOREIGN KEY (templateid) REFERENCES schedule_template(id)
    );
END
GO

-- Create schedule_schedules_item table
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'schedule_schedules_item')
BEGIN
    CREATE TABLE schedule_schedules_item (
        id INT IDENTITY(1,1) PRIMARY KEY,
        schedulesid INT,
        templateitemid INT,
        durationMinute BIGINT,
        startTime TIME,
        endTime TIME,
        FOREIGN KEY (schedulesid) REFERENCES schedule_schedules(id),
        FOREIGN KEY (templateitemid) REFERENCES schedule_template_item(id)
    );
END
GO

-- Create user_users table
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'user_users')
BEGIN
    CREATE TABLE user_users (
        id BIGINT IDENTITY(1,1) PRIMARY KEY,
        email NVARCHAR(255) NOT NULL,
        username NVARCHAR(255) NOT NULL,
        realname NVARCHAR(255) NOT NULL,
        password NVARCHAR(255) NOT NULL,
        isAdmin BIT NOT NULL,
        isActive BIT NOT NULL
    );
END
GO

COMMIT TRANSACTION;
GO