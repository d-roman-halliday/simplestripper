<?php

/**
 * 
 * @version $Id$
 * @copyright 2004
 */
// Please note that this version has been modified to work in the new php where global varibles are not allowed
// This modification was done by Stephen Rice at n4yza.com and you can contact him on that web site.
// get some bookkeeping out of the way
// This is Version 1.9

define('FPDF_FONTPATH', 'fpdf/font/');
require "fpdf/fpdf.php";
require "cellz.php";
$publisherfont = "Helvetica";
$largefontsize = "16";
$mediumfontsize = "12";
$smallfontsize = "9";
//define varibles no longer global per Stephen Rice
//*******************
$titlesize = $_POST['titlesize'];
$titlefont = $_POST['titlefont'];
$framecolor = $_POST['framecolor'];
$background = $_POST['background'];
$backgroundcolor = $_POST['backgroundcolor'];
$artistbackgroundcolor = $_POST['artistbackgroundcolor'];
$titlefont = $_POST['titlefont'];
$titlea = $_POST['titlea'];
$titleb = $_POST['titleb'];
$artista = $_POST['artista'];
$artistb = $_POST['artistb'];
$publisher = $_POST['publisher'];
$publisherid = $_POST['publisherid'];
$fontcolor = $_POST['fontcolor'];
$fontbold = $_POST['fontbold'];
$fontitalic = $_POST['fontitalic'];
$fontunderline = $_POST['fontunderline'];
$artistbgr = $_POST['artbackground'];
$leftbar = $_POST['leftbar'];
$rightbar = $_POST['rightbar'];
$labeltype = $_POST['labeltype'];
$prelabel = $_POST['prelabel'];
$imagename = $_POST['imagename'];
$font_style = $fontbold.$fontitalic.$fontunderline;
//
// start building pdf
$pdf = new ZPDF('P', 'pt', 'Letter');
$pdftitle = 'Jukebox Title Strips';
$author = 'Simple Stripper Version 1.8 Modified by Stephen Rice';
$pdf -> SetTitle($pdftitle);
$pdf -> SetAuthor($author);
$pdf -> SetLeftMargin(18);
$pdf -> SetTopMargin(18);
$pdf -> AddPage();
switch($titlesize){
 case "small":
     $fontsize = $smallfontsize;
     break;
 case "medium":
     $fontsize = $mediumfontsize;
     break;
 case "large":
     $fontsize = $largefontsize;
     break;
     }

//#################################################
// START SET The LINE color Routine 
//#################################################
    $a= hex2rgb($framecolor);     
    $stripr = $a[0];
    $stripg = $a[1];
    $stripb = $a[2];
//#########################################
// Start set the background COLOR ROUTINE
//#########################################
    $a= hex2rgb($backgroundcolor);     
    $stripbgr = $a[0];
    $stripbgg = $a[1];
    $stripbgb = $a[2];

//#########################################
// Start set the artist background COLOR ROUTINE
//#########################################
    $a= hex2rgb($artistbackgroundcolor);     
    $artiststripbgr = $a[0];
    $artiststripbgg = $a[1];
    $artiststripbgb = $a[2];
    
    
//################################################
// START Paint the frame Background if selected Routine
//################################################
if ($background){
 $pdf -> SetLineWidth(6);
 $pdf -> SetDrawColor($stripbgr, $stripbgg, $stripbgb);
 $pdf -> SetFillColor($stripbgr, $stripbgg, $stripbgb);
 for ($colorbgr = 0; $colorbgr <=9;$colorbgr++) {
 $pdf -> Rect(20, 26+ $colorbgr * 72, 212, 60, 'FD');
 $pdf -> Rect(308, 26+ $colorbgr * 72, 212, 60, 'FD');
    }
}
//#########################################
//End Paint the Background Routine
//#########################################

