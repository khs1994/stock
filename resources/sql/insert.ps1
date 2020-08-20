mysql -uroot -pmytest -e "drop database stock"
mysql -uroot -pmytest -e "create database stock"

foreach($item in $(ls *.sql)){
    $filename = $item.name
    mysql -uroot -pmytest -e "source $filename"
}
