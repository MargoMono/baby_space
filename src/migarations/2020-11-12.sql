create table language
(
    id         int auto_increment,
    name       varchar(255) not null,
    alias      varchar(255) not null,
    code       varchar(255) not null,
    file_id    int          not null,
    constraint languages_pk
        primary key (id),
    constraint languages_file_id_fk
        foreign key (file_id) references file (id)
);

INSERT INTO language (name, alias, code, file_id) VALUE ('Русский', 'russian', 'ru,ru_RU.UTF-8,ru_RU,russian', '67');


CREATE TABLE `product_description`
(
    `id`               int(11)      AUTO_INCREMENT,
    `product_id`       int(11)      NOT NULL,
    `language_id`      int(11)      NOT NULL,
    `name`             varchar(255) NOT NULL,
    `description`      text         DEFAULT NULL,
    `tag`              text         DEFAULT NULL,
    `meta_title`       varchar(255) DEFAULT NULL,
    `meta_description` varchar(255) DEFAULT NULL,
    `meta_keyword`     varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `name` (`name`),
    CONSTRAINT product_description_product_id_fk
        FOREIGN KEY (product_id) REFERENCES product (id)
            ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT product_description_language_id_fk
        FOREIGN KEY (language_id) REFERENCES language (id)
            ON UPDATE CASCADE ON DELETE CASCADE
);

create table currency
(
    id         int auto_increment,
    name       varchar(255) not null,
    code       varchar(255) not null,
    alias      varchar(255) not null,
    constraint currency_pk
        primary key (id)
);

create table product_currency
(
    id          int(11) auto_increment,
    currency_id int(11) not null,
    product_id  int(11) not null,
    constraint product_currency_pk
        primary key (id),
    constraint product_currency_product_id_fk
        foreign key (product_id) references product (id) ON DELETE CASCADE ON UPDATE CASCADE,
    constraint product_currency_currency_id_fk
        foreign key (currency_id) references currency (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE country
(
    id         int(11) AUTO_INCREMENT,
    name       varchar(128) NOT NULL,
    iso_code_2 varchar(2)   NOT NULL,
    iso_code_3 varchar(3)   NOT NULL,
    status     tinyint(1)   NOT NULL DEFAULT '1',
    file_id    int          NOT NULL,
    primary key (id),
    constraint country_file_id_fk
        foreign key (file_id) references file (id)
);