create table file
(
    `id`    INT AUTO_INCREMENT,
    `name`  VARCHAR(255) NOT NULL,
    `alias` VARCHAR(255) NOT NULL,
    `type`  VARCHAR(255) NOT NULL,
    CONSTRAINT file_pk
        PRIMARY KEY (id)
);

INSERT INTO file (id, name, alias, type)
VALUES (1, '1599155021Васильковый металлик', '16061464611599155021Васильковый металлик.png', 'image/png');

create table role
(
    `id`         INT AUTO_INCREMENT,
    `name`       VARCHAR(255) NOT NULL,
    `permission` INT          NOT NULL,
    CONSTRAINT role_pk
        PRIMARY KEY (id)
);

INSERT INTO role (name, permission)
VALUES ('Администратор', 100);

create table user
(
    `id`         INT AUTO_INCREMENT,
    `name`       VARCHAR(255) NOT NULL,
    `email`      VARCHAR(255) NOT NULL,
    `password`   VARCHAR(32)  NOT NULL,
    `salt`       VARCHAR(32)  NOT NULL,
    `active_hex` VARCHAR(32)  NOT NULL,
    `role_id`    INT          NOT NULL,
    CONSTRAINT user_pk
        PRIMARY KEY (id),
    CONSTRAINT user_role_id_fk
        FOREIGN KEY (role_id) REFERENCES role (id)
);

INSERT INTO user (name, email, password, salt, active_hex, role_id)
VALUES ('Маргарита Моногарова', 'margomonogarova@gmail.com', '4f03866f3db72e717c88541d53da2af1', '94231654',
        'de88a3763b1636bec5474c277ee48c7d', 1);

create table language
(
    `id`      INT AUTO_INCREMENT,
    `name`    VARCHAR(255) NOT NULL,
    `alias`   VARCHAR(255) NOT NULL,
    `code`    VARCHAR(255) NOT NULL,
    `file_id` INT          NOT NULL,
    CONSTRAINT languages_pk
        PRIMARY KEY (id),
    CONSTRAINT languages_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id)
);

INSERT INTO language (name, alias, code, file_id) VALUE ('Русский', 'russian', 'ru,ru_RU.UTF-8,ru_RU,russian', '1');

create table blog
(
    `id`                INT AUTO_INCREMENT,
    `name`              VARCHAR(255) NOT NULL,
    `short_description` VARCHAR(255) NOT NULL,
    `description`       TEXT         NOT NULL,
    `file_id`           INT          NOT NULL,
    `alias`             VARCHAR(255) NOT NULL,
    `tag`               VARCHAR(255)          DEFAULT NULL,
    `meta_title`        VARCHAR(255)          DEFAULT NULL,
    `meta_description`  VARCHAR(255)          DEFAULT NULL,
    `meta_keyword`      VARCHAR(255)          DEFAULT NULL,
    `created_at`        DATETIME     NOT NULL DEFAULT now(),
    CONSTRAINT blog_pk
        PRIMARY KEY (id),
    CONSTRAINT blog_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id) ON UPDATE CASCADE
);

create table category
(
    `id`               INT(11) AUTO_INCREMENT,
    `parent_id`        INT(11)               DEFAULT NULL,
    `name`             VARCHAR(255) NOT NULL,
    `description`      TEXT,
    `file_id`          INT          NOT NULL,
    `status`           BOOL         NOT NULL DEFAULT 1,
    `alias`            VARCHAR(255) NOT NULL,
    `tag`              text                  DEFAULT NULL,
    `meta_title`       VARCHAR(255)          DEFAULT NULL,
    `meta_description` VARCHAR(255)          DEFAULT NULL,
    `meta_keyword`     VARCHAR(255)          DEFAULT NULL,
    CONSTRAINT category_pk
        PRIMARY KEY (id),
    CONSTRAINT category_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id) ON UPDATE CASCADE
);

create table product
(
    `id`          INT(11) AUTO_INCREMENT,
    `category_id` INT(11)        NOT NULL,
    `price`       DECIMAL(15, 2) NOT NULL,
    `sale`        INT(3)         NOT NULL,
    `file_id`     INT(11)        NOT NULL,
    `status`      BOOL           NOT NULL DEFAULT 1,
    `alias`       VARCHAR(255)   NOT NULL,
    `sort`        INT(11),
    `created_at`  DATETIME       NULL,
    CONSTRAINT product_pk
        PRIMARY KEY (id),
    CONSTRAINT product_category_id_fk
        FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE CASCADE,
    CONSTRAINT product_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id) ON UPDATE CASCADE
);

