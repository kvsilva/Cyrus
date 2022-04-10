-- DATABASE
drop database if exists cyrus;
create database cyrus;
use cyrus;
SET SQL_MODE='ALLOW_INVALID_DATES';

-- GLOBAL_SETTINGS
CREATE TABLE global_settings (name varchar(255) PRIMARY KEY, value text, value_binary blob, data_type varchar(50) NOT NULL DEFAULT 'string');

-- RESOURCE
CREATE TABLE resource (id int(24) primary key auto_increment, title varchar(200), description varchar(800), extension varchar(10) NOT NULL, path text NOT NULL, available int(1) default 1 check(available in (0, 1)));

-- LANGUAGE
CREATE TABLE language (id int(3) PRIMARY KEY AUTO_INCREMENT, code varchar(25) UNIQUE KEY NOT NULL, name varchar(50) UNIQUE KEY NOT NULL, original_name varchar(50) UNIQUE KEY);

-- USER
CREATE TABLE user (id int(16) primary key auto_increment, email varchar(255) UNIQUE KEY NOT NULL, username varchar(255) UNIQUE KEY NOT NULL, password varchar(40) NOT NULL, birthdate DATE, sex int(1) check(sex in (1, 2, 3)), creation_date TIMESTAMP default now(), status varchar(240) default null, profile_image int(24) REFERENCES resource(id) ON UPDATE CASCADE ON DELETE SET NULL, profile_background int(24) REFERENCES resource(id) ON UPDATE CASCADE ON DELETE SET NULL, about_me text default null, verified int(1) DEFAULT 0 check(verified IN (0, 1)), display_language int(3) REFERENCES language(id) ON UPDATE CASCADE ON DELETE SET NULL, email_communication_language int(3) REFERENCES language(id) ON UPDATE CASCADE ON DELETE SET NULL, translation_language int(3) REFERENCES language(id) ON UPDATE CASCADE ON DELETE SET NULL, night_mode int(1) DEFAULT 0 check(night_mode in (0, 1)), available int(1) DEFAULT 1 check(available in (0, 1)));

-- SOURCE_TYPE
Create table source_type (id int(2) primary key auto_increment, name varchar(40));

-- AUDIENCE
Create table audience (id int(2) PRIMARY KEY AUTO_INCREMENT, name varchar(40) not null, age int(3) not null);

-- ANIME
create table anime (id int(12) PRIMARY KEY AUTO_INCREMENT, title varchar(120), original_title varchar(120), synopsis text, start_date date default now(), end_date date default null, mature int(1) default 0 check(mature in (0, 1)), launch_day int(1) check(launch_day in (1, 2, 3, 4, 5, 6, 7)), source int(2) NOT NULL references source_type(id) ON UPDATE CASCADE ON DELETE SET NULL, audience int(2) NOT NULL references audience(id) ON UPDATE CASCADE ON DELETE SET NULL, trailer varchar(1000) default null, available int(1) default 1 check(available in (0, 1)));

-- SEASON
CREATE TABLE season (id bigint(20) primary key, anime int(16) not null references anime(id) ON UPDATE CASCADE ON DELETE NO ACTION, numeration int(3) not null, name varchar(200) not null, synopsis text, release_date date default now() not null, available int(1) default 1 check(available in (0,1)), unique key(anime, numeration));

-- VIDEO_TYPE
Create table video_type(id int(2) primary key auto_increment, name varchar(50) not null);

-- VIDEO
create table video(id int(16) primary key auto_increment, anime int(16) not null references anime(id) ON UPDATE CASCADE ON DELETE SET NULL, season int(3) references season(id) ON UPDATE CASCADE ON DELETE SET NULL, video_type int(2) not null references video_type(id) ON UPDATE CASCADE ON DELETE SET NULL, numeration int(6) not null, title varchar(100), synopsis text, duration int(7) not null, opening_start int(7), opening_end int(7), ending_start int(7), ending_end int(7), path text not null, available int(1) default 1 check(available in (0, 1)));

-- SUBTITLE
Create table subtitle(id int(18) primary key auto_increment, video int(16) not null references video(id) ON UPDATE CASCADE ON DELETE SET NULL, language int(3) not null references language(id) ON UPDATE CASCADE ON DELETE SET NULL, path varchar(500), available int(1) default 1 check(available in (0, 1)));

-- DUBBING
Create table dubbing (id int(18) primary key auto_increment, video int(16) not null references video(id) ON UPDATE CASCADE ON DELETE SET NULL, language int(3) not null references language(id) ON UPDATE CASCADE ON DELETE SET NULL, path varchar(500), available int(1) default 1 check(available in (0, 1)));

-- PUNISHMENT_TYPE
Create table punishment_type(id int(3) primary key auto_increment, name varchar(80) not null);

