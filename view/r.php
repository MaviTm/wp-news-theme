<?php
	function maviTmViewr($instance,$query){
		while ($query->have_posts())  {
			$query->the_post();
			if(has_post_thumbnail()) {
			$large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'large'); 
			?><div class="tekResim">
				<a href="<?=$large_image_url[0];?>" title="<?=the_title_attribute('echo=0');?>" class="postAimgSingle" rel="MaviTmWidgetGal">
					<?php the_post_thumbnail(array(100,70 ), array( 'class' => 'rImg','title'=>the_title_attribute('echo=0') )); ?>
				</a>
			</div>
			<?php
			}
		}
	}
?>