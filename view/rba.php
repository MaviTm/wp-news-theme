<?php
	function maviTmViewrba($instance,$query){
			$maviTm =& MaviTmLoads::factory('MaviTmLoads');
			while ($query->have_posts())  {
			$query->the_post();
			?><div class="rba">
				<a href="<?=get_permalink();?>" title="<?=the_title_attribute('echo=0');?>">
					<?=$maviTm->mavitmThumb('braImg');?>
				</a>
				<a href="<?=get_permalink();?>" class="postA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?></a>				
				<p class="postDesc"><?=karakterKirp(get_the_excerpt(),$instance['tetxLimit']);?></p>
				<div class="clear"></div>
			</div>
			
			<?php
		}
	}
?>