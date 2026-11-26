# TYPO3 derives the columns from the TCA; these differ from what it would derive.

CREATE TABLE tx_twevents_event (
	# An IANA time zone identifier, not free text
	timezone varchar(64) DEFAULT 'Europe/Berlin' NOT NULL,
	# A language tag of BCP 47, such as "en"
	main_language varchar(35) DEFAULT 'en' NOT NULL,
	# The link to register shows unless an editor holds it back
	show_ticket_link smallint unsigned DEFAULT '1' NOT NULL,
	# The uid of a single page, not a list
	page int(10) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_twevents_session (
	# A language tag of BCP 47, such as "de", or empty for the language of the event
	language varchar(35) DEFAULT '' NOT NULL
);

CREATE TABLE tx_twevents_organization (
	# Six decimal places locate a building, two only a town
	latitude decimal(9,6) DEFAULT NULL,
	longitude decimal(9,6) DEFAULT NULL
);
