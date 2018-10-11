<?php

require __DIR__.'./../src/autoload.php';

$stock = new Stock();

$sql = 'SELECT sum(price*number) FROM stock WHERE type="buy"';

// 买入

$buy = $stock->exec($sql);

// 卖出

$sql = 'SELECT sum(price*number) FROM stock WHERE type = "sell"';

$sell = $stock->exec($sql);

// 分红

$sql = 'SELECT sum(price*number) FROM stock WHERE type = "other"';

$dividend = $stock->exec($sql);

// 印花税

$sql = 'select sum(price*number*0.001) FROM stock WHERE type="sell"';

$tax = $stock->exec($sql);

// 过户费

$sql = 'SELECT sum(transfer_fee) FROM stock';

$transfer_fee = $stock->exec($sql);

// 交易佣金

$sql = 'SELECT sum(commission) FROM stock';

$commission = $stock->exec($sql);

$profit = $sell + $dividend - $buy - $tax - $transfer_fee - $commission;

$profit = round($profit, 2);

echo <<<EOF
买入 ¥$buy <hr>

卖出 ¥$sell <hr>

分红 ¥$dividend <hr>

印花税 ¥$tax <hr>

过户费 ¥$transfer_fee <hr>

券商佣金 ¥$commission <hr>

盈利 ¥$profit <hr>
EOF;
