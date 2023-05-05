-- create database
create database `Web_development_bike_shop`;

-- use database
USE `Web_development_bike_shop`;

-- drop database`Web_development_bike_shop`;
-- drop table `user`;
-- drop table `order`;
-- drop table order_detail;
-- drop table product;
-- drop table transaction;

-- create table user
CREATE TABLE `User`(
	`user_id` int not null auto_increment primary key,
    `name` varchar(255) not null,
    `email_address` varchar(255) not null,
    `password` CHAR(128) not null,
    `user_roll` int not null,
    `registration` datetime not null
);

create table `Order` (
	`order_id` int not null auto_increment primary key,
    `user_id` int not null,
    `name` varchar(255) null,
    `email_address` varchar(255) not null,
    `created` datetime not null
);

create table `Order_detail` (
	`order_detail_id` int not null auto_increment primary key,
    `order_id` int not null,
    `product_id` int not null,
    `amount` int not null
);

create table `Product` (
	`product_id` int not null auto_increment primary key,
    `price` float not null,
    `name` varchar(255) not null,
    `image` varchar(255) null,
    `description` varchar(500) null
);

create table `transaction` (
	`transaction_id` int not null auto_increment primary key,
    `amount` float not null,
    `user_id` int not null,
    `created` datetime not null,
    `order_id` int not null, 
    `status` varchar(50) not null
);


create table `Jwt_token` (
	`jwt_id` int not null auto_increment primary key,
    `token` varchar(255) not null
);

Insert into `User` values
(1, 'Bob','bob@mail.com', 'a87d39824d58b1e934e2a7d8d06931948fc0d0f37194ef90908477d864ba6eee8f27d8f3c49097108f1d59bc68e1e7acd8a21f2224cbb6c2df9ed1d68c00e076', 1, '2021-03-20 15:00'),
(2, 'James','james@mail.com', '150ecd566852903d94357fe24fc782add2b8a9b8342d1e87ea18c9d818187d4c8cf08c33d8fe53539291a7786c86f8a65840a723140fac6dde7514b9deade1ea', 2, '2021-04-20 15:00');
-- Bob FE$6346j2mwfgw
-- James F$%Y$#1sdqe
-- 0 normal user
-- 1 admin user
-- 2 super admin user

insert into `order` values
(1, 1, 'Bob', 'bob@mail.com', '2021-03-20 15:05'),
(2, 1, 'Bob', 'bob@mail.com', '2021-03-21 15:05'),
(3, 2, 'James', 'james@mail.com', '2021-03-21 15:05');

insert into `order_detail` values
(1, 1, 1, 2),
(2, 1, 3, 1),
(3, 1, 4, 2),
(4, 2, 1, 5),
(5, 2, 5, 10),
(6, 3, 2, 2),
(7, 3, 3, 7);

insert into `product` values
(1, 30.0, 'Zadel', 'leren-zadel', 'Heb je ook wel eens last van schurende benen of een harde zit na een lange rit? Zit je zadel weer vol water of ben je constant aan het zweten? Daar heeft Deso het Comfortabel Gel Fietszadel op bedacht! Hiermee ben je verzekerd van een lekkere zachte en brede zit. Bij dit model draait het allemaal om comfortabel fietsen, daarom kun jij met dit zadel voortaan altijd de fiets pakken!'),
(2, 200.0, 'Oma fiets', 'grote-oma-fiets', 'Deze prachtige 28 inch omafiets heeft een framemaat van 57 cm. Het stuur en de zadel zijn in hoogte verstelbaar. De fiets is voorzien van dubbelwandige aluminium velgen, een bagagedrager en een terugtraprem. De kettingkast is gesloten met een lakdoek. Inclusief jasbeschermers, zijstandaard en bel. De fiets is voorzien van LED-verlichting (d.m.v. batterij). '),
(3, 40.0, 'Fiets stuur groot', 'fiets-stuur-klein', 'Zeer comfortabel trekking stuur met een 25,4 mm stuurpenklemdiameter. Specificaties: Stuurklem diameter: 25,4 mm Breedte: 550 mm Dikte: 1,5 mm Handvatlengte: 140 mm Verhoging: 72 mm Terugliggend: 68° Materiaal: Staal Veiligheidslevel: 3 Gewicht: 615 gram '),
(4, 15.0, 'Fiets bel klein', 'fiets-bel-groot', 'Met onze fietsbel kunt u in een paar seconden uw fiets of e-step opsporen dankzij de Apple airtag. Die u makkelijk kunt verbergen in de fietsbel. Niemand zal het merken dat uw fiets/ e-step getrackt kan worden. Het monteren van de fietsbel doet u in 1 minuut. Met onze meegeleverde schroevendraaier. U moet zich ook geen zorgen maken om uw Airtag omdat onze fietsbel waterdicht is. Dus fietsen in de regen is geen enkel probleem.'),
(5, 25.0, 'Fiets ketting groot', 'fiets-ketting', 'Corrosiebestendige coating voor een langere levensduur Productspecificaties: Groep: NEXUS Model: CN-NX10 Type: 1/2 "x 1/8" Versnellingen voor: 1-speed Versnellingen achter: 1-speed SIL-TEC-coating: Nee. Toepassingsgebied: Stad / Comfort Gewicht: ca. 364 g (114 schakels) Verbinding: kettingpen.');

insert into `transaction` values
(1, 130, 1, '2021-03-20 15:05', 1, 'completed'),
(2, 400, 1, '2021-03-21 15:05', 2, 'refunded'),
(3, 680, 2, '2021-03-20 15:05', 3, 'pending');

select * from order_detail;
select * from `product`;
select * from `user`; 

select `order`.`order_id` as id, `order`.`user_id`, `order`.`name`, `order`.`email_address`, `order`.`created`, `order_detail`.`order_detail_id`, `order_detail`.`product_id`, `product`.`name` as product_name, `order_detail`.`amount`, `product`.`price` 
from `order`
left join `order_detail` on `order`.`order_id` = `order_detail`.`order_id`
left join `product` on `product`.`product_id` = `order_detail`.`product_id`
where `order`.`user_id` = 1 LIMIT 5 OFFSET 0;

select * from `product` where `product`.`product_id`; 

SELECT `transaction`.`transaction_id` as id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` from `transaction` left join `user` on `transaction`.`user_id` = `user`.`user_id` where `transaction`.`transaction_id` = 1 and `transaction`.`user_id` = 1;

SELECT `transaction`.`transaction_id` as id, `transaction`.`amount`, `transaction`.`user_id`, `user`.`name`, `transaction`.`created`, `transaction`.`order_id`, `transaction`.`status` from `transaction`
left join `user` on `transaction`.`user_id` = `user`.`user_id` where `transaction`.`user_id` = 1;

SELECT `user`.`user_id`, `user`;
