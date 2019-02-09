/*Ввод данных пользователя*/
INSERT INTO `user` (name, email, password) VALUES
    ('Дональд Дак', 'donaldduck@hz.ru', 1234),
	('Дарт Вейдер', 'dartVader@hz.ru', 1234);

/*Ввод категорий задач*/
INSERT INTO `category` (user_id, category_name) VALUES
    (2,'Входящие'), 
    (1,'Учеба'), 
	(1,'Работа'),
	(2,'Домашние дела'), 
	(2,'Авто');

/*Ввод задач*/
INSERT INTO `task` (category_id, task_status, task_name, deadline) VALUES 
	(3, 0, 'Собеседование в IT компании', '01.12.2019'),
    (3, 0, 'Выполнить тестовое задание', '25.12.2019'),
    (2, 1, 'Сделать задание первого раздела', '21.12.2019'),
    (1, 0, 'Встреча с другом', '22.12.2019'),
    (4, 0, 'Купить корм для кота', NULL ),
    (4, 0, 'Заказать пиццу', NULL );

----- Получить список из всех проектов для одного пользователя -----
SELECT * FROM category
	WHERE user_id = 1;

----- Получить список из всех задач для одного проекта -----
SELECT * FROM task
	WHERE category_id = 3;

----- Пометить задачу как выполненную -----
UPDATE task SET task_status = 1
	WHERE task_name = 'Купить корм для кота';

----- Jбновить название задачи по её идентификатору -----
UPDATE task SET task_name = 'Купить корм для кота-обжоры'
	WHERE task_id = 5;