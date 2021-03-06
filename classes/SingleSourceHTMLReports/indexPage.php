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
class indexPage extends singleSourceHTMLReportBase{

    private $linklist;

    function __construct( HTMLOutputRenderSource & $obj, $linklist ){
        $this->linklist = $linklist;
        parent::__construct( $obj );
    }

    public function popuplatePageBody(){
        $descr =  ($this->parent->getType() === "S" ) ?
            $this->parent->getPureSource() . " - " . $this->config->getLongNameOfSatSource( $this->parent->getSource()) :
            $this->parent->getPureSource() . " (" . $this->parent->getVisibleType()  . ")";
        $this->setPageTitle( "Overview: " . $descr );
        $this->setDescription("All regional sections, reports and downloads for ". $this->parent->getPureSource() . " in a nutshell.");
        $this->addBodyHeader( "overview" );
        //$userconfig = $this->config->getUploadUserConfigBySource( $this->parent->getType(), $this->parent->getPureSource());
        //$this->appendToBody('<p> This source is being provided by: ' . $userconfig["visibleName"] . '</p>'."\n");
        $this->appendToBody('<ul class="singleSourceMainMenu">');
        $this->appendToBody('<li><p class="caption">Statistics</p>');
        $this->collectStatisticsOfSource();
        $this->appendToBody('</li>');
        foreach ($this->linklist as $linkarray){
            $description = ( $linkarray[2] !== "" ? '<span>'.$linkarray[2] .'</span>' : '');
            $caption = (substr( $linkarray[0], 0, 8) === "Download") ? $linkarray[0] : "Show " . $linkarray[0];
            $this->appendToBody(
                '<li><p class="caption">'.$caption.'</p><p class="description">' .
                $description . '</p>'.
                '<p class="button"><a href="'.$linkarray[1].'">Show</a></p></li>'
            );
        }
        $this->appendToBody('<br clear="all" /></ul>');
        $this->addToOverviewAndSave( $this->parent->getPureSource(), "index.html");
    }

    private function collectStatisticsOfSource(){
        $types = array( "TV", "Radio", "Data");
        $fta = array ( false, true);
        $total = 0;
        $this->appendToBody('<table class="sourcestats"><tr><th>Number of channels</th><th>FTA</th><th>encrypted</th><th>Sum</th></tr>');
        foreach ($types as $type){
            $this->appendToBody("<tr><td>$type</td>");
            $rowtotal = 0;
            foreach ($fta as $isencrypted){
                $count = $this->collectNumberOfChannelsOfSourceSection( $type, $isencrypted);
                $this->appendToBody("<td>$count</td>");
                $rowtotal += $count;
            }
            $this->appendToBody("<td>$rowtotal</td></tr>");
            $total += $rowtotal;
        }
        $this->appendToBody("<tr><td>Summary</td><td></td><td></td><td>$total</td></tr>");
        $this->appendToBody("</table>");
    }

    private function collectNumberOfChannelsOfSourceSection( $type, $encrypted = false){
        $result = $this->db->query( $this->getCustomChannelListSQL( $type, $encrypted, "", "COUNT(*) AS number" ) );
        $info = $result->fetch(PDO::FETCH_ASSOC);
        return $info["number"];
    }

}
?>