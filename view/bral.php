<?php
	function maviTmViewbral($instance,$query){
		$maviTm =& MaviTmLoads::factory('MaviTmLoads');
		echo '<ul class="bral">';
		while ($query->have_posts())  {
			$query->the_post();
			?><li class="brali"><a href="<?=get_permalink();?>" class="bralpostA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?> </a>
				<ul class="bralInfo">
					<li>
						<a href="<?=get_permalink();?>" title="<?=the_title_attribute('echo=0');?>">
							<?=$maviTm->mavitmThumb('braImg');?>
						</a>
						<p class="postDesc"><?=karakterKirp(get_the_excerpt(),$instance['tetxLimit']);?></p>
					</li>
				</ul>
			</li>
			<?php
		}
		echo '</ul>';
	}

?>