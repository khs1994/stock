USE test;
SELECT *
FROM stock;
INSERT stock VALUES (NULL, "2015_11_12", "平安证券", "600221", "海南航空", "buy", 4.20, 100, 5, 0, 0.06);
INSERT stock VALUES (NULL, "2015_11_13", "平安证券", "600221", "海南航空", "sell", 4.17, 100, 5, 0.417, 0.06);

INSERT stock VALUES (NULL, "2015_11_19", "平安证券", "000918", "嘉凯城", "buy", 5.28, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2015_11_23", "平安证券", "000918", "嘉凯城", "sell", 5.94, 100, 5, 0.594, 0);

INSERT stock VALUES (NULL, "2015_10_30", "平安证券", "000630", "铜陵有色", "buy", 3.91, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2015_11_13", "平安证券", "000630", "铜陵有色", "buy", 3.91, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2015_11_16", "平安证券", "000630", "铜陵有色", "sell", 3.92, 100, 5, 0.392, 0);
INSERT stock VALUES (NULL, "2015_12_04", "平安证券", "000630", "铜陵有色", "buy", 3.81, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2015_12_08", "平安证券", "000630", "铜陵有色", "sell", 3.71, 200, 5, 0.371 * 2, 0);

INSERT stock VALUES (NULL, "2015_12_08", "平安证券", "600050", "中国联通", "buy", 6.15, 100, 5, 0, 0.06);
INSERT stock VALUES (NULL, "2015_12_25", "平安证券", "600050", "中国联通", "sell", 6.25, 100, 5, 0.625, 0.06);

INSERT stock VALUES (NULL, "2015_12_21", "平安证券", "000630", "铜陵有色", "buy", 3.76, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2015_12_28", "平安证券", "000630", "铜陵有色", "buy", 3.64, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2015_12_31", "平安证券", "000630", "铜陵有色", "sell", 3.59, 200, 5, 0.359 * 2, 0);
