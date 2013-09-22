-- copying clients from stock_orders to  clients table
insert into clients(name, email, broker) select distinct customer, 'no-email@email.com', broker from stock_orders

-- changing customer name to id
update stock_orders s set customer = (select id from clients c where c.name = customer and c.broker = s.broker)

-- changing customer column type from varchar to int
alter table stock_orders modify customer int
