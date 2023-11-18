<?php

class external_site_manager {
    public $external_site_url;
    public $external_site_release_artist_preference = 'T'; // R = Release ¦ T = Track
    public $external_site_year_preference = 'N';
    public $external_site_LabelName_include = false;
    public $external_site_CatalogNumber_include = false;

    // Keeping this for refernce but it's not active as the site protection for discogs blocks it
    public $external_site_image_preference;

    function __construct(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->populate_post_variables();
        }
    }

    protected function populate_post_variables(){
        // Array Variables
        $this->external_site_url = trim(stripslashes($_POST['external_site_url']));
        $this->external_site_release_artist_preference = trim(stripslashes($_POST['external_site_release_artist_preference']));
        $this->external_site_year_preference = trim(stripslashes($_POST['external_site_year_preference']));

        if(isset($_POST['external_site_LabelName_include']) && $_POST['external_site_LabelName_include'] == 'external_site_LabelName_include_t') {
            $this->external_site_LabelName_include = true;
        }
        if(isset($_POST['external_site_CatalogNumber_include']) && $_POST['external_site_CatalogNumber_include'] == 'external_site_CatalogNumber_include_t') {
            $this->external_site_CatalogNumber_include = true;
        }
    }
}


class discogs_api_client {

    private $user_agent = 'SimpleStripper/3.1 +https://github.com/d-roman-halliday/simplestripper'; // https://www.discogs.com/developers
    private $timeout = 2; // 2 is seconds

    private $api_client_AuthConfigured = False;
    private $api_client_ConsumerKey;
    private $api_client_ConsumerSecret;

    function __construct(){
        ////////////////////////////////////////////////////////////////////////
        // Configure authentication for API
        ////////////////////////////////////////////////////////////////////////

        ////////////////////////////////////////////////////////////////////////
        // Try from env variables
        if (   null !== getenv('DISCOGS_API_CLIENT_KEY')
            && strlen(trim(getenv('DISCOGS_API_CLIENT_KEY')))
           ) {
            $this->api_client_ConsumerKey = getenv('DISCOGS_API_CLIENT_KEY');
        }
        if (   null !== getenv('DISCOGS_API_CLIENT_SECRET')
            && strlen(trim(getenv('DISCOGS_API_CLIENT_SECRET'))) ) {
            $this->api_client_ConsumerSecret = getenv('DISCOGS_API_CLIENT_KEY');
        }

        ////////////////////////////////////////////////////////////////////////
        // Try from apache config (less secure, as can be surfaced by phpinfo())
        if (   null !== ini_get('DISCOGS_API_CLIENT_KEY')
            && strlen(trim(ini_get('DISCOGS_API_CLIENT_KEY')))
           ) {
            $this->api_client_ConsumerKey = trim(ini_get('DISCOGS_API_CLIENT_KEY'));
        }
        if (   null !== ini_get('DISCOGS_API_CLIENT_SECRET')
            && strlen(trim(ini_get('DISCOGS_API_CLIENT_SECRET'))) ) {
            $this->api_client_ConsumerSecret = trim(ini_get('DISCOGS_API_CLIENT_KEY'));
        }

        ////////////////////////////////////////////////////////////////////////
        // If we got configuration, set a boolean value
        if (   strlen($this->api_client_ConsumerSecret) > 1
            && strlen($this->api_client_ConsumerKey)    > 1
           ) {
            $this->api_client_AuthConfigured = True;
        }

    }

    public function get_track_data_from_url($discogs_url, $discogs_artist_pref, $debug_output = False){
        ////////////////////////////////////////////////////////////////////////
        //Output Var
        ////////////////////////////////////////////////////////////////////////
        $trackArray = [];

        ////////////////////////////////////////////////////////////////////////
        // manage API Endpoint request
        // Using regex, extract information from regular URL and build API request
        ////////////////////////////////////////////////////////////////////////
        if ($debug_output) { echo "<p>URL Requested: $discogs_url</p>"; }

        $pattern_regex = "/(master|release)\/([0-9]*)/i";
        $match_1 = preg_match($pattern_regex, $discogs_url, $matches_array);

        $dump_debug_regex = False;
        if ($debug_output && $dump_debug_regex) {
            echo "<p>match: $matches_array[0] - $matches_array[1] : $matches_array[2]</p>";
            echo "<pre>";
            print_r($matches_array);
            echo "</pre>";
        }
        $discogs_item_type = $matches_array[1];
        $discogs_item_id = $matches_array[2];

        $discogs_api_endpoint = 'Unset';
        if ( $discogs_item_type == 'release') {
            $discogs_api_endpoint = "https://api.discogs.com/releases/$discogs_item_id";
        }
        if ( $discogs_item_type == 'master') {
            $discogs_api_endpoint = "https://api.discogs.com/masters/$discogs_item_id";
        }

        if ($debug_output) { echo "<p>API Endpoint Request: $discogs_api_endpoint</p>"; }

        ////////////////////////////////////////////////////////////////////////
        // Configure cURL and make request
        // Building & Testing can be done with: https://incarnate.github.io/curl-to-php/
        ////////////////////////////////////////////////////////////////////////
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $discogs_api_endpoint);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the result from the request rather than dumping it out (and returning the status)
        
