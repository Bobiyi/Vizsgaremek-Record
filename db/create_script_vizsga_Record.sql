create database vizsga_record
character set utf8mb4
collate utf8mb4_hungarian_ci;

use vizsga_record;

set NAMES utf8mb4 collate utf8mb4_hungarian_ci;

create table artist(
	id int primary key auto_increment,
	name varchar(64) not null,
	active_since year,
	nationality varchar(64),
	`url` varchar(128),
	is_group bit(1) not null,
	icon_path varchar(255),
	cover_path varchar(255)
);

create table `record`(
	id int primary key auto_increment,
	`name` varchar(64) not null,
	type_id int,
	release_year year,
	`length` int,
	file_path varchar(255)
);

create table record_type (
	id int primary key auto_increment,
	type_name varchar(32) not null
);

create table artist_record(
	artist_id int,
	record_id int,
	`role` enum("featured","producer"),
	primary key(artist_id,record_id)
);

alter table artist_record add constraint frk_Artist_ID foreign key (artist_id) references artist(id) on delete cascade;
alter table artist_record add constraint frk_Record_ID foreign key (record_id) references record(id) on delete cascade;

alter table record add constraint frk_Type foreign key (type_id) references record_type(id);
alter table record modify release_year year default year(CURRENT_DATE());
alter table record modify `length` int default 1;
alter table record add constraint chk_Length check (`length`>0);

alter table record_type add constraint uc_Type unique (type_name);

alter table artist add constraint uc_Name unique (name);
alter table artist modify active_since year default year(CURRENT_DATE());
alter table artist modify is_group bit(1) default 0;

create index idx_Record_Artist on artist_record(artist_id,record_id);

create table `user`(
	id int primary key auto_increment,
	password_hash varchar(128) not null,
	name varchar(64) not null,
	email varchar(64),
	phone varchar(15),
	`role` enum("admin","user"),
	created_at timestamp,
	updated_at timestamp
);

create table favourite(
	user_id int,
	record_id int,
	primary key(user_id,record_id)
);

alter table favourite add constraint frk_User_ID foreign key (user_id) references user(id) on delete cascade;
alter table favourite add constraint frk_Favourite_Record_ID foreign key (record_id) references record(id) on delete cascade;

alter table `user` add constraint uc_Email unique (email);
alter table `user` add constraint uc_Phone unique (phone);
alter table `user` modify `role` enum("admin","user") default("user");
alter table `user` modify created_at date default(CURRENT_DATE());
alter table `user` modify updated_at date default(CURRENT_DATE());

create index idx_Favourite on favourite(user_id,record_id);

create index idx_User_Name on user(name);

CREATE TABLE request (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    `type` ENUM('new_artist', 'new_record', 'edit_artist', 'edit_record') NOT NULL,
    payload JSON NOT NULL,
    `status` ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    admin_note VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    reviewed_at TIMESTAMP,
    CONSTRAINT frk_Request_User FOREIGN KEY (user_id) REFERENCES user(id)
);

create index idx_Request_User on request(user_id);

create index idx_Request_Status on request(`status`);

create or replace user web_api@localhost identified by "web";

grant SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP, INDEX, REFERENCES on vizsga_record.* to web_api@localhost;

DELIMITER //

Create or replace trigger trg_insert_record_year_check BEFORE INSERT on record
for each row
BEGIN
	IF NEW.release_year > year(CURRENT_DATE()) THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Release year cannot be in the future';
	end if;
end;
//
Create or replace trigger trg_insert_artist_year_check BEFORE INSERT on artist
for each row
BEGIN
	IF NEW.active_since > year(CURRENT_DATE()) THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Active since cannot be in the future';
	end if;
end;

DELIMITER ;