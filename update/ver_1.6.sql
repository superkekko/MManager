CREATE or replace VIEW total AS select c.cat_id AS cat_id, concat('#', c.color) AS color, c.cat_name AS cat_name, (case when (c.cat_id <> c.parent_id) then c.parent_id else 0 end) AS parent_id, (case when (c.cat_id <> c.parent_id) then cc.cat_name else '' end) AS parent_cat, m.type AS type, m.dat_mov AS dat_mov, concat('#', u.color) AS usr_color, m.usr_mov AS usr_mov, m.val AS val from (((movement m join category c ON ((m.cat_id = c.cat_id))) join category cc ON ((c.parent_id = cc.cat_id))) join user u ON ((u.usr_id = m.usr_mov)))
drop view tot_usr
drop view tot_eu_month
drop view tot_eu_month_tmp
drop view tot_eu
drop view tot_cat_date
drop view tot_cat
drop view tot
insert into mversion (db) values ('1.6');