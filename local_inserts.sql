<<<<<<< HEAD
<<<<<<< HEAD
INSERT INTO ParentTitle (pid, title) VALUES ("ac0ab955-1aaf-43dd-a332-5c6419629608","testdir");
INSERT INTO Images (guid, imageWidth, imageHeight, format, author, dateModified, description, url) VALUES ("29b5e537-4889-4213-b1b3-63252bd1942c",null,null,"png","Sahana",20171206,"null","./testdir\hello.png");
INSERT INTO ParentChild (pid, cid) VALUES ("ac0ab955-1aaf-43dd-a332-5c6419629608","29b5e537-4889-4213-b1b3-63252bd1942c");
INSERT INTO ChildType (cid, type) VALUES ("29b5e537-4889-4213-b1b3-63252bd1942c","images");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("c46ed046-3486-492e-8223-5045cda9e0d6","txt","Sahana",20171206,"null","./testdir\hello.txt");
INSERT INTO ParentChild (pid, cid) VALUES ("ac0ab955-1aaf-43dd-a332-5c6419629608","c46ed046-3486-492e-8223-5045cda9e0d6");
INSERT INTO ChildType (cid, type) VALUES ("c46ed046-3486-492e-8223-5045cda9e0d6","documents");
INSERT INTO Audio (guid, runningTime, format, author, dateModified, description, url) VALUES ("21d81b23-8721-4075-af9b-239071bdedaf",null,"wav","Sahana",20171206,"null","./testdir\hello.wav");
INSERT INTO ParentChild (pid, cid) VALUES ("ac0ab955-1aaf-43dd-a332-5c6419629608","21d81b23-8721-4075-af9b-239071bdedaf");
INSERT INTO ChildType (cid, type) VALUES ("21d81b23-8721-4075-af9b-239071bdedaf","audio");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("d31fcee8-177c-4b90-b186-019ea537aaea","html","Sahana",20171206,"null","./testdir\hello1.html");
INSERT INTO ParentChild (pid, cid) VALUES ("ac0ab955-1aaf-43dd-a332-5c6419629608","d31fcee8-177c-4b90-b186-019ea537aaea");
INSERT INTO ChildType (cid, type) VALUES ("d31fcee8-177c-4b90-b186-019ea537aaea","documents");
INSERT INTO Audio (guid, runningTime, format, author, dateModified, description, url) VALUES ("1e56daa2-c011-4086-8f39-de56d841bbac",null,"mp3","Sahana",20171206,"null","./testdir\hello1.mp3");
INSERT INTO ParentChild (pid, cid) VALUES ("ac0ab955-1aaf-43dd-a332-5c6419629608","1e56daa2-c011-4086-8f39-de56d841bbac");
INSERT INTO ChildType (cid, type) VALUES ("1e56daa2-c011-4086-8f39-de56d841bbac","audio");
INSERT INTO ParentChild (pid, cid) VALUES ("ac0ab955-1aaf-43dd-a332-5c6419629608","9f32860c-408b-4f13-af4b-e2184c0a84d8");
INSERT INTO ChildType (cid, type) VALUES ("9f32860c-408b-4f13-af4b-e2184c0a84d8","Directory");
INSERT INTO ParentTitle (pid, title) VALUES ("01d6b951-a131-4b70-a0e9-c8590f7b4d36","testdir\test2");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("82f7f012-1fb5-4452-a1ab-ccc3f4f51cf9","doc","Sahana",20171206,"null","./testdir\test2\hello2.doc");
INSERT INTO ParentChild (pid, cid) VALUES ("01d6b951-a131-4b70-a0e9-c8590f7b4d36","82f7f012-1fb5-4452-a1ab-ccc3f4f51cf9");
INSERT INTO ChildType (cid, type) VALUES ("82f7f012-1fb5-4452-a1ab-ccc3f4f51cf9","documents");
INSERT INTO Images (guid, imageWidth, imageHeight, format, author, dateModified, description, url) VALUES ("49b7b0c0-5881-4469-bad0-2a5af621f18d",null,null,"gif","Sahana",20171206,"null","./testdir\test2\hello2.gif");
INSERT INTO ParentChild (pid, cid) VALUES ("01d6b951-a131-4b70-a0e9-c8590f7b4d36","49b7b0c0-5881-4469-bad0-2a5af621f18d");
INSERT INTO ChildType (cid, type) VALUES ("49b7b0c0-5881-4469-bad0-2a5af621f18d","images");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("11592207-88d2-4e99-9a0e-c784197f0d0e","html","Sahana",20171206,"null","./testdir\test2\hello2.html");
INSERT INTO ParentChild (pid, cid) VALUES ("01d6b951-a131-4b70-a0e9-c8590f7b4d36","11592207-88d2-4e99-9a0e-c784197f0d0e");
INSERT INTO ChildType (cid, type) VALUES ("11592207-88d2-4e99-9a0e-c784197f0d0e","documents");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("1943ae0f-0f20-432e-b5b7-6e37ecdce165","txt","Sahana",20171206,"null","./testdir\test2\hello2.txt");
INSERT INTO ParentChild (pid, cid) VALUES ("01d6b951-a131-4b70-a0e9-c8590f7b4d36","1943ae0f-0f20-432e-b5b7-6e37ecdce165");
INSERT INTO ChildType (cid, type) VALUES ("1943ae0f-0f20-432e-b5b7-6e37ecdce165","documents");
=======
=======
>>>>>>> e4b48bea91f7a2e97c76fd2ce18e148109799795
INSERT INTO ParentTitle (pid, title) VALUES ("c8fa1ac5-cd14-4e69-aab7-2158a8ee4895","testdir");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("351961a9-dc01-47da-8a4f-0fe0b98ccc53","txt","doreagan",20171205,"hello.txt","testdir/hello.txt");
INSERT INTO ParentChild (pid, cid) VALUES ("c8fa1ac5-cd14-4e69-aab7-2158a8ee4895","351961a9-dc01-47da-8a4f-0fe0b98ccc53");
INSERT INTO ChildType (cid, type) VALUES ("351961a9-dc01-47da-8a4f-0fe0b98ccc53","documents");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("dcce7d73-9855-4fea-9173-99b0c62cf5c3","html","doreagan",20171205,"hello1.html","testdir/hello1.html");
INSERT INTO ParentChild (pid, cid) VALUES ("c8fa1ac5-cd14-4e69-aab7-2158a8ee4895","dcce7d73-9855-4fea-9173-99b0c62cf5c3");
INSERT INTO ChildType (cid, type) VALUES ("dcce7d73-9855-4fea-9173-99b0c62cf5c3","documents");
INSERT INTO Images (guid, imageWidth, imageHeight, format, author, dateModified, description, url) VALUES ("5a9d0984-f021-40ee-a374-6287ce455969",null,null,"png","doreagan",20171205,"hello.png","testdir/hello.png");
INSERT INTO ParentChild (pid, cid) VALUES ("c8fa1ac5-cd14-4e69-aab7-2158a8ee4895","5a9d0984-f021-40ee-a374-6287ce455969");
INSERT INTO ChildType (cid, type) VALUES ("5a9d0984-f021-40ee-a374-6287ce455969","images");
INSERT INTO Audio (guid, runningTime, format, author, dateModified, description, url) VALUES ("90e9d523-7778-40e3-9221-9aedb08ae48a",null,"mp3","doreagan",20171205,"hello1.mp3","testdir/hello1.mp3");
INSERT INTO ParentChild (pid, cid) VALUES ("c8fa1ac5-cd14-4e69-aab7-2158a8ee4895","90e9d523-7778-40e3-9221-9aedb08ae48a");
INSERT INTO ChildType (cid, type) VALUES ("90e9d523-7778-40e3-9221-9aedb08ae48a","audio");
INSERT INTO Audio (guid, runningTime, format, author, dateModified, description, url) VALUES ("2ba601db-ed58-4b39-94e0-1a48e5188455",null,"wav","doreagan",20171205,"hello.wav","testdir/hello.wav");
INSERT INTO ParentChild (pid, cid) VALUES ("c8fa1ac5-cd14-4e69-aab7-2158a8ee4895","2ba601db-ed58-4b39-94e0-1a48e5188455");
INSERT INTO ChildType (cid, type) VALUES ("2ba601db-ed58-4b39-94e0-1a48e5188455","audio");
INSERT INTO ParentChild (pid, cid) VALUES ("c8fa1ac5-cd14-4e69-aab7-2158a8ee4895","6073a2bb-82d4-4969-8552-18e1f4b37bee");
INSERT INTO ChildType (cid, type) VALUES ("6073a2bb-82d4-4969-8552-18e1f4b37bee","Directory");
INSERT INTO ParentTitle (pid, title) VALUES ("6073a2bb-82d4-4969-8552-18e1f4b37bee","test2");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("60d189fc-155b-4d75-ae51-30c0e882b224","txt","doreagan",20171205,"hello2.txt","testdir/test2/hello2.txt");
INSERT INTO ParentChild (pid, cid) VALUES ("6073a2bb-82d4-4969-8552-18e1f4b37bee","60d189fc-155b-4d75-ae51-30c0e882b224");
INSERT INTO ChildType (cid, type) VALUES ("60d189fc-155b-4d75-ae51-30c0e882b224","documents");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("645cdc1a-89f9-4264-95fc-65afa96757cc","html","doreagan",20171205,"hello2.html","testdir/test2/hello2.html");
INSERT INTO ParentChild (pid, cid) VALUES ("6073a2bb-82d4-4969-8552-18e1f4b37bee","645cdc1a-89f9-4264-95fc-65afa96757cc");
INSERT INTO ChildType (cid, type) VALUES ("645cdc1a-89f9-4264-95fc-65afa96757cc","documents");
INSERT INTO Images (guid, imageWidth, imageHeight, format, author, dateModified, description, url) VALUES ("72a19698-4086-4c95-ab7d-384456aa7364",null,null,"gif","doreagan",20171205,"hello2.gif","testdir/test2/hello2.gif");
INSERT INTO ParentChild (pid, cid) VALUES ("6073a2bb-82d4-4969-8552-18e1f4b37bee","72a19698-4086-4c95-ab7d-384456aa7364");
INSERT INTO ChildType (cid, type) VALUES ("72a19698-4086-4c95-ab7d-384456aa7364","images");
INSERT INTO Documents (guid, docFormat, author, dateModified, description, url) VALUES ("beb869f1-98f9-4e9e-8d31-edef20e9ded3","doc","doreagan",20171205,"hello2.doc","testdir/test2/hello2.doc");
INSERT INTO ParentChild (pid, cid) VALUES ("6073a2bb-82d4-4969-8552-18e1f4b37bee","beb869f1-98f9-4e9e-8d31-edef20e9ded3");
INSERT INTO ChildType (cid, type) VALUES ("beb869f1-98f9-4e9e-8d31-edef20e9ded3","documents");
<<<<<<< HEAD
>>>>>>> e4b48bea91f7a2e97c76fd2ce18e148109799795
=======
>>>>>>> e4b48bea91f7a2e97c76fd2ce18e148109799795
