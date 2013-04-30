<?php
/**************************************************************************
Copyright 2013 Roy Russo

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Latest Builds: https://github.com/royrusso
 **************************************************************************/

class Config
{
    /**************************************
     * Feel free to edit these values.
     *************************************/
    function Config()
    {
        $this->cfg = array
        (
            'FREEBASE_API_KEY' => '',
            'FREEBASE_SERVICE_URL' => 'https://www.googleapis.com/freebase/v1/search'
        );
    }

    /****************************************************
     * Retrieves a specific param from the hash          *
     ****************************************************/
    function param($paramName = "")
    {
        return ($this->cfg[$paramName]);
    }

}

?>