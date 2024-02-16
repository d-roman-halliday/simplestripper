<?php

/**
 * 
 * @version $Id$
 * @copyright 2003
 */
class SsFpdfExtended extends FPDF{

    // Extend standard cell function with one that allows scaling text within the bo (so if the text is too long, it's squished into place)

    // It looks like this class was inspired by these examples:
    // - https://www.fpdf.org/en/script/script32.php
    // - https://www.fpdf.org/en/script/script62.php
    // Common calls (from printstrips.php):
    // - CellZ(148, 0, $titleb[$recordtoprint], '', '', 'C');
    // - CellZ(60,  0, $publisherinfo,          '', '', 'L');

    function CellZ($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '')
    {
        //      w = Cell width. If 0, the cell extends up to the right margin. (same as standard cell())
        //      h = Cell height. Default value: 0. (same as standard cell())
        //   text = String to print. Default value: empty string. (same as standard cell())
        // border = <not used always blank>  (same as standard cell())
        //     ln = <not used always blank>  (same as standard cell())
        //  align = <text alignment> : L = Left, C = Center

        // Output a cell
        $k = $this -> k;

        //If vertical position is over the PageBreakTrigger, add a Page Break
        if($this -> y + $h > $this -> PageBreakTrigger and !$this -> InFooter and $this -> AcceptPageBreak())
        {
            // Automatic page break
            $x = $this -> x;
            $ws = $this -> ws;
            if($ws > 0)
            {
                $this -> ws = 0;
                $this -> _out('0 Tw');
            }
            $this -> AddPage($this -> CurOrientation);
            $this -> x = $x;
            if($ws > 0)
            {
                $this -> ws = $ws;
                $this -> _out(sprintf('%.3f Tw', $ws * $k));
            }
        }

        // Set horizontal point within margin if none is give/0 value
        if($w == 0)
            $w = $this -> w - $this -> rMargin - $this -> x;

        // Not used by printstrips.php (border always '')
        $s = '';
        if($fill == 1 or $border == 1)
        {
            if($fill == 1)
                $op = ($border == 1) ? 'B' : 'f';
            else
                $op = 'S';
            $s = sprintf('%.2f %.2f %.2f %.2f re %s ', $this -> x * $k, ($this -> h - $this -> y) * $k, $w * $k, - $h * $k, $op);
        }

        // Not used by printstrips.php (border always '')
        if(is_string($border))
        {
            $x = $this -> x;
            $y = $this -> y;
            if(is_int(strpos($border, 'L')))
                $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $x * $k, ($this -> h - $y) * $k, $x * $k, ($this -> h - ($y + $h)) * $k);
            if(is_int(strpos($border, 'T')))
                $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $x * $k, ($this -> h - $y) * $k, ($x + $w) * $k, ($this -> h - $y) * $k);
            if(is_int(strpos($border, 'R')))
                $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', ($x + $w) * $k, ($this -> h - $y) * $k, ($x + $w) * $k, ($this -> h - ($y + $h)) * $k);
            if(is_int(strpos($border, 'B')))
                $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $x * $k, ($this -> h - ($y + $h)) * $k, ($x + $w) * $k, ($this -> h - ($y + $h)) * $k);
        }

        // Craete text part
        if(trim($txt) != '')
        {
            // FPDF has limitations of fonts and encoding
            // If problems percist, I might need to switch to tFPDF: http://www.fpdf.org/?go=script&id=92

            // Dirty hack: Replace a common problem character
            //$txt = str_replace("’", "'", $txt);

            // These didn't work Change encoding - Thin original strings from discogs are UTF-8
            //$alternative_encoded_string = mb_convert_encoding($txt, 'ASCII', 'UTF-8');           // No
            //$alternative_encoded_string = mb_convert_encoding($txt, 'ISO-8859-1', 'UTF-8');      // No
            //$alternative_encoded_string = mb_convert_encoding($txt, 'ASCII//TRANSLIT', 'UTF-8'); // No
            //$alternative_encoded_string = mb_convert_encoding($txt,  'windows-1252', 'UTF-8');   // Yes

            // replace Microsoft Word version of single  and double quotations marks (“ ” ‘ ’) with  regular quotes (' and ")
            //$alternative_encoded_string = iconv('UTF-8', 'ASCII//TRANSLIT', $txt); // Yes
            // From FAQ: Don't use UTF-8 with the standard fonts; they expect text encoded in windows-1252
            $alternative_encoded_string = iconv('UTF-8', 'windows-1252', $txt);      // Yes

            $txt = $alternative_encoded_string;

            // CellZ: Scale text (if string is too long for box)
            $txt_scale = 100.0;
            $str_width = $this -> GetStringWidth($txt);
            if ($str_width > $w) $txt_scale = $w / $str_width * 100.0;

            if($align == 'R')
                // CellZ
                $dx = $w - $this -> cMargin - $this -> GetStringWidth($txt) * $txt_scale / 100.0;
            elseif($align == 'C')
                // CellZ
                $dx = ($w - $this -> GetStringWidth($txt) * $txt_scale / 100.0) / 2;
            else
                $dx = $this -> cMargin;
            if($this -> ColorFlag)
                $s .= 'q ' . $this -> TextColor . ' ';
            $txt2 = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));

            // CellZ: Create text string with scaling information to insert into pdf
            $s .= sprintf('BT %.2f %.2f Td %.2f Tz (%s) Tj 100 Tz ET', ($this -> x + $dx) * $k, ($this -> h - ($this -> y + .5 * $h + .3 * $this -> FontSize)) * $k, $txt_scale, $txt2);
            if($this -> underline)
                $s .= ' ' . $this -> _dounderline($this -> x + $dx, $this -> y + .5 * $h + .3 * $this -> FontSize, $txt);
            if($this -> ColorFlag)
                $s .= ' Q';
            if($link)
                $this -> Link($this -> x + $dx, $this -> y + .5 * $h - .5 * $this -> FontSize, $this -> GetStringWidth($txt), $this -> FontSize, $link);
        }

        // Craete output
        if($s)
            $this -> _out($s);
        $this -> lasth = $h;
        if($ln > 0)
        {
            // Go to next line
            $this -> y += $h;
            if($ln == 1)
                $this -> x = $this -> lMargin;
        }
        else
            $this -> x += $w;
    }
}
?>