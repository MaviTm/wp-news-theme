<?php
	//sadece ana sayfa icin olan kisimlar
	//indexUst, indexSag, manset, mansetSag, indexOrta, indexAlt, indexEnAlt
	register_sidebar(array(
	 	'name'=> 'Ana sayfa en ust','id'=> 'indexust','description' => 'Ana sayfa kategori alti','before_widget' => '<div class="ustWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>','after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Ana Sayfa Sag Alan','id' => 'indexsag','description' => 'Ana Sayfa sag alan','before_widget' => '<div class="sagWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Ana Sayfa Manset Alani','id' => 'manset','description' => 'Ana Sayfa manset kismi', 'before_widget' => '','after_widget' => '<div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="fullNone">', 'after_title' => '</div>',
	));register_sidebar(array(
		'name' => 'Ana Sayfa Manset sag taraf','id' => 'mansetsag','description' => 'Ana Sayfa mansetin sag kismi','before_widget' => '<div class="div230">', 'after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>','after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Ana Sayfa Content Ustu manset alti','id' => 'indexorta','description' => 'Ana Sayfa mansetin alt kismi','before_widget' => '<div class="ortaWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));
	/*
	register_sidebar(array(
		'name' => 'Ana Sayfa Content alti','id' => 'indexAlt','description' => 'Ana Sayfa icerik gelen kismin alti','before_widget' => '<div class="ortaBlok">','after_widget' => '</div><div class="mrBottom10"></div>','before_title' => '<h2 class="title2">','after_title' => '</h2>',
	));
	*/
	register_sidebar(array(
	 		'name' => 'Ana sayfa en alt kategori ustu','id' => 'indexenalt','description' => 'Ana sayfa en alt kategori ustu','before_widget' => '<div class="ustWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));

	
	//sadece category ve archive
	//singleUst, singleSag, singleOrta, singleAlt, singleYorumAlt, singleEnAlt
	register_sidebar(array(
	 	'name'=> 'Kategori en ust 980px','id'=> 'categoryust','description' => 'Kategori sayfa kategori alti','before_widget' => '<div class="ustWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>','after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Kategori Manset Alani','id' => 'categorymanset','description' => 'Kategori manset kismi', 'before_widget' => '','after_widget' => '<div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="fullNone">', 'after_title' => '</div>',
	));register_sidebar(array(
		'name' => 'Kategori Manset sag taraf','id' => 'categorymansetsag','description' => 'Kategori mansetin sag kismi','before_widget' => '<div class="div230">', 'after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>','after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Kategori Sag Alan 250px','id' => 'categorysag','description' => 'Kategori Sayfa sag alan','before_widget' => '<div class="sagWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Kategori Content ustu 720px','id' => 'categoryorta','description' => 'Kategori Sayfa mansetin alt kismi','before_widget' => '<div class="ortaWidget">','after_widget' => '</div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Kategori Content alti 720px','id' => 'categoryalt','description' => 'Kategori ayfa icerik gelen kismin alti','before_widget' => '<div class="ortaWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>','after_title' => '</h2></div>',
	));register_sidebar(array(
	 		'name' => 'Kategori en alt 980px','id' => 'categoryenalt','description' => 'Kategori sayfa en alt kategori ustu','before_widget' => '<div class="ustWidget">','after_widget' => '</div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));
	
	
	//sadece single, attachment, page sayfa icin olan kisimlar
	//singleUst, singleSag, singleOrta, singleAlt, singleYorumAlt, singleEnAlt
	register_sidebar(array(
	 	'name'=> 'Single sayfa en ust','id'=> 'singleust','description' => 'Single sayfa kategori alti','before_widget' => '<div class="ustWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>','after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Single Sayfa Sag Alan','id' => 'singlesag','description' => 'Single Sayfa sag alan','before_widget' => '<div class="sagWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Single Sayfa Content icerik gelen kismin ustu','id' => 'singleorta','description' => 'Single Sayfa mansetin alt kismi','before_widget' => '<div class="ortaBlok">','after_widget' => '</div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Single Sayfa Content alti','id' => 'singlealt','description' => 'Single ayfa icerik gelen kismin alti','before_widget' => '<div class="ortaWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>','after_title' => '</h2></div>',
	));register_sidebar(array(
		'name' => 'Single Sayfa yorum alti','id' => 'singleyorumalt','description' => 'Single Sayfa yorumlarin alti','before_widget' => '<div class="ortaWidget">','after_widget' => '</div><div class="clear"></div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>','after_title' => '</h2></div>',
	));register_sidebar(array(
	 		'name' => 'Single sayfa en alt kategori ustu','id' => 'singleenalt','description' => 'Single sayfa en alt kategori ustu','before_widget' => '<div class="ustWidget">','after_widget' => '</div><div class="mrBottom10"></div>','before_title' => '<div class="defaultTitle"><h2>', 'after_title' => '</h2></div>',
	));
	
	////Tum sayfalar icin ayni olan kisimlar
	//footerAlan, logoYani
	register_sidebar(array(
	 		'name' => 'Tum sayfalar footer kismi','id' => 'footeralan','description' => 'sitenin tum bolumlerinde (tum sayfalarda ayni)', 'before_widget' => '<div class="footerBlok">','after_widget' => '</div><div class="mrBottom10"></div>','before_title' => '<div class="fullNone">','after_title' => '</div>',
	));register_sidebar(array(
	 		'name' => 'Tum sayfalar Logo Yani','id' => 'logoyani','description' => 'logonuzun yanindaki reklam alani 468x60 tum sayfalarda ayni','before_widget' => '<div class="468x60">','after_widget' => '</div>','before_title' => '<div class="fullNone">', 'after_title' => '</div>',
	));
?>