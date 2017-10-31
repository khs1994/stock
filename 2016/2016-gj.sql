USE laravel_admin;
SHOW VARIABLES LIKE 'character%';
SELECT *
FROM stock;
INSERT stock VALUES (97, "2016_01_26", "国金证券", "600221", "海南航空", "buy", 3.21, 100, 5, 0, 0.06);
INSERT stock VALUES (98, "2016_02_01", "国金证券", "600221", "海南航空", "sell", 2.99, 100, 5, 0.299, 0.06);

INSERT stock VALUES (99, "2016_02_23", "国金证券", "600567", "山鹰纸业", "buy", 3.11, 100, 5, 0, 0.06);
INSERT stock VALUES (100, "2016_03_03", "国金证券", "600567", "山鹰纸业", "sell", 3.04, 100, 5, 0.304, 0.06);

INSERT stock VALUES (101, "2016_08_29", "国金证券", "000630", "铜陵有色", "buy", 2.62, 100, 5, 0, 0);
INSERT stock VALUES (102, "2016_11_03", "国金证券", "000630", "铜陵有色", "sell", 2.75, 100, 5, 0.275, 0);
