create table language
(
    id         int auto_increment,
    name       varchar(255) not null,
    alias      varchar(255) not null,
    code       varchar(255) not null,
    by_default bool default 0,
    file_id    int          not null,
    constraint languages_pk
        primary key (id),
    constraint languages_file_id_fk
        foreign key (file_id) references file (id)
);

INSERT INTO language (name, alias, code, file_id) VALUE ('Русский', 'russian', 'ru,ru_RU.UTF-8,ru_RU,russian', '67')