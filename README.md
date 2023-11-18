# simplestripper

Generator for Jukebox title strips. You can see the working versin over at: [simplestripper.roman-halliday.com](https://simplestripper.roman-halliday.com/)

Uses [fpdf](http://www.fpdf.org/) with an input form to generate a pdf file with customisable jukebox strips.

See the attached license file(s) for GNU GPL license.

## V3 Changes

### V3.1.0

This was pushed for release faster than expected, as the regular site scraping of discogs had broken (they addedt a javascriopt check which curl couldn't work with). Now that the API is configured, future changes on it should be easier.

1. Made more columns with show/hide buttons.
2. Changed the iscogs inteagration to use proper API
3. Extended the discogs integration to get more items including release year information (Tried to import album art, but they have block the external image links. Tried to get publisher informatio but old scrapingmethod broke).
4. Technical reworking to split out code into classes, e.g. the submitted form data (reusable in form and strip creation).
5. Lots of behind the scenes technical changes and tweaks (these should make future enhancements easier).
6. Some little UI tweaks.

### V3.0.0

1. New php driven input form (html page archived to html4form.html)
2. Discogs.com integration (give a url for discogs and fetch data)
3. Update index page to use HTML 5 & bootstrap
4. Customise output artist box styles, provide a box to select different artist box style (Arrow, square, hex)
5. Show/hide columns for publisher (A lesser used function to make form cleaner)
6. Added an option to convert all artist and/or track names to upper case

### Future Changes & Ideas

* Cleanup layout of options for labels
* Better use of bootstrap for ease of use and navigation (also make it pretty)
* Add/enhance fonts, try:
  * http://www.fpdf.org/en/tutorial/tuto7.htm
   * https://www.dafont.com/metal-lord.font
* Make more options global OR label speciffic (such as hit/other markings)
* Artist/track speciffic fonts (optional)
* Image based backgrund for labels rather than drawing them (more options)
* Rework drawing to allow for:
  * More dynamic sizing
  * Ink saving (don't print empty boxes)
  * Combined image/text only labels
* Import/export the form/label data as CSV

## V2 Changes (Stephen Rice)

Extensively modified by Stephen Rice.

At version 2.4 (02/27/2020) the following major changes had been implemented:
1. You now can print a page without any lines on it in case you prefer to use pre printed labels.
2. Updates have been done to remain compliant with the newer php server software.
3. Included a updated FPDF version 1.82 software that handles printing in the zip file.
4. You now have color pickers available so that you can change colors of different parts of the label. You should click on the color and pick a color and when done either click off the color or click r tab key. This allows you to have many more choices for the colors would like to print.
5. The labels will now open in a new window so that all the information you have entered is still available if you want to make changes.

## Original (George Howell)

At time of writing, all mirrors of original seem to be down... Fortunately Steve published the updated code (as linked above) so that open source idea of the software could continue:

> I've cobbled together a jukebox title stripper for use via the web.
It is located at:
> http://www.refundersrefuge.org/simplestripper/index.html
>
> This version doesn't let you store information (future version, I promise).
If you can think of any features BESIDES that, please let me know. I would
set it up to print directly onto the blank sheets, but I don't have any.
If someone would send me a couple, I could easily make the changes.
>
> If you have apache (maybe it would work on IIS??) and PHP (required) then
you can download a tarball with the page and php sources from that link.
>
> You will need a copy of acrobat reader (I don't know if xpdf and other
software will work, let me know if it does). And a printer. And a pair of
scissors
> There's no support for single sided strips (used on 78's??) or for
cd title cards, and I'm not adding them. The source is there, you can
do it, or get someone else to do it for you.
>
> Going to start working on a standalone version. That will be Free.
>
> It'll be there for the forseeable future. If I move it, I'll put a
redirector, but I don't see that happening. Feel free to grab the files and
put it on your own sight as well, if you wish. I just had to write it,
because I was disgusted at the thought of laying out $50 for such a
simple program.

 - Taken from: https://alt.collecting.juke-boxes.narkive.com/iRDvgWWu/free-jukebox-titlestrips-online

There is a note on another hosted copy of the code, saying:
> This software is Copyright 2004, [George Howell](mailto:simplestrips@refundersrefuge.org), and is released under the GNU GPL v2. The source can be found [here](http://www.refundersrefuge.org/simplestripper/simplestripper.tar.gz). You also need a copy of the fpdf.php library available [here](http://www.fpdf.org/).

# Links & Other Sites

Some other implementations, and hosting of version 2.4 and below:

* Stephen Rice: http://www.n4yza.com/jukebox/jukebox_labels/index.html
* GraphicStripper v 0.0.2: http://www.pinballrebel.com/archive/other/onlinenew/index.html
* SimpleStripper v 0.0.2: http://www.pinballrebel.com/archive/other/onlinestrips/index.html
* Mikes Arcade: https://www.mikesarcade.com/arcade/titlestrips.html
* Isolated Desert Compound (an awesome javascript form implementation, with templates for using a laser cutter): https://www.isolateddesertcompound.com/stripper/
