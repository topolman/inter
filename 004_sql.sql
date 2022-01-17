-- Поле возраст не требуется, т.к. есть дата рождения.
-- Среднее место на соревнованиях - отстутствует, т.к. является вычисляемым (если судить по заданию 3.)
CREATE TABLE MARATHON.dbo.[Participants] (
	participant_id INT IDENTITY NOT NULL,
	firstname VARCHAR(100) NOT NULL, --Имя
	surname VARCHAR(100) NULL, --Отчество
	lastname VARCHAR(100) NOT NULL, --Фамилия
	email VARCHAR(255) NOT NULL, -- Электронная почта
	phone VARCHAR(10) NOT NULL, -- Номер телефона
	birth_date DATE NOT NULL, -- Дата рождения
	pass VARCHAR(10) NOT NULL, --Паспорт серия
	bio VARCHAR(MAX) NULL, --Биография
	video VARCHAR(1000) NULL, --Ссылка на презентацию
	create_dt DATETIME DEFAULT getdate() NOT NULL, -- создание записи
	update_dt DATETIME DEFAULT getdate() NOT NULL, -- обновление записи
	CONSTRAINT PK__Participant PRIMARY KEY (participant_id),
	CONSTRAINT UK__Email UNIQUE (email),
	CONSTRAINT UK__Phone UNIQUE (phone),
	CONSTRAINT UK_Pass UNIQUE (pass)
);

SELECT TOP(5) s.OP_INGR, COUNT(u.LABEL)
FROM APWHS.DBO.[_STORE] AS s
JOIN APWHS.DBO.[_UNIT] AS u ON s.UNIT_ID = u.UNIT_ID 
GROUP BY s.OP_INGR, u.LABEL 
ORDER BY COUNT(u.label) DESC

-- Запрос. Выбрать топ 5 спортсменов больше остальных посетивших соревнований
-- Строю на основе схемы из задания 3.
SELECT TOP(5)   p.lastname+' '+p.firstname+ISNULL(' '+p.surname,'') AS fio
				      , COUNT(c.competition_id) AS cnt
FROM MARATHON.dbo.Participants AS p
JOIN MARATHON.dbo.Participants_to_competitions AS p2c ON p.participant_id = p2c.participant_id
JOIN MARATHON.dbo.Competitions AS c ON c.competition_id = p2c.competition_id
GROUP BY p.participant_id
ORDER BY COUNT(c.competition_id) DESC