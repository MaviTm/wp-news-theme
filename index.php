<?php get_header(); require_once(get_template_directory().'/inc/MaviTmLoads.php'); $maviTm =& MaviTmLoads::factory('MaviTmLoads');?>
<div id="content" role="main">

	<?php if(is_dynamic_sidebar('indexust')){echo '<div class="blokUst">';dynamic_sidebar('indexust');echo '</div>';}?>
	
	<div class="siteMain">
			<div class="mansetArea">
				<div class="mansetBlok">
					<?php if(is_dynamic_sidebar('manset')){dynamic_sidebar('manset');}?>
					<div class="clear"></div>
				</div>
				<div class="mansetSagBlok">
					<?php if(is_dynamic_sidebar('mansetsag')){dynamic_sidebar('mansetsag');}?>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			
			<?php if(is_dynamic_sidebar('indexorta')){echo '<div class="ortaBlok">';dynamic_sidebar('indexorta');echo '</div>';}?>
	</div>
	
	<div class="sagAlan">
			<?php if(is_dynamic_sidebar('indexsag')){dynamic_sidebar('indexsag');}?>
	</div>
	
	<div class="clear"></div>
	
	<?php if(is_dynamic_sidebar('indexenalt')){echo '<div class="blokUst">';dynamic_sidebar('indexenalt');echo '</div>';}?>
	
	<div class="clear"></div>
</div>
<?php get_footer(); ?>