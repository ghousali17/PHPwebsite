CREATE TABLE users( 
	u_id int(255) AUTO_INCREMENT PRIMARY KEY not null,  
	u_name varchar(256) not null, 
	u_passwd varchar(256) not null,
	u_token varchar(128) );

CREATE TABLE gallery(
    imgId int(255) AUTO_INCREMENT PRIMARY KEY not null,
    imgName LONGTEXT not null,
    imgMode varchar(256) not null,
    imgOwner varchar(256) not null,
    imgFullName LONGTEXT not null,
    imgOrder LONGTEXT not null
    
);

INSERT INTO users (u_name, u_passwd) values ('admin', 'minda123');
INSERT INTO users (u_name, u_passwd) values ('Alice', 'csci4140');
     
