create table file
(
    id         int auto_increment,
    name       varchar(255),
    alias      varchar(255)           null,
    author     varchar(255)           null,
    created_at datetime               null,
    updated_at datetime default now() null,
    constraint file_pk
        primary key (id)
);


create table portfolio
(
    id          int auto_increment,
    name        varchar(255)           null,
    description varchar(255)           null,
    file_id     int                    not null,
    updated_ut  datetime default now() null,
    created_at  datetime               null,
    constraint portfolio_pk
        primary key (id),
    constraint portfolio_file_id_fk
        foreign key (file_id) references file (id)
);

create table role
(
    id         int auto_increment,
    name       varchar(50) not null,
    permission int         not null,
    constraint role_pk
        primary key (id)
);

create table user
(
    id         int auto_increment,
    name       varchar(255) not null,
    email      varchar(255) not null,
    password   varchar(32)  NOT NULL,
    salt       varchar(32)  not null,
    active_hex varchar(32)  not null,
    role_id    int          not null,
    constraint user_pk
        primary key (id),
    constraint user_role_id_fk
        foreign key (role_id) references role (id)
);

create table blog
(
    id              int auto_increment,
    name            varchar(255)           not null,
    description     varchar(255)           not null,
    content         longtext               not null,
    file_id         int                    not null,
    alias           varchar(255)           not null,
    tag_title       mediumtext,
    tag_description mediumtext,
    tag_keywords    mediumtext,
    updated_ut      datetime default now() null,
    created_at      datetime               null,
    constraint new_pk
        primary key (id),
    constraint new_id_fk
        foreign key (file_id) references file (id)
);

create table category
(
    id              int(11) auto_increment,
    parent_id       int(11)      not null default 0,
    name            varchar(255) not null,
    description     longtext,
    file_id         int          not null,
    enabled         bool                  default 1,
    alias           varchar(255) not null,
    position        int(11),
    tag_title       mediumtext,
    tag_description mediumtext,
    tag_keywords    mediumtext,
    updated_ut      datetime              default now() null,
    created_at      datetime     null,
    constraint category_pk
        primary key (id),
    constraint category_file_id_fk
        foreign key (file_id) references file (id)
);

create table page
(
    id              int(11) auto_increment,
    name            varchar(255) not null,
    content         longtext,
    tag_title       mediumtext,
    tag_description mediumtext,
    tag_keywords    mediumtext,
    constraint page_pk
        primary key (id)
);

create table comment
(
    id          int(11) auto_increment,
    parent_id   int(11),
    user_name   varchar(255) not null,
    user_email  varchar(255) not null,
    description varchar(255) not null,
    content     longtext     not null,
    allow       bool         not null default 0,
    created_at  datetime              default now() null,
    constraint new_pk
        primary key (id)
);

create table comment_file
(
    id         int(11) auto_increment,
    file_id    int(11) not null,
    comment_id int(11) not null,
    constraint new_pk
        primary key (id),
    constraint comment_file_file_id_fk
        foreign key (file_id) references file (id),
    constraint comment_file_comment_id_fk
        foreign key (comment_id) references comment (id)
            on update cascade on delete cascade
);

create table product
(
    id              int(11) auto_increment,
    category_id     int(11)                not null,
    file_id         int(11)                not null,
    name            varchar(255)           not null,
    description     varchar(255)           null,
    content         longtext               null,
    enabled         bool     default 1,
    alias           varchar(255)           not null,
    position        int(11),
    tag_title       mediumtext,
    tag_description mediumtext,
    tag_keywords    mediumtext,
    updated_ut      datetime default now() null,
    created_at      datetime               null,
    constraint product_pk
        primary key (id),
    constraint product_category_id_fk
        foreign key (category_id) references category (id),
    constraint product_file_id_fk
        foreign key (file_id) references file (id)
);

create table product_recommendations
(
    id                int(11) auto_increment,
    product_id        int(11) not null,
    recommendation_id int(11) not null,
    constraint product_recommendations_pk
        primary key (id),
    constraint product_id_product_id_fk
        foreign key (product_id) references product (id)
            on update cascade on delete cascade,
    constraint recommendation_id_product_id_fk
        foreign key (recommendation_id) references product (id)
            on update cascade on delete cascade
);

