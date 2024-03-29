<?php
/**
* Catalog page navigation
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
* @version $Id: actbox_catalog_postrequest_navigation.php 39496 2014-03-03 16:45:40Z weinert $
*/

/**
* Catalog page navigation - outputs a list of catalog
* catagories and links to a catalog content page
*
* @package Papaya-Modules
* @subpackage Free-Catalog
*/
class actionbox_catalog_postrequest_navigation extends base_actionbox {

  /**
  * Content edit fields
  * @var array $editFields
  */
  var $editFields = array(
    'page_id' => array ('Page Id', 'isNum', TRUE, 'input', 5, ''),
    'categ_base_id' => array ('Catalog Id', 'isNum', TRUE, 'input', 5, ''),
    'image' => array('Image', 'isSomeText', TRUE, 'mediafile', 400, '', '')
  );

  /**
  * Parameter name
  * @var string $paramName
  */
  var $paramName = 'catalog_request';

  /**
   * Get cache id
   *
   * @access public
   * @param string $additionalCacheString
   * @return boolean defualt value FALSE
   */
  function getCacheId($additionalCacheString = '') {
    $this->initializeParams();
    if (!empty($this->params['relocation'])) {
      return parent::getCacheId(
        $this->params['relocation'].$additionalCacheString
      );
    } else {
      return parent::getCacheId($additionalCacheString);
    }
  }

  /**
  * Return page XML
  *
  * @access public
  * @return string $str
  */
  function getParsedData() {
    $this->initializeParams();
    if (!empty($this->params['relocation'])) {
      $url = $this->getAbsoluteURL($this->params['relocation']);
      /** @var papaya_page $page */
      $page = $GLOBALS['PAPAYA_PAGE'];
      $page->protectedRedirect(301, $url);
    }

    $pageId = @(int)$this->data['page_id'];
    if (isset($GLOBALS['PAPAYA_PAGE'])
      && isset($GLOBALS['PAPAYA_PAGE']->requestData['categ_id'])) {
        $currentCategId = (int)$GLOBALS['PAPAYA_PAGE']->requestData['categ_id'];
    } else {
      $currentCategId = 0;
    }
    $catalog = new base_catalog();
    $catalog->tableTopics = $this->parentObj->tableTopics;
    $catalog->tableTopicsTrans = $this->parentObj->tableTopicsTrans;
    $catalog->loadCatalogList(
      empty($this->data['categ_base_id']) ? 0 : (int)$this->data['categ_base_id'],
      $this->parentObj->getContentLanguageId()
    );
    if (isset($catalog->catalogList) && is_array($catalog->catalogList) &&
        count($catalog->catalogList) > 0) {
      $str = sprintf(
        '<sitemap action="%s" parametername="%s" href="%s" img="%s" format="static" date="%s">'.LF,
        papaya_strings::escapeHTMLChars($this->getWebLink()),
        papaya_strings::escapeHTMLChars($this->paramName.'[relocation]'),
        papaya_strings::escapeHTMLChars($this->getWebLink($pageId)),
        papaya_strings::escapeHTMLChars($this->getWebMediaLink($this->data['image'])),
        date('Y-m-d H:i:s')
      );
      foreach ($catalog->catalogList as $category) {
        $focus = ($category['catalog_id'] == $currentCategId) ? ' focus="focus"' : '';
        $str .= sprintf(
          '<mapitem id="%d" href="%s" title="%s" enctitle="%s"'.
          ' children="0" allchildren="0" visible="1" %s/>'.LF,
          papaya_strings::escapeHTMLChars($category['catalog_id']),
          papaya_strings::escapeHTMLChars(
            $this->getWebLink(
              $pageId,
              NULL,
              NULL,
              NULL,
              NULL,
              $category['catalog_title'],
              $category['catalog_id']
            )
          ),
          papaya_strings::escapeHTMLChars($category['catalog_title']),
          urlencode($category['catalog_title']),
          $focus
        );
      }
      $str .= '</sitemap>'.LF;
      return $str;
    }
    return '';
  }
}
