USE stock;
SHOW VARIABLES LIKE 'character%';
SELECT *
FROM stock;
INSERT stock VALUES (NULL, "2015_12_31", "平安证券", "000301", "东方市场", "buy", 6.59, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2016_01_06", "平安证券", "000301", "东方市场", "sell", 5.78, 100, 5, 0.578, 0);

INSERT stock VALUES (NULL, "2015_01_06", "平安证券", "601880", "大连港", "buy", 5.27, 100, 5, 0, 0.06);
INSERT stock VALUES (NULL, "2015_01_08", "平安证券", "601880", "大连港", "sell", 5.04, 100, 5, 0.504, 0.06);

INSERT stock VALUES (NULL, "2015_01_08", "平安证券", "601288", "农业银行", "buy", 3.16, 100, 5, 0, 0.06);
INSERT stock VALUES (NULL, "2015_01_22", "平安证券", "601288", "农业银行", "sell", 3.09, 100, 5, 0.309, 0.06);
