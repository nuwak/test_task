# https://www.db-fiddle.com/f/6inWtYh33aUtJPAyKtADAZ/0


create table author
(
  id   int(11) unsigned auto_increment
    primary key,
  name varchar(255) default '' not null
)
  charset = utf8
  auto_increment = 1;

create table book
(
  id    int(11) unsigned auto_increment
    primary key,
  title varchar(255) default '' not null
)
  charset = utf8
  auto_increment = 1;

create table author_book
(
  author_id int unsigned not null,
  book_id   int unsigned not null,
  primary key (author_id, book_id),
  constraint author_book_author_id_fk
  foreign key (author_id) references author (id)
    on update cascade
    on delete cascade,
  constraint author_book_book_id_fk
  foreign key (author_id) references book (id)
    on update cascade
    on delete cascade
)
  engine = InnoDB
  default charset = utf8;

INSERT INTO author (id, name) VALUES (1, 'Петров');
INSERT INTO author (id, name) VALUES (2, 'Иванов');
INSERT INTO author (id, name) VALUES (3, 'Сидоров');
INSERT INTO book (id, title) VALUES (1, 'Колобок');
INSERT INTO book (id, title) VALUES (2, 'Алиса в стране чудес');
INSERT INTO book (id, title) VALUES (3, 'Ежик в тумане');
INSERT INTO author_book (author_id, book_id) VALUES (1, 1);
INSERT INTO author_book (author_id, book_id) VALUES (1, 2);
INSERT INTO author_book (author_id, book_id) VALUES (2, 1);
INSERT INTO author_book (author_id, book_id) VALUES (2, 2);
INSERT INTO author_book (author_id, book_id) VALUES (3, 1);

SELECT book.title, count(author_book.author_id) AS authors_count
FROM book
  INNER JOIN author_book ON author_book.book_id = book.id
GROUP BY author_book.book_id
HAVING authors_count = 3;
