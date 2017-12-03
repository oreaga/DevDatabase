CREATE TABLE Videos (
    guid varchar(255),
    runningTime int,
    format varchar(255),
    author varchar(255),
    dateModified int,
    description varchar(1024),
    url varchar(1024)
);

CREATE TABLE Images (
    guid varchar(255),
    imageWidth int,
    imageHeight int,
    format varchar(255),
    author varchar(255),
    dateModified int,
    description varchar(1024),
    url varchar(1024)
);

CREATE TABLE Audio (
    guid varchar(255),
    runningTime int,
    format varchar(255),
    author varchar(255),
    dateModified int,
    description varchar(1024),
    url varchar(1024)
);

CREATE TABLE Documents (
    guid varchar(255),
    docFormat varchar(255),
    author varchar(255),
    dateModified int,
    description varchar(1024),
    url varchar(1024)
);

CREATE TABLE ParentChild (
    pid varchar(255),
    cid varchar(255)
);

CREATE TABLE ParentTitle (
    pid varchar(255),
    title varchar(1024)
);

CREATE TABLE ChildType (
    cid varchar(255),
    type varchar(255)
);
