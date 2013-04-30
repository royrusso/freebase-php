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
include_once("class/config.inc.php");

$param = $_POST['q'];
if (!empty($param)) {
    $proc = new Query();
    $proc->callFreeBase($param);
} else {
    echo 'No Input Found';
    return;
}


class Query
{
    var $cfg;

    function Query()
    {
        $this->cfg = new Config();
    }

    function callFreeBase($param)
    {
        $params = array(
            'filter' => '(all name:"' . $param . '" type:"/people/person")',
            //'filter' => '(all name:"Ronald Reagan" type:"/people/person")',
            'lang' => 'en',
            'type' => '/people/person',
            'key' => $this->cfg->param('FREEBASE_API_KEY')
        );
        $url = $this->cfg->param('FREEBASE_SERVICE_URL') . '?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $results = curl_exec($ch);
        if ($results === FALSE) {
            echo(curl_error($ch));
            return array();
        }
        curl_close($ch);

        $response = json_decode($results, true);

        $id = $response['result'][0]['id'];

        $params = array('key' => $this->apikey);
        $service_url = 'https://www.googleapis.com/freebase/v1/topic';
        $url = $service_url . $id . '?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $results = curl_exec($ch);
        if ($results === FALSE) {
            echo(curl_error($ch));
            return array();
        }
        curl_close($ch);
        $searchResults = $results;
        $response = json_decode($results, true);

        // /common/topic/description
        $fbid = $response['id'];
        if (empty($fbid)) {
            echo "\nNothing to log...\n\n";
            return;
        }
        $fbname = $response['property']['/type/object/name']['values'][0]['value'];
        $summary = $response['property']['/common/topic/description']['values'][0]['value'];
        // /people/person/date_of_birth
        $birth = $response['property']['/people/person/date_of_birth']['values'][0]['value'];
        $death = $response['property']['/people/deceased_person/date_of_death']['values'][0]['value'];
        // /common/topic/notable_for
        $firstType = $response['property']['/common/topic/notable_for']['values'][0]['text'];
        $firstTypeID = $response['property']['/common/topic/notable_for']['values'][0]['id'];
        $rawtypesArr = $response['property']['/common/topic/notable_types']['values'];
        $rawakaArr = $response['property']['/common/topic/alias']['values'];
        $rawimgArr = $response['property']['/common/topic/image']['values'];

        $typesArr = array();
        if (!empty($rawtypesArr)) {
            foreach ($rawtypesArr as $type) {
                array_push($typesArr, array('TEXT' => $type['text'], 'ID' => $type['id']));
            }
        }
        if (!empty($firstType)) {
            array_push($typesArr, array('TEXT' => $firstType, 'ID' => $firstTypeID));
        }

        $akaArr = array();
        if (!empty($rawakaArr)) {
            foreach ($rawakaArr as $aka) {
                array_push($akaArr, array('TEXT' => $aka['text']));
            }
        }

        $imgArr = array();
        if (!empty($rawimgArr)) {
            foreach ($rawimgArr as $image) {
                array_push($imgArr, array('TEXT' => $image['text'], 'ID' => $image['id']));
                // https://usercontent.googleapis.com/freebase/v1/image/m/02bhspn
                // https://usercontent.googleapis.com/freebase/v1/image/m/02nqg_h
            }
        }
        $authorArr = array(
            'FREEBASENAME' => $fbname,
            'FREEBASEID' => $fbid,
            'SUMMARY' => $summary,
            'DOB' => $birth,
            'DOD' => $death,
            'TYPES' => $typesArr,
            'IMAGES' => $imgArr,
            'ALSOKNOWNAS' => $akaArr
        );

        $arrJSON = json_encode($authorArr);
        $out = '<ul class="nav nav-tabs" id="resultsTab">';
        $out .= '<li class="active"><a href="#clean">Cleansed Result</a></li>';
        $out .= '<li><a href="#person">JSON Person</a></li>';
        $out .= '<li><a href="#search">JSON Search</a></li></ul>';
        $out .= '<div class="tab-content">';
        $out .= '<div class="tab-pane active" id="clean"><textarea class="field span12" rows="20">Person Array:' . "\n\n" . $this->indent($arrJSON) . '</textarea></div>';
        $out .= '<div class="tab-pane" id="person"><textarea  class="field span12" rows="20">Complete Person JSON:' . "\n\n" . $this->indent($results) . '</textarea></div>';
        $out .= '<div class="tab-pane" id="search"><textarea class="field span12" rows="20">Person Search Results:' . "\n\n" . $this->indent($searchResults) . '</textarea></div>';
        $out .= '</div>';
        $out .= '<script>';

        $out .= '$("#resultsTab").on("click", "a", function(e){';
        $out .= 'e.preventDefault();';
        $out .= "$(this).tab('show')";
        $out .= '});';
        echo $out;
        return;

    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     *
     * @param string $json The original JSON string to process.
     *
     * @return string Indented version of the original JSON string.
     */
    function indent($json)
    {

        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '  ';
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;

        for ($i = 0; $i <= $strLen; $i++) {

// Grab the next character in the string.
            $char = substr($json, $i, 1);

// Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

// If this character is the end of an element,
// output a new line and indent the next line.
            } else if (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

// Add the character to the result string.
            $result .= $char;

// If the last character was the beginning of an element,
// output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return $result;
    }
}

?>