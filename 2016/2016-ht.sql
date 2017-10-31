USE laravel_admin;
SHOW VARIABLES LIKE 'character%';
SELECT *
FROM stock;
INSERT stock VALUES (NULL, "2016_03_29", "华泰证券", "600221", "海南航空", "buy", 3.15, 100, 5, 0, 0.06);
INSERT stock VALUES (NULL, "2016_05_05", "华泰证券", "600221", "海南航空", "sell", 3.27, 100, 5, 0.327, 0.06);

INSERT stock VALUES (NULL, "2016_08_16", "华泰证券", "600546", "山煤国际", "buy", 3.44, 100, 5, 0, 0.06);
INSERT stock VALUES (NULL, "2016_12_06", "华泰证券", "600546", "山煤国际", "sell", 4.84, 100, 5, 0.484, 0.06);
