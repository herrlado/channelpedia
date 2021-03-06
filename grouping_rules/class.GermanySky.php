<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 - 2012 Henning Pingel
*  All rights reserved
*
*  This script is part of the yaVDR project. yaVDR is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*/

class GermanySky  extends ruleBase{

    function __construct(){

    }

    function getConfig(){
        return array (
            "country" => "sky_de",
            "lang" => "deu", //this is the language code used in the channels audio description
            "validForSatellites" => array( "S19.2E"),
            "validForCableProviders" => "all",
            "validForTerrProviders" => array(), //todo: set none!!!
        );
    }

    function getGroups(){
        return array (

/*
 *    10 FTA HDTV (we don't expect to find much here)
 *    15 FTA SDTV  (we don't expect to find much here)
 *
 *    30 Welt scrambled HDTV
 *    40 Welt scrambled SDTV
 *    41 Welt Extra scrambled SDTV
 *
 *    50 Film scrambled HDTV
 *    51 Film scrambled SDTV
 *
 *   100 Sport scrambled HDTV
 *   110 Sport scrambled SDTV
 *   120 Sport scrambled SDTV (channels dynamicly assigned to live matches)
 *
 *   200 Select Portal FTA SDTV
 *   201 Select scrambled SDTV
 *
 *   400 Blue Movie HDTV
 *   401 Blue Movie SDTV
 *
 *   450 Diverse scrambled SDTV (what still didn't match...)
 *
 *   500 Diverse scrambled Radio
 */


            array(
                "title" => "",
                "outputSortPriority" => 10,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeHDTV,
                "customwhere" => " AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined')"
            ),

            array(
                "title" => "Select Portal",
                "outputSortPriority" => 200,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined') AND name LIKE 'sky select%'"
            ),


            array(
                "title" => "",
                "outputSortPriority" => 15,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined')"
            ),

            array(
                "title" => "Sport",
                "outputSortPriority" => 100,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeHDTV,
                "languageOverrule" => "", //ESPN America HD is in English!
                "customwhere" => " AND (UPPER(provider) = 'SKY') AND name != '.' AND NOT name LIKE '%news%' AND NOT name LIKE '%eurosport hd%'  AND (name LIKE '%sport%' OR name LIKE 'sky bundesliga%' OR name LIKE 'espn%')"
                //OR provider = '' OR UPPER(provider) = 'UNDEFINED'
            ),

            array(
                "title" => "Blue Movie",
                "outputSortPriority" => 400,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeHDTV,
                "languageOverrule" => "",
                "customwhere" => " AND (UPPER(provider) = 'SKY' OR provider = '') AND name LIKE '%blue movie%'"
            ),

            array(
                "title" => "Blue Movie",
                "outputSortPriority" => 401,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "languageOverrule" => "",
                "customwhere" => " AND (UPPER(provider) = 'SKY' OR provider = '') AND name LIKE '%blue movie%'"
            ),

            //provider undefined only wilhelm.tel --> sky
            array(
                "title" => "Film",
                "outputSortPriority" => 50,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeHDTV,
                "customwhere" => " AND name NOT LIKE '% - %' ".
                                 "AND name != 'Spieldaten' ".
                                 "AND name NOT LIKE  '%pitlane%' ".
                                 "AND name NOT LIKE  '%racer%' ".
                                 "AND name NOT LIKE  '%konf%' ".
                                 "AND name NOT LIKE  '%liga%' ".
                                 "AND name NOT LIKE '%sky 3d%' ".
                                 "AND name NOT LIKE '%krimi%' ".
                                 "AND name NOT LIKE '%sport news%' ".
                                 "AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined') ".
                                 "AND name != '.' ".
                                 "AND (name LIKE 'sky%' OR name LIKE '%mgm%' OR name LIKE 'disney cinemagic%')"
            ),

            array(
                "title" => "Welt Extra",
                "outputSortPriority" => 41,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND ".DE_PRIVATE_PRO7_RTL . "AND NOT (" . AUSTRIA." OR ".SWITZERLAND.")"
            ),

            array(
                "title" => "Welt Extra",
                "outputSortPriority" => 41,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND (
                                     name LIKE 'axn action%' OR
                                     lower(name) = 'boomerang' OR
                                     name LIKE 'cartoon network%' OR
                                     lower(name) = 'history' OR
                                     name LIKE 'kinowelt tv%' OR
                                     name LIKE 'biography channel%' OR
                                     name LIKE 'tnt film%' OR
                                     name LIKE 'romance tv%' OR
                                     name LIKE 'animax%' OR
                                     name LIKE 'espn%' OR
                                     name LIKE 'eurosport 2%'
                                 ) "
            ),

            array(
                "title" => "Welt",
                "outputSortPriority" => 30,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeHDTV,
                //"languageOverrule" => "",
                "customwhere" => " AND (UPPER(provider) = 'SKY') AND name != '.'"
                //OR provider = '' OR UPPER(provider) = 'UNDEFINED'
            ),

            array(
                "title" => "Sport",
                "outputSortPriority" => 110,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "languageOverrule" => "", //ESPN America HD is in English!
                "customwhere" => " AND (UPPER(provider) = 'SKY') AND name != '.' AND NOT name LIKE '%news%' AND (name LIKE '%sport%' OR name LIKE 'sky bundesliga%' OR name LIKE '%espn%')"
                //OR provider = '' OR UPPER(provider) = 'UNDEFINED'
            ),

            //provider undefined only wilhelm.tel --> sky
            array(
                "title" => "Film",
                "outputSortPriority" => 51,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND name NOT LIKE '% - %' AND name != 'Spieldaten' AND name NOT LIKE  '%pitlane%'  AND name NOT LIKE  '%racer%' AND name NOT LIKE  '%konf%' AND name NOT LIKE  '%liga%' AND name NOT LIKE '%sky 3d%' AND name NOT LIKE '%krimi%'  AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined') AND name != '.' AND (name LIKE 'sky%' OR name LIKE '%mgm%' OR name LIKE 'disney cinemagic%')"
            ),

            //provider undefined only wilhelm.tel --> sky
            array(
                "title" => "Welt",
                "outputSortPriority" => 40,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND name NOT LIKE '% - %' AND name != 'Spieldaten'  AND name NOT LIKE  '%pitlane%'  AND name NOT LIKE  '%racer%' AND name NOT LIKE  '%konf%' AND name NOT LIKE  '%liga%' AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined') AND name != '.'"
            ),

            //provider undefined only wilhelm.tel --> sky
            array(
                "title" => "Select",
                "outputSortPriority" => 201,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND name LIKE '%|%' AND name LIKE '% - %' AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined')"
            ),

            //provider undefined only wilhelm.tel --> sky
            array(
                "title" => "Sport",
                "outputSortPriority" => 120,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND (name LIKE '% - %' OR name = 'Spieldaten' OR name LIKE '%konf%' OR name LIKE '%liga%' OR name LIKE  '%pitlane%'  OR name LIKE  '%racer%' ) AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined')"
            ),

            //provider undefined only wilhelm.tel --> sky
            array(
                "title" => "Diverse",
                "outputSortPriority" => 450,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" => " AND (UPPER(provider) = 'SKY' OR provider = '' OR provider = 'undefined')"
            ),

            //provider undefined only wilhelm.tel --> sky
            array(
                "title" => "Diverse",
                "outputSortPriority" => 500,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeRadio,
                "customwhere" => " AND (UPPER(provider) LIKE 'SKY' OR provider = '' OR provider = 'undefined')"
            ),
        );
    }
}

?>