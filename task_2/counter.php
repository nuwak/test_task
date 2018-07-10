<?php

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
