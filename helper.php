<?php
/**
 * @package     SlideShow
 * @subpackage  mod_articles_slideshow
 * @copyright   Copyright (C) 2013 MS, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Tiago Garcia modulo reescrito 90% do original(reslider).
 * @internal    Módulo Slide Show - LRCF008
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_content/helpers/route.php';
require_once JPATH_SITE.'/modules/mod_articles_category/helper.php';

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

/**
 * Helper for mod_articles_slideshow
 *
 * @package     Joomla.Site
 * @subpackage  mod_articles_slideshow
 */
class modArticlesSlideShowHelper
{
	public static function getList(&$params){

		$app = JFactory::getApplication();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$appParams = JFactory::getApplication()->getParams();

		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));

		$model->setState('filter.published', 1);

		$model->setState('list.select');

		// Category filter
		$cat = $params->get('catid');
		$model->setState('filter.category_id', (int) $cat[0]);
		$model->setState('filter.subcategories', true);

		// set only featured
		$model->setState('filter.featured', 'only');

		// Set ordering
		$ordering = $params->get('ordering', 'a.ordering');
		$model->setState('list.ordering', $ordering);

		if (trim($ordering) == 'rand()')
		{
			$model->setState('list.direction', '');
		}
		else
		{
			$model->setState('list.direction', 'ASC');
		}

		//	Retrieve Content
		$items = $model->getItems();

		foreach ($items as &$item)
		{
			$item->slug = $item->id.':'.$item->alias;
			// $item->catslug = $item->catid.':'.$item->category_alias;
			$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
			$item->linkText = JText::_('MOD_ARTICLES_SLIDESHOW_READMORE');

			$images = json_decode($item->images);  // decode da imagem.

			// Exibe link externo.
			$linkExterno = '';
			$target = null;

			// verifica se o titulo do caption vai ser exibido
			if ($params->get('caption_titulo')) {
				$caption_titulo = $item->title;
			}
			// verifica se o texto caption vai ser exibido
			if ($params->get('caption_texto')) {
				$caption_texto = $item->introtext;
			}

			// configura se a div flex cpation vai ser exibida.
			$flex = 0;
			if($params->get('caption_titulo') || $params->get('caption_texto')){
				$flex = 1;
			}

			// Texto - se for introtext, limpando e limitando.
			if ($params->get('caption_texto'))
			{
				$caption_texto = JHtml::_('content.prepare', $caption_texto, '', 'mod_articles_slideshow.content');
				$caption_texto = modArticlesCategoryHelper::_cleanIntrotext($caption_texto);
				$caption_texto = preg_replace('/<img[^>]*>/', '', $caption_texto);
				$caption_texto = modArticlesCategoryHelper::truncate($caption_texto, 220);
			}

			if ($params->get('uselinks')) {

				$link = "";
				$label = "";
				$urls = json_decode($item->urls);

				if ($params->get('link_artigo_externo')){
					$link = $item->link;
				}
				else
				{
					if (!empty($urls->urla)) :
						$link = $urls->urla;
					$target = $urls->targeta;
					$id = 'a';
					$label = $urls->urlatext;
					endif;
				}

				// If no target is present, use the default
				// $target = $target ? $target : $item->params->get('target'.$id);

				// verifica se tem url e trata o tipo  de window
				switch ($target)
				{
					case 1:
					// open in a new window
					$linkExterno = '<a href="'. htmlspecialchars($link) .'" target="_blank"  rel="nofollow">'
					. "<img src='".JURI::base().htmlspecialchars($images->image_intro)."' alt='".htmlspecialchars($label)."'>" .'</a>';
					break;

					case 2:
					// open in a popup window
					$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=600';
					$linkExterno = "<a href=\"" . htmlspecialchars($link) . "\" onclick=\"window.open(this.href, 'targetWindow', '".$attribs."'); return false;\">"
					. "<img src='".JURI::base().htmlspecialchars($images->image_intro)."' alt='".htmlspecialchars($label)."'>" .'</a>';
					break;

					default:
					// open in parent window
					$linkExterno =  '<a href="'.  htmlspecialchars($link) . '" rel="nofollow">'
					. "<img src='".JURI::base().htmlspecialchars($images->image_intro)."' alt='".htmlspecialchars($label)."'>" .' </a>';
					break;
				}

				switch ($target)
				{
					case 1:
					// open in a new window
					$linkCaption = '<a href="'. htmlspecialchars($link) .'" target="_blank"  rel="nofollow">'
					.($params->get('caption_titulo') == 1 ? "<h4>".htmlspecialchars($caption_titulo)."</h4>" : '')
					. ($params->get('caption_texto') == 1 ? "<p>".htmlspecialchars($caption_texto)."</p>" : '') .'</a>';
					break;

					case 2:
					// open in a popup window
					$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=600';
					$linkCaption = "<a href=\"" . htmlspecialchars($link) . "\" onclick=\"window.open(this.href, 'targetWindow', '".$attribs."'); return false;\">"
					. ($params->get('caption_titulo') == 1 ? "<h4>".htmlspecialchars($caption_titulo)."</h4>" : '')
					. ($params->get('caption_texto') == 1 ? "<p>".htmlspecialchars($caption_texto)."</p>" : '') .'</a>';
					break;

					default:
					// open in parent window
					$linkCaption =  '<a href="'.  htmlspecialchars($link) . '" rel="nofollow">'
					. ($params->get('caption_titulo') == 1 ? "<h4>".htmlspecialchars($caption_titulo)."</h4>" : '')
					. ($params->get('caption_texto') == 1 ? "<p>".htmlspecialchars($caption_texto)."</p>" : '') .' </a>';
					break;
				}
			}

			// constroi a lista <li>
			$item->metadesc = "<li>"
				// Usando links nas imagens para o artigo
			. ($params->get('uselinks') == 1 ? $linkExterno : "<img src='".JURI::base().htmlspecialchars($images->image_intro)."' alt='".$images->image_intro_alt."'>")
				// Usando Caption
			. ($flex == 1 ? "<div class='flex-caption hidden-phone'>" : '');

			if ($params->get('caption_link')){
				$item->metadesc .= $linkCaption;
			}else{
				$item->metadesc .= ($params->get('caption_titulo') == 1 ? "<h4>".htmlspecialchars($caption_titulo)."</h4>" : '')
				. ($params->get('caption_texto') == 1 ? "<p>".htmlspecialchars($caption_texto)."</p>" : '');
			}
			$item->metadesc .= ($flex == 1 ? "</div>" : '') . "</li>";

		}
		return $items;
	}


	public static function load_jquery(&$params)
	{
		if($params->get('load_jquery')){
			JLoader::import( 'joomla.version' );
			$version = new JVersion();
			if (version_compare( $version->RELEASE, '2.5', '<=')) {
				$doc = JFactory::getDocument();
				$app = JFactory::getApplication();
				$file= JURI::root(true).'/modules/mod_articles_slideshow/assets/js/jquery-1.7.2.min.js';
				$file2= JURI::root(true).'/modules/mod_articles_slideshow/assets/js/noconflict.js';
				$doc->addScript($file);
				$doc->addScript($file2);
			} else {
				JHtml::_('jquery.framework');
			}
		}
	}
}