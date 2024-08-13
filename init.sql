#!/usr/bin/env bash

DB_NAME="insaat_app"

mysql -u mysql -p -e "
CREATE DATABASE IF NOT EXISTS $DB_NAME;

USE $DB_NAME;

CREATE TABLE IF NOT EXISTS customers (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255),
    gsm VARCHAR(255),
    email VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS worksites (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(1024),
    address VARCHAR(1024),
    initial_price DECIMAL(10, 2),
    cost DECIMAL(10, 2),
    in_progress BOOLEAN
);

CREATE TABLE IF NOT EXISTS payment_types (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS materials (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS main_storage (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    material_id INTEGER,
    quantity INTEGER,
    cost_by_piece DECIMAL(10, 2)
);

CREATE TABLE IF NOT EXISTS customer_worksites (
    customer_id INTEGER,
    worksite_id INTEGER
);

CREATE TABLE IF NOT EXISTS worksite_expenses (
    worksite_id INTEGER,
    material_id INTEGER,
    quantity INTEGER,
    cost_by_piece DECIMAL(10, 2)
);

CREATE TABLE IF NOT EXISTS payments (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    worksite_id INTEGER,
    payment_type INTEGER,
    amount DOUBLE(10, 2)
);

INSERT INTO customers (fullname, gsm, email) VALUES 
('John Doe', '123-456-7890', 'john.doe@example.com'),
('Jane Smith', '987-654-3210', 'jane.smith@example.com'),
('Bob Johnson', '555-123-4567', 'bob.johnson@example.com');

INSERT INTO worksites (description, address, initial_price, cost, in_progress) VALUES 
('Residential Construction', '123 Main St, Cityville', 100000.00, 20000.00, 1),
('Office Building Renovation', '456 Office Park, Townsville', 500000.00, 21250.00, 0),
('Bridge Repair', '789 Bridge Rd, Hamlet', 200000.00, 3000.00, 1);

INSERT INTO payment_types (name) VALUES 
('Cash'),
('Credit Card'),
('Bank Transfer'),
('Cheque');

INSERT INTO materials (name) VALUES 
('Concrete'),
('Steel'),
('Bricks'),
('Wood'),
('Glass'),
('Cement'),
('Gravel'),
('Sand'),
('Plaster'),
('Asphalt');

INSERT INTO main_storage (material_id, quantity, cost_by_piece) VALUES 
(1, 1000, 100.00),
(2, 500, 200.00),
(3, 2000, 50.00),
(4, 1500, 75.00),
(5, 100, 300.00),
(6, 800, 25.00),
(7, 1200, 10.00),
(8, 10000, 5.00),
(9, 700, 20.00),
(10, 400, 150.00);

INSERT INTO customer_worksites (customer_id, worksite_id) VALUES 
(1, 1),
(2, 2),
(3, 3);

INSERT INTO worksite_expenses (worksite_id, material_id, quantity, cost_by_piece) VALUES 
(1, 1, 100, 100.00),
(1, 2, 50, 200.00),
(2, 3, 200, 50.00),
(2, 4, 150, 75.00),
(3, 5, 10, 300.00);

INSERT INTO payments (worksite_id, payment_type, amount) VALUES 
(1, 1, 50000.00),
(1, 2, 20000.00),
(2, 3, 100000.00),
(3, 4, 80000.00);
"
