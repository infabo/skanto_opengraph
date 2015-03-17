create table pages (
	tx_skantoopengraph_title tinytext,
	tx_skantoopengraph_description text,
);

create table pages_language_overlay (
	tx_skantoopengraph_title tinytext,
	tx_skantoopengraph_description text,
);

create table tt_content (
	tx_skantoopengraph_exclude tinyint(4) default '0' NOT NULL,
);
