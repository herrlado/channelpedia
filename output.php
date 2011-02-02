<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Henning Pingel
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

require_once 'class.cpbasics.php';
require_once 'class.cpoutput.php';

//output
$x = new cpOutput("/home/hp/Desktop/channels/");

define("HD_CHANNEL"," UPPER(name) LIKE '% HD%' ");
define("DE_PRIVATE_PRO7_RTL"," (provider = 'ProSiebenSat.1' OR provider='Pro7 & Sat.1' OR provider = 'RTL World' OR provider = 'RTL') ");
define("DE_PUBLIC_PROVIDER", " (provider LIKE 'ARD%' OR provider = 'ZDFvision' OR provider = 'ZDF vision') ");

define("AUSTRIA", " (LOWER(name) LIKE '%sterreich' OR LOWER(name) LIKE '% austria' OR UPPER(name) LIKE '% A') ");
define("SWITZERLAND", " (UPPER(name) LIKE '% CH' OR LOWER(name) LIKE '% Schweiz' OR UPPER(name) LIKE 'SF%') ");

define("CABLE_PROVIDER","C[Germany_KabelBW]");

//this filters channels that don't fit into some standard categories
$filter_astra1_fta = " AND ((tid != '1092' AND tid != '1113' AND provider != '-') OR (name = 'DMAX')) AND provider != 'SKY' ";

createEssentialListsDE("S19.2E", $x);
createEssentialListsDE(CABLE_PROVIDER, $x);

$x->updateLabelsOfChannelSelection(
    $label = "au.FTA.SDTV.Private",
    $source = "S19.2E",
    $caidMode = 1,
    $mediaType = 1,
    $language = "deu",
    $orderby="UPPER(name) ASC",
    $customwhere = $filter_astra1_fta . "AND NOT ". HD_CHANNEL. "AND ". AUSTRIA. "AND ". DE_PRIVATE_PRO7_RTL
);

$x->updateLabelsOfChannelSelection(
    $label = "ch.FTA.SDTV.Private",
    $source = "S19.2E",
    $caidMode = 1,
    $mediaType = 1,
    $language = "deu",
    $orderby="UPPER(name) ASC",
    $customwhere = $filter_astra1_fta . "AND NOT ". HD_CHANNEL . "AND ". SWITZERLAND. "AND ". DE_PRIVATE_PRO7_RTL
);

/*
$x->updateLabelsOfChannelSelection(
    $label = "FTA.rubbish",
    $source = "S19.2E",
    $caidMode = 1,
    $mediaType = 1,
    $language = "deu",
    $orderby="UPPER(name) ASC",
    $customwhere =" AND (tid = '1092' OR tid = '1113' OR provider = '-' OR provider = 'SKY') "
    );
*/

$x->updateLabelsOfChannelSelection(
    $label = "at.FTA.Radio.ORF",
    $source = "S19.2E",
    $caidMode = 1,
    $mediaType = 2,
    $language = "deu",
    $orderby="UPPER(name) ASC",
    $customwhere = " AND provider = 'ORF' "
    );

/*

$x->updateLabelsOfChannelSelection(
    $label = "_long.FTA.radio",
    $source = "S28.2E",
    $caidMode = 1,
    $mediaType=2,
    $language = "",
    $orderby="UPPER(name) ASC"
    );

$x->updateLabelsOfChannelSelection( $label = "_long.FTA.tv", $source = "S28.2E", $caidMode = 1, $mediaType=1, $language = "", $orderby="UPPER(name) ASC");
$x->updateLabelsOfChannelSelection( $label = "_long.complete", $source = "S19.2E", $caidMode = 0, $mediaType = 0, $language = "" );
$x->updateLabelsOfChannelSelection( $label = "_long.FTA", $source = "S19.2E", $caidMode = 1, $mediaType = 0, $language = "");
$x->updateLabelsOfChannelSelection( $label = "_long.scrambled", $source = "S28.2E", $caidMode = 2);
$x->updateLabelsOfChannelSelection( $label = "_long.all", $source = "S28.2E", $caidMode = 0);
$x->updateLabelsOfChannelSelection( $label = "_long.C_Germany_KabelBW", $source = CABLE_PROVIDER);
*/

