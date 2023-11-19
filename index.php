<?php
////////////////////////////////////////////////////////////////////////////////
// Setup & Config of page (main processing is set to happen in "debug output" area)
////////////////////////////////////////////////////////////////////////////////
//Debugging Flag (so I can hide the ugly when not testing)
$debug_output = false;

require "titlestrip.php";
require "external_site_parser.php";

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Styles -->
        <!-- Bootstrap CSS -->
        <link  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous" defer></script>
        <!-- make me pretty with: https://bootswatch.com/ -->
        <!-- <link  href="css/bootstrap.css" rel="stylesheet">     -->
        <!-- <link  href="css/bootstrap.min.css" rel="stylesheet"> -->
        <!-- End: Styles -->

        <!-- Scripts -->
        <script type="text/javascript" src="jscolor/jscolor.js" defer></script>

        <title>
            SimpleStripper &copy; David Roman-Halliday - Roman-Halliday.com
        </title>
    </head>
    <body>
        <div class="container">
            <h1>SimpleStripper 3</h1>
            <p>
                Generator for Jukebox title strips, with functionality to import track &amp;
                artist information from <a href="http://www.discogs.com">discogs.com</a>. 
                Realeased under an open source. This version is verymuch a "work in progress"
                so it's ugly and I've only focused on the track/artist names for now. 
                See more under "about".
            </p>
            <p>
                The original input form (HTML 4 by Stephen) has been renamed to <a href="html4form.html">html4form.html</a> and is still available.
            </p>
            <p>
                There is a lot of work to do on the "look and feel", but I've focused on functionality for now.
            </p>
        </div>
        <div class="container">
            <nav>
                <div class="nav nav-tabs align-items-baseline" id="nav_ID" role="tablist">
<?php
if ($debug_output) {
?>
                    <button class="nav-link"        id="nav_ID-C_00" data-bs-toggle="tab" data-bs-target="#nav_C-C_00" type="button" role="tab" aria-controls="#nav_C-C_00" aria-selected="false">
                        <h3>
                            <span>Debug</span>
                        </h3>
                    </button>
<?php
}
?>
                    <button class="nav-link"        id="nav_ID-C_01" data-bs-toggle="tab" data-bs-target="#nav_C-C_01" type="button" role="tab" aria-controls="#nav_C-C_01" aria-selected="false">
                        <h3>
                            <span>Example</span>
                        </h3>
                    </button>
                    <button class="nav-link"        id="nav_ID-C_02" data-bs-toggle="tab" data-bs-target="#nav_C-C_02" type="button" role="tab" aria-controls="#nav_C-C_02" aria-selected="false">
                        <h3>
                            <span>Documentation</span>
                        </h3>
                    </button>
                    <button class="nav-link"        id="nav_ID-C_03" data-bs-toggle="tab" data-bs-target="#nav_C-C_03" type="button" role="tab" aria-controls="#nav_C-C_03" aria-selected="false">
                        <h3>
                            <span>About</span>
                        </h3>
                    </button>
                </div>
            </nav>
            <div class="tab-content mt-5" id="nav-tabContent">

                <div class="tab-pane fade show" id="nav_C-C_01" role="tabpanel" aria-labelledby="nav_ID-C_01">
                    <p>
                        Here is a sample of each type of label (TEXT OR IMAGE) you can make
                        using the RED frame and showing the different areas of a label that
                        you can change. See the instructions for more details.
                    </p>
                    <div class="text-center">
                        <img src="colorlabel.jpg" width="372" height="122" alt="" />
                        <img src="colorimage.jpg" width="370" height="120" alt="" />
                    </div>
                </div>

                <div class="tab-pane fade show" id="nav_C-C_02" role="tabpanel" aria-labelledby="nav_ID-C_02">
                    <h2>Main Form &amp; PDF Creation</h2>
                    <p>
                        Enter information you want printed. If A side artist is the same as the
                        B side artist, you can leave one blank.
                        <br /> If you don't want printing in the left bar or right bar just
                        leave it blank and nothing will be printed.
                        <br /> If you don't want publisher information printed, don't enter
                        it.
                        <br /> You can select any combination of such as Bold, Italic, or
                        underline or none of the aforementioned.
                        <br /> You can add color to just the artist box by selecting it and it
                        will have backcolor while the rest is another color.
                        <br /> You can select background color and it will color all but the
                        artist box allowing you to choose if you want the whole label colored
                        or not.
                        <br /> You can select to print images and images for each label will
                        be printed on the label but the Left and Right Bars text will not be
                        printed.
                    </p>
                    <h2>Discogs Input</h2>
                    <p>Some testing URLs:</p>
                    <ul>
                        <li>Individual single: https://www.discogs.com/master/96610-Snow-Informer</li>
                        <li>Box set (one release artist, varying artist names for tracks): https://www.discogs.com/master/1141135-Jimi-Hendrix-Classic-Singles-Collection</li>
                        <li>Box set (one artist): https://www.discogs.com/release/501595-The-Who-The-First-Singles-Box</li>
                    </ul>
                </div>

                <div class="tab-pane fade show" id="nav_C-C_03" role="tabpanel" aria-labelledby="nav_ID-C_03">
                    <p>
                        SimpleStripper is an open surce project, originaly by George Howell...
                    </p>
                    <h2>
                        Key Release history
                    </h2>
                    <p>
                        SimpleStripper v 3 (2023) Remodeled by David Roman-Halliday<br />
                        Modified from v 2 (02/27/2020) Extensively Modified by Stephen Rice.<br />
                        Originally written by George Howell (2001)?
                    </p>
                    <h2>Getting The Code &amp; Release Information</h2>
                    <p>
                        The code, and release information (details of cahnges) are available on github: <a href="https://github.com/d-roman-halliday/simplestripper">https://github.com/d-roman-halliday/simplestripper</a>
                    </p>
                </div>

                <div class="tab-pane fade show" id="nav_C-C_00" role="tabpanel" aria-labelledby="nav_ID-C_00">
                    <p>
                        This is for development/testing ignore this.
                    </p>
