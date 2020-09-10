mysql -uroot -pmytest -e "drop database stock"
mysql -uroot -pmytest -e "create database stock"

$EXEC_DIR=$PWD

cd $PSScriptRoot

foreach($item in $(ls *.sql)){
    $filename = $item.name
    write-host "==> Handle $filename"
    mysql -uroot -pmytest -e "source $filename"
}

cd $EXEC_DIR
