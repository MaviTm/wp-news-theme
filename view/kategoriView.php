<?php
	//tek kategori gosterimleri
	function widgetKategoriListesi($instance){
			
			$maviTm =& MaviTmLoads::factory('MaviTmLoads');
			
			$left = array(0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36);
			$left2 = array(1,4,7,10,13,16,19,22,25,28,30,33,36);
			$poz = $instance['arg']['id']; 
			$genislik = $instance['poz'][$poz];
			if($genislik != '980'){
				$bak = $left;
				$class = array('left', 'right');
			}else{
				$bak = $left2;
				$class = array('lrm', 'left');
			}
			$order = isset($instance['postOrderBy']) ? $instance['postOrderBy'] : 'DESC';
			$order_by = isset($instance['postOrder']) ? $instance['postOrder'] : 'date';
			$query_args = array(
				'posts_per_page' => ($instance['postLimit'] > 0 ? $instance['postLimit'] : 5),
				'order' => $order,
				'orderby' => $order_by,
			);
			
			$kategoriler = explode(',',$instance['kategoriler']);
			if(!is_array($kategoriler)){ return ;}
			
			$i = 0;
			foreach ($kategoriler as $cat){
				$x = 0; $viewAlan = '<div class="kategoriUstView">'; $viewAlanUrl = '';
				$query_args['cat'] = $cat;
				$query = new WP_query($query_args);
				while ($query->have_posts())  {
					$query->the_post();
					$viewAlan .= '<div class="katUst'.($x >= 1 ? ' none': '').' degis'.$x.'">';
					$viewAlan .= '<a href="'.get_permalink().'" title="'.the_title_attribute('echo=0').'">'.$maviTm->mavitmThumb('braImg').'</a>';
					$viewAlan  .= '<a href="'.get_permalink().'" title="'.the_title_attribute('echo=0').'" class="postA">'.karakterKirp(get_the_title(),$instance['titleLimit']).'</a>';
					$viewAlanUrl .= '<a href="'.get_permalink().'" title="'.the_title_attribute('echo=0').'" class="katDegistirUrl" rel="degis'.$x.'">'.karakterKirp(get_the_title(),$instance['titleLimit']).'</a>'; 
					$viewAlan .= '<p class="postDesc">'.karakterKirp(get_the_excerpt(),$instance['tetxLimit']).'</p><div class="clear"></div>';
					$viewAlan .= '</div>';
				$x++;}
				
				$viewAlan .= '</div><div class="clear"></div>';
				$katBaslik = get_the_category_by_ID($cat);
				?>
					<div class="kategoriKutulari <?=in_array($i,$bak) ? $class[0] : $class[1];?>">
						<div class="kategoriBaslik">
							<a href="<?=get_category_link($cat);?>" class="catTitle"><?=$katBaslik;?></a>
							<a href="<?=get_bloginfo('url');?>/?cat=<?=$cat;?>&feed=rss2" class="catRss">
								<img src="<?php bloginfo('template_directory');?>/images/feed.png" alt="Rss" title="<?=$katBaslik;?> Rss" />
							</a>
						</div>
						<div class="katDegisenAlan">
							<?=$viewAlan;?>
						</div>
						<?=$viewAlanUrl;?>
					</div>
				<?php
			$i++;}
	}
?>