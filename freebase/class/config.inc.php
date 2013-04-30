<?php

class Config
{
    function Config()
    {
        $this->cfg = array
        (
            'FREEBASE_API_KEY' => 'ENTER_YOUR_KEY_HERE',
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