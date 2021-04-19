CREATE TABLE category ( cat_id int(10) NOT NULL AUTO_INCREMENT, parent_id int(10) NOT NULL, cat_name varchar(100) NOT NULL, color varchar(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '000000', income varchar(1) NOT NULL DEFAULT ' ', PRIMARY KEY (cat_id), UNIQUE KEY cat_id_UNIQUE (cat_id) )AUTO_INCREMENT=1;
CREATE TABLE log ( note varchar(100) DEFAULT NULL, tms timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP )
CREATE TABLE movement ( mov_id int(11) NOT NULL AUTO_INCREMENT, cat_id int(10) NOT NULL, val decimal(10,2) NOT NULL DEFAULT '0.00', type varchar(1) NOT NULL, dat_mov date NOT NULL, usr_mov varchar(20) NOT NULL, note varchar(100) NOT NULL DEFAULT ' ', usr_id varchar(20) NOT NULL, tms_upd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (mov_id), KEY INDEX001 (cat_id,dat_mov,usr_id) )AUTO_INCREMENT=1;
CREATE TABLE user ( usr_id varchar(64) NOT NULL, email varchar(100) NOT NULL DEFAULT ' ', passwd varchar(64) NOT NULL COMMENT 'SHA1', tms_upd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, admin varchar(1) NOT NULL DEFAULT 'N', valid varchar(1) NOT NULL DEFAULT 'S', PRIMARY KEY (usr_id) )
CREATE TABLE mversion (upd int(20) NOT NULL AUTO_INCREMENT, db varchar(20) NOT NULL, web varchar(20) NOT NULL, PRIMARY KEY (upd)) AUTO_INCREMENT=1;
CREATE VIEW mov AS select m.mov_id AS mov_id, c.color AS color, (case when (c.cat_id = c.parent_id) then c.cat_name else concat(cp.cat_name, ' (', c.cat_name, ')') end) AS cat_name, c.cat_id AS cat_id, m.val AS val, m.dat_mov AS dat_mov, m.usr_mov AS usr_mov, m.note AS note, m.tms_upd AS tms_upd from ((movement m join category c) join category cp) where ((m.cat_id = c.cat_id) and (c.parent_id = cp.cat_id));
CREATE VIEW tot AS select abs(sum(m.val)) AS val, m.type AS income, year(m.dat_mov) AS year from movement m group by m.type , year(m.dat_mov);
CREATE VIEW cat AS select c.cat_id AS cat_id, c.parent_id AS parent_id, c.cat_name AS cat_name, c.color AS color, c.income AS income, (case when (c.cat_id = c.parent_id) then ' ' else c1.cat_name end) AS parent_cat, (select count(1) from mov where (mov.cat_id = c.cat_id)) AS num_mov, (select (case when (count(1) > 2) then 'S' else 'N' end) from category cc where (c.cat_id = cc.parent_id)) AS have_ch from (category c join category c1) where (c.parent_id = c1.cat_id) order by c.parent_id , c.cat_id;
CREATE VIEW tot_cat AS select sum(m.val) AS val, c.cat_name AS cat_name, c.cat_id AS cat_id, c.income AS income, year(m.dat_mov) AS year from (movement m join category c) where (m.cat_id = c.cat_id) group by c.cat_name , c.cat_id , c.income , year(m.dat_mov);
CREATE VIEW tot_cat_date AS select sum(m.val) AS val, c.cat_name AS cat_name, c.cat_id AS cat_id, c.income AS income, month(m.dat_mov) AS month, year(m.dat_mov) AS year from (movement m join category c) where (m.cat_id = c.cat_id) group by c.cat_name , c.cat_id , c.income , month(m.dat_mov) , year(m.dat_mov) union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 1 AS '1', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 2 AS '2', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 3 AS '3', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 4 AS '4', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 5 AS '5', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 6 AS '6', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 7 AS '7', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 8 AS '8', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 9 AS '9', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 10 AS '10', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 11 AS '11', 0 AS '0' from category union select 0 AS '0', category.cat_name AS cat_name, category.cat_id AS cat_id, category.income AS income, 12 AS '12', 0 AS '0' from category;
CREATE VIEW tot_eu_month AS select sum(m.val) AS val, m.type AS income, month(m.dat_mov) AS month, year(m.dat_mov) AS year from movement m group by m.type , month(m.dat_mov) , year(m.dat_mov);
CREATE VIEW tot_eu_month_tmp AS select tot_eu_month.month AS month, tot_eu_month.year AS year, (case when (tot_eu_month.income = 'P') then tot_eu_month.val else 0 end) AS income, (case when (tot_eu_month.income = 'N') then tot_eu_month.val else 0 end) AS outcome from tot_eu_month;
CREATE VIEW tot_eu AS select t.month AS month, t.year AS year, sum(t.income) AS income, (-(1) * sum(t.outcome)) AS outcome from tot_eu_month_tmp t group by t.month , t.year;
CREATE VIEW tot_usr AS select abs(sum(m.val)) AS val, m.usr_mov AS usr_mov, c.income AS income, year(m.dat_mov) AS year from (movement m join category c ON ((c.cat_id = m.cat_id))) group by m.usr_mov , c.income , year(m.dat_mov);
INSERT INTO user VALUES ('admin',' ',sha1('admin'),sysdate(),'S','S');
INSERT INTO mversion (db, web) VALUES ('1.0', '1.0');
DELIMITER // CREATE PROCEDURE `csv_import`() BEGIN  DECLARE n_key INT; DECLARE v_key VARCHAR(255); DECLARE n_cat INT; DECLARE cat CURSOR FOR SELECT cat_id FROM category where keyword <> '';  OPEN cat;  read_loop: LOOP FETCH cat INTO n_cat; SET n_key = 1; select ifnull(trim(SPLIT_STR(c.keyword, ',', n_key)),'') into v_key from category c where c.cat_id = n_cat;  WHILE v_key <> '' DO insert into movement (cat_id, val, type, dat_mov, usr_mov, usr_id, note, imp_key) select distinct c.cat_id, cast(replace(i.value, ',', '.') as DECIMAL(10,2)) as value, case when cast(replace(i.value, ',', '.') as DECIMAL(10,2)) > 0 then 'P' else 'N' end as type, STR_TO_DATE(i.dat_mov, '%d/%m/%Y') as dat_mov, i.usr_mov, 'IMP_MASS', i.description, i.imp_key from importcsv i join category c on lower(i.description) like lower(concat('%',trim(SPLIT_STR(c.keyword, ',', n_key)),'%')) and c.cat_id = n_cat and i.mov_imp <> 'S' and not exists (select 1 from movement mm where mm.cat_id = c.cat_id and mm.dat_mov = STR_TO_DATE(i.dat_mov, '%d/%m/%Y') and mm.val = cast(replace(i.value, ',', '.') as DECIMAL(10,2)));  update importcsv i set i.mov_imp = 'S' where i.imp_key in (select imp_key from movement);  commit;  SET n_key = n_key +1;  select ifnull(trim(SPLIT_STR(c.keyword, ',', n_key)), '') into v_key from category c where c.cat_id = n_cat; END WHILE; END LOOP; END// DELIMITER ;
DELIMITER // CREATE FUNCTION `SPLIT_STR`(x VARCHAR(255), delim VARCHAR(12), pos INT ) RETURNS varchar(255) CHARSET utf8 RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos), CHAR_LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1), delim, '')// DELIMITER ;