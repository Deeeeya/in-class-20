CREATE DATABASE `in_class_20`;

CREATE TABLE `posts`
(
    `post_id` int(10) AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `post_text` varchar(200),
    primary key (`post_id`)
)

insert into posts (username, post_text)
values ('Deeya', 'This is fun.');
insert into posts (username, post_text)
values ('Miguel', 'I know right?');