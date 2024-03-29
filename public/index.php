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

function year_sql($year,$prefix=true)
{
    if ($year) {
        $sql = "SELECT DISTINCT list_id FROM stock WHERE type='sell' and time LIKE '${year}_%' and list_id !=0";
        // echo $sql;

        // var_dump($stock->exec($sql,false));

        $stock = new Stock();

        $r = $stock->exec($sql, false);

        // var_dump($r);

        $a = '(';

        foreach ($r as $item) {
            $a .= $item['list_id'] . ',';
        }

        $a = trim($a, ',');

        $a = $a . ')';

        // var_dump($a);

        if(!$prefix){
            return ' list_id in ' . $a;
        }

        $a = ' and list_id in ' . $a;

        return $a;
    }
}

function get_result($securities_trader, $stock_name, $year = null)
{
    $stock = new Stock();

    # $year = $year ? " and time LIKE '${year}_%'":'';

    // if ($securities_trader || $stock_name) {
    //     $year = year_sql($year);
    // } else {
    //     $year = null;
    // }

    $year = year_sql($year);

    // 买入

    $sql = 'SELECT sum(price*number) FROM stock WHERE type="buy"' . securities_trader_where($securities_trader) . stock_name_where($stock_name) . $year;

    $buy = $stock->exec($sql);

    // 卖出

    $sql = 'SELECT sum(price*number) FROM stock WHERE type = "sell"' . securities_trader_where($securities_trader) . stock_name_where($stock_name) . $year;

    $sell = $stock->exec($sql);

    // 分红

    $sql = 'SELECT sum(price*number) FROM stock WHERE type = "other"' . securities_trader_where($securities_trader) . stock_name_where($stock_name) . $year;

    $dividend = $stock->exec($sql);

    // 印花税

    $sql = 'select sum(round(price*number*0.001,2)) FROM stock WHERE type="sell" and left(stock_id, 2) not in ("11","12","15")' . securities_trader_where($securities_trader) . stock_name_where($stock_name) . $year;

    $tax = $stock->exec($sql);

    // 过户费

    if ($year && !($securities_trader || $stock_name)) {
        $year = ' WHERE '. trim($year,' and ');
    }

    $sql = 'SELECT sum(round(transfer_fee,2)) FROM stock' . securities_trader_where($securities_trader, true) . stock_name_where($stock_name, $securities_trader === false) . $year;

    $transfer_fee = $stock->exec($sql);

    // 交易佣金

    $sql = 'SELECT sum(commission) FROM stock' . securities_trader_where($securities_trader, true) . stock_name_where($stock_name, $securities_trader === false) . $year;

    $commission = $stock->exec($sql);

    $profit = $sell + $dividend - $buy - $tax - $transfer_fee - $commission;

    $profit = round($profit, 2);

    $detail = [];

    if ($stock_name or $securities_trader) {
        $detail = $stock->exec('select * from stock ' . securities_trader_where($securities_trader, true) . stock_name_where($stock_name, $securities_trader === false) . $year.'ORDER BY time ASC', false);
    }

    return compact('buy', 'sell', 'dividend', 'tax', 'transfer_fee', 'commission', 'profit', 'detail');
}

$securities_trader = $_GET['securities_trader'] ?? false;
$stock_name = $_GET['stock'] ?? false;
$securities_trader_list = $_GET['securities_trader_list'] ?? false;
$stock_list = $_GET['stock_list'] ?? false;
$json = $_GET['json'] ?? false;
$year = $_GET['year'] ?? null;

if ($stock_name) {
    header('Content-Type: application/json');
    echo json_encode(get_result($securities_trader, $stock_name, $year));

    return;
}

$stock = new Stock();
$securities_traders = $stock->exec('select DISTINCT securities_trader from stock', false);
$stock_id_and_name = $stock->exec('select DISTINCT stock_id,name from stock' . ($securities_trader ? ' where securities_trader="' . $securities_trader . '"' . year_sql($year) : ($year?" WHERE ".year_sql($year,false):'')), false);

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
    echo json_encode(get_result($securities_trader, false, $year));

    return;
}

['buy' => $buy, 'sell' => $sell, 'dividend' => $dividend, 'tax' => $tax, 'transfer_fee' => $transfer_fee, 'commission' => $commission, 'profit' => $profit] = get_result($securities_trader, $stock_name, $year);

echo <<<EOF
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link href="https://cdn.staticfile.org/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

<title>Stock | khs1994.com</title>
</head>

