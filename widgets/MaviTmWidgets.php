<?php
if (!class_exists('MaviTmWidgets')) {
	class MaviTmWidgets {
		function MaviTmWidgets() {
		}

		function init() {
			add_action("widgets_init", array(&$this, "load_widgets"));
		}

		function load_widgets() {
			$template_path = get_template_directory();

			include_once ($template_path . '/widgets/MaviTmEmbed.php');
			include_once ($template_path . '/widgets/MaviTmPostList.php');
			include_once ($template_path . '/widgets/MaviTmManset.php');
			include_once ($template_path . '/widgets/MaviTmKategori.php');
			include_once ($template_path . '/widgets/MaviTmCarusel.php');

			register_widget("MaviTmEmbed");
			register_widget("MaviTmPostList");
			register_widget("MaviTmManset");
			register_widget("MaviTmKategori");
			register_widget("MaviTmCarusel");
		}
	}
}
?>