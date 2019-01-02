<?php
	function maviTmViewrbam($instance,$query){
		$maviTm =& MaviTmLoads::factory('MaviTmLoads');
		$width = (intval(100 / $instance['postLimit']) - 1);
		echo '<div class="minManset">';
		$barbar = ''; $x = 1;
		while ($query->have_posts())  {
			$query->the_post();
			?>
			<div class="minMansetIc">
				<div class="minMansetResim">
					<a href="<?=get_permalink();?>" title="<?=the_title_attribute('echo=0');?>">
						<?=$maviTm->mavitmThumb('braImg');?>
					</a>
				</div>
				<div class="minMansetText">
					<a href="<?=get_permalink();?>" class="postA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?></a>
				</div>
			</div>
			
			<?php
			$barbar .= '<li'.($instance['postLimit'] == $x ? ' class="noBorder"' : '').' style="width:'.$width.'%;"><a href="'.get_permalink().'" title="'.the_title_attribute('echo=0').'">'.$x.'</a></li>';
			$x++;
		} 
		echo '<ul class="minMansetBar">'.$barbar.'</ul>
		</div>';
	}
?>