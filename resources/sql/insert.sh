#!/bin/bash

mysql -uroot -pmytest -e "drop database stock"
mysql -uroot -pmytest -e "create database stock"

for sql in $(ls *.sql)
do
  mysql -uroot -pmytest < ${sql};
done
