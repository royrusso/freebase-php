freebase-php
============

Sample PHP script that reads Person data via the FreeBase.com API and builds a local Person object.

The script takes a Person name and calls the FreeBase REST service for a summary of that Person, if found.

The domain object (Person), consists of only parts of the entire Person object returned:

Helpful Links
============
You need a 
The Freebase API documentation can be found here: https://developers.google.com/freebase/


```php
$Person = array(
'FBNAME' => // name as it exists in the FreeBase DB,
'FBID' => // Freebase ID,
'SUMMARY' => // short paragraph summarizing the Person,
'DOB' => // Date of Birth,
'DOD' => //Date of Death,
'TYPES' => //,
'IMAGES' => $imgArr,
'AKA' => $akaArr
```


I originally wrote a version of this script to extract Author data for my site, http://www.worldofquotes.com.
