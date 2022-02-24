# DROP DATABASE stock;

# CREATE DATABASE IF NOT EXISTS stock;

USE stock;

CREATE TABLE IF NOT EXISTS stock (
  # ID
  id                BIGINT PRIMARY KEY AUTO_INCREMENT,
  # 时间
  time              VARCHAR(10)             NOT NULL,
  # 券商
  securities_trader VARCHAR(10)             NOT NULL,
  # 代码
  stock_id          VARCHAR(10)             NOT NULL,
  # 名称
  name              VARCHAR(10)             NOT NULL,
  # 类型
  type              ENUM ("buy", "sell", "other"),
  # 价格
  price             DECIMAL(6, 3)           NOT NULL,
  # 数量
  number            BIGINT                  NOT NULL,
  # 佣金
  commission        DECIMAL(10, 2) UNSIGNED NOT NULL,
  # 印花税 卖出 成交金额 * 0.1%
  tax               DECIMAL(10, 2) UNSIGNED NOT NULL,
  # 2015/08/01 过户费 上交所 双向 成交面额 *0.002 % 深圳包含在佣金中
  transfer_fee      DECIMAL(10, 3) UNSIGNED NOT NULL,
  # list id
  list_id           BIGINT DEFAULT 0
);