function createEssentialListsDE( $source, $x ){

    $x->updateLabelsOfChannelSelection(
        $label = "de.FTA.SDTV.Public",
        $source,
        $caidMode = 1,
        $mediaType = 1,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere =
            " AND NOT ".HD_CHANNEL.
            "AND UPPER(name) NOT LIKE '%TEST-%' ".
            "AND ( ".
            "(provider = 'ARD' AND ( UPPER(name) LIKE '%ERSTE%' OR UPPER(name) LIKE '%EINS%' OR UPPER(name) LIKE '%ARTE%' OR UPPER(name) LIKE '%PHOENIX%' )) ".
            " OR provider = 'ZDFvision' OR provider = 'ZDF vision'".
        	") "
    );

    $x->updateLabelsOfChannelSelection(
        $label = "de.FTA.SDTV.Public_Regional",
        $source,
        $caidMode = 1,
        $mediaType = 1,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere =
            " AND NOT ".HD_CHANNEL.
            " AND UPPER(name) NOT LIKE '%TEST-%'".
            " AND provider = 'ARD' AND NOT ( UPPER(name) LIKE '%ERSTE%' OR UPPER(name) LIKE '%EINS%' OR UPPER(name) LIKE '%ARTE%' OR UPPER(name) LIKE '%PHOENIX%' ) "
    );

    $x->updateLabelsOfChannelSelection(
        $label = "de.FTA.HDTV.Public",
        $source,
        $caidMode = 1,
        $mediaType = 1,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere = " AND " . HD_CHANNEL. " AND ".DE_PUBLIC_PROVIDER
    );

    $x->updateLabelsOfChannelSelection(
        $label = "de.FTA.HDTV.Private",
        $source,
        $caidMode = 1,
        $mediaType = 1,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere = "AND ". HD_CHANNEL. " AND NOT (" . DE_PUBLIC_PROVIDER . " OR ".AUSTRIA." " . " OR ".SWITZERLAND.")"
    );

    $x->updateLabelsOfChannelSelection(
        $label = "de.FTA.SDTV.Private",
        $source,
        $caidMode = 1,
        $mediaType = 1,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere = "AND ".DE_PRIVATE_PRO7_RTL . "AND NOT (" . HD_CHANNEL . " OR ".AUSTRIA." OR ".SWITZERLAND.")"
    );

    $filter_astra1_fta = " AND ((tid != '1092' AND tid != '1113' AND provider != '-') OR (name = 'DMAX')) AND provider != 'SKY' ";

    $x->updateLabelsOfChannelSelection(
        $label = "de.FTA.SDTV.Rest_Private",
        $source,
        $caidMode = 1,
        $mediaType = 1,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere =
            $filter_astra1_fta . " AND NOT (". HD_CHANNEL . " OR ".DE_PUBLIC_PROVIDER. " OR ".DE_PRIVATE_PRO7_RTL." OR ".AUSTRIA." OR ".SWITZERLAND.") "
    );

    $x->updateLabelsOfChannelSelection(
        $label = "de.FTA.Radio.ARD_ZDF",
        $source,
        $caidMode = 1,
        $mediaType = 2,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere = " AND ".DE_PUBLIC_PROVIDER
    );

    $x->updateLabelsOfChannelSelection(
        $label = "de.FTA.Radio.Private",
        $source,
        $caidMode = 1,
        $mediaType = 2,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere = " AND NOT ".DE_PUBLIC_PROVIDER
    );

    $x->updateLabelsOfChannelSelection(
        $label = "de.SKY.SDTV",
        $source,
        $caidMode = 2,
        $mediaType = 1,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere = " AND (UPPER(provider) = 'SKY' OR provider = '') AND name != '.' AND UPPER(name) NOT LIKE '% HD%' "
    );

    $x->updateLabelsOfChannelSelection(
        $label = "de.SKY.HDTV",
        $source,
        $caidMode = 2,
        $mediaType = 1,
        $language = "deu",
        $orderby="UPPER(name) ASC",
        $customwhere = " AND (UPPER(provider) = 'SKY' OR provider = '') AND name != '.' AND UPPER(name) LIKE '% HD%' "
    );
}

function createEssentialListsFRA( $source, $x ){

    $x->updateLabelsOfChannelSelection(
        $label = "fra.FTA.TV",
        $source = "S19.2E",
        $caidMode = 1,
        $mediaType = 1,
        $language = "fra",
        $orderby = "UPPER(name) ASC"
    );
/*
    $x->updateLabelsOfChannelSelection(
        $label = "_long.fra.scrambled.TV",
        $source = "S19.2E",
        $caidMode = 2,
        $mediaType = 1,
        $language = "fra",
        $orderby="UPPER(name) ASC",
        $customwhere = ""
    );
*/
}
?>