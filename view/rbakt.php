<?php
	function maviTmViewrbakt($instance,$query){
			$maviTm =& MaviTmLoads::factory('MaviTmLoads');
			while ($query->have_posts())  {
			$query->the_post();
			?><div class="rbakt">
				<a href="<?=get_permalink();?>" title="<?=the_title_attribute('echo=0');?>">
					<?=$maviTm->mavitmThumb('rbaktImg');?>
				</a>
				<a href="<?=get_permalink();?>" class="postA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?></a>				
				<p class="postDesc"><?=karakterKirp(get_the_excerpt(),$instance['tetxLimit']);?></p>
				<div class="altBilgi">
					<p class="braktKat"><?php the_category(', '); ?></p> 
					<p class="braktTarih"><?php the_time('j F, Y'); ?> <?php the_time('g:ia'); ?></p>
					<p class="braktYorum"><?php the_time('j F, Y'); ?> <?php the_time('g:ia'); ?></p>
				</div>
				<div class="clear"></div>
			</div>
			<?php
		}
	}
	
	function maviTmViewrbaktGlob($instance){
			$maviTm =& MaviTmLoads::factory('MaviTmLoads');
			while (have_posts())  {
			the_post();
			?><div class="rbakt">
				<a href="<?=get_permalink();?>" title="<?=the_title_attribute('echo=0');?>">
					<?=$maviTm->mavitmThumb('rbaktImg');?>
				</a>
				<a href="<?=get_permalink();?>" class="postA" title="<?=the_title_attribute('echo=0');?>"><?=karakterKirp(get_the_title(),$instance['titleLimit']);?></a>				
				<p class="postDesc"><?=karakterKirp(get_the_excerpt(),$instance['tetxLimit']);?></p>
				<div class="altBilgi">
					<p class="braktKat"><?php the_category(', '); ?></p> 
					<p class="braktTarih"><?php the_time('j F, Y'); ?> <?php the_time('g:ia'); ?></p>
					<p class="braktYorum">(<?php comments_number('0', '1', '%');?>)</p>
				</div>
				<div class="clear"></div>
			</div>
			<?php
		}
	}
?>