<?php

                    ////////////////////////////////////////////////////////////////////////////
                    // Parse any existing titlestrip information
                    ////////////////////////////////////////////////////////////////////////////
                    $ts_manager = new titlestrip_manager;

                    ////////////////////////////////////////////////////////////////////////////
                    // Parse any existing form information (for external site parsing)
                    ////////////////////////////////////////////////////////////////////////////
                    $ex_manager = new external_site_manager;

                    ////////////////////////////////////////////////////////////
                    // Form default values management
                    ////////////////////////////////////////////////////////////

                    // External Site Configuration
                    $external_site_release_artist_preference_checked_t = 'checked="checked"';
                    $external_site_release_artist_preference_checked_r = '';
                    if (isset($ex_manager->external_site_release_artist_preference) and $ex_manager->external_site_release_artist_preference == "R") {
                        $external_site_release_artist_preference_checked_t = '';
                        $external_site_release_artist_preference_checked_r = 'checked="checked"';
                    }

                    $external_site_year_preference_checked_n = '';
                    if ($ex_manager->external_site_year_preference === 'N') {$external_site_year_preference_checked_n  = 'checked="checked"';}
                    $external_site_year_preference_checked_l = '';
                    if ($ex_manager->external_site_year_preference === 'L') {$external_site_year_preference_checked_l  = 'checked="checked"';}
                    $external_site_year_preference_checked_r = '';
                    if ($ex_manager->external_site_year_preference === 'R') {$external_site_year_preference_checked_r  = 'checked="checked"';}

                    $external_site_LabelName_include_checked = ''; // Not in basic API
                    //if ($ex_manager->external_site_LabelName_include) {$external_site_LabelName_include_checked  = 'checked="checked"';}

                    $external_site_CatalogNumber_include_checked = ''; // Not in basic API
                    //if ($ex_manager->external_site_CatalogNumber_include) {$external_site_CatalogNumber_include_checked  = 'checked="checked"';}

                    // Title Strip Configuration
                    $artist_upper_id_checked = '';
                    if ($ts_manager->artist_upper_case) {$artist_upper_id_checked = 'checked="checked"';}

                    $track_upper_id_checked = '';
                    if ($ts_manager->track_upper_case) {$track_upper_id_checked  = 'checked="checked"';}


                    ////////////////////////////////////////////////////////////
                    // Request data from External URL (discogs)
                    ////////////////////////////////////////////////////////////
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $discogs_url = $ex_manager->external_site_url;
                        $discogs_artist_pref = $ex_manager->external_site_release_artist_preference;
                    }

                    //Initialise array (for any fetched data)
                    $trackArray = array();

                    //Get array of data from URL
                    if (isset($discogs_url) and strlen(trim($discogs_url)) > 0) { // we have a request to work with
                        // New: Using API from discogs
                        $api_client = new discogs_api_client;
                        $trackArray = $api_client->get_track_data_from_url($discogs_url,$discogs_artist_pref,$debug_output);
                    }

                    ////////////////////////////////////////////////////////////
                    // Dump Variables
                    ////////////////////////////////////////////////////////////

                    if ($debug_output) {
                        echo '<h3>Variable Dumps (main)</h3>' . "\n";
                        echo '<h4>Track Array</h4>' . "\n";
                        echo '<pre>' . "\n";
                        var_dump($trackArray);
                        echo '</pre>' . "\n";
                        echo '<h4>External Site Manager</h4>' . "\n";
                        echo '<pre>' . "\n";
                        var_dump($ex_manager);
                        echo '</pre>' . "\n";
                        echo '<h4>Title Strips Manager</h4>' . "\n";
                        echo '<pre>' . "\n";
                        var_dump($ts_manager);
                        echo '</pre>' . "\n";
                    }
