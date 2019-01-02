<?php
	function maviTmViewrb($instance,$query){
		$maviTm =& MaviTmLoads::factory('MaviTmLoads');
		while ($query->have_posts())  {
			$query->the_post();
			?><div class="rb">
				<a href="<?=get_permalink();?>" title="<?=the_title_attribute('echo=0');?>" class="postAimg">
					<?=$maviTm->mavitmThumb('rbImg');?>
				</a>
				<div class="clear"></div>
				<a href="<?=get_permalink();?>" class="postA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?></a>
			</div>
			
			<?php
		}
	}
?>