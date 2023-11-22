<?php

class titlestrip {

    // Variables populated by the form fields
    public $track_a;
    public $track_b;
    public $artist_a;
    public $artist_b;
    public $left_bar;
    public $right_bar;
    public $publisher;
    public $publisher_id;
    public $image_reference;

    // Variables used for control and management
    public $image_file_reference;
    protected $image_file_is_temporary = False;

    function get_combined_artist() {
        if (strlen($this->artist_a) > 0 && strlen($this->artist_b) > 0) {
            if ($this->artist_a == $this->artist_b) {
                $combinedartist = $this->artist_a;
            } else {
                $combinedartist = "$this->artist_a/$this->artist_b";
            }
        } elseif (strlen($this->artist_a) > 0) {
            $combinedartist = "$this->artist_a";
        } elseif (strlen($this->artist_b) > 0) {
            $combinedartist = "$this->artist_b";
        } else {
            $combinedartist = "";
        }

        return trim($combinedartist);

    }

    function get_combined_publisherinfo() {
        $combined_publisherinfo = "$this->publisher $this->publisher_id";
        return trim($combined_publisherinfo);
    }

    function has_set_values() {
        $result = False;
        if (   (isset($this->track_a)         and strlen($this->track_a)         > 0)
            or (isset($this->track_b)         and strlen($this->track_b)         > 0)
            or (isset($this->artist_a)        and strlen($this->artist_a)        > 0)
            or (isset($this->artist_b)        and strlen($this->artist_b)        > 0)
            or (isset($this->left_bar)        and strlen($this->left_bar)        > 0)
            or (isset($this->right_bar)       and strlen($this->right_bar)       > 0)
            or (isset($this->publisher)       and strlen($this->publisher)       > 0)
            or (isset($this->publisher_id)    and strlen($this->publisher_id)    > 0)
            or (isset($this->image_reference) and strlen($this->image_reference) > 0)
           )
        {
            $result = True;
        }
        return $result;
    }

    function image_refernce_is_url() {

        $result = False;

        if(filter_var($this->image_reference, FILTER_VALIDATE_URL))
        {
            //ToDo: Any extra validation e.g. domains here
            $result = True;
        }

        return $result;
    }

    function is_valid_image_file_type($image_filename_to_check) {
        $result = False;
        $valid_file_types = ['jpg','jpeg','png','gif'];
        $image_file_type = pathinfo($image_filename_to_check, PATHINFO_EXTENSION); // to get extension

        if (in_array($image_file_type, $valid_file_types)) {
            $result = True;
        } else {
            $result = False;
        }

        return $result;
    }

    function delete_local_image_file() {
        if (!$this->image_file_is_temporary) {
            throw new Exception('Not deleting a non-temp image');
        } else {
            $file_pointer = $this->image_file_reference;

            // Only try to delete a file if it exists
            if (!file_exists($file_pointer)) {
                throw new Exception('File not exists/already deleted: '.$file_pointer);
            }

            // Use unlink() function to delete a file (it returns False on error)
            if (!unlink($file_pointer)) {
                //echo ("$file_pointer cannot be deleted due to an error");
                throw new Exception('Failed to delete file: '.$file_pointer);
            }

            // Check we were succesful
            if (file_exists($file_pointer)) {
                throw new Exception('File not deleted: '.$file_pointer);
            }
        }
    }

    function set_image_file_reference() {
        if (    !$this->image_refernce_is_url()
            and file_exists($this->image_reference) // It's not a URL and it's one of our existing local files
           )
        {
            if (!$this->is_valid_image_file_type($this->image_reference)) {
                throw new Exception('Invalid image file type: '.$this->image_reference);
            } else {
                if (file_exists($this->image_reference)) {
                    $this->image_file_reference = $this->image_reference;
                } else {
                    throw new Exception('File doesn\'t exist: '.$this->image_reference);
                }

                $this->image_file_is_temporary = False;
            }
        } else { // It's a URL and we want to download and create it localy
            $url_file_path = parse_url($this->image_reference, PHP_URL_PATH); // This trims anything off the end of the filename (like HTML arguments)
            $url_file_name = basename($url_file_path); // to get just the file name
            $unique_id = uniqid();
            $local_file_name = 'temp-image_'.$unique_id.'_'.$url_file_name;

            if (!$this->is_valid_image_file_type($url_file_name)) {
                throw new Exception('Invalid image file type in URL: '.$url_file_name);
            }

            $this->image_file_reference = "images/$local_file_name";
            $this->image_file_is_temporary = True;

            if (file_exists($this->image_file_reference)) {
                throw new Exception('File already exists: '.$this->image_file_reference);
            }
        }
    }

    function download_and_store_url_image_file() {
        // Test to make sure it's not already there
        if (file_exists($this->image_file_reference)) {
            throw new Exception('This File already exists: '.$this->image_file_reference);
        }

        // Download and store file localy
        $ch = curl_init($this->image_reference);
        $fp = fopen($this->image_file_reference, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        // Test its there
        if (!file_exists($this->image_file_reference)) {
            throw new Exception('Failed to create image file: '.$this->image_file_reference);
        }
    }
}

class titlestrip_manager {

    // Array Variables (used internaly to allocate to titlestrips array)
    protected $post_array_title_a;
    protected $post_array_title_b;
    protected $post_array_artist_a;
    protected $post_array_artist_b;
    protected $post_array_left_bar;
    protected $post_array_right_bar;
    protected $post_array_publisher;
    protected $post_array_publisher_id;
    protected $post_array_image_reference;

