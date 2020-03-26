CREATE TABLE pages
(
    tx_twevents_layout_past    varchar(255) DEFAULT '' NOT NULL,
    tx_twevents_layout_running varchar(255) DEFAULT '' NOT NULL,
    tx_twevents_layout_future  varchar(255) DEFAULT '' NOT NULL,
);

CREATE TABLE tt_content
(
    tx_twevents_slug   varchar(255) DEFAULT ''  NOT NULL,
    tx_twevents_stages int(11)      DEFAULT '7' NOT NULL,
);

#
# Table structure for table 'tx_twevents_domain_model_organization'
#
CREATE TABLE tx_twevents_domain_model_organization
(

    name           varchar(255)     DEFAULT ''  NOT NULL,
    slug           varchar(255)     DEFAULT ''  NOT NULL,
    label          varchar(255)     DEFAULT ''  NOT NULL,
    street_address text             DEFAULT ''  NOT NULL,
    postal_code    varchar(255)     DEFAULT ''  NOT NULL,
    locality       varchar(255)     DEFAULT ''  NOT NULL,
    region         varchar(255)     DEFAULT ''  NOT NULL,
    country        int(11)          DEFAULT '0' NOT NULL,
    longitude      DECIMAL(19, 16)  DEFAULT '0' NOT NULL,
    latitude       DECIMAL(19, 16)  DEFAULT '0' NOT NULL,
    map_url        text             DEFAULT ''  NOT NULL,
    website        varchar(255)     DEFAULT ''  NOT NULL,
    twitter        varchar(255)     DEFAULT ''  NOT NULL,
    facebook       varchar(255)     DEFAULT ''  NOT NULL,
    email          varchar(255)     DEFAULT ''  NOT NULL,
    phone          varchar(255)     DEFAULT ''  NOT NULL,
    logo           int(11) unsigned             NOT NULL default '0',
    photos         int(11) unsigned DEFAULT '0' NOT NULL,
    description    text,
    categories     int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_twevents_domain_model_event'
#
CREATE TABLE tx_twevents_domain_model_event
(

    name                 varchar(255)     DEFAULT ''  NOT NULL,
    slug                 varchar(255)     DEFAULT ''  NOT NULL,
    event_start          datetime         DEFAULT NULL,
    event_end            datetime         DEFAULT NULL,
    attendance_mode      tinyint(4)       DEFAULT '0' NOT NULL,
    status               tinyint(4)       DEFAULT '0' NOT NULL,
    summary              varchar(255)     DEFAULT ''  NOT NULL,
    description          text,
    location_description text,
    ticket_description   text,
    ticket_url           varchar(255)     DEFAULT ''  NOT NULL,
    facebook             varchar(255)     DEFAULT ''  NOT NULL,
    colloq               varchar(255)     DEFAULT ''  NOT NULL,
    website              varchar(255)     DEFAULT ''  NOT NULL,
    hashtag              varchar(255)     DEFAULT ''  NOT NULL,
    livestream_url       varchar(255)     DEFAULT ''  NOT NULL,
    livestream_embed     varchar(255)     DEFAULT ''  NOT NULL,
    livestream_start     datetime         DEFAULT NULL,
    livestream_end       datetime         DEFAULT NULL,
    downloads            int(11) unsigned DEFAULT '0' NOT NULL,
    location             int(11) unsigned DEFAULT '0',
    sponsors             int(11) unsigned DEFAULT '0' NOT NULL,
    organizers           int(11) unsigned DEFAULT '0' NOT NULL,
    presentations        int(11) unsigned DEFAULT '0' NOT NULL,
    coverage             int(11) unsigned DEFAULT '0' NOT NULL,
    categories           int(11) unsigned DEFAULT '0' NOT NULL,
    page                 int(11) unsigned
);

#
# Table structure for table 'tx_twevents_domain_model_person'
#
CREATE TABLE tx_twevents_domain_model_person
(

    given_name  varchar(255)     DEFAULT ''  NOT NULL,
    family_name varchar(255)     DEFAULT ''  NOT NULL,
    slug        varchar(255)     DEFAULT ''  NOT NULL,
    photo       int(11) unsigned             NOT NULL default '0',
    summary     text,
    biography   text,
    teaser      text,
    website     varchar(255)     DEFAULT ''  NOT NULL,
    twitter     varchar(255)     DEFAULT ''  NOT NULL,
    github      varchar(255)     DEFAULT ''  NOT NULL,
    facebook    varchar(255)     DEFAULT ''  NOT NULL,
    email       varchar(255)     DEFAULT ''  NOT NULL,
    phone       varchar(255)     DEFAULT ''  NOT NULL,
    categories  int(11) unsigned DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_twevents_domain_model_sponsor'
#
CREATE TABLE tx_twevents_domain_model_sponsor
(
    label        varchar(255)     DEFAULT ''  NOT NULL,
    event        int(11) unsigned DEFAULT '0' NOT NULL,
    organization int(11) unsigned DEFAULT '0',
    type         int(11) unsigned DEFAULT '0' NOT NULL,
    sorting      int(11) unsigned DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_twevents_domain_model_coverage'
#
CREATE TABLE tx_twevents_domain_model_coverage
(

    event        int(11) unsigned DEFAULT '0' NOT NULL,
    presentation int(11) unsigned DEFAULT '0' NOT NULL,

    title        varchar(255)     DEFAULT ''  NOT NULL,
    type         int(11)          DEFAULT '0' NOT NULL,
    url          varchar(255)     DEFAULT ''  NOT NULL,
    author       int(11)          DEFAULT '0' NOT NULL,
    author_name  varchar(255)     DEFAULT ''  NOT NULL,
    sorting      int(11)          DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_twevents_domain_model_note'
#
CREATE TABLE tx_twevents_domain_model_note
(
    presentation int(11) unsigned DEFAULT '0' NOT NULL,

    author       int(11)          DEFAULT '0' NOT NULL,
    author_name  varchar(255)     DEFAULT ''  NOT NULL,
    content      text,
    sorting      int(11)          DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_twevents_domain_model_presentation'
#
CREATE TABLE tx_twevents_domain_model_presentation
(

    event       int(11) unsigned DEFAULT '0' NOT NULL,

    title       varchar(255)     DEFAULT ''  NOT NULL,
    slug        varchar(255)     DEFAULT ''  NOT NULL,
    type        int(11)          DEFAULT '0' NOT NULL,
    summary     text,
    description text,
    hashtag     varchar(255)     DEFAULT ''  NOT NULL,
    start       datetime         DEFAULT NULL,
    duration    int(11)          DEFAULT '0' NOT NULL,
    performers  int(11) unsigned DEFAULT '0' NOT NULL,
    coverage    int(11) unsigned DEFAULT '0' NOT NULL,
    note        int(11) unsigned DEFAULT '0' NOT NULL,
    categories  int(11) unsigned DEFAULT '0' NOT NULL,
    video       int(11) unsigned DEFAULT '0' NOT NULL,
    page        int(11) unsigned,
);

#
# Table structure for table 'tx_twevents_event_person_mm'
#
CREATE TABLE tx_twevents_event_person_mm
(
    uid_local       int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign     int(11) unsigned DEFAULT '0' NOT NULL,
    sorting         int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid_local, uid_foreign),
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_twevents_presentation_person_mm'
#
CREATE TABLE tx_twevents_presentation_person_mm
(
    uid_local       int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign     int(11) unsigned DEFAULT '0' NOT NULL,
    sorting         int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid_local, uid_foreign),
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);
