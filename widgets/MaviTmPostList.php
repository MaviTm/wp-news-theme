<?php
	class MaviTmPostList extends WP_Widget{
		
		function MaviTmPostList() {
			$widget_ops = array('classname' => 'widget_MaviTmPostList', 'description' => __('&#304;&ccedil;eriklerinizi de&#287;i&#351;ik formatlarda listeleyin'));
			$control_ops = array('width' => 390, 'height' => 350);
			$this->WP_Widget('MaviTmPostList', __('MaviTm - Post List'), $widget_ops, $control_ops);
		}
		
		function widget($args, $instance){
			extract($args);
			$maviTm =& MaviTmLoads::factory('MaviTmLoads');
			$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );

			$order = isset($instance['postOrderBy']) ? $instance['postOrderBy'] : 'DESC';
			$order_by = isset($instance['postOrder']) ? $instance['postOrder'] : 'date';
			
			$query_args = array(
				'posts_per_page' => ($instance['postLimit'] > 0 ? $instance['postLimit'] : 5),
				'order' => $order,
				'orderby' => $order_by,
			);
			
			//mansette gorunenler diger yerlerde gizlensin istenildi ise
			if(get_option('MaviTmHideManset')){
				$mPostlar = $maviTm->tasiyiciGet('mansetPostlar');
				if(is_array($mPostlar)){
					$query_args['post__not_in'] = $mPostlar;
				}
			}
			
			
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
			}elseif (isset($_GET['cat']) && intval($_GET['cat']) > 0){
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
			
			if (isset($cat_tax) && isset($format_tax)) {
				$tax_query[] = $cat_tax;
				$tax_query[] = $format_tax;
				unset($query_args['cat']);
				$query_args['tax_query'] = $tax_query;
			}
			elseif (isset($cat_tax) || isset($format_tax)) {
				unset($query_args['cat']);
				$tax_query = array(isset($cat_tax) ? $cat_tax : $format_tax);
				$query_args['tax_query'] = $tax_query;
			}
			
			echo $before_widget;
			if($instance['baslikKullanma']){
				echo str_replace('defaultTitle','title title'.$instance['baslikKullanma'],$before_title);		
				echo $title;
				echo $after_title;
			}
			$query = new WP_query($query_args);
			if (isset($query->posts) && is_array($query->posts) && count($query->posts) > 0 && !($separate_widgets && ($post_style == 'thumbnail-full' || $post_style == 'thumbnail-excerpt'))) {
				$noSutun = array("b","rbam","bral");
				?>
					<div class="maviTmPost<?=in_array($instance['postStyle'],$noSutun) == false ? ' widget'.$instance['postSutun'] : '';?>">
						<?php
						$modFile = get_template_directory().'/view/'.$instance['postStyle'].'.php';
						if(file_exists($modFile) && is_file($modFile)){
							include_once($modFile);
							$call = 'maviTmView'.$instance['postStyle'];
							$call($instance,$query);
						}else{
							include_once (get_template_directory().'/view/bra.php');
							maviTmViewbra($instance,$query);
						}
						/*
						while ($query->have_posts())  {
							$query->the_post();
							echo '<li class="css"><a href="'.get_permalink().'" class="post">'.get_the_title().'</a></li>';
						}
						*/
						?>
					</div>
				<?php
			}else{
				//echo 'beni bekleyin.';
			}
			?>
				
			<?php
			echo $after_widget;
		}
		
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance["category"] = $new_instance["category"];
			$instance['postFormats'] = $new_instance['postFormats'];
			$title = stripslashes(trim($new_instance["title"]));
			$instance["title"] = strip_tags($title);// == '' ? $cat->cat_name : $title;
			$instance['postStyle'] = $new_instance['postStyle'];
			$instance['postType'] = $new_instance['postType'];
			$instance['postLimit'] = $new_instance['postLimit'];
			$instance['postOrder'] = $new_instance['postOrder'];
			$instance['postOrderBy'] = $new_instance['postOrderBy'];
			$instance['postSutun'] = $new_instance['postSutun'];
			$instance['baslikKullanma'] = $new_instance['baslikKullanma'];
			$instance['titleLimit'] = $new_instance['titleLimit'];
			$instance['tetxLimit'] = $new_instance['tetxLimit'];
			return $instance;
		}
		
		function form( $instance ) {
			$defaults = array(
				"title" => "&#304;&ccedil;erikler",
				"postLimit" => 5
			);
			$instance = wp_parse_args((array)$instance, $defaults);
			
			?>
			
				<p><img src="<?php bloginfo('template_directory');?>/images/video.jpg" alt="widget-video" align="right" style="padding: 1px; margin: 0px 0px 10px 10px; border: 1px solid #C0CCD3; width:100%;" />
				<div style="clear: both; padding-bottom: 15px; margin-bottom: 20px; border-bottom: 1px solid #C0CCD3;"></div></p>
				<p>
					<label for="<?php echo $this->get_field_id("title"); ?>">
					<?php _e( '<p><b>Ba&#351;l&#305;k :</b></p>' ); ?>
					<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
					<br /><?php _e( 'Bu widget i&ccedil;in bir ba&#351;l&#305;k girin veya varsay&#305;lan metin kullanmak i&ccedil;in bo&#351; b&#305;rak&#305;n.' ); ?><br /><br />
					</label>
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<label for="<?php echo $this->get_field_id('baslikKullanma'); ?>"><?php _e('Baslik:', 'MaviTm'); ?></label>
					<select id="<?php echo $this->get_field_id('baslikKullanma'); ?>" name="<?php echo $this->get_field_name('baslikKullanma'); ?>" class='widefat'>
					<?php
						$options = array(
							'0' => __('Baslik Kullanma', 'MaviTm'),
							'mavi' => __('Mavi', 'MaviTm'),
							'kirmizi' => __('Kirmizi', 'MaviTm'),
							'yesil' => __('Yesil', 'MaviTm'),
							'turuncu' => __('Turuncu', 'MaviTm'),
						);
						foreach ($options as $option_name => $option_text) {
					?>
									<option <?php if (isset($instance['baslikKullanma'])) selected($option_name, $instance['baslikKullanma']); ?> value='<?php echo $option_name;?>'><?php echo $option_text; ?></option>
					<?php
							}
					?>
					</select>
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Kategori Se&ccedil;iniz:', 'MaviTm'); ?></label>
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
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<label for="<?php echo $this->get_field_id('postFormats'); ?>"><?php _e('Format:', 'MaviTm'); ?></label>
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
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<label for="<?php echo $this->get_field_id('postStyle'); ?>"><?php _e('&#304;&ccedil;eriklerin g&ouml;r&uuml;nt&uuml;lenme &#350;ekli:', 'MaviTm'); ?></label>
					<select id="<?php echo $this->get_field_id('postStyle'); ?>" name="<?php echo $this->get_field_name('postStyle'); ?>" class='widefat'>
					<?php
						$post_styles = array('bra' => __('Se&ccedil;iniz', 'MaviTm'),
							'b' => __('Sadece Ba&#351;l&#305;k (aa) (sutun kullanmaz)', 'MaviTm'), //baslik
							'r' => __('Sadece resim (aa or yy) (sutun bulundugu yere gore)', 'MaviTm'), //resim (resim galerisi icin)
							'rb' => __('Resim ve alt&#305;nda ba&#351;l&#305;k (aa or yy) (sutun bulundugu yere gore)', 'MaviTm'),//resim - baslik
							'br' => __('Ba&#351;l&#305;k ve alt&#305;nda resim (aa or yy) (sutun bulundugu yere gore)', 'MaviTm'),//resim - baslik
							'rba' => __('Resim, ba&#351;l&#305;k ve a&ccedil;&#305;klama (aa or yy)', 'MaviTm'), //resim - baslik - aciklama
							'bra' => __('Ba&#351;l&#305;k, resim ve a&ccedil;&#305;klama (aa or yy)', 'MaviTm'), //baslik - resim - aciklama
							'ba' => __('Ba&#351;l&#305;k ve a&ccedil;&#305;klama (aa or yy)', 'MaviTm'), //baslik - aciklama
							'rbam' => __('Minik Manset halinde (&uuml;zerine gelince resim de&#287;i&#351;en) (Hicbiri tek Kutu)', 'MaviTm'), //resim - baslik -aciklama (mini manset)
							'bral' => __('Ba&#351;l&#305;k listesi (&uuml;zerine gelince alt&#305;nda resim ve a&ccedil;&#305;klama g&ouml;r&uuml;nen) (Hicbiri tek Kutu)', 'MaviTm'), //Baslik - resim - aciklama (hover list)
						);
						foreach ($post_styles as $type_id => $type_name) {
					?>
							<option <?php if (isset($instance['postStyle'])) selected($type_id, $instance['postStyle']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
					<?php
							}
					?>
					</select>
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<label for="<?php echo $this->get_field_id('postType'); ?>"><?php _e('&#304;&ccedil;eriklerin g&ouml;r&uuml;nt&uuml;lenme tipi:', 'MaviTm'); ?></label>
					<select id="<?php echo $this->get_field_id('postType'); ?>" name="<?php echo $this->get_field_name('postType'); ?>" class='widefat'>
					<?php
						$post_type = array(
							'yy' => __('Yan yana g&ouml;ster (yy)', 'MaviTm'),
							'aa' => __('Alt Alta g&ouml;ster (aa)', 'MaviTm'),
						);
						foreach ($post_type as $type_id => $type_name) {
					?>
							<option <?php if (isset($instance['postType'])) selected($type_id, $instance['postType']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
					<?php
							}
					?>
					</select>
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
				
					<div style="width:120px; float:left;">
							<label for="<?php echo $this->get_field_id('postOrder'); ?>"><?php _e('<p><b>S&#305;ralanma :</b></p>', 'MaviTm'); ?>
							<select id="<?php echo $this->get_field_id('postOrder'); ?>" name="<?php echo $this->get_field_name('postOrder'); ?>" class='widefat'>
							<?php
								$postOrder = array(
									'date' => __('Eklenme tarihiyle s&#305;rala', 'MaviTm'),
									'id' => __('Post id ye g&ouml;re', 'MaviTm'),
									'author' => __('Ekleyen ismine g&ouml;re', 'MaviTm'),
									'title' => __('Ba&#351;l&#305;&#287;a g&ouml;re ', 'MaviTm'),
									'modified' => __('D&uuml;zenlenme tarihine g&ouml;re', 'MaviTm'),
									'comment_count' => __('Yorum say&#305;s&#305;na g&ouml;re', 'MaviTm'),
									'rand' => __('Rastgele', 'MaviTm'),
								);
								foreach ($postOrder as $type_id => $type_name) {
							?>
									<option <?php if (isset($instance['postOrder'])) selected($type_id, $instance['postOrder']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
							<?php
									}
							?>
							</select>
							<br /><?php _e( 'Field &#350;e&ccedil;imi.' ); ?><br /></label>
					</div>
				
					<div style="width:260px; float:right;">
							<label for="<?php echo $this->get_field_id('postOrderBy'); ?>"><?php _e('<p><b>S&#305;ralanma &#351;ekli :</b></p>', 'MaviTm'); ?>
							<select id="<?php echo $this->get_field_id('postOrderBy'); ?>" name="<?php echo $this->get_field_name('postOrderBy'); ?>" class='widefat'>
							<?php
								$postSutun = array(
									'DESC' => __('Son eklenenden ilk eklenene (DESC)', 'MaviTm'),
									'ASC' => __('&#304;lk eklenenden son eklenene (ASC)', 'MaviTm'),
									'0' => __('Rastgele se&ccedil;im i&ccedil;in tan&#305;ms&#305;z', 'MaviTm')
								);
								foreach ($postSutun as $type_id => $type_name) {
							?>
									<option <?php if (isset($instance['postOrderBy'])) selected($type_id, $instance['postOrderBy']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
							<?php
									}
							?>
							</select>
							<br /><?php _e( 'S&#305;ralama &ouml;nceli&#287;i.' ); ?><br /></label>
					</div>
					
				</p>
				<div style="clear:both; line-height:0px;"></div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<div style="width:100px; float:left; margin-right:8px;">
						<label for="<?php echo $this->get_field_id("postLimit"); ?>">
						<?php _e( '<p><b>Ka&ccedil; tane :</b></p>' ); ?>
						<input class="widefat" id="<?php echo $this->get_field_id("postLimit"); ?>" name="<?php echo $this->get_field_name("postLimit"); ?>" type="text" value="<?php echo esc_attr($instance["postLimit"]); ?>" />
						<br /><?php _e( 'Limit.' ); ?><br /></label>
					</div>
					
					<div style="width:120px; float:left;">
							<label for="<?php echo $this->get_field_id('postSutun'); ?>"><?php _e('<p><b>Ka&ccedil; S&uuml;tun Olsun :</b></p>', 'MaviTm'); ?>
							<select id="<?php echo $this->get_field_id('postSutun'); ?>" name="<?php echo $this->get_field_name('postSutun'); ?>" class='widefat'>
							<?php
								$postSutun = array(
									'1' => __('1 Tane', 'MaviTm'),
									'2' => __('2 Tane', 'MaviTm'),
									'3' => __('3 Tane', 'MaviTm'),
									'4' => __('4 Tane', 'MaviTm')
								);
								foreach ($postSutun as $type_id => $type_name) {
							?>
									<option <?php if (isset($instance['postSutun'])) selected($type_id, $instance['postSutun']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
							<?php
									}
							?>
							</select>
							<br /><?php _e( 'Yan Yana i&ccedil;in.' ); ?><br /></label>
					</div>
				</p>
				<div style="clear:both; line-height:0px;"></div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
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
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<p>
					<b>Not : </b> Bu widget ekledi&#287;iniz sidebar pozisyonuna g&ouml;re de&#287;i&#351;iklik g&ouml;sterecektir<br />
					Kategori se&ccedil;imi yapmad&#305;&#287;&#305;n&#305;zda, hangi kategoriye girerseniz onun bilgilerini 
getirir. E&#287;er kategori se&ccedil;mi yapmad&#305;n&#305;z ve bir kategori i&ccedil;erisindede de&#287;ilseniz 
belirtmi&#351; oldu&#287;unuz s&#305;ralamaya g&ouml;re t&uuml;m kategorilerden getirir.
				</p>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
			<?php			
			
		}
	}
?>