<?php
	class MaviTmManset extends WP_Widget{
		
		function MaviTmManset() {
			$widget_ops = array('classname' => 'widget_MaviTmManset', 'description' => __('iceriklerinizi manset olarak goruntuler'));
			$control_ops = array('width' => 500, 'height' => 350);
			$this->WP_Widget('MaviTmManset', __('MaviTm - Haber Manset'), $widget_ops, $control_ops);
		}
		
		function widget( $args, $instance ) {
			extract($args);
			$maviTm =& MaviTmLoads::factory('MaviTmLoads');
			$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
						
			$order = 'DESC';
			$order_by = isset($instance['postOrder']) ? $instance['postOrder'] : 'date';
			
			$query_args = array(
				'posts_per_page' => ($instance['postLimit'] > 0 ? $instance['postLimit'] : 5),
				'order' => $order,
				'orderby' => $order_by,
			);
			
			$tax_query = array(
				'relation' => 'AND',
			);
			
			if ($instance["category"] != '0' && $instance["category"] != 0) {
				$query_args['cat'] = $instance["category"];
				$cat_tax = array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => array($instance["category"]),
					'operator' => 'IN',
				);
			}elseif (is_category()){
				global $wp_query;
				$cat_obj = $wp_query->get_queried_object();
				//$cat_obj->name or $cat_obj->term_id;
				$query_args['cat'] = $cat_obj->term_id;
				$cat_tax = array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => array($cat_obj->term_id),
					'operator' => 'IN',
				);
			}
			
			if ($instance['postFormats'] != '') {
				$pf = $instance['postFormats'];
				$format_tax = array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => array("post-format-$pf"),
					'operator' => 'IN',
				);
			}
			
			if (isset($format_tax)) {
				unset($query_args['cat']);
				$tax_query = array(isset($cat_tax) ? $cat_tax : $format_tax);
				$query_args['tax_query'] = $tax_query;
			}
			echo $before_widget;
			?>
			
			<div class="mansetler">
				<?php
				$query = new WP_query($query_args);
				$manset = array(); $mansetBar = array(); $gor = '';
				if (isset($query->posts) && is_array($query->posts) && count($query->posts) > 0 ) {
					while ($query->have_posts())  {	$query->the_post();
						$resim = $maviTm->MaviTmMansetResimBul();
						
						$gor = '<div class="mansetWiev none">';
						$gor .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.$resim['buyuk'].'</a>';
						
						if($instance['mansetText'] != 'bos'){
							$gor .= '<div class="mansetDesc">';
							$gor .= '<h3>'.karakterKirp(stripslashes(strip_tags(get_the_title())),(intval($instance['titleLimit']) > 0 ? intval($instance['titleLimit']) : 30)).'</h3>';
							if($instance['mansetText'] == 'baslikDesc'){
								$gor .= '<p>'.karakterKirp(stripslashes(strip_tags(get_the_excerpt())),(intval($instance['tetxLimit']) > 0 ? intval($instance['tetxLimit']) : 100)).'</p>';
							}
							$gor .= '</div>';
						}

						$gor .= '</div>';					
						$manset[] = $gor;
						$gor = '';
						$id = get_the_ID();
						$mansetBar[$id]['resim'] = $resim['kucuk'];
						$mansetBar[$id]['link'] = get_permalink();
						$mansetBar[$id]['title'] = get_the_title();
					}
				}else{
					$manset[] = '<div class="mansetWiev"><img src="'.get_bloginfo("template_url").'/images/resimyok.jpg" alt="resimYok" class="mansetResim" />
					<div class="mansetDesc"><h3>Veri Bulunmuyor.</h3></div></div>';
					$mansetBar[] = '<img src="'.get_bloginfo("template_url").'/images/resimyok.jpg" alt="resimYok" class="mansetResim" />';
				}
				$toplam = count($mansetBar);
				echo '<div class="mensetSlide">';
				echo implode('',$manset);
				echo '</div>';
				$ulClass = 'hover'.rand(5,99999);
				$speed = $instance['mansetSpeed'] < 2000 ? 8000 : $instance['mansetSpeed'];
				
				//ikinci yada 3. manset eklendi ise oncekilerinin bilgilerine sahip cik onlari koru ve gozet ezilmelerine ve somuruye ugramasina musade etme
				$ulstyle = $maviTm->tasiyiciGet('mansetUl');
				$speedNe = $maviTm->tasiyiciGet('mansetSpeed');
				$mPostlar = $maviTm->tasiyiciGet('mansetPostlar');
				
				if(empty($ulstyle)){
					$maviTm->tasiyiciSet('mansetUl',$ulClass);	
					$maviTm->tasiyiciSet('mansetSpeed',$speed);	
					$maviTm->tasiyiciSet('mansetPostlar',array_keys($mansetBar));
				}else{
					if(is_array($ulstyle)){
						$ulKaydet = array_merge($ulstyle,array($ulClass));
						$speedK = array_merge($speedNe,array($speed));
						$maviTm->tasiyiciSet('mansetUl',$ulKaydet);
						$maviTm->tasiyiciSet('mansetSpeed',$ulKaydet);
						$postIdleri = array_unique(array_merge($mPostlar,array_keys($mansetBar)));
						$maviTm->tasiyiciSet('mansetPostlar',$postIdleri);
					}else{
						$maviTm->tasiyiciSet('mansetUl',array($ulstyle,$ulClass));
						$maviTm->tasiyiciSet('mansetSpeed',array($speed,$speedNe));	
						$postIdleri = array_unique(array_merge($mPostlar,array_keys($mansetBar)));
						$maviTm->tasiyiciSet('mansetPostlar',$postIdleri);
					}
				}
				
				?>
				
				<?php if($instance['postStyle'] == 'resim'){?>
				<ul class="<?=$ulClass;?> mansetBarResimli" rel="<?=$ulClass;?>">
					<?php foreach ($mansetBar as $bar){?>
							<li class="mnsResim"><a href="<?=$bar['link'];?>" title="<?=$bar['title'];?>"><?=$bar['resim'];?></a></li>	
					<?php }?>
				</ul>
				<?php 
					}else{
					$width = ((100 / $query_args['posts_per_page']) - 0.2);
				?>
					<ul class="<?=$ulClass;?> mansetBar" rel="<?=$ulClass;?>">
						<?php $x = 1; foreach ($mansetBar as $bar){?>
								<li class="mnsSayi<?=$toplam == $x ? ' noBorder' : '';?>" style="width:<?=$width;?>%;"><a href="<?=$bar['link'];?>" title="<?=$bar['title'];?>"><?=$x;?></a></li>
						<?php $x++;}?>
					</ul>
					
				<?php }?>
				
			</div>	
			
			<?php
			echo $after_widget;
		}
		
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance["category"] = $new_instance["category"];
			$instance['postFormats'] = $new_instance['postFormats'];
			$instance['postOrder'] = $new_instance['postOrder'];
			$instance['postLimit'] = $new_instance['postLimit'];
			$instance['postStyle'] = $new_instance['postStyle'];
			$instance['mansetText'] = $new_instance['mansetText'];
			$instance['titleLimit'] = $new_instance['titleLimit'];
			$instance['tetxLimit'] = $new_instance['tetxLimit'];
			$instance['mansetSpeed'] = $new_instance['mansetSpeed'];
			return $instance;
		}
		
		function form( $instance ) {
			$defaults = array(
				"postLimit" => 10,
				"titleLimit" => 5,
				"tetxLimit" => 10,
				"mansetSpeed" => 8000
			);
			$instance = wp_parse_args((array)$instance, $defaults);
			
			?>
			
				<p><img src="<?php bloginfo('template_directory');?>/images/video.jpg" alt="MaviTm" align="right" style="padding: 1px; margin: 0px 0px 10px 10px; border: 1px solid #C0CCD3; width:100%;" />
				<div style="clear: both; padding-bottom: 15px; margin-bottom: 20px; border-bottom: 1px solid #C0CCD3;"></div></p>
				
				
				<p>
					<div style="width:240px; float:left; margin-right:19px;">
						<label for="<?php echo $this->get_field_id('category'); ?>"><b><?php _e('Kategori Se&ccedil;iniz:', 'MaviTm'); ?></b></label>
						<?php
							$cat_args = array('hierarchical' => 1,
								'name' => $this->get_field_name('category'),
								'class' => 'widefat',
								'show_option_all' => __('(T&uuml;m Kategoriler)', 'MaviTm'),
							);
							if (isset($instance['category'])) $cat_args['selected'] = $instance['category'];
							wp_dropdown_categories($cat_args);
						?>
						<i><?php _e("Hangi kategori icin listeleme yap&#305;caks&#305;n&#305;z ?", "MaviTm"); ?></i>
					</div>
					<div style="width:240px; float:left;">
						<label for="<?php echo $this->get_field_id('postFormats'); ?>"><b><?php _e('Format:', 'MaviTm'); ?></b></label>
						<select id="<?php echo $this->get_field_id('postFormats'); ?>" name="<?php echo $this->get_field_name('postFormats'); ?>" class='widefat'>
						<?php
							$options = array(
								'' => __('T&uuml;m Formatlar', 'MaviTm'),
								'default' => __('Standart', 'MaviTm'),
								'aside' => __('Kenar', 'MaviTm'),
								'gallery' => __('Galeri', 'MaviTm'),
								'link' => __('Link', 'MaviTm'),
								'image' => __('Image', 'MaviTm'),
								'quote' => __('Quote', 'MaviTm'),
								'status' => __('Status', 'MaviTm'),
								'video' => __('Video', 'MaviTm'),
								'audio' => __('Audio', 'MaviTm'),
								'chat' => __('Chat', 'MaviTm')
							);
							foreach ($options as $option_name => $option_text) {
						?>
										<option <?php if (isset($instance['postFormats'])) selected($option_name, $instance['postFormats']); ?> value='<?php echo $option_name;?>'><?php echo $option_text; ?></option>
						<?php
								}
						?>
						</select>
					</div>
					
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
				
					<div style="width:120px; float:left; margin-right:10px;">
							<label for="<?php echo $this->get_field_id('postOrder'); ?>"><?php _e('<p><b>S&#305;ralanma :</b></p>', 'MaviTm'); ?></label>
							<select id="<?php echo $this->get_field_id('postOrder'); ?>" name="<?php echo $this->get_field_name('postOrder'); ?>" class='widefat'>
							<?php
								$postOrder = array(
									'date' => __('Eklenme tarihiyle s&#305;rala (DESC)', 'MaviTm'),
									'id' => __('Post id ye g&ouml;re (DESC)', 'MaviTm'),
									'author' => __('Ekleyen ismine g&ouml;re (DESC)', 'MaviTm'),
									'title' => __('Ba&#351;l&#305;&#287;a g&ouml;re (DESC)', 'MaviTm'),
									'modified' => __('D&uuml;zenlenme tarihine g&ouml;re (DESC)', 'MaviTm'),
									'comment_count' => __('Yorum say&#305;s&#305;na g&ouml;re (DESC)', 'MaviTm'),
									'rand' => __('Rastgele', 'MaviTm'),
								);
								foreach ($postOrder as $type_id => $type_name) {
							?>
									<option <?php if (isset($instance['postOrder'])) selected($type_id, $instance['postOrder']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
							<?php
									}
							?>
							</select>
							<br /><?php _e( 'Field &#350;e&ccedil;imi.' ); ?><br />
					</div>
					<div style="width:60px; float:left; margin-right:10px;">
						<label for="<?php echo $this->get_field_id("postLimit"); ?>"><?php _e( '<p><b>Ka&ccedil; tane :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("postLimit"); ?>" name="<?php echo $this->get_field_name("postLimit"); ?>" type="text" value="<?php echo esc_attr($instance["postLimit"]); ?>" />
						<br /><?php _e( 'Limit.' ); ?><br />
					</div>
					<div style="width:120px; float:left; margin-right:10px;">
							<label for="<?php echo $this->get_field_id('postStyle'); ?>"><?php _e('<p><b>Gorunum :</b></p>', 'MaviTm'); ?></label>
							<select id="<?php echo $this->get_field_id('postStyle'); ?>" name="<?php echo $this->get_field_name('postStyle'); ?>" class='widefat'>
							<?php
								$postOrder = array(
									'resim' => __('Resim Kareleri', 'MaviTm'),
									'sayi' => __('Sayi kutulari', 'MaviTm')
								);
								foreach ($postOrder as $type_id => $type_name) {
							?>
									<option <?php if (isset($instance['postStyle'])) selected($type_id, $instance['postStyle']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
							<?php
									}
							?>
							</select>
							
					</div>
					<div style="width:150px; float:left;">
						<label for="<?php echo $this->get_field_id("mansetSpeed"); ?>"><?php _e( '<p><b>Otomatik gecis hizi :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("mansetSpeed"); ?>" name="<?php echo $this->get_field_name("mansetSpeed"); ?>" type="text" value="<?php echo esc_attr($instance["mansetSpeed"]); ?>" />
						<br /><?php _e( '5Sn => 5000, 8Sn => 8000.' ); ?><br />
					</div>
					
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
				
					<div style="width:160px; float:left; margin-right:10px;">
							<label for="<?php echo $this->get_field_id('mansetText'); ?>"><?php _e('<p><b>Yazi Gorunumu :</b></p>', 'MaviTm'); ?></label>
							<select id="<?php echo $this->get_field_id('mansetText'); ?>" name="<?php echo $this->get_field_name('mansetText'); ?>" class='widefat'>
							<?php
								$postOrder = array(
									'baslik' => __('Sadece Baslik', 'MaviTm'),
									'baslikDesc' => __('Baslik ve aciklama', 'MaviTm'),
									'bos' => __('Hic birsey gosterme', 'MaviTm'),
								);
								foreach ($postOrder as $type_id => $type_name) {
							?>
									<option <?php if (isset($instance['mansetText'])) selected($type_id, $instance['mansetText']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
							<?php
									}
							?>
							</select>
							<br /><?php _e( 'Resim uzerinde neler gorunsun.' ); ?><br />
					</div>
					<div style="width:150px; float:left; margin-right:10px;">
						<label for="<?php echo $this->get_field_id("titleLimit"); ?>"><?php _e( '<p><b>Baslik kelime! sayisi :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("titleLimit"); ?>" name="<?php echo $this->get_field_name("titleLimit"); ?>" type="text" value="<?php echo esc_attr($instance["titleLimit"]); ?>" />
						<br /><?php _e( 'h3 baslik.' ); ?><br />
					</div>
					<div style="width:150px; float:left; margin-right:19px;">
						<label for="<?php echo $this->get_field_id("tetxLimit"); ?>"><?php _e( '<p><b>Text kelime! sayisi :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("tetxLimit"); ?>" name="<?php echo $this->get_field_name("tetxLimit"); ?>" type="text" value="<?php echo esc_attr($instance["tetxLimit"]); ?>" />
						<br /><?php _e( 'p text.' ); ?><br />
					</div>
				</p>
				<div style="clear:both; line-height:0px;"></div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<b>Not:</b> <i>Otomatik gecis hizi hizi min. 2000 dir altinda girsenizde 8000 olur 2000 veya ustu bir deger giriniz</i><br />
					<i>Manset stop olayi kalici degil, manset uzerine gelindiginde durur gidildiginde devam eder.</i>
				</p>
			<?php			
			
		}
				
		
	}
?>