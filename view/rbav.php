<?php
	function maviTmViewrbav($instance,$query){
			$maviTm =& MaviTmLoads::factory('MaviTmLoads');
			while ($query->have_posts())  {
			$query->the_post();
			?><li>
				<a href="<?=get_permalink();?>" title="<?=the_title_attribute('echo=0');?>">
					<?=$maviTm->mavitmThumb('rbavImg'); // rbav-> resim - baslik - aciklama - vitrin?>
				</a>
				<a href="<?=get_permalink();?>" class="postA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?></a>				
				<p class="postDesc"><?=karakterKirp(get_the_excerpt(),$instance['tetxLimit']);?></p>
				<div class="clear"></div>
			</li>
			
			<?php
		}
	}
?>