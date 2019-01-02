<?php
	function maviTmViewbr($instance,$query){
			$maviTm =& MaviTmLoads::factory('MaviTmLoads');
			while ($query->have_posts())  {
			$query->the_post();
			?><div class="br">
				<a href="<?=get_permalink();?>" class="postA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?></a>
				<div class="clear"></div>
				<a href="<?=get_permalink();?>" title="<?=the_title_attribute('echo=0');?>" class="postAimg">
					<?=$maviTm->mavitmThumb('brImg');?>
				</a>
				<div class="clear"></div>
			</div>
			
			<?php
		}
	}
?>