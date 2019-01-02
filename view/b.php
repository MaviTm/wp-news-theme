<?php
	function maviTmViewb($instance,$query){
		echo '<ul class="postTekBaslik">';
			while ($query->have_posts())  {
				$query->the_post();
				echo '<li class="postTekBaslikLi"><a href="'.get_permalink().'" class="postA" title="'.the_title_attribute('echo=0').'">'.karakterKirp(get_the_title(),$instance['tetxLimit']).'</a></li>
				';
			}
		echo '</ul>';	
	}
?>