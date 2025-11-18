CREATE DATABASE events_db;
USE events_db;

-- Events Table
CREATE TABLE events (
    evCode INT AUTO_INCREMENT PRIMARY KEY,
    evName VARCHAR(255) NOT NULL,
    evDate DATE NOT NULL,
    evFee DECIMAL(10,2) NOT NULL
);

-- Participants Table
CREATE TABLE participants (
    partID INT AUTO_INCREMENT PRIMARY KEY,
    partFName VARCHAR(100) NOT NULL,
    partLName VARCHAR(100) NOT NULL,
    partDRate DECIMAL(5,2) DEFAULT 0
);

-- Registration Table
CREATE TABLE registration (
    regCode INT AUTO_INCREMENT PRIMARY KEY,
    partID INT,
    evCode INT,
    regDate DATE NOT NULL,
    regFeePaid DECIMAL(10,2) NOT NULL,
    regPMode ENUM('Cash', 'Card'),
    FOREIGN KEY (partID) REFERENCES participants(partID),
    FOREIGN KEY (evCode) REFERENCES events(evCode)
);