switch($labeltype){
 case "text":

//#########################################
//Start Print Text Labels Routine
//#########################################
for($horiz = 0;$horiz <= 1;$horiz++){
 // and do it nine times for ten rows
for($vert = 0;$vert <= 9;$vert++){
 $pdf -> SetFont($titlefont, $font_style, $fontsize);
if ($prelabel == "") {
//###########################################################
//START Print the top and bottom lines of the labels Routine
//###########################################################
 $pdf -> SetLineWidth(2);
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
 $pdf -> Line(18 + $horiz * 288, 22 + $vert * 72, 234 + $horiz * 288, 22 + $vert * 72);
 if ( $vert >0 ) {
   $pdf -> Line(18 + $horiz * 288, 22 + $vert * 72, 234 + $horiz * 288, 22 + $vert * 72);
 }
 $pdf -> Line(18 + $horiz * 288, 90 + $vert * 72, 234 + $horiz * 288, 90 + $vert * 72);
//#########################################
//End Top and BOttom Line Routine
//#########################################


//###########################################################
//START Print the LEFT and RIGHT lines of the labels Routine
//###########################################################
 $pdf -> SetLineWidth(2);
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
 $pdf -> Line(16 + $horiz, 22 + $vert * 72, 16 + $horiz, 90 + $vert * 72);
 $pdf -> Line(236 + $horiz, 22 + $vert * 72, 236 + $horiz, 90 + $vert * 72);
 $pdf -> Line(303 + $horiz, 22 + $vert * 72, 303 + $horiz, 90 + $vert * 72);
 $pdf -> Line(524 + $horiz, 22 + $vert * 72, 524 + $horiz, 90 + $vert * 72);
//###########################################################
//END Print the LEFT and RIGHT lines of the labels Routine
//###########################################################

//#########################################
//START Make the Box for the Artist Routine
//#########################################
 $pdf -> SetLineWidth(1);
 if ($artistbgr){
 $pdf -> SetFillColor($artiststripbgr, $artiststripbgg, $artiststripbgb);
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
 $pdf -> Rect(39 + $horiz * 288, 45 + $vert * 72, 168, 18,'DF');
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
}
else{
 $pdf -> SetFillColor(255, 255, 255);
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
 $pdf -> Rect(39 + $horiz * 288, 45 + $vert * 72, 168, 18,'DF');
}
//#########################################
//End Make Box for Artist Routine
//#########################################

//##############################################
//START PLACE BARS ON SIDE OF ARTIST BOX ROUTINE
//##############################################

 // bars on side of artist box
 $pdf -> SetFillColor($stripr, $stripg, $stripb);
 $pdf -> Rect(17 + $horiz * 288, 50 + $vert * 72, 22, 9,'FD');
 $pdf -> Rect(207 + $horiz * 288, 50 + $vert * 72, 28, 9,'FD');
//##############################################
//END PLACE BARS ON SIDE OF ARTIST BOX ROUTINE
//##############################################
 
//##############################################
//START Print the left and right bars if wanted
//##############################################
 $recordtoprint = (($horiz * 10) + $vert + 1);
 $pdf -> SetXY(18 + $horiz * 288, 54 + $vert * 72);
 $pdf -> SetTextColor(255,255,255);
 $pdf -> SetXY(18 + $horiz * 288, 54 + $vert * 72);
 $pdf -> CellZ(20, 0, $leftbar[$recordtoprint], '', '' , 'C');
 $pdf -> SetXY(208 + $horiz * 288, 54 + $vert * 72);
 $pdf -> CellZ(20, 0, $rightbar[$recordtoprint], '', '' , 'C');
//##############################################
//END Print the left and right bars if wanted
//##############################################

//######################################
///Start Make Arrow Heads for Artist Box
//######################################
for($arrow = 0;$arrow <= 12;$arrow++){
  $pdf -> Line(39.2 + $horiz * 288, $arrow + 48 + $vert * 72, 44 + $horiz * 288, 55 + $vert * 72);
  $pdf -> Line(206.2 + $horiz * 288, $arrow + 48 + $vert * 72, 201 + $horiz * 288, 55 + $vert * 72);
}
//######################################
///END Make Arrow Heads for Artist Box
//######################################


} // end if prelabel     


//#########################################
// START Change Font Color Routine
//#########################################

    $a= hex2rgb($fontcolor);     
    $fontcolorr = $a[0];
    $fontcolorg = $a[1];
    $fontcolorb = $a[2];
    $pdf -> SetTextColor($fontcolorr,$fontcolorg,$fontcolorb);
  
//#########################################
// END Font Color Routine
//#########################################


//##############################################
//END Print the Type and hit if wanted
//##############################################


 // next line picks which record to print based on which cell we are working on
$recordtoprint = (($horiz * 10) + $vert + 1);
 // next lines sets top left corner of box. Extra y offset to get off
// of lines. line after that prints title of a side
$pdf -> SetXY(18 + $horiz * 288, 34 + $vert * 72);
 $titlea[$recordtoprint] = stripslashes($titlea[$recordtoprint]);
 $pdf -> CellZ(212, 0, $titlea[$recordtoprint], '', '' , 'C');
 // set position and print title of b side
$pdf -> SetXY(18 + $horiz * 288, 73 + $vert * 72);
 $titleb[$recordtoprint] = stripslashes($titleb[$recordtoprint]);
 $pdf -> CellZ(212 , 0, $titleb[$recordtoprint], '', '' , 'C');



//####################################################
///Start Print The Artists, Publisher and Publisher ID
//####################################################
 // combine artist a and b into one string if needed
if ($artista[$recordtoprint] && $artistb[$recordtoprint]){
 $combinedartist = ("$artista[$recordtoprint]/$artistb[$recordtoprint]");
 }elseif ($artista[$recordtoprint]){
 $combinedartist = ("$artista[$recordtoprint]");
 }elseif ($artistb[$recordtoprint]){
 $combinedartist = ("$artistb[$recordtoprint]");
 }else{
 $combinedartist = ("");
 }
 $combinedartist = stripslashes($combinedartist);
 $pdf -> SetXY(44 + $horiz * 288, 54 + $vert * 72);
 $pdf -> CellZ(156 , 0, $combinedartist, '', '' , 'C');
 $publisherinfo = "$publisher[$recordtoprint] $publisherid[$recordtoprint]";
 $publisherinfo = stripslashes($publisherinfo);
 $pdf -> SetFont('Helvetica', '', 6);
 $pdf -> SetXY(174 + $horiz * 288, 86 + $vert * 72);
 $pdf -> CellZ(60, 0, $publisherinfo, '', '', 'L');

 }

}
//#########################################
//End Print Text Labels Routine
//#########################################
break;
 case "image":
//######################################
//######################################
// Print labels with an images on them
// Do it twice for two columns
//######################################
//######################################
for($horiz = 0;$horiz <= 1;$horiz++){
 // and do it nine times for ten rows
for($vert = 0;$vert <= 9;$vert++){
 $pdf -> SetFont($titlefont, $font_style, $fontsize);
if ($prelabel == "") {
//###########################################################
//START Print the top and bottom lines of the labels Routine
//###########################################################
 $pdf -> SetLineWidth(2);
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
 $pdf -> Line(18 + $horiz * 288, 22 + $vert * 72, 234 + $horiz * 288, 22 + $vert * 72);
 if ( $vert >0 ) {
   $pdf -> Line(18 + $horiz * 288, 22 + $vert * 72, 234 + $horiz * 288, 22 + $vert * 72);
 }
 $pdf -> Line(18 + $horiz * 288, 90 + $vert * 72, 234 + $horiz * 288, 90 + $vert * 72);
//#########################################
//End Top and BOttom Line Routine
//#########################################


//#############################################################################
//START Print the LEFT, Picture box line, and RIGHT lines of the labels Routine
//#############################################################################
 $pdf -> SetLineWidth(2);
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
 $pdf -> Line(16 + $horiz, 22 + $vert * 72, 16 + $horiz, 90 + $vert * 72);      //Print the Left Line col 1
 $pdf -> Line(236 + $horiz, 22 + $vert * 72, 236 + $horiz, 90 + $vert * 72);    //Print the left line col 2
 $pdf -> Line(81 + $horiz, 22 + $vert * 72, 81 + $horiz, 90 + $vert * 72);      //Print the right line next to the picture col 1
 $pdf -> Line(369 + $horiz, 22 + $vert * 72, 369 + $horiz, 90 + $vert * 72);    //Print the right line next to the picture col 2
 $pdf -> Line(303 + $horiz, 22 + $vert * 72, 303 + $horiz, 90 + $vert * 72);    //Print the right line col 1
 $pdf -> Line(524 + $horiz, 22 + $vert * 72, 524 + $horiz, 90 + $vert * 72);    //Print the right line col 2
//###########################################################
//END Print the LEFT and RIGHT lines of the labels Routine
//###########################################################



//#########################################
//START Make the Box for the Artist Routine
//#########################################
 $pdf -> SetLineWidth(1);
 if ($artistbgr){
 $pdf -> SetFillColor($artiststripbgr, $artiststripbgg, $artiststripbgb);
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
 $pdf -> Rect(82 + $horiz * 288, 45 + $vert * 72, 154, 18,'DF');
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
}
else{
 $pdf -> SetFillColor(255, 255, 255);
 $pdf -> SetDrawColor($stripr, $stripg, $stripb);
 $pdf -> Rect(82 + $horiz * 288, 45 + $vert * 72, 154, 18,'DF');
}
//#########################################
//End Make Box for Artist Routine
//#########################################

} //End if prelabel
 
//#########################################
// START Change Font Color Routine
//#########################################

    $a= hex2rgb($fontcolor);     
    $fontcolorr = $a[0];
    $fontcolorg = $a[1];
    $fontcolorb = $a[2];
    $pdf -> SetTextColor($fontcolorr,$fontcolorg,$fontcolorb);
  
//#########################################
// END Font Color Routine
//#########################################

 // next line picks which record to print based on which cell we are working on
$recordtoprint = (($horiz * 10) + $vert + 1);

// #########################################################
// Print A IMAGE ON THE LABEL ROUTINE. 
// #########################################################

If ($imagename[$recordtoprint] >"") {
$pdf->Image($imagename[$recordtoprint],16.8+$horiz * 288,23.2+ $vert * 72,0,65);
}
else{
}

// #########################################################
// Print THE TITLES OF THE RECORDS ROUTINE. 
 // next lines sets top left corner of box. Extra y offset to get off
// of lines. line after that prints title of a side
// #########################################################

$pdf -> SetXY(84 + $horiz * 288, 34 + $vert * 72);
 $titlea[$recordtoprint] = stripslashes($titlea[$recordtoprint]);
 $pdf -> CellZ(148, 0, $titlea[$recordtoprint], '', '' , 'C');
 // set position and print title of b side
$pdf -> SetXY(84 + $horiz * 288, 73 + $vert * 72);
 $titleb[$recordtoprint] = stripslashes($titleb[$recordtoprint]);
 $pdf -> CellZ(148 , 0, $titleb[$recordtoprint], '', '' , 'C');


//####################################################
///Start Print The Artists, Publisher and Publisher ID
//####################################################
 // combine artist a and b into one string if needed
if ($artista[$recordtoprint] && $artistb[$recordtoprint]){
 $combinedartist = ("$artista[$recordtoprint]/$artistb[$recordtoprint]");
 }elseif ($artista[$recordtoprint]){
 $combinedartist = ("$artista[$recordtoprint]");
 }elseif ($artistb[$recordtoprint]){
 $combinedartist = ("$artistb[$recordtoprint]");
 }else{
 $combinedartist = ("");
 }
 $combinedartist = stripslashes($combinedartist);
 $pdf -> SetXY(84 + $horiz * 288, 54 + $vert * 72);
 $pdf -> CellZ(148 , 0, $combinedartist, '', '' , 'C');
 $publisherinfo = "$publisher[$recordtoprint] $publisherid[$recordtoprint]";
 $publisherinfo = stripslashes($publisherinfo);
 $pdf -> SetFont('Helvetica', '', 6);
 $pdf -> SetXY(174 + $horiz * 288, 86 + $vert * 72);
 $pdf -> CellZ(60, 0, $publisherinfo, '', '', 'L');

 }

}
break;
//#########################################
//End Print Text Labels Routine
//#########################################
}
// we're done. Send it out
$pdf -> Output('titlestrips.pdf', 'I');

    function &hex2rgb($hex, $asString = true)
    {
        // strip off any leading #
        if (0 === strpos($hex, '#')) {
            $hex = substr($hex, 1);
        } else if (0 === strpos($hex, '&H')) {
            $hex = substr($hex, 2);
        }      

        // break into hex 3-tuple
        $cutpoint = ceil(strlen($hex) / 2)-1;
        $rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

        // convert each tuple to decimal
        $rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
        $rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
        $rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

        return $rgb; //($asString ? "{$rgb[0]} {$rgb[1]} {$rgb[2]}" : $rgb);
    }
?>
