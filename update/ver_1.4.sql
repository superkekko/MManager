create table importcsv (imp_key INT(10) NOT NULL AUTO_INCREMENT, dat_mov varchar(100) not null, description varchar(1000) not null, value varchar(100) not null, usr_mov varchar(20) not null, mov_imp char(1)not null default '', PRIMARY KEY (imp_key))AUTO_INCREMENT=1;
insert into mversion (db) values ('1.4');