    // Non Array Variables
    public $titlesize;
    public $titlefont;
    public $framecolor;
    public $background;
    public $backgroundcolor;
    public $artistbackgroundcolor;

    public $fontcolor;
    public $fontbold;
    public $fontitalic;
    public $fontunderline;
    public $artistbgr;
    public $leftbar;
    public $rightbar;
    public $labeltype;
    public $prelabel = False;
    public $artistBoxStyle;

    public $artist_upper_case = False;
    public $track_upper_case = False;

    public $ink_saver = False;

    // Array for title strips
    public $titlestrips = array();

    function __construct(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->populate_post_variables();
            $this->populate_titlestrips();
        }
    }

    protected function populate_post_variables(){
        // Array Variables
        $this->post_array_title_a = $_POST['titlea'];
        $this->post_array_title_b = $_POST['titleb'];
        $this->post_array_artist_a = $_POST['artista'];
        $this->post_array_artist_b = $_POST['artistb'];
        $this->post_array_left_bar = $_POST['leftbar'];
        $this->post_array_right_bar = $_POST['rightbar'];
        $this->post_array_publisher = $_POST['publisher'];
        $this->post_array_publisher_id = $_POST['publisherid'];
        $this->post_array_image_reference = $_POST['imagename'];

        // Non Array Variables
        if(isset($_POST['prelabel'])) { $this->prelabel = (bool)trim($_POST['prelabel']);} // Any non empty value is True

        $this->framecolor = $_POST['framecolor'];
        $this->background = $_POST['background'];
        $this->backgroundcolor = $_POST['backgroundcolor'];
        $this->artistbackgroundcolor = $_POST['artistbackgroundcolor'];


        $this->artistbgr = $_POST['artbackground'];
        $this->leftbar = $_POST['leftbar'];
        $this->rightbar = $_POST['rightbar'];
        $this->labeltype = $_POST['labeltype'];


        $this->artistBoxStyle = trim(stripslashes($_POST['artistBoxStyle']));
        // Set default
        if ($this->artistBoxStyle == "") {
            $this->artistBoxStyle = 'arrows';
        }

        // Fonts & Styles
        $this->titlesize = $_POST['titlesize'];
        $this->titlefont = $_POST['titlefont'];


        $this->fontcolor = $_POST['fontcolor'];

        $this->fontbold = $_POST['fontbold'];
        $this->fontitalic = $_POST['fontitalic'];
        $this->fontunderline = $_POST['fontunderline'];

        $this->font_style = $this->fontbold . $this->fontitalic . $this->fontunderline;

        if(isset($_POST['artist_upper']) && $_POST['artist_upper'] == 'artist_upper_case') {
            $this->artist_upper_case = True;
        } else {
            $this->artist_upper_case = False;
        }

        if(isset($_POST['track_upper']) && $_POST['track_upper'] == 'track_upper_case') {
            $this->track_upper_case = True;
        } else {
            $this->track_upper_case = False;
        }

        if(isset($_POST['ink_saver']) && $_POST['ink_saver'] == 'ink_saver') {
            $this->ink_saver = True;
        } else {
            $this->ink_saver = False;
        }

    }

    function populate_titlestrips(){
        for ($i = 1; $i <= 20; $i ++) {
            $this->titlestrips[$i] = new titlestrip();
            $this->titlestrips[$i]->track_a = trim(stripslashes($this->post_array_title_a[$i]));
            $this->titlestrips[$i]->track_b = trim(stripslashes($this->post_array_title_b[$i]));
            $this->titlestrips[$i]->artist_a = trim(stripslashes($this->post_array_artist_a[$i]));
            $this->titlestrips[$i]->artist_b = trim(stripslashes($this->post_array_artist_b[$i]));
            $this->titlestrips[$i]->left_bar = trim(stripslashes($this->post_array_left_bar[$i]));
            $this->titlestrips[$i]->right_bar = trim(stripslashes($this->post_array_right_bar[$i]));
            $this->titlestrips[$i]->publisher = trim(stripslashes($this->post_array_publisher[$i]));
            $this->titlestrips[$i]->publisher_id = trim(stripslashes($this->post_array_publisher_id[$i]));
            $this->titlestrips[$i]->image_reference = trim(stripslashes($this->post_array_image_reference[$i]));

            if (    isset($this->titlestrips[$i]->image_reference)
                and strlen($this->titlestrips[$i]->image_reference) > 0
               )
            {
                //$check_message = 'Setting Image: '.$this->titlestrips[$i]->image_reference;
                //error_log(print_r($check_message, True));

                $this->titlestrips[$i]->set_image_file_reference();
            }
        }
    }

}

function &hex2rgb($hex, $asString = True)
{
    // strip off any leading #
    if (0 === strpos($hex, '#')) {
        $hex = substr($hex, 1);
    } else if (0 === strpos($hex, '&H')) {
        $hex = substr($hex, 2);
    }

    // break into hex 3-tuple
    $cutpoint = ceil(strlen($hex) / 2) - 1;
    $rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

    // convert each tuple to decimal
    $rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
    $rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
    $rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

    return $rgb; // ($asString ? "{$rgb[0]} {$rgb[1]} {$rgb[2]}" : $rgb);
}

?>