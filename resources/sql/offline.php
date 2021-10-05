<?php
require __DIR__ . '/../../src/Stock.php';

$stock = new Stock();

$result = $stock->exec('select time,stock_id from stock;', false);

foreach ($result as $item) {
    // var_dump($item);

    ['time' => $time, 'stock_id' => $stock_id] = $item;

    if (in_array(substr($stock_id, 0, 2), ['60', '11'])) {
        $stock_id = 'sh' . $stock_id;
    } else {
        $stock_id = 'sz' . $stock_id;
    }

    // var_dump($time, $stock_id);

    $curl = curl_init();

    $year = substr($time, 2, 2);
    $month = substr($time, 5, 2);
    $day = substr($time, 8, 2);

    if (str_starts_with($month, '0')) {
        $month = substr($month, 1, 1);
    }
    if (str_starts_with($day, '0')) {
        $day = substr($day, 1, 1);
    }

    $time = $year . '-' . $month . '-' . $day;

    // var_dump($time, $stock_id);

    curl_setopt($curl, CURLOPT_URL, "https://web.ifzq.gtimg.cn/appstock/app/fqkline/get?param=${stock_id},day,2015-1-1,${time},1,bfq");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $r = curl_exec($curl);

    $filename = __DIR__ . '/../../public/stock_data/' . $stock_id . '-' . $time;

    if (!file_exists($filename)) {
        file_put_contents($filename, $r);
    }else{
        $filename = realpath($filename);
        echo "file $filename exists\n";
        
    }
}
