#!/bin/bash

mysql -uroot -pmytest -e "drop database stock"
mysql -uroot -pmytest -e "create database stock"

if [ -f .gitignore ];then
  cd resources/sql
fi

for sql in $(ls *.sql)
do
  mysql -uroot -pmytest < ${sql};
done
