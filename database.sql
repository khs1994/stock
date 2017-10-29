CREATE DATABASE stock;
DROP DATABASE stock;
USE stock;
CREATE TABLE stock(
  # ID
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  # 时间
  time VARCHAR(10) NOT NULL,
  # 券商
  securities_trader VARCHAR(5) NOT NULL,
  # 代码
  stock_id CHAR(6) NOT NULL,
  # 名称
  name VARCHAR(5) NOT NULL,
  # 类型
  type ENUM("buy","sell","other"),
  # 价格
  price FLOAT(6,2) NOT NULL ,
  # 数量
  number BIGINT NOT NULL,
  # 佣金
  commission FLOAT(10,2) UNSIGNED NOT NULL,
  # 税费
  tax FLOAT(10,2) UNSIGNED NOT NULL
);