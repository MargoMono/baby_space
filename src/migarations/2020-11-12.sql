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

INSERT INTO language (name, alias, code, file_id) VALUE ('Русский', 'russian', 'ru,ru_RU.UTF-8,ru_RU,russian', '67')


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
