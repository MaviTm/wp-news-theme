<?php
	function maviTmViewba($instance,$query){
		while ($query->have_posts())  {
			$query->the_post();
			?><div class="ba">
				<a href="<?=get_permalink();?>" class="postA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?></a>
				<p class="postDesc"><a href="<?=get_permalink();?>" class="postDescA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_excerpt(),$instance['tetxLimit']);?></a></p>
				<div class="clear"></div>
			</div>
			
			<?php
		}
	}
?>