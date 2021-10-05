<?php

require __DIR__ . './../src/autoload.php';

function securities_trader_where($securities_trader, bool $where = false)
{
    if (!$securities_trader) {
        return '';
    }
    $prefix = $where ? ' where ' : '';
    return $prefix . ($securities_trader ? ($prefix ? '' : ' and ') . 'securities_trader="' . $securities_trader . '"' : '');
}

function stock_name_where($stock_name, bool $where = false)
{
    if (!$stock_name) {
        return '';
    }
    $prefix = $where ? ' where ' : '';
    return $prefix . ($stock_name ? ($prefix ? '' : ' and ') . 'name="' . $stock_name . '"' : '');
}

function get_result($securities_trader, $stock_name)
{
    $stock = new Stock();

    $sql = 'SELECT sum(price*number) FROM stock WHERE type="buy"' . securities_trader_where($securities_trader) . stock_name_where($stock_name);

    // 买入

    $buy = $stock->exec($sql);

    // 卖出

    $sql = 'SELECT sum(price*number) FROM stock WHERE type = "sell"' . securities_trader_where($securities_trader) . stock_name_where($stock_name);

    $sell = $stock->exec($sql);

    // 分红

    $sql = 'SELECT sum(price*number) FROM stock WHERE type = "other"' . securities_trader_where($securities_trader) . stock_name_where($stock_name);

    $dividend = $stock->exec($sql);

    // 印花税

    $sql = 'select sum(round(price*number*0.001,2)) FROM stock WHERE type="sell" and left(stock_id, 2) not in ("11","12","15")' . securities_trader_where($securities_trader) . stock_name_where($stock_name);

    $tax = $stock->exec($sql);

    // 过户费

    $sql = 'SELECT sum(round(transfer_fee,2)) FROM stock' . securities_trader_where($securities_trader, true) . stock_name_where($stock_name, $securities_trader === false);

    $transfer_fee = $stock->exec($sql);

    // 交易佣金

    $sql = 'SELECT sum(commission) FROM stock' . securities_trader_where($securities_trader, true) . stock_name_where($stock_name, $securities_trader === false);

    $commission = $stock->exec($sql);

    $profit = $sell + $dividend - $buy - $tax - $transfer_fee - $commission;

    $profit = round($profit, 2);

    $detail = [];

    if ($stock_name or $securities_trader) {
        $detail = $stock->exec('select * from stock ' . securities_trader_where($securities_trader, true) . stock_name_where($stock_name, $securities_trader === false). 'ORDER BY time ASC', false);
    }

    return compact('buy', 'sell', 'dividend', 'tax', 'transfer_fee', 'commission', 'profit', 'detail');
}

$securities_trader = $_GET['securities_trader'] ?? false;
$stock_name = $_GET['stock'] ?? false;
$securities_trader_list = $_GET['securities_trader_list'] ?? false;
$stock_list = $_GET['stock_list'] ?? false;
$json = $_GET['json'] ?? false;

if ($stock_name) {
    header('Content-Type: application/json');
    echo json_encode(get_result($securities_trader, $stock_name));

    return;
}

$stock = new Stock();
$securities_traders = $stock->exec('select DISTINCT securities_trader from stock', false);
$stock_id_and_name = $stock->exec('select DISTINCT stock_id,name from stock' . ($securities_trader ? ' where securities_trader="' . $securities_trader . '"' : ''), false);

if ($securities_trader_list) {
    header('Content-Type: application/json');
    echo json_encode($securities_traders);

    return;
}

if ($stock_list) {
    header('Content-Type: application/json');
    echo json_encode($stock_id_and_name);

    return;
}

if ($securities_trader and $json) {
    header('Content-Type: application/json');
    echo json_encode(get_result($securities_trader, false));

    return;
}

['buy' => $buy, 'sell' => $sell, 'dividend' => $dividend, 'tax' => $tax, 'transfer_fee' => $transfer_fee, 'commission' => $commission, 'profit' => $profit] = get_result($securities_trader, $stock_name);

echo <<<EOF
<h1>总览</h1>

买入 ¥$buy <hr>

卖出 ¥$sell <hr>

分红 ¥$dividend <hr>

印花税 ¥$tax <hr>

过户费 ¥$transfer_fee <hr>

券商佣金 ¥$commission <hr>

盈利 ¥$profit <hr>

<h1>WEB</h1>

<h4>券商详情</h4>
<a href='?securities_trader=国联证券'>?securities_trader=国联证券</a>

<h1>API</h1>

<h4>券商列表</h4>
<a href='?securities_trader_list=true'>?securities_trader_list=true</a>
<h4>股票列表</h4>
<a href='?stock_list=true'>?stock_list=true</a>
<h4>券商全部成交</h4>
<a href='?securities_trader=国联证券&json=true'>?securities_trader=国联证券&json=true</a>
<h4>股票全部成交</h4>
<a href='?stock=好当家'>?stock=好当家
<h4>某券商某股票成交</h4>
<a href='?securities_trader=国金证券&stock=好当家'>?securities_trader=国金证券&stock=好当家</a>
EOF;

echo "<h1>券商</h1><div style='height:50px'>";

foreach ($securities_traders as $item) {
    $securities_trader_item = $item['securities_trader'];
    echo '<div
    style="float:left;width:150px;hight:200px"
    ><a href="/?securities_trader=' . $item['securities_trader'] . '"><h4>' . $securities_trader_item . '</h4></a></div>';
}

echo "</div><h1>股票</h1>";

foreach ($stock_id_and_name as $item) {
    $stock_id = $item['stock_id'];
    $stock_name = $item['name'];
    $prefix = 'SZ';

    if (in_array(substr($stock_id, 0, 2), ['11', '60'])) {
        $prefix = 'SH';
    }

    ['buy' => $buy, 'sell' => $sell, 'dividend' => $dividend, 'tax' => $tax, 'transfer_fee' => $transfer_fee, 'commission' => $commission, 'profit' => $profit] = get_result($securities_trader, $stock_name);

    $color = $profit > 0 ? "red" : "green";

    echo "<div
       style='float:left;width:170px'
    ><h4 style='color: $color'>" . $prefix . $stock_id . $stock_name . '</h4>';
    $f10_link = "https://xueqiu.com/stock/f10/compinfo.json?symbol=${prefix}${stock_id}";
    $xueqiu = "https://xueqiu.com/S/${prefix}${stock_id}";

    echo "<a href='/?stock=$stock_name'>成交明细(JSON)</a><br/>";
    echo "<a href='/chart.html?stock=$stock_name'>成交明细(图表)</a><br/>";
    echo "<a href='$xueqiu'>行情</a><br/>";
    echo "<a href='$f10_link'>F10</a>";

    echo "<p style='color: $color;font-size: large;font-family:\"Microsoft YaHei\"; '>盈亏 $profit</p>";
    echo "</div>";

    continue;
    echo <<<EOF
<pre>    
{
    "name": "$stock_name",
    "id": $stock_id,
    "buy": "$buy",
    "sell": "$sell",
    "commission": "$commission",
    "profit": "$profit",
    "xueqiu": "$xueqiu",
    "f10": "$f10_link"
}
</pre>
EOF;
}
