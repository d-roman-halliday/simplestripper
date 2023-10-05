<?php
////////////////////////////////////////////////////////////////////////////////
// Setup & Config of page (main processing is set to happen in "debug output" area)
////////////////////////////////////////////////////////////////////////////////
//Debugging Flag (so I can hide the ugly when not testing)
$debug_output = true;

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
                        SimpleStripper v 3 (2023) Tweaked by David Roman-Halliday<br />
                        Modified from v 2 (02/27/2020) Extensively Modified by Stephen Rice.<br />
                        Originally written by George Howell (2001)?
                    </p>
                    <h3>Key Changes v3</h3>
                    <h4>
                        v3.1
                    </h4>
                    <ul>
                        <li>URL input for image (now any image can be used, made to fit by height).</li>
                        <li>Background Changes:
                            <ul>
                                <li>Independant class for titlestrip manipulations and POST interaction (reusable and clean).</li>
                                <li>Bug fixes: Resolving some bugs coming up in error log (not visible in front end).</li>
                            </ul>
                        </li>
                    </ul>
                    <h4>
                        V3 Core Version
                    </h4>
                    <ul>
                        <li>New php driven input form (html page archived to html4form.html).</li>
                        <li>Discogs.com integration (give a url for discogs and fetch data).</li>
                        <li>Update index page to use HTML 5 &amp; bootstrap.</li>
                        <li>Customise output artist box styles, provide a box to select different artist box style (Arrow, square, hex).</li>
                        <li>Show/hide columns for publisher (A lesser used function to make the form cleaner).</li>
                        <li>Added an option to convert all artist and/or track names to upper case.</li>
                        <li>Bug fix: Better handling of double quotes in the form.</li>
                        <li>Bug fix: Stripping spaces from start/finish of artists/titles.</li>
                    </ul>
                    <h4>
                        Planned Changes &amp; Suggestions for the future
                    </h4>
                    <ul>
                        <li>Import data using the<a href='https://musicbrainz.org/doc/MusicBrainz_API'>MusicBrainz API</a></li>
                        <li>Import artwork from discogs/MusicBrainz</li>
                        <li>Make more options global OR label speciffic (such as hit/other markings)</li>
                        <li>More fonts</li>
                        <li>Artist/track speciffic fonts (optional)</li>
                        <li>Image based backgrund for labels rather than drawing them (more options)</li>
                        <li>Rework drawing to allow for:
                            <ul>
                                <li>More dynamic sizing</li>
                                <li>Ink saving (don't print empty boxes)</li>
                                <li>combined image/text only labels</li>
                            </ul>
                        </li>
                    </ul>
                    <h3>Key Changes v2</h3>
                    <p>
                        Made by Stephen Rice
                    </p>
                    <ul>
                        <li>You now can print a page without any lines on it in case you prefer to use pre printed labels.</li>
                        <li>Updates have been done to remain compliant with the newer php server software.</li>
                        <li>Included a updated FPDF version 1.82 software that handles printing in the zip file.</li>
                        <li>You now have color pickers available so that you can change colors of different parts of the label. You should click on the color and pick a color and when done either click off the color or click r tab key. This allows you to have many more choices for the colors would like to print.</li>
                        <li>The labels will now open in a new window so that all the information you have entered is still available if you want to make changes.</li>
                    </ul>
                    <h2>Older versions</h2>
                    <p>
                        By George Howell. At this time, all mirrors of original seem to be down... Fortunately Steve published the updated code (as linked above) so that open source idea of the software could continue.
                    </p>
                    <h2>Getting The Code</h2>
                    <p>
                        The code is available on github: <a href="https://github.com/d-roman-halliday/simplestripper">https://github.com/d-roman-halliday/simplestripper</a>
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

                    foreach($ts_manager->titlestrips as $titlestrip)
                    {
                        $combined_artist = $titlestrip->get_combined_artist();
                        echo "titlestrip_artist: $titlestrip->track_a / - $combined_artist<br>\n";
                    }

                    ////////////////////////////////////////////////////////////
                    // Request data from External URL (discogs)
                    ////////////////////////////////////////////////////////////

                    //Initialise array (for any fetched data)
                    $trackArray = array();

                    //Default values (preference for track/release artist)
                    $t_checked = 'checked="checked"';
                    $r_checked = '';

                    //Get data from form (if submitted)
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $discogs_url = trim(stripslashes($_POST['discogs_url']));
                        $discogs_artist_pref = trim(stripslashes($_POST['discogs_artist_pref']));
                    }

                    //Get array of data from URL
                    if (isset($discogs_url) and strlen(trim($discogs_url)) > 0) { // we have a request to work with
                        $trackArray = external_site_parser_discogs($discogs_url,$discogs_artist_pref,$debug_output);
                    }

                    //Change settings for dislay of form (preference for track/release artist)
                    if (isset($discogs_artist_pref) and $discogs_artist_pref == "R") {
                        $t_checked = '';
                        $r_checked = 'checked="checked"';
                    }

                    ////////////////////////////////////////////////////////////
                    // Request data from External URL (discogs)
                    ////////////////////////////////////////////////////////////

                    if ($debug_output) {
                        echo '<h3>Variable Dumps (main)</h3>' . "\n";
                        echo '<h4>Track Array</h4>' . "\n";
                        echo '<pre>' . "\n";
                        var_dump($trackArray);
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
                Because the Publisher information isn't a common thing for people to use, I've hidden it from the form. 
                You can show and hide the coluns using the buttons below, the visibility of the columns doesn't impact
                anything in them or if it's sent to the label generator.
            </p>
            <div id="toolbar">
                <button type="button" id="button1" class="btn btn-secondary" onclick="changeVisOn()">Show Publisher Columns</button>
                <button type="button" id="button2" class="btn btn-secondary" onclick="changeVisOff()">Hide Publisher Columns</button>
            </div>
            <script>
                function changeVisOn() {
                    // What to do
                    document.getElementById('p1_tr_00').style.display = 'table-cell';
                    document.getElementById('p2_tr_00').style.display = 'table-cell';
<?php
                    for ($i = 1; $i <= 20; $i ++) {
                        echo '                    document.getElementById("p1_tr_'.$i.'").style.display = "table-cell";'."\n";
                        echo '                    document.getElementById("p2_tr_'.$i.'").style.display = "table-cell";'."\n";
                        }
?>

                }
                function changeVisOff() {
                    // What to do
                    document.getElementById('p1_tr_00').style.display = 'none';
                    document.getElementById('p2_tr_00').style.display = 'none';
<?php
                    for ($i = 1; $i <= 20; $i ++) {
                        echo '                    document.getElementById("p1_tr_'.$i.'").style.display = "none";'."\n";
                        echo '                    document.getElementById("p2_tr_'.$i.'").style.display = "none";'."\n";
                    }
?>
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
            <p>Get data from discogs and append to strip information. This will append to the rows below, note for now only artist and track names will be loaded and refreshed.</p>
            <p>
                <input type="text"  name="discogs_url" id="discogs_url" ><br>
                <input type="radio" name="discogs_artist_pref" value="R" <?php echo $r_checked; ?>>Prefer Release Artist<br>
                <input type="radio" name="discogs_artist_pref" value="T" <?php echo $t_checked; ?>>Prefer Track Artist<br>
                <input type="submit" value="Submit">
            </p>
            <h3>Strip Details</h3>
            <p>This gets appended to using the discogs tool above, anything populated can be manuialy modified</p>
            <table id="table"
                   style="text-align: left; width: 100%;"
                   >
                <tbody>
                    <tr>
                        <td style="vertical-align: top;"><br></td>
                        <td style="vertical-align: top; font-weight: bold;">Title A</td>
                        <td style="vertical-align: top; font-weight: bold;">Title B</td>
                        <td style="vertical-align: top; font-weight: bold;">Artist A</td>
                        <td style="vertical-align: top; font-weight: bold;">Artist B</td>
                        <td id="p1_tr_00" style="vertical-align: top; font-weight: bold; display:none">Publisher</td>
                        <td id="p2_tr_00" style="vertical-align: top; font-weight: bold; display:none">Publisher ID</td>
                        <td style="vertical-align: top; font-weight: bold;">Left Bar</td>
                        <td style="vertical-align: top; font-weight: bold;">Right Bar</td>
                        <td style="vertical-align: top; font-weight: bold;">Image/Image URL</td>
                        <td style="vertical-align: top; font-weight: bold;">Image/URL Toggle</td>
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
    if (isset($ts_manager)){
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

        if (!is_null($trackArray) && is_array($trackArray) && isset($trackArray[0])) {
            $trackData = array_shift($trackArray);
            $row_track_a  = $trackData['trackName'];
            $row_artist_a = $trackData['displayArtist'];
        }

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
    echo '                        <td text-align: right; font-weight: bold;">'.$i.'</td>'."\n";
    echo '                        <td>                                       <input name="titlea['.$i.']"      value="'.$row_track_a.'"></td>'."\n";
    echo '                        <td>                                       <input name="titleb['.$i.']"      value="'.$row_track_b.'"></td>'."\n";
    echo '                        <td>                                       <input name="artista['.$i.']"     value="'.$row_artist_a.'"></td>'."\n";
    echo '                        <td>                                       <input name="artistb['.$i.']"     value="'.$row_artist_b.'"></td>'."\n";
    echo '                        <td id="p1_tr_'.$i.'" style="display:none"><input name="publisher['.$i.']"   value="'.$row_publisher.'"></td>'."\n";
    echo '                        <td id="p2_tr_'.$i.'" style="display:none"><input name="publisherid['.$i.']" value="'.$row_publisher_id.'"></td>'."\n";
    echo '                        <td>                                       <input name="leftbar['.$i.']"     value="'.$row_left_bar.'"></td>'."\n";
    echo '                        <td>                                       <input name="rightbar['.$i.']"    value="'.$row_right_bar.'"></td>'."\n";
    echo '                        <td><select id="img_in_'.$i.'"                    name="imagename['.$i.']">';
    echo '<option value="">None</option>';
    echo '<option value="images/wreath.jpg">Wreath</option>';
    echo '<option value="images/santa.jpg">Santa</option>';
    echo '<option value="images/jukebox.jpg">Jukebox</option>';
    echo '<option value="images/rickynelson.jpg">Ricky Nelson</option>';
    echo '<option value="images/beachboys1.jpg">Beach Boys Picture 1</option>';
    echo '</select>'."\n";
    echo '                        </td>'."\n";
    echo '                        <td><button type="button" id="img_switch_bt_'.$i.'" class="btn btn-secondary" onclick="img_switch_fn('.$i.')">Change</button></td>'."\n";
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
            <input id="font_01" type="radio" name="titlefont" value="Times"                    ><label for="font_01">Times</label><br>
            <input id="font_02" type="radio" name="titlefont" value="Helvetica"                ><label for="font_02">Helvetica</label><br>
            <input id="font_03" type="radio" name="titlefont" value="Courier" checked="checked"><label for="font_03">Courier</label>
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
                <input type="checkbox" id="artist_upper_id" name="artist_upper" value="artist_upper_case">
                <br>
                <label for="track_upper_id">Convert all track names to upper case</label>
                <input type="checkbox" id="track_upper_id" name="track_upper" value="track_upper_case">
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
