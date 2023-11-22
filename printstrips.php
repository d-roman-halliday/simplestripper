<?php

/**
 *
 * @version $Id$
 * @copyright 2023
 */

// Please note that this version has been tweaked by David Roman-Halliday (adding new functionality)
// Peviously modified to work in the new php where global variables are not allowed by Stephen Rice at n4yza.com and you can contact him on that web site.
// Originally written by George Howell

// get some bookkeeping out of the way
// This is Version 3.1 (still mostly backwards compatible with inputs)

define('FPDF_FONTPATH', 'fpdf/font/');
require "fpdf/fpdf.php";
require "cellz.php";

require "titlestrip.php";

$publisherfont = "Helvetica";
$largefontsize = "16";
$mediumfontsize = "12";
$smallfontsize = "9";

// Get variables from POST and populate titlestrip objects
$ts_manager = new titlestrip_manager;

//
// start building pdf
$pdf = new SsFpdfExtended('P', 'pt', 'Letter'); // Imported from cellz.php containing an extended version of FPDF
$pdftitle = 'Jukebox Title Strips';
$author = 'Simple Stripper Version 3.1 Modified by David Roman-Halliday';


$pdf->SetTitle($pdftitle);
$pdf->SetAuthor($author);
$pdf->SetLeftMargin(18);
$pdf->SetTopMargin(18);
$pdf->AddPage();

