# CREATE DATABASE laravel_admin;
# DROP DATABASE stock;
USE laravel_admin;
CREATE TABLE IF NOT EXISTS stock (
  # ID
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  # 时间
  time              VARCHAR(10)           NOT NULL,
  # 券商
  securities_trader VARCHAR(5)            NOT NULL,
  # 代码
  stock_id          CHAR(6)               NOT NULL,
  # 名称
  name              VARCHAR(5)            NOT NULL,
  # 类型
  type              ENUM ("buy", "sell", "other"),
  # 价格
  price             FLOAT(6, 2)           NOT NULL,
  # 数量
  number            BIGINT                NOT NULL,
  # 佣金
  commission        FLOAT(10, 2) UNSIGNED NOT NULL,
  # 印花税 卖出 成交金额 * 0.1%
  tax               FLOAT(10, 2) UNSIGNED NOT NULL,
  # 过户费 上交所 双向 成交面额 *0.06 % 深圳包含在佣金中
  transfer_fee      FLOAT(10, 3) UNSIGNED NOT NULL
);
