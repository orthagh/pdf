<?php
/**
 * @version $Id$
 -------------------------------------------------------------------------
 LICENSE

 This file is part of PDF plugin for GLPI.

 PDF is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 PDF is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with Reports. If not, see <http://www.gnu.org/licenses/>.

 @package   pdf
 @authors   Nelly Mahu-Lasson, Remi Collet
 @copyright Copyright (c) 2009-2015 PDF plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.indepnet.net/projects/pdf
 @link      http://www.glpi-project.org/
 @since     2009
 --------------------------------------------------------------------------
*/


class PluginPdfContract_Item extends PluginPdfCommon {

   static $rightname = "plugin_pdf";


   function __construct(CommonGLPI $obj=NULL) {
      $this->obj = ($obj ? $obj : new Contract_Item());
   }


   static function pdfForItem(PluginPdfSimplePDF $pdf, CommonDBTM $item){
      global $DB,$CFG_GLPIG;

      $type = $item->getType();
      $ID   = $item->getField('id');
      $con  = new Contract();

      $query = "SELECT *
                FROM `glpi_contracts_items`
                WHERE `glpi_contracts_items`.`items_id` = '".$ID."'
                      AND `glpi_contracts_items`.`itemtype` = '".$type."'";

      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $i = $j = 0;

      $pdf->setColumnsSize(100);
      if ($number > 0) {
         $pdf->displayTitle('<b>'._N('Associated contract', 'Associated contracts', 2).'</b>');

         $pdf->setColumnsSize(19,19,19,16,11,16);
         $pdf->displayTitle(__('Name'), _x('phone', 'Number'), __('Contract type'),
                            __('Supplier'), __('Start date'), __('Initial contract period'));

         $i++;

         while ($j < $number) {
            $cID     = $DB->result($result, $j, "contracts_id");
            $assocID = $DB->result($result, $j, "id");

            if ($con->getFromDB($cID)) {
               $pdf->displayLine(
                  (empty($con->fields["name"]) ? "(".$con->fields["id"].")" : $con->fields["name"]),
                  $con->fields["num"],
                  Html::clean(Dropdown::getDropdownName("glpi_contracttypes",
                                                       $con->fields["contracttypes_id"])),
                  str_replace("<br>", " ", $con->getSuppliersNames()),
                  Html::convDate($con->fields["begin_date"]),
                  sprintf(_n('%d month', '%d months', $con->fields["duration"]),
                          $con->fields["duration"]));
            }
            $j++;
         }
      } else {
         $pdf->displayTitle("<b>".__('No item found')."</b>");
      }
      $pdf->displaySpace();
   }
}