switch ($ts_manager->titlesize) {
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

// #################################################
// START SET The LINE color Routine
// #################################################
$a = hex2rgb($ts_manager->framecolor);
$stripr = $a[0];
$stripg = $a[1];
$stripb = $a[2];

// #########################################
// Start set the background COLOR ROUTINE
// #########################################
$a = hex2rgb($ts_manager->backgroundcolor);
$stripbgr = $a[0];
$stripbgg = $a[1];
$stripbgb = $a[2];

// #########################################
// Start set the artist background COLOR ROUTINE
// #########################################
$a = hex2rgb($ts_manager->artistbackgroundcolor);
$artiststripbgr = $a[0];
$artiststripbgg = $a[1];
$artiststripbgb = $a[2];

// #########################################
// Start set the font COLOR ROUTINE
// #########################################
$a = hex2rgb($ts_manager->fontcolor);
$fontcolorr = $a[0];
$fontcolorg = $a[1];
$fontcolorb = $a[2];

// ################################################
// START Paint the frame Background if selected Routine
// ################################################
if ($ts_manager->background) {
    $pdf->SetLineWidth(6);
    $pdf->SetDrawColor($stripbgr, $stripbgg, $stripbgb);
    $pdf->SetFillColor($stripbgr, $stripbgg, $stripbgb);
    for ($colorbgr = 0; $colorbgr <= 9; $colorbgr ++) {
        $pdf->Rect(20, 26 + $colorbgr * 72, 212, 60, 'FD');
        $pdf->Rect(308, 26 + $colorbgr * 72, 212, 60, 'FD');
    }
}

// #########################################
// End Paint the Background Routine
// #########################################

switch ($ts_manager->labeltype) {
    case "text":

        // #########################################
        // Start Print Text Labels Routine
        // #########################################
        for ($horiz = 0; $horiz <= 1; $horiz ++) {
            // and do it zero to nine times for ten rows
            for ($vert = 0; $vert <= 9; $vert ++) {
                // ####################################################
                // Pick which record/strip to print based on which cell we are working on
                // ####################################################
                $recordtoprint = (($horiz * 10) + $vert + 1);

                // ####################################################
                // Skip if it's empty (ink saver)
                // ####################################################
                if (   $ts_manager->ink_saver == True
                    && $ts_manager->titlestrips[$recordtoprint]->has_set_values() == False
                   ) {
                    continue;
                }

                $pdf->SetFont($ts_manager->titlefont, $ts_manager->font_style, $fontsize);
                if ( ! $ts_manager->prelabel) {
                    // ###########################################################
                    // START Print the top and bottom lines of the labels Routine
                    // ###########################################################
                    $pdf->SetLineWidth(2);
                    $pdf->SetDrawColor($stripr, $stripg, $stripb);
                    $pdf->Line(18 + $horiz * 288, 22 + $vert * 72, 234 + $horiz * 288, 22 + $vert * 72);
                    if ($vert > 0) {
                        $pdf->Line(18 + $horiz * 288, 22 + $vert * 72, 234 + $horiz * 288, 22 + $vert * 72);
                    }
                    $pdf->Line(18 + $horiz * 288, 90 + $vert * 72, 234 + $horiz * 288, 90 + $vert * 72);
                    // #########################################
                    // End Top and Bottom Line Routine
                    // #########################################

                    // ###########################################################
                    // START Print the LEFT and RIGHT lines of the labels Routine
                    // ###########################################################
                    $pdf->SetLineWidth(2);
                    $pdf->SetDrawColor($stripr, $stripg, $stripb);
                    $pdf->Line(16  + $horiz * 288, 22 + $vert * 72, 16  + $horiz * 288, 90 + $vert * 72);
                    $pdf->Line(236 + $horiz * 288, 22 + $vert * 72, 236 + $horiz * 288, 90 + $vert * 72);
                    // ###########################################################
                    // END Print the LEFT and RIGHT lines of the labels Routine
                    // ###########################################################

                    // #########################################
                    // START Make the Box for the Artist Routine
                    // #########################################

                    $ab_draw_arrows = True;
                    $ab_draw_box = True;
                    $ab_draw_hex = False;

                    switch ($ts_manager->artistBoxStyle) {
                        case "arrows":
                            $ab_draw_arrows = True;
                            $ab_draw_box = True;
                            $ab_draw_hex = False;
                            break;
                        case "rect":
                            $ab_draw_arrows = False;
                            $ab_draw_box = True;
                            $ab_draw_hex = False;
                            break;
                        case "hex":
                            $ab_draw_arrows = False;
                            $ab_draw_box = False;
                            $ab_draw_hex = True;
                            break;
                    }


                    $pdf->SetLineWidth(1);

                    if ($ab_draw_box) {
                        if ($ts_manager->artistbgr) {
                            $pdf->SetFillColor($artiststripbgr, $artiststripbgg, $artiststripbgb);
                        } else {
                            $pdf->SetFillColor(255, 255, 255);//blank fill
                        }
                        $pdf->SetDrawColor($stripr, $stripg, $stripb);
                        $pdf->Rect(39 + $horiz * 288, 45 + $vert * 72, 168, 18, 'DF');


                        // ##############################################
                        // START PLACE BARS ON SIDE OF ARTIST BOX ROUTINE
                        // ##############################################
                        $pdf->SetFillColor($stripr, $stripg, $stripb);
                        $pdf->Rect(17  + $horiz * 288, 50 + $vert * 72, 22, 9, 'FD');
                        $pdf->Rect(207 + $horiz * 288, 50 + $vert * 72, 28, 9, 'FD');
                        // ##############################################
                        // END PLACE BARS ON SIDE OF ARTIST BOX ROUTINE
                        // ##############################################
                    }

                    if ($ab_draw_arrows) {
                        // ######################################
                        // /Start Make Arrow Heads for Artist Box
                        // ######################################
                        for ($arrow = 0; $arrow <= 12; $arrow ++) {
                            $pdf->Line(39.2  + $horiz * 288, $arrow + 48 + $vert * 72, 44  + $horiz * 288, 55 + $vert * 72);
                            $pdf->Line(206.2 + $horiz * 288, $arrow + 48 + $vert * 72, 201 + $horiz * 288, 55 + $vert * 72);
                        }
                        // ######################################
                        // /END Make Arrow Heads for Artist Box
                        // ######################################
                    }

                    if ($ab_draw_hex) {
                        // #########################################
                        // Long Hex -- Note: $artistbgr not implemented
                        // Alternative approach, polygon function: http://www.fpdf.org/en/script/script60.php
                        // #########################################
                        //$pdf->Line(39 + $horiz * 288, 45 + $vert * 72     , 39 + $horiz * 288 + 168, 45 + $vert * 72     );
                        //$pdf->Line(39 + $horiz * 288, 45 + $vert * 72 + 18, 39 + $horiz * 288 + 168, 45 + $vert * 72 + 18);

                        // Reference point naming
                        // _t = top    _l = left  _h = horizontal
                        // _b = bottom _r = right _v = vertical

                        $point_t_l_h = 39 + $horiz * 288;
                        $point_t_l_v = 45 + $vert * 72;
                        $point_b_l_h = $point_t_l_h;
                        $point_b_l_v = $point_t_l_v + 18;

                        $point_m_l_h = $point_t_l_h - 9;
                        $point_m_l_v = $point_t_l_v + 9;

                        $point_t_r_h = $point_t_l_h + 168;
                        $point_t_r_v = $point_t_l_v;
                        $point_b_r_h = $point_t_r_h;
                        $point_b_r_v = $point_b_l_v;

                        $point_m_r_h = $point_b_r_h + 9;
                        $point_m_r_v = $point_t_r_v + 9;

                        $pdf->Line($point_t_l_h, $point_t_l_v, $point_t_r_h, $point_t_r_v);
                        $pdf->Line($point_b_l_h, $point_b_l_v, $point_b_r_h, $point_b_r_v);
                        $pdf->Line($point_t_l_h, $point_t_l_v, $point_m_l_h, $point_m_l_v);
                        $pdf->Line($point_b_l_h, $point_b_l_v, $point_m_l_h, $point_m_l_v);
                        $pdf->Line($point_t_r_h, $point_t_r_v, $point_m_r_h, $point_m_r_v);
                        $pdf->Line($point_b_r_h, $point_b_r_v, $point_m_r_h, $point_m_r_v);

                        // ##############################################
                        // START PLACE BARS ON SIDE OF ARTIST BOX ROUTINE
                        // ##############################################
                        $point_f_l_h = 18 + $horiz * 288;
                        $point_f_l_v = $point_m_l_v;

                        $point_f_r_h = 236 + $horiz * 288;
                        $point_f_r_v = $point_m_l_v;

                        // Use a fat line to start with
                        $pdf->SetLineWidth(2);
                        $pdf->Line($point_f_l_h - 1, $point_f_l_v, $point_m_l_h - 1, $point_m_l_v);
                        $pdf->Line($point_f_r_h + 1, $point_f_r_v, $point_m_r_h + 1, $point_m_r_v);

                        //set it back to make the box wider (with custom offsets to work around the hex shape)
                        $pdf->SetLineWidth(1);
                        $pdf->Line($point_f_l_h - 1, $point_f_l_v -   2, $point_m_l_h + 1.5, $point_m_l_v -   2);
                        $pdf->Line($point_f_l_h - 1, $point_f_l_v - 1.5, $point_m_l_h +   1, $point_m_l_v - 1.5);
                        $pdf->Line($point_f_l_h - 1, $point_f_l_v -   1, $point_m_l_h + 0.5, $point_m_l_v -   1);
                        $pdf->Line($point_f_l_h - 1, $point_f_l_v +   1, $point_m_l_h + 0.5, $point_m_l_v +   1);
                        $pdf->Line($point_f_l_h - 1, $point_f_l_v + 1.5, $point_m_l_h +   1, $point_m_l_v + 1.5);
                        $pdf->Line($point_f_l_h - 1, $point_f_l_v +   2, $point_m_l_h + 1.5, $point_m_l_v +   2);

                        $pdf->Line($point_f_r_h + 1, $point_f_r_v -   2, $point_m_r_h - 1.5, $point_m_r_v -   2);
                        $pdf->Line($point_f_r_h + 1, $point_f_r_v - 1.5, $point_m_r_h -   1, $point_m_r_v - 1.5);
                        $pdf->Line($point_f_r_h + 1, $point_f_r_v -   1, $point_m_r_h - 0.5, $point_m_r_v -   1);
                        $pdf->Line($point_f_r_h + 1, $point_f_r_v +   1, $point_m_r_h - 0.5, $point_m_r_v +   1);
                        $pdf->Line($point_f_r_h + 1, $point_f_r_v + 1.5, $point_m_r_h -   1, $point_m_r_v + 1.5);
                        $pdf->Line($point_f_r_h + 1, $point_f_r_v +   2, $point_m_r_h - 1.5, $point_m_r_v +   2);

                        // ##############################################
                        // END PLACE BARS ON SIDE OF ARTIST BOX ROUTINE
                        // ##############################################
                    }

                    // #########################################
                    // End Make Box for Artist Routine
                    // #########################################

                } // end if prelabe (creating box for strip)

                // ####################################################
                // Get text properties from strip
                // Including: combine artist a and b into one string if needed
                // ####################################################
                $track_a = $ts_manager->titlestrips[$recordtoprint]->track_a;
                $track_b = $ts_manager->titlestrips[$recordtoprint]->track_b;
                $leftbar = $ts_manager->titlestrips[$recordtoprint]->left_bar;
                $rightbar = $ts_manager->titlestrips[$recordtoprint]->right_bar;
                $combinedartist = $ts_manager->titlestrips[$recordtoprint]->get_combined_artist();
                $publisherinfo = $ts_manager->titlestrips[$recordtoprint]->get_combined_publisherinfo();

                if ($ts_manager->track_upper_case) {
                    $track_a = strtoupper($track_a);
                    $track_b = strtoupper($track_b);
                }

                if ($ts_manager->artist_upper_case) {
                    $combinedartist = strtoupper($combinedartist);
                }

                // #########################################
                // START Change Font Color Routine
                // #########################################

                $pdf->SetTextColor($fontcolorr, $fontcolorg, $fontcolorb);

                // #########################################
                // END Font Color Routine
                // #########################################

                // ####################################################
                // /Start Print The Titles
                // ####################################################

                // set position (top left coordinates) and print title of A side
                $pdf->SetXY(18 + $horiz * 288, 34 + $vert * 72);
                $pdf->CellZ(212, 0, $track_a, '', '', 'C');

                // set position (top left coordinates) and print title of B side
                $pdf->SetXY(18 + $horiz * 288, 73 + $vert * 72);
                $pdf->CellZ(212, 0, $track_b, '', '', 'C');

                // ####################################################
                // /End Print The Titles
                // ####################################################

                // ####################################################
                // /Start Print The Artists, Publisher and Publisher ID
                // ####################################################

                $pdf->SetXY(44 + $horiz * 288, 54 + $vert * 72);
                $pdf->CellZ(156, 0, $combinedartist, '', '', 'C');

                $pdf->SetFont('Helvetica', '', 6);
                $pdf->SetXY(174 + $horiz * 288, 86 + $vert * 72);
                $pdf->CellZ(60, 0, $publisherinfo, '', '', 'L');

                // ##############################################
                // START Print the Type and hit if wanted
                // ##############################################

                // Set text color to white (transparent on paper)
                $pdf->SetTextColor(255, 255, 255);

                // next lines sets top left corner of box. Extra y offset to get off
                // of lines. line after that prints left bar content
                $pdf->SetXY(18 + $horiz * 288, 54 + $vert * 72);
                $pdf->CellZ(20, 0, $leftbar, '', '', 'C');

                // Do the same with the right
                $pdf->SetXY(208 + $horiz * 288, 54 + $vert * 72);
                $pdf->CellZ(20, 0, $rightbar, '', '', 'C');

                // ##############################################
                // END Print the Type and hit if wanted
                // ##############################################

            }
        }
        // #########################################
        // End Print Text Labels Routine
        // #########################################
        break;
    case "image":
        // ######################################
        // ######################################
        // Print labels with an images on them
        // Do it twice for two columns
        // ######################################
        // ######################################
        for ($horiz = 0; $horiz <= 1; $horiz ++) {
            // and do it nine times for ten rows
            for ($vert = 0; $vert <= 9; $vert ++) {
                // ####################################################
                // Pick which record/strip to print based on which cell we are working on
                // ####################################################
                $recordtoprint = (($horiz * 10) + $vert + 1);

                // ####################################################
                // Skip if it's empty (ink saver)
                // ####################################################
                if (   $ts_manager->ink_saver == True
                    && $ts_manager->titlestrips[$recordtoprint]->has_set_values() == False
                   ) {
                    continue;
                }

                $pdf->SetFont($ts_manager->titlefont, $font_style, $fontsize);
                if ( ! $ts_manager->prelabel) {
                    // ###########################################################
                    // START Print the top and bottom lines of the labels Routine
                    // ###########################################################
                    $pdf->SetLineWidth(2);
                    $pdf->SetDrawColor($stripr, $stripg, $stripb);
                    $pdf->Line(18 + $horiz * 288, 22 + $vert * 72, 234 + $horiz * 288, 22 + $vert * 72);
                    if ($vert > 0) {
                        $pdf->Line(18 + $horiz * 288, 22 + $vert * 72, 234 + $horiz * 288, 22 + $vert * 72);
                    }
                    $pdf->Line(18 + $horiz * 288, 90 + $vert * 72, 234 + $horiz * 288, 90 + $vert * 72);
                    // #########################################
                    // End Top and BOttom Line Routine
                    // #########################################

                    // #############################################################################
                    // START Print the LEFT, Picture box line, and RIGHT lines of the labels Routine
                    // #############################################################################
                    $pdf->SetLineWidth(2);
                    $pdf->SetDrawColor($stripr, $stripg, $stripb);
                    $pdf->Line(16  + $horiz * 288, 22 + $vert * 72, 16  + $horiz * 288, 90 + $vert * 72); // Print the Left Line
                    $pdf->Line(236 + $horiz * 288, 22 + $vert * 72, 236 + $horiz * 288, 90 + $vert * 72); // Print the far right line
                    $pdf->Line(81  + $horiz * 288, 22 + $vert * 72, 81  + $horiz * 288, 90 + $vert * 72); // Print the right line next to the picture

                    // ###########################################################
                    // END Print the LEFT and RIGHT lines of the labels Routine
                    // ###########################################################

                    // #########################################
                    // START Make the Box for the Artist Routine
                    // #########################################
                    $pdf->SetLineWidth(1);
                    if ($ts_manager->artistbgr) {
                        $pdf->SetFillColor($artiststripbgr, $artiststripbgg, $artiststripbgb);
                    } else {
                        $pdf->SetFillColor(255, 255, 255); // white/blank
                    }
                    $pdf->SetDrawColor($stripr, $stripg, $stripb);
                    $pdf->Rect(82 - 0.5 + $horiz * 288, 45 + $vert * 72, 154, 18, 'DF'); // Take 0.5 from width for even borders (line width)

                    // #########################################
                    // End Make Box for Artist Routine
                    // #########################################
                } // End if prelabel

                // ####################################################
                // Get text properties from strip
                // Including: combine artist a and b into one string if needed
                // ####################################################

                $track_a = $ts_manager->titlestrips[$recordtoprint]->track_a;
                $track_b = $ts_manager->titlestrips[$recordtoprint]->track_b;
                $leftbar = $ts_manager->titlestrips[$recordtoprint]->left_bar;
                $rightbar = $ts_manager->titlestrips[$recordtoprint]->right_bar;
                $combinedartist = $ts_manager->titlestrips[$recordtoprint]->get_combined_artist();
                $publisherinfo = $ts_manager->titlestrips[$recordtoprint]->get_combined_publisherinfo();

                if ($ts_manager->track_upper_case) {
                    $track_a = strtoupper($track_a);
                    $track_b = strtoupper($track_b);
                }

                if ($ts_manager->artist_upper_case) {
                    $combinedartist = strtoupper($combinedartist);
                }

                // #########################################
                // START Change Font Color Routine
                // #########################################
                $pdf->SetTextColor($fontcolorr, $fontcolorg, $fontcolorb);

                // #########################################
                // END Font Color Routine
                // #########################################


                // #########################################################
                // Print A IMAGE ON THE LABEL ROUTINE.
                // #########################################################

                if (    isset($ts_manager->titlestrips[$recordtoprint]->image_reference)
                    and strlen($ts_manager->titlestrips[$recordtoprint]->image_reference) > 0
                   )
                {
                    if ($ts_manager->titlestrips[$recordtoprint]->image_refernce_is_url()) {
                        // Download file and put into PDF
                        $ts_manager->titlestrips[$recordtoprint]->download_and_store_url_image_file();

                        // Put temp file into PDF
                        $image_file = $ts_manager->titlestrips[$recordtoprint]->image_file_reference;
                        $pdf->Image($image_file, 17 + $horiz * 288, 23 + $vert * 72,  0, 66); // Fixed height, auto width

                        // Delete temp file downloaded
                        $ts_manager->titlestrips[$recordtoprint]->delete_local_image_file();
                    } else {
                        // Put existing file into PDF
                        $image_file = $ts_manager->titlestrips[$recordtoprint]->image_file_reference;
                        $pdf->Image($image_file, 17 + $horiz * 288, 23 + $vert * 72,  0, 66); // Fixed height, auto width
                      //$pdf->Image($image_file, 17 + $horiz * 288, 23 + $vert * 72, 63, 66); // Fixed Dimensions
                    }
                }

                // #########################################################
                // Print THE TITLES OF THE RECORDS ROUTINE.
                // next lines sets top left corner of box. Extra y offset to get off
                // of lines. line after that prints title of a side
                // #########################################################

                // set position (top left coordinates) and print title of A side
                $pdf->SetXY(84 + $horiz * 288, 34 + $vert * 72);
                $pdf->CellZ(148, 0, $track_a, '', '', 'C');

                // set position (top left coordinates) and print title of B side
                $pdf->SetXY(84 + $horiz * 288, 73 + $vert * 72);
                $pdf->CellZ(148, 0, $track_b, '', '', 'C');

                // ####################################################
                // /Start Print The Artists, Publisher and Publisher ID
                // ####################################################

                $pdf->SetXY(84 + $horiz * 288, 54 + $vert * 72);
                $pdf->CellZ(148, 0, $combinedartist, '', '', 'C');

                $pdf->SetFont('Helvetica', '', 6);
                $pdf->SetXY(174 + $horiz * 288, 86 + $vert * 72);
                $pdf->CellZ(60, 0, $publisherinfo, '', '', 'L');
            }
        }
        break;
    // #########################################
    // End Print Text Labels Routine
    // #########################################
}
// we're done. Send it out
$pdf->Output('titlestrips.pdf', 'I');

?>
