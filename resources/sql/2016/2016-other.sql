USE test;
SELECT *
FROM stock;
INSERT stock VALUES (NULL, "2016_02_02", "银河证券", "000725", "京东方A", "buy", 2.27, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2016_02_03", "银河证券", "000725", "京东方A", "sell", 2.25, 100, 5, 0.225, 0);

INSERT stock VALUES (NULL, "2016_01_21", "国联证券", "000301", "东方市场", "buy", 4.90, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2016_02_18", "国联证券", "000301", "东方市场", "sell", 4.65, 100, 5, 0.465, 0);

INSERT stock VALUES (NULL, "2016_03_22", "大同证券", "000667", "美好集团", "buy", 4.08, 100, 5, 0, 0);
INSERT stock VALUES (NULL, "2016_04_01", "大同证券", "000667", "美好集团", "other", 2.50, 1, 0, 0, 0);
INSERT stock VALUES (NULL, "2016_05_10", "大同证券", "000667", "美好集团", "sell", 3.73, 100, 5, 0.373, 0);