-- PUNISHMENT
Create table punishment(id int(24) primary key auto_increment, user int(16) not null references user(id) ON UPDATE CASCADE ON DELETE SET NULL, punishment_type int(3) not null references punishment_type(id) ON UPDATE CASCADE ON DELETE SET NULL, reason varchar(4000) not null, lasts_until timestamp not null, performed_by int(16) not null references user(id) ON UPDATE CASCADE ON DELETE SET NULL, performed_date timestamp default now(), revoked_by int(16) references user(id) ON UPDATE CASCADE ON DELETE SET NULL, revoked_date timestamp null, revoked_reason varchar(4000), available int(1) default 1 check(available in (0, 1)));

-- GENDER
Create table gender(id int(3) primary key auto_increment, name varchar(40) not null);

-- ANIME_GENDER
Create table anime_gender(anime int(16) references anime(id) ON UPDATE CASCADE ON DELETE CASCADE, gender int(3) references gender(id) ON UPDATE CASCADE ON DELETE CASCADE, primary key (anime, gender));

-- ANIME_STATUS
Create table anime_status(id int(2) primary key auto_increment, name varchar(50) not null);

-- USER_ANIME_STATUS
Create table user_anime_status(user int(16) references user(id) ON UPDATE CASCADE ON DELETE CASCADE, anime int(16) references anime(id) ON UPDATE CASCADE ON DELETE CASCADE, status int(2) references anime_status(id) ON UPDATE CASCADE ON DELETE CASCADE, date timestamp default now(), primary key(user, anime, status));

-- HISTORY
Create table history(user int(16) references user(id) ON UPDATE CASCADE ON DELETE CASCADE, video int(16) references video(id) ON UPDATE CASCADE ON DELETE CASCADE, date timestamp default now(), watched_until int(7) default 0, primary key(user, video));

-- TICKET_STATUS
Create table ticket_status(id int(2) primary key auto_increment, name varchar(80) not null);

-- TICKET
Create table ticket(id int(16) primary key auto_increment, user int(16) not null references user(id) ON UPDATE CASCADE ON DELETE SET NULL, title varchar(100) not null, attended_by int(16) not null references user(id) ON UPDATE CASCADE ON DELETE SET NULL, status int(2) not null references ticket_status(id) ON UPDATE CASCADE ON DELETE SET NULL, created_at timestamp default now(), closed_at timestamp null, closed_by int(16) references user(id) ON UPDATE CASCADE ON DELETE SET NULL, evaluation int(2) check(evaluation >= 0 AND evaluation <= 10));

-- TICKET_MESSAGE
Create table ticket_message(id int(16) primary key auto_increment, ticket int(16) not null references ticket(id) ON UPDATE CASCADE ON DELETE CASCADE, author int(16) not null references user(id), content text not null, sent_at timestamp default now());

-- TICKET_MESSAGE_ATTACHMENT
Create table ticket_message_attachment(message int(16) references ticket(id) ON UPDATE CASCADE ON DELETE CASCADE, resource int(24) references resource(id) ON UPDATE CASCADE ON DELETE CASCADE, primary key(message, resource));

-- ROLE
Create table role(id int(3) primary key auto_increment, name varchar(50) unique key not null);

-- PERMISSION
Create table permission(id int(4) primary key auto_increment, tag varchar(50) unique key not null, name varchar(60) unique key not null, description varchar(255) not null);

-- ROLE_PERMISSION
Create table role_permission(role int(3) references role(id) ON UPDATE CASCADE ON DELETE CASCADE, permission int(4) references permission(id) ON UPDATE CASCADE ON DELETE CASCADE, primary key(role, permission));

-- USER_ROLE
Create table user_role(user int(16) references user(id) ON UPDATE CASCADE ON DELETE CASCADE, role int(3) references role(id) ON UPDATE CASCADE ON DELETE CASCADE, primary key(user, role));

-- ACCOUNT_PLANS
Create table account_plan(id int(3) primary key auto_increment, name varchar(40) unique key not null, duration int(16) not null, price double(12, 2) not null, stack int(3) not null default 1, maximum int(3) default 0, available int(1) default 1 check(available in (0, 1)));

-- ACCOUNT_PURCHASE
Create table account_purchase(id int(16) primary key auto_increment, user int(16) not null references user(id) ON UPDATE CASCADE ON DELETE CASCADE, account_plan int(3) not null references account_plan(id) ON UPDATE CASCADE ON DELETE SET NULL, price double(12, 2) not null, purchased_on timestamp default now(), duration int(16) not null, revoked_by int(16) references user(id) ON UPDATE CASCADE ON DELETE SET NULL, revoked_reason varchar(4000), revoked_at timestamp null, rescued_at timestamp null, available int(1) DEFAULT 1 check(available in (0, 1)));

-- LOG_ACTION
Create table log_action(id int(6) primary key auto_increment, name varchar(50) UNIQUE KEY not null, description text not null);

-- LOG
Create table log(id int(30) primary key auto_increment, user int(16) not null references user(id) ON UPDATE CASCADE ON DELETE NO ACTION, action_type int(4) references log_action(id) ON UPDATE CASCADE ON DELETE SET NULL, arguments text);

-- INSERTS














