
Query 1
INSERT INTO clients (clientFirstname, clientLastname, clientEmail, clientPassword, clientLevel, comment) Values ('Tony', 'Stark', 'tony@starkent.com', 'IamIronM@n', 1, 'I am the real Ironman');

Query 2
UPDATE clients SET ClientLevel = 3 WHERE clientFirstname = 'Tony';

Query 3
UPDATE inventory
SET    invDescription = replace(invDescription, 'small interior', 'spacious interior')
WHERE  invModel LIKE 'Hummer';

Query 4
SELECT *
FROM inventory 
INNER JOIN carclassification ON inventory.classificationId = carclassification.classificationId
WHERE carclassification.classificationName = 'SUV';

Query 5
DELETE FROM inventory WHERE invMake = 'Jeep' AND invModel = 'Wrangler';

Query 6
UPDATE inventory SET invImage=CONCAT('/phpmotors',invImage), invThumbnail = CONCAT('/phpmotors', invThumbnail);