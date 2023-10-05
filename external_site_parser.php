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
        // Get release data (First release item in array is the one we want, all others are related releases)
        // - artist (if set)
        // - year
        // - label info
        ////////////////////////////////////////////////////////////////////////////

        foreach ($json_data_decoded_data as $item) { // foreach element in $arr
            $itemType = $item->__typename;
            // We are interestd in Release entries
            if ($itemType != 'Release') {
                continue;
            }

            echo '<h4>JSON Data - Release</h4>' . "\n";
            //echo '<pre>' . "\n";
            //var_dump($item);
            //echo '</pre>' . "\n";

            $releaseArtistName = $item->primaryArtists[0]->displayName; // always first item in array
            $releaseYear = $item->released;
            $releaseCountry = $item->country;

            // Pick first in array (not sure how to be sure which is best, but in cases where speciffic release has beenpicked this is the correct. In other cases ists apsudo random)
            $releaseLabelCatalogNumber = $item->labels[0]->catalogNumber;
            $releaseLabelName = $item->labels[0]->displayName;

            ////////////////////////////////////////////////////////////////////
            // Try and extract release image ID (used to get image from other image items)
            // This feels like a mess as it's breaking text out of JSON thenformatting it again.
            // There isprobably a much cleaner way of doing it, but this works (for now)
            ////////////////////////////////////////////////////////////////////
            $releaseImageID='';
            foreach ($item as $sub_item) { // foreach element in $arr
                if (!isset($sub_item->__typename) or is_null($sub_item->__typename)) {
                    continue;
                }
                $sub_itemType = $sub_item->__typename;
                if ($sub_itemType != 'ImagesConnection') {
                    continue;
                }

                $releaseImageRefStr = $sub_item->edges[0]->node->__ref;
                preg_match('/\{(.+)\}/s', $releaseImageRefStr, $releaseImageRefStrJson);
                $releaseImageRefJson = json_decode($releaseImageRefStrJson[0]);

                $releaseImageID = $releaseImageRefJson->id;
            }

            ////////////////////////////////////////////////////////////////////
            // Show what we got (debug)
            ////////////////////////////////////////////////////////////////////
            if ($debug_output) {
                echo '<h4>Release Info</h4>'.  "\n" . '<p>' . '</br>' . "\n";
                echo 'Release Artist: ' . $releaseArtistName . '</br>' . "\n";
                echo 'Release Year: ' . $releaseYear . '</br>' . "\n";
                echo 'Release Label Name: ' . $releaseLabelName . '</br>' . "\n";
                echo 'Release Label CatalogNumber: ' . $releaseLabelCatalogNumber . '</br>' . "\n";
                echo 'Release Image ID: ' . $releaseImageID . '</br>' . "\n";
                echo '</p>' . "\n";
            }

            // The first Release item contains the artist and attributes we are interested in...
            // Other releases are related items that aren't important to us
            break;
        }

        ////////////////////////////////////////////////////////////////////////
        // Try and get cover art
        ////////////////////////////////////////////////////////////////////////

        foreach ($json_data_decoded_data as $item) { // foreach element in $arr
            $itemType = $item->__typename;
            // We are interestd in Image entries
            if ($itemType != 'Image') {
                continue;
            }

            // Filter for the image we got the ID from the Release
            $itemImageID = $item->id;
            if ($itemImageID != $releaseImageID) {
                continue;
            }

            // Dirty parsing of JSON as part of it is stored as string which then needs to be reconverted to json
            $itemImageFullsizeRefStr = $item->fullsize->__ref;
            preg_match('/\{(.+)\}/s', $itemImageFullsizeRefStr, $itemImageFullsizeRefStrJson);
            $itemImageFullsizeRefJson = json_decode($itemImageFullsizeRefStrJson[0]);

            $releaseImageRef = $itemImageFullsizeRefJson->sourceUrl;

            ////////////////////////////////////////////////////////////////////
            // Show what we got (debug)
            ////////////////////////////////////////////////////////////////////
            if ($debug_output) {
                echo '<h4>Release Artwork</h4>'.  "\n" . '<p>' . '</br>' . "\n";
                echo 'Release Image URL: ' . $releaseImageRef . '</br>' . "\n";
                echo '</p>' . "\n";
            }

        }

        ////////////////////////////////////////////////////////////////////////
        // Get track details
        ////////////////////////////////////////////////////////////////////////

        if ($debug_output) { echo '<h4>Track Info</h4>'.  "\n" . '<p>' . '</br>' . "\n"; }

        foreach ($json_data_decoded_data as $item) { // foreach element in $arr

            $itemType = $item->__typename;

            // We are interestd in these
            if ($itemType != 'Track') {
                continue;
            }

            //echo '<h4>JSON Data - Track</h4>' . "\n";
            //echo '<pre>' . "\n";
            //var_dump($item);
            //echo '</pre>' . "\n";

            $trackData['trackName'] = $item->title;
            $trackData['trackPosition'] = $item->position;

            $trackData['trackArtist'] = '';
            if (   !is_null($item->primaryArtists)
                && is_array($item->primaryArtists)
                && isset($item->primaryArtists[0]) ) {
                $trackData['trackArtist'] = $item->primaryArtists[0]->displayName; // always first item in array (if exists)
            }
            $trackData['releaseArtist'] = $releaseArtistName;
            $trackData['releaseYear'] = $releaseArtistName;
            $trackData['releaseCountry'] = $releaseCountry;
            $trackData['releaseLabelCatalogNumber'] = $releaseLabelCatalogNumber;
            $trackData['releaseLabelName'] = $releaseLabelName;
            $trackData['releaseArtworkURL'] = $releaseImageRef; //Note : This seems to get blocked on the server by cloudflair (preventing external scraping/hosting)

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

            // If the prefference didn't work, try whateever has a value
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

    $dump_variables = False; // Extra switch for debugging control
    if ($debug_output and $dump_variables) {
        echo '<h3>Variable Dumps (external_site_parser_discogs)</h3>' . "\n";
        echo '<h4>Track Array</h4>' . "\n";
        echo '<pre>' . "\n";
        var_dump($trackArray);
        echo '</pre>' . "\n";
        echo '<h4>JSON Data</h4>' . "\n";
        echo '<pre>' . "\n";
        var_dump($json_data_decoded_data);
        echo '</pre>' . "\n";
    }

    return $trackArray;

}

?>