        if ($this->api_client_AuthConfigured == True) {
            ////////////////////////////////////////////////////////////////////
            // API Authentication information
            ////////////////////////////////////////////////////////////////////
            $headers = array();
            $headers[] = "Authorization: Discogs key=$this->api_client_ConsumerKey, secret=$this->api_client_ConsumerSecret";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $api_responce = curl_exec($ch);

        curl_close($ch);

        ////////////////////////////////////////////////////////////////////////
        // Parse API responce
        ////////////////////////////////////////////////////////////////////////
        $api_responce = json_decode($api_responce);

        ////////////////////////////////////////////////////////////////////////
        // Release level data
        $releaseYear = $api_responce->year;
        $releaseArtistName = $api_responce->artists[0]->name;

        ////////////////////////////////////////////////////////////////////////
        // Track level
        if ($debug_output) { echo "<h3>Track Dta</h3><p>"; }

        foreach ($api_responce->tracklist as $item) {
            ////////////////////////////////////////////////////////////////////
            // Get data from JSON
            $trackData['trackName'] = $item->title;
            $trackData['trackPosition'] = $item->position;

            $trackData['trackArtist'] = '';
            if (   !is_null($item->artists)
                && is_array($item->artists)
                && isset($item->artists[0]) ) {
                $trackData['trackArtist'] = $item->artists[0]->name; // always first item in array (if exists)
            }

            $trackData['releaseArtist'] = $releaseArtistName;
            $trackData['releaseYear'] = $releaseYear;

            // These items were available in the data from website but not in basic API endpoint
            //$trackData['releaseCountry'] = $releaseCountry;
            //$trackData['releaseLabelCatalogNumber'] = $releaseLabelCatalogNumber;
            //$trackData['releaseLabelName'] = $releaseLabelName;
            //$trackData['releaseArtworkURL'] = $releaseImageRef; //Note : This seems to get blocked on the server by cloudflair (preventing external scraping/hosting)

            ////////////////////////////////////////////////////////////////////
            // If this is a row of information without a track position, skip
            if (strlen(trim($trackData['trackPosition'])) == 0) {
                continue;
            }

            ////////////////////////////////////////////////////////////////////
            // Calculate Track or Release level artist info
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

            ////////////////////////////////////////////////////////////////////
            // strip a trailing number in brackets (for artists where there are more than one with the same name)
            if (    (strpos($trackData['displayArtist'], '(') !== false) //str_contains() is a PHP8 new function
                and (strpos($trackData['displayArtist'], ')') !== false)
               ) {
                $trackData['displayArtist'] = trim(preg_replace('/\([0-9]*\)$/i', '', $trackData['displayArtist']));
            }

            if ($debug_output) {
                echo 'Track: ' . $trackData['trackPosition'] . ' = ' . $trackData['trackName'] . ' by '. $trackData['displayArtist'] .'(' . $trackData['trackArtist'] . ' | ' . $trackData['releaseArtist'] . ')</br>' . "\n";
            }

            ////////////////////////////////////////////////////////////////////
            // Append to array
            $trackArray[] = $trackData;

        }

        if ($debug_output) { echo "</p>"; }

        ////////////////////////////////////////////////////////////////////////
        // Dmup Output for debugging
        ////////////////////////////////////////////////////////////////////////
        $dump_variables = False; // Extra switch for debugging control
        if ($debug_output and $dump_variables) {
            echo '<h3>Variable Dumps (external_site_parser_discogs)</h3>' . "\n";
            echo '<h4>Track Array</h4>' . "\n";
            echo '<pre>' . "\n";
            var_dump($trackArray);
            echo '</pre>' . "\n";
            echo '<h4>API Responce</h4>' . "\n";
            echo '<pre>' . "\n";
            var_dump($api_responce);
            echo '</pre>' . "\n";
            echo '<h4>discogs_api_client</h4>' . "\n";
            echo '<pre>' . "\n";
            var_dump($this);
            echo '</pre>' . "\n";
        }

        return $trackArray;
    }
}


function external_site_parser_discogs ($discogs_url, $discogs_artist_pref, $debug_output = False){

    ////////////////////////////////////////////////////////////////////////////
    // BROKEN FOR NOW!
    // Change in site checking for javascript breaks site parsing
    // Since before: 2023-11-18
    // Need to try discogs API
    // e.g.  curl https://api.discogs.com/masters/96610 --user-agent "FooBarApp/3.0"
    ////////////////////////////////////////////////////////////////////////////


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
        $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:89.0) Gecko/20100101 Firefox/89.0';
        //$cookie_file = tmpfile(); // as it wants them

        curl_setopt($ch, CURLOPT_URL, $webPageURL);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
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

        // Debugging which found that javascript check breaks things
        $dump_scripts = False; // Extra switch for debugging control
        if ($debug_output and $dump_scripts) {
            echo '<h3>Variable Dumps (external_site_parser_discogs)</h3>' . "\n";
            echo '<h4>Dom</h4>' . "\n";
            echo '<pre>' . "\n";
            var_dump($htmlDom);
            echo '</pre>' . "\n";
            echo '<h4>Scripts</h4>' . "\n";
            echo '<pre>' . "\n";
            var_dump($scripts);
            echo '</pre>' . "\n";
        }

        // Loop through the DOMNodeList.
        // We can do this because the DOMNodeList object is traversable.
        $json_data = '{"data":{}}';
        foreach ($scripts as $script) {

            // Get details from entity
            $scriptText = $script->nodeValue;
            $scriptType = $script->getAttribute('type');
            $scriptID   = $script->getAttribute('id');

            //echo "<p>$scriptType - $scriptID</p>";

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