?>
                </div>
            </div>
        </div>

        <div class="container pt-5">
        <h2>Main Form</h2>
            <p>
                Because the Publisher information isn't a common thing for people to use, I've hidden it from the form (although it's still there).
                You can show and hide columns using the buttons below, the visibility of the columns doesn't impact
                the values in them, or if they are sent to the label generator (everything is sent).
            </p>
            <div id="toolbar">
                <button type="button" id="button1_sh_rn" class="btn btn-secondary" onclick="changeVisOffSelected('rn')">Hide Row Number</button>

                <!-- <button type="button" id="button1_sh_ta" class="btn btn-secondary" onclick="changeVisOffSelected('ta')">Hide Title A Column</button> -->
                <!-- <button type="button" id="button1_sh_tb" class="btn btn-secondary" onclick="changeVisOffSelected('tb')">Hide Title B Column</button> -->

                <!-- <button type="button" id="button1_sh_aa" class="btn btn-secondary" onclick="changeVisOffSelected('aa')">Hide Artist A Column</button> -->
                <!-- <button type="button" id="button1_sh_ab" class="btn btn-secondary" onclick="changeVisOffSelected('ab')">Hide Artist B Column</button> -->

                <button type="button" id="button1_sh_pb" class="btn btn-secondary" onclick="changeVisOnPublishers()">Show Publisher Columns</button>

                <button type="button" id="button1_sh_lb" class="btn btn-secondary" onclick="changeVisOffSelected('lb')">Hide Left Bar Column</button>
                <button type="button" id="button1_sh_rb" class="btn btn-secondary" onclick="changeVisOffSelected('rb')">Hide Right Bar Column</button>

                <button type="button" id="button1_sh_im" class="btn btn-secondary" onclick="changeVisOffSelected('im')">Hide Image Column</button>
                <button type="button" id="button1_sh_ib" class="btn btn-secondary" onclick="changeVisOffSelected('ib')">Hide Image Toggle Column</button>
            </div>
            <script>
                function changeVisOffSelected(column_ref) {
                    for (var i = 0; i <= 20; i++) {
                        column_element = document.getElementById(column_ref + '_tr_' + i);
                        if (column_element) {
                            column_element.style.display = 'none';
                        }
                    }
                    control_button_1_element = document.getElementById('button1_sh_' + column_ref);
                    if (control_button_1_element) {
                        old_text = control_button_1_element.textContent;
                        new_text = old_text.replace("Hide", "Show");
                        control_button_1_element.textContent = new_text;
                        control_button_1_element.style.backgroundColor="green";
                        control_button_1_element.setAttribute('onclick','changeVisOnSelected(\'' + column_ref + '\')');
                    }
                }
                function changeVisOnSelected(column_ref) {
                    for (var i = 0; i <= 20; i++) {
                        column_element = document.getElementById(column_ref + '_tr_' + i);
                        if (column_element) {
                            column_element.style.display = 'table-cell';
                        }
                    }
                    control_button_1_element = document.getElementById('button1_sh_' + column_ref);
                    if (control_button_1_element) {
                        old_text = control_button_1_element.textContent;
                        new_text = old_text.replace("Show", "Hide");
                        control_button_1_element.textContent = new_text;
                        control_button_1_element.style.backgroundColor="blue";
                        control_button_1_element.setAttribute('onclick','changeVisOffSelected(\'' + column_ref + '\')');
                    }
                }
                function changeVisOnPublishers() {
                    changeVisOnSelected('p1');
                    changeVisOnSelected('p2');

                    control_button_1_element = document.getElementById('button1_sh_pb');
                    if (control_button_1_element) {
                        control_button_1_element.textContent = control_button_1_element.textContent.replace("Show", "Hide");
                        control_button_1_element.style.backgroundColor="blue";
                        control_button_1_element.setAttribute('onclick','changeVisOffPublishers()');
                    }
                }
                function changeVisOffPublishers() {
                    changeVisOffSelected('p1');
                    changeVisOffSelected('p2');

                    control_button_1_element = document.getElementById('button1_sh_pb');
                    if (control_button_1_element) {
                        control_button_1_element.textContent = control_button_1_element.textContent.replace("Hide", "Show");
                        control_button_1_element.style.backgroundColor="green";
                        control_button_1_element.setAttribute('onclick','changeVisOnPublishers()');
                    }
                }
                // Control image selection type
                function img_switch_fn(element_id) {
                    element_ref = 'img_in_'+element_id;
                    this_element = document.getElementById(element_ref);
                    this_element_name = this_element.name;
                    this_element_value = this_element.value;
                    if (this_element.type == "select-one") {
                        new_e = document.createElement("input");
                        new_e.id = element_ref;
                        new_e.name = this_element_name;
                        new_e.value = this_element_value;
                        this_element.replaceWith(new_e);
                    } else if (this_element.type == "text") {
                        new_e = document.createElement("select");

                        //Options (there must be a better way of doing this)
                        option1 = document.createElement("option");
                        option1.text = "None";
                        option1.value = '';
                        new_e.add(option1);

                        option2 = document.createElement("option");
                        option2.text = "Wreath";
                        option2.value = 'images/wreath.jpg';
                        new_e.add(option2);

                        option3 = document.createElement("option");
                        option3.text = "Santa";
                        option3.value = 'images/santa.jpg';
                        new_e.add(option3);

                        option4 = document.createElement("option");
                        option4.text = "Jukebox";
                        option4.value = 'images/jukebox.jpg';
                        new_e.add(option4);

                        option5 = document.createElement("option");
                        option5.text = "Ricky Nelson";
                        option5.value = 'images/rickynelson.jpg';
                        new_e.add(option5);

                        option6 = document.createElement("option");
                        option6.text = "Beach Boys Picture 1";
                        option6.value = 'images/beachboys1.jpg';
                        new_e.add(option6);

                        new_e.id = element_ref;
                        new_e.name = this_element_name;
                        new_e.value = this_element_value;
                        this_element.replaceWith(new_e);
                    }
                }
            </script>
        <form method="post" name="record_entry">
            <h3>Discogs</h3>
            <p>Get data from discogs and append to strip information. This will append to the rows below. For now only artist and track names (and release year if selected) will be loaded and refreshed.</p>
            <p>
                <label for="external_site_url_id">Discogs URL:</label>
                <input type="text"  id="external_site_url_id" name="external_site_url" id="external_site_url_id" ><br>
            </p>
            <h4>
                External Site Configurations
            </h4>
            <p>
                <label for="external_site_release_artist_preference_id_r">Prefer Release Artist</label>
                <input type="radio" id="external_site_release_artist_preference_id_r" name="external_site_release_artist_preference" value="R" <?php echo $external_site_release_artist_preference_checked_r; ?>><br>
                <label for="external_site_release_artist_preference_id_t">Prefer Track Artist</label>
                <input type="radio" id="external_site_release_artist_preference_id_t" name="external_site_release_artist_preference" value="T" <?php echo $external_site_release_artist_preference_checked_t; ?>><br>
            </p>
            <p>
                <label for="external_site_year_preference_id_n">Don't include Release Year</label>
                <input type="radio" id="external_site_year_preference_id_n" name="external_site_year_preference" value="N" <?php echo $external_site_year_preference_checked_n; ?>><br>
                <label for="external_site_year_preference_id_l">Release Year in Left Bar</label>
                <input type="radio" id="external_site_year_preference_id_l" name="external_site_year_preference" value="L" <?php echo $external_site_year_preference_checked_l; ?>><br>
                <label for="external_site_year_preference_id_r">Release Year in Right Bar</label>
                <input type="radio" id="external_site_year_preference_id_r" name="external_site_year_preference" value="R" <?php echo $external_site_year_preference_checked_r; ?>><br>

            </p>
            <p>
                <!-- Not available in basic API -->
<!--
                <label for="external_site_LabelName_include_id">Include Label Name</label>
                <input type="checkbox" id="external_site_LabelName_include_id"     name="external_site_LabelName_include"     value="external_site_LabelName_include_t"     <?php echo $external_site_LabelName_include_checked; ?>>
                <br>
                <label for="external_site_CatalogNumber_include_id">Include Catalog Number</label>
                <input type="checkbox" id="external_site_CatalogNumber_include_id" name="external_site_CatalogNumber_include" value="external_site_CatalogNumber_include_t" <?php echo $external_site_CatalogNumber_include_checked; ?>>
-->
            </p>
            <p>
                <input type="submit" value="Submit">
            </p>
            <h3>Strip Details</h3>
            <p>This gets appended to using the discogs tool above, anything populated can be manuialy modified</p>
            <table id="table"
                   style="text-align: left; width: 100%;"
                   >
                <tbody>
                    <tr>
                        <td id="rn_tr_0" style="vertical-align: top;"><br></td>
                        <td id="ta_tr_0" style="vertical-align: top; font-weight: bold;">Title A</td>
                        <td id="tb_tr_0" style="vertical-align: top; font-weight: bold;">Title B</td>
                        <td id="aa_tr_0" style="vertical-align: top; font-weight: bold;">Artist A</td>
                        <td id="ab_tr_0" style="vertical-align: top; font-weight: bold;">Artist B</td>
                        <td id="p1_tr_0" style="vertical-align: top; font-weight: bold; display:none">Publisher</td>
                        <td id="p2_tr_0" style="vertical-align: top; font-weight: bold; display:none">Publisher ID</td>
                        <td id="lb_tr_0" style="vertical-align: top; font-weight: bold;">Left Bar</td>
                        <td id="rb_tr_0" style="vertical-align: top; font-weight: bold;">Right Bar</td>
                        <td id="im_tr_0" style="vertical-align: top; font-weight: bold;">Image/Image URL</td>
                        <td id="ib_tr_0" style="vertical-align: top; font-weight: bold;">Image/URL Toggle</td>
                    </tr>
<?php

for ($i = 1; $i <= 20; $i ++) {

    $row_track_a = '';
    $row_track_b =  '';
    $row_artist_a = '';
    $row_artist_b = '';
    $row_left_bar = '';
    $row_right_bar = '';
    $row_publisher = '';
    $row_publisher_id = '';
    $row_image = '';

    $row_already_populated = False;
    if (   isset($ts_manager)
        && is_array($ts_manager->titlestrips)
        && isset($ts_manager->titlestrips[$i])
       ){
        $row_already_populated = $ts_manager->titlestrips[$i]->has_set_values();
    }

    if ($row_already_populated) {

        $row_track_a = $ts_manager->titlestrips[$i]->track_a;
        $row_track_b = $ts_manager->titlestrips[$i]->track_b;
        $row_artist_a = $ts_manager->titlestrips[$i]->artist_a;
        $row_artist_b = $ts_manager->titlestrips[$i]->artist_b;
        $row_left_bar = $ts_manager->titlestrips[$i]->left_bar;
        $row_right_bar = $ts_manager->titlestrips[$i]->right_bar;
        $row_publisher = $ts_manager->titlestrips[$i]->publisher;
        $row_publisher_id = $ts_manager->titlestrips[$i]->publisher_id;
        $row_image = $ts_manager->titlestrips[$i]->image_reference;

    } else {

        // Artist/Trak A (and all other data from the release)
        if (!is_null($trackArray) && is_array($trackArray) && isset($trackArray[0])) {
            $trackData = array_shift($trackArray);
            $row_track_a  = $trackData['trackName'];
            $row_artist_a = $trackData['displayArtist'];

            // Release Year
            if(!is_null($trackData['releaseYear'])) {
                if ($ex_manager->external_site_year_preference === 'L') { $row_left_bar = $trackData['releaseYear']; }
                if ($ex_manager->external_site_year_preference === 'R') { $row_right_bar = $trackData['releaseYear']; }
            }
        }

        // Artist/Trak B
        if (!is_null($trackArray) && is_array($trackArray) && isset($trackArray[0])) {
            $trackData = array_shift($trackArray);
            $row_track_b  = $trackData['trackName'];
            $row_artist_b = $trackData['displayArtist'];
        }

        //Protection for artist twice
        if ($row_artist_a == $row_artist_b) {
            $row_artist_b = '';
        }

    }

    //Clean up HTML display characters...
    $row_track_a  = htmlentities($row_track_a);
    $row_track_b  = htmlentities($row_track_b);
    $row_artist_a = htmlentities($row_artist_a);
    $row_artist_b = htmlentities($row_artist_b);
    $row_left_bar = htmlentities($row_left_bar);
    $row_right_bar = htmlentities($row_right_bar);
    $row_publisher = htmlentities($row_publisher);
    $row_publisher_id = htmlentities($row_publisher_id);

    //$row_image = htmlentities($row_image); // Not needed

    echo '                    <tr>'."\n";
    echo '                        <td id="rn_tr_'.$i.'" text-align: right; font-weight: bold;">'.$i.'</td>'."\n";
    echo '                        <td id="ta_tr_'.$i.'">                     <input name="titlea['.$i.']"      value="'.$row_track_a.'"></td>'."\n";
    echo '                        <td id="tb_tr_'.$i.'">                     <input name="titleb['.$i.']"      value="'.$row_track_b.'"></td>'."\n";
    echo '                        <td id="aa_tr_'.$i.'">                     <input name="artista['.$i.']"     value="'.$row_artist_a.'"></td>'."\n";
    echo '                        <td id="ab_tr_'.$i.'">                     <input name="artistb['.$i.']"     value="'.$row_artist_b.'"></td>'."\n";
    echo '                        <td id="p1_tr_'.$i.'" style="display:none"><input name="publisher['.$i.']"   value="'.$row_publisher.'"></td>'."\n";
    echo '                        <td id="p2_tr_'.$i.'" style="display:none"><input name="publisherid['.$i.']" value="'.$row_publisher_id.'"></td>'."\n";
    echo '                        <td id="lb_tr_'.$i.'">                     <input name="leftbar['.$i.']"     value="'.$row_left_bar.'"></td>'."\n";
    echo '                        <td id="rb_tr_'.$i.'">                     <input name="rightbar['.$i.']"    value="'.$row_right_bar.'"></td>'."\n";
    echo '                        <td id="im_tr_'.$i.'"><select id="img_in_'.$i.'"  name="imagename['.$i.']">';
    echo '<option value="">None</option>';
    echo '<option value="images/wreath.jpg">Wreath</option>';
    echo '<option value="images/santa.jpg">Santa</option>';
    echo '<option value="images/jukebox.jpg">Jukebox</option>';
    echo '<option value="images/rickynelson.jpg">Ricky Nelson</option>';
    echo '<option value="images/beachboys1.jpg">Beach Boys Picture 1</option>';
    echo '</select>'."\n";
    echo '                        </td>'."\n";
    echo '                        <td id="ib_tr_'.$i.'"><button type="button" id="img_switch_bt_'.$i.'" class="btn btn-secondary" onclick="img_switch_fn('.$i.')">Change</button></td>'."\n";
    echo '                    </tr>'."\n";

}

?>
                </tbody>
            </table>

            <h3>Strip Output Settings</h3>
            <p>Don't change these until finished importing from discogs as they reset when fetching the data.</p>

            <h4>
                Frame Color
            </h4>
            <p>
                <label for="framecolor_p">Select color:</label>
                <input id="framecolor_p" name="framecolor" class="color" value="ff0000" >
            </p>
            <h4>
                Background
            </h4>
            <input id="background_y" type="radio" name="background" value="TRUE"              ><label for="background_y">Yes</label><br>
            <input id="background_n" type="radio" name="background" value="" checked="checked"><label for="background_n">No</label><br>
            <label for="bgcolor_p">Select color:</label>
            <input id="bgcolor_p" name="backgroundcolor" class="color" value="FFC0CB">
            <h4>
                Artist Background
            </h4>
            <input id="artbackground_y" type="radio" name="artbackground" value="TRUE"               ><label for="artbackground_y">Yes</label><br>
            <input id="artbackground_n" type="radio" name="artbackground" value="" checked="checked" ><label for="artbackground_n">No</label><br>
            <label for="artistbackgroundcolor_p">Select color:</label>
            <input id="artistbackgroundcolor_p" name="artistbackgroundcolor" class="color" value="FFC0CB" >
            <h4>
                Font
            </h4>
            <input id="font_01" type="radio" name="titlefont" value="Times"                      ><label for="font_01">Times</label><br>
            <input id="font_02" type="radio" name="titlefont" value="Helvetica" checked="checked"><label for="font_02">Helvetica</label><br>
            <input id="font_03" type="radio" name="titlefont" value="Courier"                    ><label for="font_03">Courier</label>
            <h4>
                Font Color
            </h4>
            <label for="fontcolor_p">Select color:</label>
            <input id="fontcolor_p" name="fontcolor" class="color" value="000000" >
            <h4>
                Print Size
            </h4>
            <input id="title_s" type="radio" name="titlesize" value="small"                    ><label for="title_s">Small</label><br>
            <input id="title_m" type="radio" name="titlesize" value="medium" checked="checked" ><label for="title_m">Medium</label><br>
            <input id="title_l" type="radio" name="titlesize" value="large"                    ><label for="title_l">Large</label><br>
            <h4>
                Bold
            </h4>
            <input id="fontbold_y" type="radio" name="fontbold" value="B"                  ><label for="fontbold_y">Yes</label><br>
            <input id="fontbold_n" type="radio" name="fontbold" value="" checked="checked" ><label for="fontbold_n">No</label><br>
            <h4>
                Italic
            </h4>
            <input id="fontitalic_y" type="radio" name="fontitalic" value="I"                  ><label for="fontitalic_y">Yes</label><br>
            <input id="fontitalic_n" type="radio" name="fontitalic" value="" checked="checked" ><label for="fontitalic_n">No</label>
            <h4>
                Underlined
            </h4>
            <input id="fontunderline_y" type="radio" name="fontunderline" value="U"                  ><label for="fontunderline_y">Yes</label><br>
            <input id="fontunderline_n" type="radio" name="fontunderline" value="" checked="checked" ><label for="fontunderline_n">No</label>
            <h4>
                Image or Text
            </h4>
            <input id="labeltype_t" type="radio" name="labeltype" value="text" checked="checked" ><label for="labeltype_t">TEXT</label><br>
            <input id="labeltype_i" type="radio" name="labeltype" value="image"                  ><label for="labeltype_i">IMAGE</label>
            <h4>
                Pre Printed Labels
            </h4>
            <input id="prelabel_y" type="radio" name="prelabel" value="Y"                  ><label for="prelabel_y">Yes</label><br>
            <input id="prelabel_n" type="radio" name="prelabel" value="" checked="checked" ><label for="prelabel_n">No</label>
            <h4>
                Artist Box
            </h4>
            <p>
                <label for="artistBoxStyle_id">Artist box format:</label>
                <select name="artistBoxStyle" id="artistBoxStyle_id">
                    <option value="arrows">Rectangle with arrows</option>
                    <option value="rect">Rectangle (no arrows)</option>
                    <option value="hex">Hexagonal (long)</option>
                </select>
            </p>
            <h4>
                Text settings
            </h4>
            <p>
                <label for="artist_upper_id">Convert all artist names to upper case</label>
                <input type="checkbox" id="artist_upper_id" name="artist_upper" value="artist_upper_case" <?php echo $artist_upper_id_checked ?>>
                <br>
                <label for="track_upper_id">Convert all track names to upper case</label>
                <input type="checkbox" id="track_upper_id"  name="track_upper"  value="track_upper_case"  <?php echo $track_upper_id_checked ?>>
            </p>
            <p>
                <!--  Reset doesn't work if form is already populated at start, javascript could help -->
                <!-- <button name="Reset"  type="reset">Clear All Fields</button>-->
                <button name="Submit" type="submit" formaction="printstrips.php" formtarget="_blank">Create PDF</button>
            </p>

        </form>
        </div>
    </body>
</html>