<body>
<script src="https://cdn.staticfile.org/bootstrap/5.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<div class="row row-cols-1 row-cols-md-1 g-4">
  <div class="col">
    <div class="card">
      <div class="card-header">总览</div>
      <ul class="list-group list-group-flush">
      <li class="list-group-item">买入 ¥$buy</li>
      <li class="list-group-item bg-light">卖出 ¥$sell</li>
      <li class="list-group-item">分红 ¥$dividend</li>
      <li class="list-group-item bg-light">印花税 ¥$tax</li>
      <li class="list-group-item">过户费 ¥$transfer_fee</li>
      <li class="list-group-item bg-light">券商佣金 ¥$commission</li>
      <li class="list-group-item">盈利 ¥$profit</li>
      </ul>
</div></div></div>
</div>

<div>
<h1>WEB</h1>

<div class="row row-cols-1 row-cols-md-3 g-4">
  <div class="col">
    <div class="card">
      <div class="card-header">券商详情</div>
      <div class="card-body">
        <a class="card-link" href='?securities_trader=国联证券'>?securities_trader=国联证券</a>
      </div>
    </div> 
  </div>        
</div>

<div>

<h1>API</h1>
<div class="row row-cols-1 row-cols-md-3 g-4">
  <div class="col">
    <div class="card">
      <div class="card-header">券商列表</div>
      <div class="card-body">
        <a class="card-link" href='?securities_trader_list=true'>?securities_trader_list=true</a>
      </div>
    </div>
  </div>  
  <div class="col">
    <div class="card">
      <div class="card-header">股票列表</div>
      <div class="card-body">
        <a href='?stock_list=true'>?stock_list=true</a>
      </div>
    </div>
   </div>
   <div class="col">  
     <div class="card">
       <div class="card-header">券商全部成交</div>
       <div class="card-body">
         <a href='?securities_trader=国联证券&json=true'>?securities_trader=国联证券&json=true</a>
       </div>
      </div>
    </div>
    <div class="col">  
      <div class="card">
        <div class="card-header">股票全部成交</div>
        <div class="card-body">
          <a href='?stock=好当家'>?stock=好当家</a>
        </div>
      </div>  
    </div>
    <div class="col">
      <div class="card">
        <div class="card-header">某券商某股票成交</div>
        <div class="card-body">
          <a href='?securities_trader=国金证券&stock=好当家'>?securities_trader=国金证券&stock=好当家</a>
        </div>
      </div>     
    </div>

</div> <!--row-->
</div> <!--root-->
EOF;

echo "<h1>券商</h1>
<div style=\"text-decoration:none\" class=\"row row-cols-2 row-cols-md-6 g-4\">";

foreach ($securities_traders as $item) {
    $securities_trader_item = $item['securities_trader'];
    echo '<div class="col">
      <div class="card text-center">
        <div class="card-body">
          <a class="btn btn-primary" href="/?securities_trader=' . $item['securities_trader'] . '">'
        . $securities_trader_item . '</a>
        </div>
      </div>
    </div>';
}

echo "</div><h1>股票</h1><div class=\"row row-cols-1 row-cols-sm-2 row-cols-md-6 g-4\">";

foreach ($stock_id_and_name as $item) {
    $stock_id = $item['stock_id'];
    $stock_name = $item['name'];
    $prefix = 'SZ';

    if (in_array(substr($stock_id, 0, 2), ['11', '60'])) {
        $prefix = 'SH';
    }

    ['buy' => $buy, 'sell' => $sell, 'dividend' => $dividend, 'tax' => $tax, 'transfer_fee' => $transfer_fee, 'commission' => $commission, 'profit' => $profit] = get_result($securities_trader, $stock_name, $year);

    $color = $profit > 0 ? "red" : "green";
    $bg_color = $color === 'red' ? "danger" : 'success';

    echo "
    <div class=\"col\" style=''>
    <div class=\"card text-white border-$bg_color\">
    <div class=\"card-header bg-$bg_color\">" . $stock_name . '(' . $prefix . $stock_id . ')' . '</div>';
    $f10_link = "https://xueqiu.com/stock/f10/compinfo.json?symbol=${prefix}${stock_id}";
    $xueqiu = "https://xueqiu.com/S/${prefix}${stock_id}";

    echo "<ul class=\"list-group list-group-flush\">
    <li class=\"list-group-item bg-light\"><a style=\"text-decoration:none\" class=\"card-link\" href='/?stock=$stock_name'>成交明细(JSON)</a></li>
    <li class=\"list-group-item\"><a style=\"text-decoration:none\" class=\"card-link\" href='/chart.html?stock=$stock_name'>成交明细(图表)</a></li>
    <li class=\"list-group-item bg-light\"><a style=\"text-decoration:none\" class=\"card-link\" href='$xueqiu'>行情</a></li>
    <li class=\"list-group-item\"><a style=\"text-decoration:none\" class=\"card-link\" href='$f10_link'>F10</a></li>
    </ul>";

    echo "<div class='card-footer bg-$bg_color'><p class=\"card-text\" style='font-size: large;font-family:\"Microsoft YaHei\"; '>盈亏 $profit</p>";
    echo "</div></div></div>";

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

echo "</div></body>";
