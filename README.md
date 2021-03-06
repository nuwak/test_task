## Задание 1
>Задание: Спроектировать схему БД для хранения библиотеки. Интересуют авторы и книги.

>Дополнительное задание: Написать SQL который вернет список книг, написанный 3-мя соавторами. Результат: книга - количество соавторов.
>Решение должно быть представлено в виде ссылки на https://www.db-fiddle.com/

[Решение](https://www.db-fiddle.com/f/6inWtYh33aUtJPAyKtADAZ/1)

## Задание 2

>**Задание**: Реализовать счетчик вызова скрипта. Было принято решение, хранить данные в файле.

```php
$filename = './counter.txt';

//Пытаем открыть файл для чтения и записи или его создать
if($descriptor = fopen($filename, 'c+'))
{
    //Ждет пока сможет заблокировать файл, только после этого выполняет код дальше
    if (flock($descriptor, LOCK_EX)) {
        //для fread $fileSize должен быть больше нуля, для этого делаем проверку.
        if ($fileSize = filesize($filename)) {
            $counter = intval(fread($descriptor, $fileSize));
        } else {
            $counter = 0;
        }
        //Очищаем файл
        ftruncate($descriptor, 0);
        //Возврашаем указатель на начало файла
        rewind($descriptor);
        fwrite($descriptor, ++$counter);
        flock($descriptor, LOCK_UN);
    } else {
        echo "Не удалось получить блокировку!";
    }

    fclose($descriptor);

} else {
    echo 'Не удалось прочитать или создать файл.';
}
```

>**Вопрос**: Какие проблемы имеет данные подход? Как вы их можете решить?
(Нельзя использовать другие технологии)

Если не блокировать файл на чтение и запись, то во время записи другой скрипт может его читать и получить старые значения. Для того чтобы этого избежать необходимо блокировать файл чтобы у тех кто его читает были актуальные данные.

>**Дополнительный вопрос**: Через некоторое время нагрузка на сервер значительно выросла. Какие проблемы вы видите? Как вы их можете решить?
Если бы вы могли выбрать другую технологию, то какую и почему?

Будет большая нагрузка на файловую систему (чтение и запись), у нее есть ограничение которое может быть достигнуто и тогда другие скрипты могут начать вставать в очередь чтобы считать данные с диска, это приведет к задержкам в ответах.

Также это уменьшает срок службы диска.

Оптимальное решение использовать Redis, метод incr. Как вариант данные можно писать в memcache, но тогда они будут потеряны при перезагрузки сервера.

## Задание 3

>**Задание**: Проведите Code Review. Необходимо написать, с чем вы не согласны и почему.

[Code Review](https://github.com/nuwak/test_task/tree/master/task_3/review)

>**Дополнительное задание**: Напишите свой вариант.
Решение должно быть представлено в виде ссылки на https://github.com/.

[Решение](https://github.com/nuwak/test_task/tree/master/task_3/src)

Решил логирование и кеширование оставить в одном декораторе хотя это нарушает SRP, но в большинстве случаев такая гибкость не требуется, она создает дополнительную сложность и плодит множество классов. Но если мы будет знать что нам действительно потребуется их комбинировать, тогда лучше сделать отдельные декораторы.

>**Требования были**: Добавить возможность получения данных от стороннего сервиса.

## Задание 4

>У вас нет доступа к библиотекам для работы с большими числами. Дано два числа в виде строки. Числа могут быть очень большими, могут не поместиться в 64 битный integer.

[Решение](https://github.com/nuwak/test_task/blob/master/task_4/sum.php)

>**Задание**: Написать функцию которая вернет сумму этих чисел.
   Решение должно быть представлено в виде ссылки на https://github.com/.

## Задание 5
>**Дано**:
```sql
CREATE TABLE test (
  id INT NOT NULL PRIMARY KEY
);

INSERT INTO test (id) VALUES (1), (2), (3), (6), (8), (9), (12);
```
>Задание: Написать SQL запрос который выведет все пропуски.

>**Результат**:

FROM | TO
--- | ---
3   | 6
6   | 8
9   | 12

> Решение должно быть представлено в виде ссылки на https://www.db-fiddle.com/.

[Решение](https://www.db-fiddle.com/f/oJYvPXrtLGZhxdWHbbBnrk/1)

Добавил такое выражение `last_id+0` т.к. db-fiddle.com сглючил, вернул в поле FROM не корректные данные. На локальной машине запрос нормально отрабатывает без этого хака.


