<?php

function external_site_parser_discogs ($discogs_url, $discogs_artist_pref, $debug_output = False){

    //initialise array (for any fetched data)
    $trackArray = array();

    if (isset($discogs_url) and strlen(trim($discogs_url)) > 0) { // we have a request to work with

        if ($debug_output and strlen(trim($discogs_url)) > 0) {
            echo "<h3>Discogs Data</h3>";
            echo "<p>\n";
            echo 'discogs_url: <a href="'.$discogs_url.'">'.$discogs_url.'</a><br>'."\n";
            echo "discogs_artist_pref: $discogs_artist_pref <br>\n";
            echo "</p>\n";

        }

        ////////////////////////////////////////////////////////////////////////////
        // Stolen from https://www.exeideas.com/2020/07/parse-webpage-to-extract-content-using-php.html
        // Give URL to fetch (needs to come from form)
        ////////////////////////////////////////////////////////////////////////////

        $webPageURL = $discogs_url;

        ////////////////////////////////////////////////////////////////////////////
        // Garb The WebPage Content
        ////////////////////////////////////////////////////////////////////////////

        $ch = curl_init();
        $timeout = 5; // 5 is seconds
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
        curl_setopt($ch, CURLOPT_URL, $webPageURL);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $webPageContent = curl_exec($ch);
        curl_close($ch);

        ////////////////////////////////////////////////////////////////////////////
        // Parse The HTML Content
        ////////////////////////////////////////////////////////////////////////////
        // Instantiate The DOMDocument Class
        $htmlDom = new DOMDocument();
        $htmlDom->validateOnParse = true;

        // Parse the HTML of the page using DOMDocument::loadHTML In UTF8 Encoding
        // @$htmlDom->loadHTML($webPageContent);
        @$htmlDom->loadHTML(mb_convert_encoding($webPageContent, 'HTML-ENTITIES', 'UTF-8'));

        ////////////////////////////////////////////////////////////////////////////
        // Extract json encoded data from page (as discogs pages are built using javascript and manipulating the json data)
        ////////////////////////////////////////////////////////////////////////////

        // There is json data under : <script id="dsdata" type="application/json">
        $scripts = $htmlDom->getElementsByTagName('script');

        // Loop through the DOMNodeList.
        // We can do this because the DOMNodeList object is traversable.
        foreach ($scripts as $script) {

            // Get details from entity
            $scriptText = $script->nodeValue;
            $scriptType = $script->getAttribute('type');
            $scriptID   = $script->getAttribute('id');

            // If the script type is empty, skip it and don't use
            if (strlen(trim($scriptType)) == 0) {
                continue;
            }

            // If the script id is empty, skip it and don't use
            if (strlen(trim($scriptID)) == 0) {
                continue;
            }

            // We only want the json data
            if ($scriptType != 'application/json') {
                continue;
            }

            // For the script ID dsdata
            if ($scriptID != 'dsdata') {
                continue;
            }

            $json_data = $scriptText;

            // Once we have found the script we want, we can stop looking at scripts (there will only be one instance of it)
            break;
        }

        ////////////////////////////////////////////////////////////////////////////
        // Parse json data to extract what we want from it
        ////////////////////////////////////////////////////////////////////////////

        $json_data_decoded = json_decode($json_data);

        // We only care about the data part, the config can be ignored
        $json_data_decoded_data = $json_data_decoded->data;

        ////////////////////////////////////////////////////////////////////////////
        // Get release artist (if set)
        ////////////////////////////////////////////////////////////////////////////

        foreach ($json_data_decoded_data as $item) { // foreach element in $arr
            if ($debug_output) { echo '<p>Release Info' . '</br>' . "\n"; }

            $itemType = $item->__typename;

            // We are interestd in Release entries
            if ($itemType != 'Release') {
                continue;
            }

            // var_dump($item);
            $releaseArtistName = $item->primaryArtists[0]->displayName; // always first item in array

            if ($debug_output) { echo 'Release Artist: ' . $releaseArtistName . '</br>' . "\n"; }

            if ($debug_output) { echo '</p>' . "\n"; }

            // The first Release item contains the artist we are intereswted in...
            // Other releases are related items that aren't important to us
            break;
        }

        ////////////////////////////////////////////////////////////////////////////
        // Get track details
        ////////////////////////////////////////////////////////////////////////////

        if ($debug_output) { echo '<p>' . "\n"; }

        foreach ($json_data_decoded_data as $item) { // foreach element in $arr

            $itemType = $item->__typename;

            // We are interestd in these
            if ($itemType != 'Track') {
                continue;
            }

            // var_dump($item);
            $trackData['trackName'] = $item->title;
            $trackData['trackPosition'] = $item->position;

            //(!is_null($trackArray) && is_array($trackArray) && isset($trackArray[0]))
            $trackData['trackArtist'] = '';
            if (   !is_null($item->primaryArtists)
                && is_array($item->primaryArtists)
                && isset($item->primaryArtists[0]) ) {
                $trackData['trackArtist'] = $item->primaryArtists[0]->displayName; // always first item in array (if exists)
            }
            $trackData['releaseArtist'] = $releaseArtistName;

            // If this is a row of information without a track position, skip
            if (strlen(trim($trackData['trackPosition'])) == 0) {
                continue;
            }

            if ($discogs_artist_pref == "T") {
                $trackData['displayArtist'] = $trackData['trackArtist'];
            }

            if ($discogs_artist_pref == "R") {
                $trackData['displayArtist'] = $trackData['releaseArtist'];
            }

            // If ther prefference didn't work, try whateever has a value
            if (strlen(trim($trackData['displayArtist'])) == 0) {
                $trackData['displayArtist'] = $trackData['trackArtist'];
            }

            if (strlen(trim($trackData['displayArtist'])) == 0) {
                $trackData['displayArtist'] = $trackData['releaseArtist'];
            }

            // strip a trailing number in brackets (for artists where there are more than one with the same name)
            if (    (strpos($trackData['displayArtist'], '(') !== false) //str_contains() is a PHP8 new function
                and (strpos($trackData['displayArtist'], ')') !== false)
               ) {
                $trackData['displayArtist'] = trim(preg_replace('/\([0-9]*\)$/i', '', $trackData['displayArtist']));
            }

            if ($debug_output) {
                echo 'Track: ' . $trackData['trackPosition'] . ' = ' . $trackData['trackName'] . ' by '. $trackData['displayArtist'] .'(' . $trackData['trackArtist'] . ' | ' . $trackData['releaseArtist'] . ')</br>' . "\n";
            }

            $trackArray[] = $trackData;

        }
    }

    if ($debug_output) { echo '</p>' . "\n"; }

    if ($debug_output) {
        echo '<h3>Variable Dumps (external_site_parser_discogs)</h3>' . "\n";
        echo '<h4>Track Array</h4>' . "\n";
        echo '<pre>' . "\n";
        var_dump($trackArray);
        echo '</pre>' . "\n";
    }

    return $trackArray;

}

?>