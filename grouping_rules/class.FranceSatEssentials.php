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

class FranceSatEssentials  extends ruleBase{

    function __construct(){

    }

    function getConfig(){
        return array(
            "country" => "fr",
            "lang" => "fra", //this is the language code used in the channels audio description
            "validForSatellites" => array( "S19.2E"),
            "validForCableProviders" => array(),//none
            "validForTerrProviders" => array(),//none
        );
    }

    function getGroups(){
        return array(

            array(
                "title" => "CSAT",
                "outputSortPriority" => 5,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeHDTV,
                "languageOverrule" => "", //for CSAT
                "customwhere" => " AND ". FRANCE_CSAT
            ),

            array(
                "title" => "CSAT",
                "outputSortPriority" => 6,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeHDTV,
                "languageOverrule" => "", //for CSAT
                "customwhere" => " AND ". FRANCE_CSAT
            ),

            array(
                "title" => "CSAT",
                "outputSortPriority" => 7,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeSDTV,
                "languageOverrule" => "", //for CSAT
            	"customwhere" =>  " AND ". FRANCE_CSAT
            ),

            array(
                "title" => "CSAT",
                "outputSortPriority" => 8,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "languageOverrule" => "", //for CSAT
            	"customwhere" =>  " AND ". FRANCE_CSAT
            ),

            array(
                "title" => "MTVNetworksEurope",
                "outputSortPriority" => 9,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "languageOverrule" => "",
                "customwhere" => " AND UPPER(provider) = 'MTV NETWORKS EUROPE' AND UPPER(name) LIKE '%FRANCE%'"
            ),

            array(
                "title" => "Diverse",
                "outputSortPriority" => 1,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeHDTV,
                "customwhere" =>  ""
            ),

            array(
                "title" => "Diverse",
                "outputSortPriority" => 2,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeHDTV,
                "customwhere" =>  ""
            ),

            array(
                "title" => "Diverse",
                "outputSortPriority" => 3,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" =>  ""
            ),

            array(
                "title" => "Diverse",
                "outputSortPriority" => 4,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeSDTV,
                "customwhere" =>  ""
            ),

            array(
                "title" => "Diverse",
                "outputSortPriority" => 20,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeRadio,
                "customwhere" => ""
            ),

            array(
                "title" => "Diverse",
                "outputSortPriority" => 21,
                "caidMode" => self::caidModeScrambled,
                "mediaType" => self::mediaTypeRadio,
                "languageOverrule" => "", //for CSAT
                "customwhere" => ""
            ),

            array(
                "title" => "CSAT",
                "outputSortPriority" => 22,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeRadio,
                "languageOverrule" => "", //for CSAT
                "customwhere" => "AND ". FRANCE_CSAT
            ),

            array(
                "title" => "CSAT",
                "outputSortPriority" => 30,
                "caidMode" => self::caidModeFTA,
                "mediaType" => self::mediaTypeData,
                "languageOverrule" => "", //for CSAT
            	"customwhere" =>  " AND ". FRANCE_CSAT
            ),

        );
    }

}

?>