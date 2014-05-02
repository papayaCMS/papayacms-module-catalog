<?php
/**
* Administration module catalog
*
* @copyright 2002-2009 by papaya Software GmbH - All rights reserved.
* @link http://www.papaya-cms.com/
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @package Papaya-Modules
* @subpackage Free-Catalog
* @version $Id: edmodule_catalog.php 39686 2014-03-24 14:16:52Z weinert $
*/

/**
* Administration module catalog
*
* @package Papaya-Modules
* @subpackage Free-Catalog
*/
class edmodule_catalog extends base_module {

  /**
  * permissions
  *
  * @var array $permissions
  */
  var $permissions = array(
    1 => 'Manage',
    2 => 'Manage types',
  );


  /**
  * Function for execute module
  *
  * @access public
  */
  function execModule() {
    if ($this->hasPerm(1, TRUE)) {
      $catalogDatabase = new admin_catalog;
      $catalogDatabase->module = $this;
      $catalogDatabase->layout = $this->layout;
      $catalogDatabase->initialize();
      $catalogDatabase->execute();
      $catalogDatabase->getXML();
    }
  }
}
