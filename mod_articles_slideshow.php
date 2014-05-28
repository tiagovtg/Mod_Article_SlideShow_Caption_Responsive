<?php
/**
 * @package     Articles_Slideshow
 * @subpackage  com_articles_slideshow
 * @copyright   Copyright (C) 2013 MS, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @internal    Módulo Slide Show - LRCF008
 * @author      Tiago Garcia.
 */

// No direct access.
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

// carrega os dados da listagem
$items = modArticlesSlideShowHelper::getList($params);

// carrega os js e css
modArticlesSlideShowHelper::load_jquery($params);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base(true) . '/modules/mod_articles_slideshow/assets/css/style.css', 'text/css' );
$doc->addStyleSheet(JURI::base(true) . '/modules/mod_articles_slideshow/assets/css/flexslider.css', 'text/css' );
$doc->addScript(JURI::base(true)     . '/modules/mod_articles_slideshow/assets/js/jquery.flexslider-min.js');

// chama a view default
require(JModuleHelper::getLayoutPath('mod_articles_slideshow'));
