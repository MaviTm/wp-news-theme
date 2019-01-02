<?php
	class MaviTmCarusel extends WP_Widget {
		public function MaviTmCarusel() {
			$widget_ops = array('classname' => 'widget_MaviTmCarusel', 'description' => __('İçeriklerinizi sağa, sola veya yukarı aşağı hareket edettiren bir uygulama'));
			$control_ops = array('width' => 390, 'height' => 350);
			$this->WP_Widget('MaviTmCarusel', __('MaviTm - Carusel'), $widget_ops, $control_ops);
		}
		
		public function widget( $args, $instance ) {
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
			if (isset($query->posts) && is_array($query->posts) && count($query->posts) > 0 ) {
				$kayacakDiv = 'vtrn'.rand(6,999999);
				$kaykayConf = array(
					"akistipi" => $instance['akistipi'],
					"netarafa" => $instance['netarafa'],
					"otomatik" => $instance['otomatik'],
					"otomatikKapat" => $instance['otomatikKapat'],
					"gecisSn" => $instance['gecisSn'], 
					"duraklamaSn" => $instance['duraklamaSn'],
					"bunauygula" => $kayacakDiv
				);
				?>
					<div class="maviTmPostKaykay">
						<div class="<?=$kayacakDiv;?>Left leftArrow"></div>
						<div class="ulkaps <?=$kayacakDiv;?> kview<?=$instance['hidden'];?>">
							<ul>
								<?php
									$file = get_template_directory().'/view/rbav.php';
									$file = str_replace(array('/','\\'), array(DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR), $file);
									include_once ($file);
									maviTmViewrbav($instance,$query);
								?>
							</ul>
						</div>
						<div class="<?=$kayacakDiv;?>Right rightArrow"></div>
						<div class="clear"></div>
					</div>
				<?php				
				$is_Kaykay = $maviTm->tasiyiciGet('kaykay');
				if(empty($is_Kaykay)){
					$maviTm->tasiyiciSet('kaykay',array($kaykayConf));
				}else{
					$is_Kaykay[] = $kaykayConf;
					$maviTm->tasiyiciSet('kaykay',$is_Kaykay);
				}
			}else{
				//echo 'beni bekleyin.';
			}
			?>
				
			<?php
			echo $after_widget;
		}
	
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			foreach ($new_instance as $key => $value) {
				$instance[$key] = strip_tags($value);
			}
			
			return $instance;
		}
		
		
		public function form($instance){
			$defaults = array(
				"title" => "Vitrin",
				"postLimit" => 10,
				"titleLimit" => 3,
				"tetxLimit" => 6,
				"gecisSn" => 400,
				"duraklamaSn" => 2000
			);
			$instance = wp_parse_args((array)$instance, $defaults);
			
			?>
				<p><img src="<?php bloginfo('template_directory');?>/images/video.jpg" alt="widget-video" align="right" style="padding: 1px; margin: 0px 0px 10px 10px; border: 1px solid #C0CCD3; width:100%;" />
				<div style="clear: both; padding-bottom: 15px; margin-bottom: 20px; border-bottom: 1px solid #C0CCD3;"></div></p>
				<div>
					<label for="<?php echo $this->get_field_id('baslikKullanma'); ?>"><?php _e('<p><b>Basl&#305;k Bi&ccedil;imi:</b></p>', 'MaviTm'); ?></label>
					<select id="<?php echo $this->get_field_id('baslikKullanma'); ?>" name="<?php echo $this->get_field_name('baslikKullanma'); ?>" class="widefat baslikOnOff">
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
				</div>
				<div>
					<label for="<?php echo $this->get_field_id("title"); ?>"><?php _e( '<p><b>Ba&#351;l&#305;k :</b></p>' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
					<br /><?php _e( 'Bu widget i&ccedil;in bir ba&#351;l&#305;k girin' ); ?><br /><br />
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<div>
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
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<div>
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
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<div>
				
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
					
				</div>
				<div style="clear:both; line-height:0px;"></div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<div>
					<div style="width:90px; float:left; margin-right:8px;">
						<label for="<?php echo $this->get_field_id("postLimit"); ?>">
						<?php _e( '<p><b>Ka&ccedil; tane :</b></p>' ); ?>
						<input class="widefat" id="<?php echo $this->get_field_id("postLimit"); ?>" name="<?php echo $this->get_field_name("postLimit"); ?>" type="text" value="<?php echo esc_attr($instance["postLimit"]); ?>" />
						<br /><?php _e( 'Limit.' ); ?><br /></label>
					</div>
					<div style="width:130px; float:left; margin-right:10px;">
						<label for="<?php echo $this->get_field_id("titleLimit"); ?>"><?php _e( '<p><b>Baslik kelime! sayisi :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("titleLimit"); ?>" name="<?php echo $this->get_field_name("titleLimit"); ?>" type="text" value="<?php echo esc_attr($instance["titleLimit"]); ?>" />
						<br /><?php _e( 'h3 baslik.' ); ?><br />
					</div>
					<div style="width:130px; float:left; margin-right:19px;">
						<label for="<?php echo $this->get_field_id("tetxLimit"); ?>"><?php _e( '<p><b>Text kelime! sayisi :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("tetxLimit"); ?>" name="<?php echo $this->get_field_name("tetxLimit"); ?>" type="text" value="<?php echo esc_attr($instance["tetxLimit"]); ?>" />
						<br /><?php _e( 'p text.' ); ?><br />
					</div>
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<div>
					<div style="width:185px; float:left; margin-right:8px;">
						<label for="<?php echo $this->get_field_id('akistipi'); ?>"><?php _e('<p><b>Görünüm :</b></p>', 'MaviTm'); ?>
						<select id="<?php echo $this->get_field_id('akistipi'); ?>" name="<?php echo $this->get_field_name('akistipi'); ?>" class='widefat'>
						<?php
							$akistipi = array(
								'h' => __('Yatay olarak ekle', 'MaviTm'),
								'v' => __('Dikey olarak ekle', 'MaviTm')
							);
							foreach ($akistipi as $type_id => $type_name) {
						?>
								<option <?php if (isset($instance['akistipi'])) selected($type_id, $instance['akistipi']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
						<?php
								}
						?>
						</select>
						<br /><?php _e( 'Ne şekilde görünsün.' ); ?><br /></label>
					</div>
					<div style="width:185px; float:left; margin-right:10px;">
						<label for="<?php echo $this->get_field_id('netarafa'); ?>"><?php _e('<p><b>Kayma :</b></p>', 'MaviTm'); ?>
						<select id="<?php echo $this->get_field_id('netarafa'); ?>" name="<?php echo $this->get_field_name('netarafa'); ?>" class='widefat'>
						<?php
							$netarafa = array(
								'1' => __('Yukarı veya sola doğru', 'MaviTm'),
								'0' => __('Aşağı veya sola doğru', 'MaviTm')
							);
							foreach ($netarafa as $type_id => $type_name) {
						?>
								<option <?php if (isset($instance['netarafa'])) selected($type_id, $instance['netarafa']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
						<?php
								}
						?>
						</select>
						<br /><?php _e( 'Otomatik seçimde kayma şekli .' ); ?><br /></label>
					</div>
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<div>
					<div style="margin-right:8px;">
						<label for="<?php echo $this->get_field_id('hidden'); ?>"><?php _e('<p><b>Dikey kullanım için gizleme sayısı :</b></p>', 'MaviTm'); ?>
						<select id="<?php echo $this->get_field_id('hidden'); ?>" name="<?php echo $this->get_field_name('hidden'); ?>" class='widefat'>
						<?php
							$akistipi = array(
								'1' => __('1 tane', 'MaviTm'),
								'2' => __('2 tane', 'MaviTm'),
								'3' => __('3 tane', 'MaviTm'),
								'4' => __('4 tane', 'MaviTm'),
							);
							foreach ($akistipi as $type_id => $type_name) {
						?>
								<option <?php if (isset($instance['hidden'])) selected($type_id, $instance['hidden']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
						<?php
								}
						?>
						</select>
						<br /><?php _e( 'Kayan kısımda kaç tane görünecek.' ); ?><br /></label>
					</div>
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<div>
					<div style="width:185px; float:left; margin-right:8px;">
						<label for="<?php echo $this->get_field_id('otomatik'); ?>"><?php _e('<p><b>Otomatik Hareket :</b></p>', 'MaviTm'); ?>
						<select id="<?php echo $this->get_field_id('otomatik'); ?>" name="<?php echo $this->get_field_name('otomatik'); ?>" class='widefat'>
						<?php
							$otomatik = array(
								'1' => __('Otomatik Hareket Etsin', 'MaviTm'),
								'0' => __('Hayıt otomatik olmasın', 'MaviTm')
							);
							foreach ($otomatik as $type_id => $type_name) {
						?>
								<option <?php if (isset($instance['otomatik'])) selected($type_id, $instance['otomatik']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
						<?php
								}
						?>
						</select>
						<br /><?php _e( 'Kendi başına hareket etsinmi?.' ); ?><br /></label>
					</div>
					<div style="width:185px; float:left; margin-right:10px;">
						<label for="<?php echo $this->get_field_id('otomatikKapat'); ?>"><?php _e('<p><b>Kullanıcı Etkisi :</b></p>', 'MaviTm'); ?>
						<select id="<?php echo $this->get_field_id('otomatikKapat'); ?>" name="<?php echo $this->get_field_name('otomatikKapat'); ?>" class='widefat'>
						<?php
							$otomatikKapat = array(
								'1' => __('Kullanıcı tıkladığında otomatik devre dışı', 'MaviTm'),
								'0' => __('Herzaman otomatik olsun', 'MaviTm')
							);
							foreach ($otomatikKapat as $type_id => $type_name) {
						?>
								<option <?php if (isset($instance['otomatikKapat'])) selected($type_id, $instance['otomatikKapat']); ?> value='<?php echo $type_id;?>'><?php echo $type_name; ?></option>
						<?php
								}
						?>
						</select>
						<br /><?php _e( 'Müdahale edildiğinde dursunmu? .' ); ?><br /></label>
					</div>
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
				<div>
					<div style="width:185px; float:left; margin-right:8px;">
						<label for="<?php echo $this->get_field_id("gecisSn"); ?>">
						<?php _e( '<p><b>Efekt Süresi :</b></p>' ); ?>
						<input class="widefat" id="<?php echo $this->get_field_id("gecisSn"); ?>" name="<?php echo $this->get_field_name("gecisSn"); ?>" type="text" value="<?php echo esc_attr($instance["gecisSn"]); ?>" />
						<br /><?php _e( 'Hareket hızı.' ); ?><br /></label>
					</div>
					<div style="width:185px; float:left; margin-right:10px;">
						<label for="<?php echo $this->get_field_id("duraklamaSn"); ?>"><?php _e( '<p><b>Duraklama Süresi :</b></p>' ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id("duraklamaSn"); ?>" name="<?php echo $this->get_field_name("duraklamaSn"); ?>" type="text" value="<?php echo esc_attr($instance["duraklamaSn"]); ?>" />
						<br /><?php _e( 'Hareket arası bekleme.' ); ?><br />
					</div>
					<div style="clear: both;"></div>
					<i>1000 değeri 1 Saniye demektir. Süreleri belirtirken buna göre değer verin.</i>
				</div>
				<div style="clear: both; padding-bottom:5px; margin-bottom: 15px; border-bottom: 1px solid #C0CCD3;"></div>
			<?php
		}
		
	}
?>