create table coating
(
    id          int(11) auto_increment,
    name        varchar(255) not null,
    description varchar(255),
    constraint coating_pk
        primary key (id)
);

create table coating_file
(
    id         int(11) auto_increment,
    file_id    int(11) not null,
    coating_id int(11) not null,
    constraint coating_file_pk
        primary key (id),
    constraint coating_file_file_id_fk
        foreign key (file_id) references file (id),
    constraint coating_file_coating_id_fk
        foreign key (coating_id) references coating (id)
);

create table product_coating
(
    id         int(11) auto_increment,
    product_id int(11) not null,
    coating_id int(11) not null,
    constraint product_coating_pk
        primary key (id),
    constraint product_coating_product_id_fk
        foreign key (product_id) references product (id)
            on update cascade on delete cascade,
    constraint product_coating_coating_id_fk
        foreign key (coating_id) references file (id)
            on update cascade on delete cascade
);

create table design
(
    id          int(11) auto_increment,
    name        varchar(255) not null,
    description varchar(255),
    constraint design_pk
        primary key (id)
);

create table design_file
(
    id        int(11) auto_increment,
    file_id   int(11) not null,
    design_id int(11) not null,
    constraint design_file_pk
        primary key (id),
    constraint design_file_file_id_fk
        foreign key (file_id) references file (id),
    constraint design_file_coating_id_fk
        foreign key (design_id) references design (id)
);

create table product_design
(
    id         int(11) auto_increment,
    product_id int(11) not null,
    design_id  int(11) not null,
    constraint product_design_pk
        primary key (id),
    constraint product_design_product_id_fk
        foreign key (product_id) references product (id)
            on update cascade on delete cascade,
    constraint product_design_design_id_fk
        foreign key (design_id) references design (id)
            on update cascade on delete cascade
);

create table product_page_kind
(
    id      int(11) auto_increment,
    name    varchar(255) not null,
    enabled bool,
    constraint product_design_pk
        primary key (id)
);

create table catalog_order
(
    id         int(11) auto_increment,
    name       varchar(255) not null,
    company    varchar(255),
    created_at datetime     null,
    constraint product_design_pk
        primary key (id)
);

create table category_file
(
    id          int(11) auto_increment,
    file_id     int(11) not null,
    category_id int(11) not null,
    constraint category_file_pk
        primary key (id),
    constraint category_file_file_id_fk
        foreign key (file_id) references file (id),
    constraint category_file_category_id_fk
        foreign key (category_id) references category (id)
);

rename table catalog_order to price_list_order;

create table price_list
(
    id      int(11) auto_increment,
    name    varchar(255) not null,
    file_id int(11)      not null,
    constraint price_list_pk
        primary key (id),
    constraint price_list_file_id_fk
        foreign key (file_id) references file (id)
);

alter table page
    add file_id int null;

alter table page
    add constraint page_file_id_fk
        foreign key (file_id) references file (id);


alter table blog
    drop foreign key new_id_fk;

alter table blog
    add constraint blog_file_id_fk
        foreign key (file_id) references file (id);


create index blog_id_fk
    on blog (file_id);


create table new
(
    id              int auto_increment,
    name            varchar(255)           not null,
    description     varchar(255)           not null,
    content         longtext               not null,
    file_id         int                    not null,
    alias           varchar(255)           not null,
    tag_title       mediumtext,
    tag_description mediumtext,
    tag_keywords    mediumtext,
    updated_ut      datetime default now() null,
    created_at      datetime               null,
    constraint new_pk
        primary key (id),
    constraint new_id_fk
        foreign key (file_id) references file (id)
);

create table product_file
(
    id         int(11) auto_increment,
    file_id    int(11) not null,
    product_id int(11) not null,
    constraint product_file_pk
        primary key (id),
    constraint product_file_file_id_fk
        foreign key (file_id) references file (id),
    constraint product_file_product_id_fk
        foreign key (product_id) references product (id)
);