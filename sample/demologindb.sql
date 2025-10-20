CREATE DATABASE IT490;
Create table users(id int not null auto_increment primary key, name varchar(255), username varchar(255), password varchar(255) not null);
INSERT INTO users (name, username, password) VALUES (steve, steve12, IloveSQL);

