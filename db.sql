-- Выведите имена клиентов и количество их заказов, созданных в марте 2015 года
-- и содержащих один или больше товаров из списка (id: 151515,151617,151514),
-- отсортируйте результат по убыванию суммы заказа
SELECT c.*, count(o.id) order_qty, DATE_FORMAT(FROM_UNIXTIME(o.ctime), '%m%Y') date_create, p.`id`
FROM `clients` c
LEFT JOIN `orders` o
    ON c.`id` = o.`clients_id`
LEFT JOIN `products` p
    ON o.`id` = p.`order_id`
WHERE p.`id` IN (151515, 151617, 151514)
GROUP BY c.`name`
HAVING date_create = '032015'
    AND order_qty > 0
ORDER BY order_qty DESC;

-- Клиентов, чей email содержит «@mail.ru» выводить с количеством заказов
-- равным 0.
SELECT c.*, count(o.id) order_qty
FROM `clients` c
LEFT JOIN `orders` o
    ON c.`id` = o.`clients_id`
WHERE c.`email` LIKE '%@mail.ru'
GROUP BY c.`name`
HAVING cnt = 0
ORDER BY cnt DESC;