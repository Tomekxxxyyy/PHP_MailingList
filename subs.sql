CREATE DATABASE mail_list default character set utf8;

CREATE TABLE subskrybenci(
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) UNIQUE NOT NULL
);
