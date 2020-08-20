<?php

require __DIR__ . './../src/autoload.php';

$securities_trader = $_GET['securities_trader'] ?? false;

$stock = new Stock();

$sql = 'SELECT sum(price*number) FROM stock WHERE type="buy"' . ($securities_trader ? ' and securities_trader="' . $securities_trader . '"' : '');

// 买入

$buy = $stock->exec($sql);

// 卖出

$sql = 'SELECT sum(price*number) FROM stock WHERE type = "sell"' . ($securities_trader ? ' and securities_trader="' . $securities_trader . '"' : '');

$sell = $stock->exec($sql);

// 分红

$sql = 'SELECT sum(price*number) FROM stock WHERE type = "other"' . ($securities_trader ? ' and securities_trader="' . $securities_trader . '"' : '');

$dividend = $stock->exec($sql);

// 印花税

$sql = 'select sum(round(price*number*0.001,2)) FROM stock WHERE type="sell" and left(stock_id, 2) not in ("11","12","15")' . ($securities_trader ? ' and securities_trader="' . $securities_trader . '"' : '');

$tax = $stock->exec($sql);

// 过户费

$sql = 'SELECT sum(round(transfer_fee,2)) FROM stock' . ($securities_trader ? ' where securities_trader="' . $securities_trader . '"' : '');

$transfer_fee = $stock->exec($sql);

// 交易佣金

$sql = 'SELECT sum(commission) FROM stock' . ($securities_trader ? ' where securities_trader="' . $securities_trader . '"' : '');

$commission = $stock->exec($sql);

$profit = $sell + $dividend - $buy - $tax - $transfer_fee - $commission;

$profit = round($profit, 2);

echo <<<EOF
<h1>总览</h1>

买入 ¥$buy <hr>

卖出 ¥$sell <hr>

分红 ¥$dividend <hr>

印花税 ¥$tax <hr>

过户费 ¥$transfer_fee <hr>

券商佣金 ¥$commission <hr>

盈利 ¥$profit <hr>

<h1>Query</h1>

?securities_trader=国联证券
EOF;

$securities_traders = $stock->exec('select DISTINCT securities_trader from stock', false);
$stock_id_and_name = $stock->exec('select DISTINCT stock_id,name from stock', false);

echo "<h1>券商</h1>";

foreach ($securities_traders as $item) {
    $securities_trader = $item[0];
    echo '<a href="/?securities_trader=' . $item[0] . '"><h4>' . $securities_trader . '</h4></a>';
}

echo "<h1>股票</h1>";

foreach ($stock_id_and_name as $item) {
    $stock_id = $item[0];
    $stock_name = $item[1];
    $prefix = 'SZ';

    if (substr($item[0], 0, 2) === '60') {
        $prefix = 'SH';
    }

    echo "<h4 style='color: blue'>" . $prefix . $item[0] . $item[1] . '</h4>';
    $f10_link = "https://xueqiu.com/stock/f10/compinfo.json?symbol=${prefix}${stock_id}";
    $xueqiu = "https://xueqiu.com/S/${prefix}${stock_id}";

    echo "<a href='$xueqiu'>行情</a><br/>";
    echo "<a href='$f10_link'>F10</a>";
}