CREATE TABLE `product_description`
(
    `id`               INT(11) AUTO_INCREMENT,
    `product_id`       INT(11)      NOT NULL,
    `language_id`      INT(11)      NOT NULL,
    `name`             VARCHAR(255) NOT NULL,
    `description`      text         DEFAULT NULL,
    `tag`              text         DEFAULT NULL,
    `meta_title`       VARCHAR(255) DEFAULT NULL,
    `meta_description` VARCHAR(255) DEFAULT NULL,
    `meta_keyword`     VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `name` (`name`),
    CONSTRAINT product_description_product_id_fk
        FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT product_description_language_id_fk
        FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table product_recommendations
(
    `id`                INT(11) AUTO_INCREMENT,
    `product_id`        INT(11) NOT NULL,
    `recommendation_id` INT(11) NOT NULL,
    CONSTRAINT product_recommendations_pk
        PRIMARY KEY (id),
    CONSTRAINT product_id_product_id_fk
        FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT product_id_recommendation_id_fk
        FOREIGN KEY (recommendation_id) REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table currency
(
    `id`    INT AUTO_INCREMENT,
    `name`  VARCHAR(255) NOT NULL,
    `code`  VARCHAR(255) NOT NULL,
    `alias` VARCHAR(255) NOT NULL,
    CONSTRAINT currency_pk
        PRIMARY KEY (id)
);


CREATE TABLE countr
(
    `id`          INT(11) AUTO_INCREMENT,
    `name`        VARCHAR(128) NOT NULL,
    `alpha2`      VARCHAR(2)   NOT NULL,
    `alpha3`      VARCHAR(3)   NOT NULL,
    `status`      BOOLEAN      NOT NULL DEFAULT 1,
    `file_id`     INT          NOT NULL,
    `currency_id` INT(11)      NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT countr_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id),
    CONSTRAINT countr_currency_id_fk
        FOREIGN KEY (currency_id) REFERENCES currency (id)
);


create table product_country
(
    `id`         INT(11) AUTO_INCREMENT,
    `country_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    CONSTRAINT product_country_pk
        PRIMARY KEY (id),
    CONSTRAINT product_country_product_id_fk
        FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT product_country_country_id_fk
        FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE ON UPDATE CASCADE
);

create table comment
(
    `id`          INT(11) AUTO_INCREMENT,
    `user_name`   VARCHAR(255) NOT NULL,
    `user_email`  VARCHAR(255) NOT NULL,
    `description` TEXT         NOT NULL,
    `status`      BOOL         NOT NULL DEFAULT 0,
    `created_at`  DATETIME              DEFAULT now() NULL,
    CONSTRAINT new_pk
        PRIMARY KEY (id)
);

create table comment_answer
(
    `id`          INT(11) AUTO_INCREMENT,
    `comment_id`  INT(11)                NOT NULL,
    `description` TEXT                   NOT NULL,
    `created_at`  DATETIME DEFAULT now() NULL,
    CONSTRAINT comment_pk
        PRIMARY KEY (id),
    CONSTRAINT comment_answer_comment_id_fk
        FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE ON UPDATE CASCADE
);

create table comment_file
(
    `id`         INT(11) AUTO_INCREMENT,
    `file_id`    INT(11) NOT NULL,
    `comment_id` INT(11) NOT NULL,
    CONSTRAINT new_pk
        PRIMARY KEY (id),
    CONSTRAINT comment_file_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id),
    CONSTRAINT comment_file_comment_id_fk
        FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE ON UPDATE CASCADE
);

create table comment_answer_file
(
    `id`                INT(11) AUTO_INCREMENT,
    `file_id`           INT(11) NOT NULL,
    `comment_answer_id` INT(11) NOT NULL,
    CONSTRAINT new_pk
        PRIMARY KEY (id),
    CONSTRAINT comment_answer_file_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id),
    CONSTRAINT comment_answer_file_comment_id_fk
        FOREIGN KEY (comment_answer_id) REFERENCES comment_answer (id) ON DELETE CASCADE ON UPDATE CASCADE
);

create table new
(
    `id`          INT AUTO_INCREMENT,
    `name`        VARCHAR(255)           NOT NULL,
    `description` VARCHAR(255)           NOT NULL,
    `file_id`     INT                    NOT NULL,
    `alias`       VARCHAR(255)           NOT NULL,
    `created_at`  DATETIME DEFAULT now() NULL,
    CONSTRAINT new_pk
        PRIMARY KEY (id),
    CONSTRAINT new_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id)
);

create table product_file
(
    `id`         INT(11) AUTO_INCREMENT,
    `file_id`    INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    CONSTRAINT product_file_pk
        PRIMARY KEY (id),
    CONSTRAINT product_file_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id),
    CONSTRAINT product_file_product_id_fk
        FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `orders`
(
    `id`                 INT(11) AUTO_INCREMENT,
    `first_name`         VARCHAR(32)    NOT NULL,
    `last_name`          VARCHAR(32)    NOT NULL,
    `email`              VARCHAR(96)    NOT NULL,
    `telephone`          VARCHAR(32)    NOT NULL,
    `country`            VARCHAR(128)   NOT NULL,
    `city`               VARCHAR(128)   NOT NULL,
    `postcode`           VARCHAR(128)   NOT NULL,
    `address`            VARCHAR(128)   NOT NULL,
    `payment_method_id`  VARCHAR(128)   NOT NULL,
    `shipping_method_id` VARCHAR(128)   NOT NULL,
    `comment`            TEXT           NOT NULL,
    `total_price`        DECIMAL(15, 2) NOT NULL DEFAULT '0.00',
    `status_id`          INT(11)        NOT NULL DEFAULT '0',
    `created_at`         DATE,
    PRIMARY KEY (`id`)
);

CREATE TABLE `order_product`
(
    `id`         INT(11) AUTO_INCREMENT,
    `order_id`   INT(11)        NOT NULL,
    `product_id` INT(11)        NOT NULL,
    `name`       VARCHAR(255)   NOT NULL,
    `quantity`   INT(4)         NOT NULL,
    `price`      DECIMAL(15, 2) NOT NULL DEFAULT '0.00',
    `sale`       DECIMAL(15, 2) NOT NULL DEFAULT '0.00',
    `total`      DECIMAL(15, 2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`id`),
    CONSTRAINT order_product_order_id_fk FOREIGN KEY (order_id) REFERENCES orders (id)
);

create table rate
(
    `id`          INT(11) AUTO_INCREMENT,
    `currency_id` INT(3)                 NOT NULL,
    `rate`        DECIMAL(15, 2)         NOT NULL,
    `date`        DATETIME DEFAULT now() NOT NULL,
    CONSTRAINT rate
        PRIMARY KEY (id),
    CONSTRAINT rate_currency_id_fk
        FOREIGN KEY (currency_id) REFERENCES currency (id) ON UPDATE CASCADE
);

create table coupon
(
    `id`         INT(11) AUTO_INCREMENT,
    `code`       varchar(20),
    `discount`   INT(3)   NOT NULL,
    `quantity`   INT(11)  NOT NULL,
    `used`       INT(11)  NOT NULL,
    `start_date` DATETIME NOT NULL,
    `end_date`   DATETIME NOT NULL,
    CONSTRAINT coupons
        PRIMARY KEY (id)
);

create table order_payment_method
(
    `id`   INT(11) AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    CONSTRAINT payment_method
        PRIMARY KEY (id)
);

create table order_shipping_method
(
    `id`   INT(11) AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    CONSTRAINT shipping_method
        PRIMARY KEY (id)
);

create table order_status
(
    `id`   INT(11) AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    CONSTRAINT order_status
        PRIMARY KEY (id)
);

create table blog
(
    `id`         INT AUTO_INCREMENT,
    `alias`      VARCHAR(255) NOT NULL,
    `file_id`    INT          NOT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT now(),
    CONSTRAINT blog_pk
        PRIMARY KEY (id),
    CONSTRAINT blog_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id) ON UPDATE CASCADE
);

create table blog_description
(
    `id`                INT AUTO_INCREMENT,
    `blog_id`           INT(11)      NOT NULL,
    `language_id`       INT(11)      NOT NULL,
    `name`              VARCHAR(255) NOT NULL,
    `short_description` VARCHAR(255) NOT NULL,
    `description`       TEXT,
    `tag`               VARCHAR(255)          DEFAULT NULL,
    `meta_title`        VARCHAR(255)          DEFAULT NULL,
    `meta_description`  VARCHAR(255)          DEFAULT NULL,
    `meta_keyword`      VARCHAR(255)          DEFAULT NULL,
    `created_at`        DATETIME     NOT NULL DEFAULT now(),
    PRIMARY KEY (`id`),
    KEY `name` (`name`),
    CONSTRAINT blog_description_blog_id_fk
        FOREIGN KEY (blog_id) REFERENCES blog (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT blog_description_language_id_fk
        FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS `sale`
(
    `id`     int(11)    NOT NULL AUTO_INCREMENT,
    `status` tinyint(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
);

create table sale_description
(
    `id`          INT AUTO_INCREMENT,
    `sale_id`     INT(11)      NOT NULL,
    `language_id` INT(11)      NOT NULL,
    `name`        VARCHAR(255) NOT NULL,
    `sale`        VARCHAR(255) NOT NULL,
    `description` TEXT,
    PRIMARY KEY (`id`),
    KEY `name` (`name`),
    CONSTRAINT sale_description_sale_id_fk
        FOREIGN KEY (sale_id) REFERENCES sale (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT sale_description_language_id_fk
        FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table new
(
    `id`         INT AUTO_INCREMENT,
    `file_id`    INT      NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT now(),
    CONSTRAINT new_pk
        PRIMARY KEY (id),
    CONSTRAINT new_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id) ON UPDATE CASCADE
);

create table new_description
(
    `id`          INT AUTO_INCREMENT,
    `new_id`      INT(11)      NOT NULL,
    `language_id` INT(11)      NOT NULL,
    `name`        VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `name` (`name`),
    CONSTRAINT new_description_new_id_fk
        FOREIGN KEY (new_id) REFERENCES new (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT new_description_language_id_fk
        FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table comment
(
    `id`         INT(11) AUTO_INCREMENT,
    `user_name`  VARCHAR(255) NOT NULL,
    `user_email` VARCHAR(255) NOT NULL,
    `status`     BOOL         NOT NULL DEFAULT 0,
    `created_at` DATETIME              DEFAULT now() NULL,
    CONSTRAINT comment_pk
        PRIMARY KEY (id)
);

create table comment_description
(
    `id`          INT AUTO_INCREMENT,
    `comment_id`  INT(11) NOT NULL,
    `language_id` INT(11) NOT NULL,
    `description` TEXT    NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT comment_description_comment_id_fk
        FOREIGN KEY (comment_id) REFERENCES comment (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT comment_description_language_id_fk
        FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table comment_answer
(
    `id`          INT(11) AUTO_INCREMENT,
    `comment_id`  INT(11)                NOT NULL,
    `created_at`  DATETIME DEFAULT now() NULL,
    CONSTRAINT comment_pk
        PRIMARY KEY (id),
    CONSTRAINT comment_answer_comment_id_fk
        FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE ON UPDATE CASCADE
);

create table comment_answer_description
(
    `id`                INT(11) AUTO_INCREMENT,
    `comment_answer_id` INT(11) NOT NULL,
    `language_id`       INT(11) NOT NULL,
    `description`       TEXT    NOT NULL,
    CONSTRAINT comment_answer_description_pk
        PRIMARY KEY (id),
    CONSTRAINT comment_answer_description_id_fk
        FOREIGN KEY (comment_answer_id) REFERENCES comment_answer (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT comment_answer_description_language_id_fk
        FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table comment_file
(
    `id`         INT(11) AUTO_INCREMENT,
    `file_id`    INT(11) NOT NULL,
    `comment_id` INT(11) NOT NULL,
    CONSTRAINT comment_file_pk
        PRIMARY KEY (id),
    CONSTRAINT comment_file_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id),
    CONSTRAINT comment_file_comment_id_fk
        FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE ON UPDATE CASCADE
);

create table comment_answer_file
(
    `id`                INT(11) AUTO_INCREMENT,
    `file_id`           INT(11) NOT NULL,
    `comment_answer_id` INT(11) NOT NULL,
    CONSTRAINT comment_answer_file_pk
        PRIMARY KEY (id),
    CONSTRAINT comment_answer_file_file_id_fk
        FOREIGN KEY (file_id) REFERENCES file (id),
    CONSTRAINT comment_answer_file_comment_id_fk
        FOREIGN KEY (comment_answer_id) REFERENCES comment_answer (id) ON DELETE CASCADE ON UPDATE CASCADE
);