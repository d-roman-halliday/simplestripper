<?php

/**
 * 
 * @version $Id$
 * @copyright 2003
 */
class ZPDF extends FPDF{
     function CellZ($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '')
    {
         // Output a cell
        $k = $this -> k;
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
         if($w == 0)
             $w = $this -> w - $this -> rMargin - $this -> x;
         $s = '';
         if($fill == 1 or $border == 1)
        {
             if($fill == 1)
                 $op = ($border == 1) ? 'B' : 'f';
             else
                 $op = 'S';
             $s = sprintf('%.2f %.2f %.2f %.2f re %s ', $this -> x * $k, ($this -> h - $this -> y) * $k, $w * $k, - $h * $k, $op);
             }
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
         if($txt != '')
        {
             // CellZ: Scale
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
             // CellZ
            $s .= sprintf('BT %.2f %.2f Td %.2f Tz (%s) Tj 100 Tz ET', ($this -> x + $dx) * $k, ($this -> h - ($this -> y + .5 * $h + .3 * $this -> FontSize)) * $k, $txt_scale, $txt2);
             if($this -> underline)
                 $s .= ' ' . $this -> _dounderline($this -> x + $dx, $this -> y + .5 * $h + .3 * $this -> FontSize, $txt);
             if($this -> ColorFlag)
                 $s .= ' Q';
             if($link)
                 $this -> Link($this -> x + $dx, $this -> y + .5 * $h - .5 * $this -> FontSize, $this -> GetStringWidth($txt), $this -> FontSize, $link);
             }
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