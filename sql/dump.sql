CREATE TABLE `insta_contacts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `phone` VARCHAR(254) CHARACTER SET utf8 COLLATE utf8_unicode_ci ,
    PRIMARY KEY (`id`)
);

INSERT INTO insta.insta_contacts (id, name, phone) VALUES (1, 'V.V. SHopenaguer', '+79205669515');
INSERT INTO insta.insta_contacts (id, name, phone) VALUES (2, 'A. S. Pushkin', '88002223535');
INSERT INTO insta.insta_contacts (id, name, phone) VALUES (3, 'A. Zelenskij', '+3804003642');
INSERT INTO insta.insta_contacts (id, name, phone) VALUES (4, 'D. Gordon', '-----');
INSERT INTO insta.insta_contacts (id, name, phone) VALUES (5, 'Pupkin Vasya', '123123123123');

CREATE TABLE `insta_user_contacts` (
                                       `id` INT NOT NULL AUTO_INCREMENT,
                                       `user_id` INT NOT NULL,
                                       `contact_id` INT NOT NULL,
                                       PRIMARY KEY (`id`)
);

INSERT INTO insta.insta_user_contacts (id, user_id, contact_id) VALUES (1, 1, 2);
INSERT INTO insta.insta_user_contacts (id, user_id, contact_id) VALUES (6, 1, 5);
INSERT INTO insta.insta_user_contacts (id, user_id, contact_id) VALUES (7, 10, 3);
INSERT INTO insta.insta_user_contacts (id, user_id, contact_id) VALUES (8, 3, 2);

CREATE TABLE `insta_users` (
                               `id` INT NOT NULL AUTO_INCREMENT,
                               `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                               `pwd` VARCHAR(254) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                               PRIMARY KEY (`id`)
);

INSERT INTO insta.insta_users (id, name, pwd) VALUES (1, 'admin', '1234');
INSERT INTO insta.insta_users (id, name, pwd) VALUES (2, 'user', '3210');
INSERT INTO insta.insta_users (id, name, pwd) VALUES (3, 'new_user', '1234');
INSERT INTO insta.insta_users (id, name, pwd) VALUES (10, 'test', 'test');