<?php get_header(); require_once(get_template_directory().'/inc/MaviTmLoads.php'); $maviTm =& MaviTmLoads::factory('MaviTmLoads');?>
<div id="content" role="main">

	<?php if(is_dynamic_sidebar('categoryust')){echo '<div class="blokUst">';dynamic_sidebar('categoryust');echo '</div>';}?>
	
	<div class="<?=get_option('MaviTmKatRightSidebar') == 1 ? 'fullMain' : 'siteMain';?>">
	
			<div class="mansetArea">
				<div class="mansetBlok">
					<?php if(is_dynamic_sidebar('categorymanset')){dynamic_sidebar('categorymanset');}?>
					<div class="clear"></div>
				</div>
				<div class="mansetSagBlok">
					<?php if(is_dynamic_sidebar('categorymansetsag')){dynamic_sidebar('categorymansetsag');}?>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			
			<?php if(is_dynamic_sidebar('categoryorta')){echo '<div class="ortaBlok">';dynamic_sidebar('categoryorta');echo '</div><div class="clear"></div>';}?>
							
			<div class="govdeBaslik"><h2>
				<?php 
					if(!isset($_GET['s'])){single_cat_title();}
					else{echo 'Arama';} 
				?>
			</h2></div>
			
			<div class="maviTmPost <?=get_option('MaviTmKategoriGorunum') > 0 ? 'fullWidget'.get_option('MaviTmKategoriGorunum') : 'fullWidget2';?>">
			
			<?php 
				if(have_posts()){ 
				include_once(get_template_directory().'/view/rbakt.php');
				$titleLimit = get_option('MaviTmKatBaslikLimit') > 0 ? get_option('MaviTmKatBaslikLimit') : 3;
				$textLimit = get_option('MaviTmKatTextLimit') > 0 ? get_option('MaviTmKatTextLimit') : 6;
				maviTmViewrbaktGlob(array('titleLimit' => $titleLimit,'tetxLimit'=>$textLimit));
			?>
				
			</div>	
					
			<?php }else{ ?>
						<div class="notice">Bu b&ouml;l&uuml;mde veri bulunmuyor.</div>
			<?php } ?>
			
			<div class="clear"></div>
			<?php maviTmSayfalama('<div class="pagenavi textRight right">','</div>');?>
			<div class="clear"></div>
			<?php if(is_dynamic_sidebar('categoryalt')){echo '<div class="ortaBlok">';dynamic_sidebar('categoryalt');echo '</div>';}?>
	</div>
	<?php if(get_option('MaviTmKatRightSidebar') == false){ ?>
		<div class="sagAlan">
				<?php if(is_dynamic_sidebar('categorysag')){dynamic_sidebar('categorysag');}?>
		</div>
	<?php }?>
	
	<div class="clear"></div>
	
	<?php if(is_dynamic_sidebar('categoryenalt')){echo '<div class="blokUst">';dynamic_sidebar('categoryenalt');echo '</div>';}?>
	
	<div class="clear"></div>
</div>

<?php get_footer(); ?>