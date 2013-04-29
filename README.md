freebase-php
============

Sample PHP script that reads People/Person data via the FreeBase.com API and builds a local Person object.

The domain object (Person), consists of only parts of the entire Person object returned:

$Person = array(
'FBNAME' => // name as it exists in the FreeBase DB,
'FBID' => // Freebase ID,
'SUMMARY' => // short paragraph summarizing the Person,
'DOB' => // Date of Birth,
'DOD' => //Date of Death,
'TYPES' => //,
'IMAGES' => $imgArr,
'AKA' => $akaArr

The Freebase API documentation can be found here: https://developers.google.com/freebase/

I originally wrote a version of this script to extract Author data for my site, http://www.worldofquotes.com.
