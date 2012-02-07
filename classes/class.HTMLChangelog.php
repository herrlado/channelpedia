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

class HTMLChangelog {

    private
        $db,
        $page;

    function __construct( $where, $pagetitle, $limit, $importance, $relPath) {
        $this->db = dbConnection::getInstance();
        $this->page = new HTMLPage($relPath);
        $this->page->setPageTitle($pagetitle);
        $this->page->appendToBody(
            '<h1>'.htmlspecialchars($pagetitle).'</h1><p>Last updated on: '. date("D M j G:i:s T Y")."</p>\n<table>\n"
        );
        if ($importance === 1 ){
            $where[] = " importance = $importance ";
        }
        $wherestring = "";
        if (count($where) > 0){
            $wherestring = "WHERE ". implode(" AND ", $where);
        }
        $result = $this->db->query(
            "SELECT DATETIME( timestamp, 'unixepoch', 'localtime' ) AS datestamp, name, combined_id, importance, update_description ".
            "FROM channel_update_log $wherestring ORDER BY timestamp DESC".$limit
        );
        foreach ($result as $row) {
            $desclist = explode("\n", $row["update_description"]);
            $desc = "";
            foreach ($desclist as $descitem){
                $delimiter = strpos( $descitem, ":");
                $desc .= "<b>" .
                    htmlspecialchars( substr( $descitem,0, $delimiter)) . "</b>" .
                    htmlspecialchars( substr( $descitem, $delimiter)) . "<br/>";
            }
            $class = "changelog_row_style_".$row["importance"];
            $this->page->appendToBody(
                '<tr class="'.$class.'"><td>'.
                htmlspecialchars( $row["datestamp"] ). "</td><td>".
                htmlspecialchars( $row["combined_id"] ). "</td><td>".
                htmlspecialchars( $row["name"] ). "</td><td>".
                $desc.
                "</td></tr>\n"
            );
        }
        $this->page->appendToBody( "<table>\n" );
    }

    public function getContents(){
        return $this->page->getContents();
    }
}
?>