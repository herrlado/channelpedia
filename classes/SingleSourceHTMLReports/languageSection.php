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
class languageSection extends singleSourceHTMLReportBase{

    private
        $language;

    function __construct( HTMLOutputRenderSource & $obj, $language ){
        $this->language = $language;
        parent::__construct( $obj );
    }

    public function popuplatePageBody(){
        $previewChannelLimit = 100;
        $this->setPageTitle( $this->parent->getSource() . " - Section " . $this->language );
        $this->addBodyHeader( $this->language );
        $this->appendToBody("");
        $nice_html_body = "";
        $nice_html_linklist = "";
        $groupIterator = new channelGroupIterator();
        $groupIterator->init($this->parent->getSource(), $this->language);
        while ($groupIterator->moveToNextChannelGroup() !== false){
            $cols = $groupIterator->getCurrentChannelGroupArray();
            //print "Processing channelgroup ".$cols["x_label"]."\n";
            $html_table = "";
            $shortlabel =
            preg_match ( "/.*?\.\d*?\.(.*)/" , $cols["x_label"], $shortlabelparts );
            if (count($shortlabelparts) == 2)
                $shortlabel =$shortlabelparts[1];
            else
                $shortlabel = $cols["x_label"];
            $prestyle = (strstr($shortlabel, "FTA") === false  || strstr($shortlabel, "scrambled") !== false) ? 'scrambled' : 'fta';
            $icons = "";
            $icons .= (strstr($shortlabel, "FTA") === false  || strstr($shortlabel, "scrambled") !== false) ? ' <img src="'.$this->relPath.'../res/icons/lock.png" class="lock_icon" />' : '';
            $escaped_anchor = htmlspecialchars($shortlabel);
            $escaped_shortlabel = $escaped_anchor. " " . $icons;
            $nice_html_body .=
                '<a name ="'.$escaped_anchor.'">'.
                '<h2 class="'.$prestyle.'">'.
                $escaped_shortlabel . " (" . $cols["channelcount"] . ' channel'.($cols["channelcount"] !== 1 ? 's':'').')'.
                "</h2>\n".
                //"<h3>VDR channel format</h3>\n".
                '<pre class="'.$prestyle.'">';
            $x = new channelIterator( $shortenSource = true);
            //print $this->source. "/" . $cols["x_label"]."\n";
            $x->init1($cols["x_label"], $this->parent->getSource(), $orderby = "UPPER(name) ASC");
            $channelNameList = array();
            while ($x->moveToNextChannel() !== false){
                if ($html_table == ""){
                    $html_table = "<h3>Table view</h3>\n<div class=\"tablecontainer\"><table class=\"nice_table\">\n<tr>";
                    foreach ($x->getCurrentChannelArrayKeys() as $header){
                        $html_table .= '<th class="'.htmlspecialchars($header).'">'.htmlspecialchars(ucfirst($header))."</th>\n";
                    }
                    $html_table .= "</tr>\n";
                }
                $curChan = $x->getCurrentChannelObject();
                $curChanString = htmlspecialchars($curChan->getChannelString());
                if (strlen($curChan->getName()) >2 && substr($curChan->getName(),0,1) !== "."){
                    if (count($channelNameList) < $previewChannelLimit){
                        $channelNameSegments = explode(',', $curChan->getName());
                        $channelNameList[] = htmlspecialchars( count($channelNameSegments) > 0 ? $channelNameSegments[0] : $curChan->getName() );
                    }
                    elseif (count($channelNameList) === $previewChannelLimit)
                        $channelNameList[] = '... ';
                }
                $popuptitle = "". $curChan->getName(). " | ".
                    ($curChan->isSatelliteSource() ?
                        "Type: DVB-S"    . ( $curChan->onS2SatTransponder()   ? "2"        : ""           ) ." | ".
                        "Polarisation: " . ( $curChan->belongsToSatVertical() ? "Vertical" : "Horizontal" ) ." | ".
                        "Band: "         . ( $curChan->belongsToSatHighBand() ? "High"     : "Low"        ) ." | ".
                        "FEC: "          . $curChan->getFECOfSatTransponder()                          ." | "
                    : "" ).
                    "Modulation: "        . $curChan->getModulation() ." | ".
                    "Frequency: "         . $curChan->getReadableFrequency() ." | ".
                    "Symbolrate: "        . $curChan->getSymbolrate() ." | ".
                    "Date added: "        . date("D, d M Y H:i:s", $curChan->getXTimestampAdded() ) . " | ".
                    "Date last changed: " . date("D, d M Y H:i:s", $curChan->getXLastChanged()    ) . " | ".
                    "Date last seen: "    . date("D, d M Y H:i:s", $curChan->getXLastConfirmed()  ) . " ".
                    "";
                //check if channel might be outdated, if so, apply additional css class
                if ( $curChan->getXLastConfirmed() < $this->parent->getLastConfirmedTimestamp())
                    $nice_html_body .= "<span title=\"".$popuptitle."\" class=\"outdated\">". $curChanString ."</span>\n";
                else
                    $nice_html_body .= "<span title=\"".$popuptitle."\">".$curChanString."</span>\n";

                $html_table .= '<tr class="'.$prestyle.'">'."\n";
                //FIXME use channel object here
                foreach ($curChan->getAsArray() as $param => $value){
                    switch ($param){
                        case "apid":
                        case "caid":
                            $value = str_replace ( array(",",";"), ",<br/>", htmlspecialchars($value ));
                            break;
                        case "frequency":
                            $sourcetype = substr($this->parent->getSource(),0,1);
                            if ($sourcetype == "S")
                                $value = $value." MHz";
                            else{
//    * MHz, kHz oder Hz angegeben.
//Der angegebene Wert wird mit 1000 multipliziert, bis er größer als 1000000 ist.
                                 $value2 = intval($value);
                                 $step = 0;    //113000
                                 while($value2 < 1000000){
                                     $step++;
                                     $value2 = $value2 * 1000;
                                 }
                                 $value = $value2 / (1000*1000);
                                 $value = $value . " Mhz";
                            }
                            break;
                        case "x_last_changed":
                            $value = date("D, d M Y H:i:s", $value);
                            break;
                        default:
                            $value = htmlspecialchars($value);
                    }
                    $html_table .= '<td class="'.htmlspecialchars($param).'">'.$value."</td>\n";
                }
                $html_table .= "</tr>\n";
            }
            $html_table .= "</table></div>";
            $nice_html_body .= "</pre>\n</a>\n";
            //$nice_html_body .= "</pre>\n".$html_table;
            // . $cols["channelcount"] . " channels: "
            $nice_html_linklist .=
                '<li><a href="#'.$escaped_anchor.'"><span class="anchorlist_groupname '.$prestyle.'">'.$escaped_shortlabel. "</span><br/>".
                (count($channelNameList) > 0 ? '<span class="anchorlist_channelnames"> <span class="single">'.implode('</span> / <span class="single">',$channelNameList).'</span></span></span>':'').
                '</a></li>'."\n";
        }

        $this->appendToBody(
            "<h2>Groups Overview</h2>\n<div class=\"group_anchors\"><ul class=\"group_anchors\">\n" .
            $nice_html_linklist . "</ul></div>\n".
            $nice_html_body
        );
        $this->addToOverviewAndSave( "", "index.html");
    }

}
?>