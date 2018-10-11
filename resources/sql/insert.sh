#!/bin/bash

for sql in $(ls *.sql)
do
  mysql -uroot -pmytest < ${sql};
done
