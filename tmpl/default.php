<?php
/**
 * @package     Slideshow
 * @subpackage  mod_articles_slideshow
 * @copyright   Copyright (C) 2013, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @internal    Módulo Slide Show - LRCF008
 */

// No direct access.
defined('_JEXEC') or die;

$category_route = 'index.php/produtos';

?>

<div class="flexslider <?php echo $params->get('moduleclass_sfx'); ?>">
  <ul class="slides">
  	<?php
  		foreach($items as $item){
  			echo $item->metadesc;
  		}
  	?>
  </ul>

  <!-- Verifica se o link para os artigos dessa categoria será mostrado. -->
  <?php if ($params->get('categoria_link')) { ?>
    <div id="slideshowDisplayNav" class="hidden-phone">
      <a title="Mais Notícias" href="<?php echo $category_route; ?>" class="leiaMais pull-right">
        Veja todos
      </a>
    </div>
  <?php } ?>

</div>


<script type="text/javascript" charset="utf-8">
  jQuery(window).load(function() {
    jQuery('.flexslider').flexslider({
       <?php if($params->get('fadeorslide') == 0){?> animation: "slide", <?php } else if ($params->get('fadeorslide') == 1){ ?> animation: "fade", <?php } ?>
       <?php if($params->get('directionNav') == 1){?> directionNav: true, <?php } else if ($params->get('directionNav') == 0){ ?> directionNav: false, <?php } ?>
       <?php if($params->get('controlNav') == 1){?> controlNav: true, <?php } else if ($params->get('controlNav') == 0){ ?> controlNav:false, <?php } ?>
       <?php if($params->get('keyboardNav') == 1){?> keyboardNav: true, <?php } else if ($params->get('keyboardNav') == 0){ ?> keyboardNav:false, <?php } ?>
       <?php if($params->get('direction') == 0){?> direction: "horizontal", <?php } else if ($params->get('direction') == 1){ ?> direction: "vertical", <?php } ?>
       <?php if($params->get('slidespeed')){ echo "slideshowSpeed:".$params->get('slidespeed')."," ;} else { ?> slideshowSpeed: 7000, <?php } ?>
       <?php if($params->get('animatespeed')){ echo "animationSpeed:".$params->get('animatespeed')."," ;} else { ?> animationSpeed: 600, <?php } ?>
       <?php if($params->get('randomorder') == 0){?> randomize: false <?php } else if ($params->get('randomorder') == 1){ ?> randomize: true <?php } ?>
    });
  });
</script>
