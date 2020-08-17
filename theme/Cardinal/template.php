<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 			template.php
* @Package:		GetSimple
* @Action:		Cardinal theme for GetSimple CMS
*
*****************************************************/
?>
<!DOCTYPE html>
<html>
<head>
	<!-- Site Title -->
	<title><?php get_page_clean_title(); ?> &lt; <?php get_site_name(); ?></title>
	<?php get_header(); ?>
	<meta name="robots" content="index, follow" />
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="<?php get_theme_url(); ?>/bootstrap451/css/bootstrap.css" media="screen" /> 
	<link rel="stylesheet" type="text/css" href="<?php get_theme_url(); ?>/bootstrap451/css/bootstrap-grid.min.css" media="screen" />
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<script src="<?php get_theme_url(); ?>/bootstrap451/js/jquery.min.js"></script> 
	<script src="<?php get_theme_url(); ?>/bootstrap451/js/bootstrap.min.js"></script> 

</head>
<body id="<?php get_page_slug(); ?>" >
	<div class="row jumbotron jumbotron-fluid " id="headerMenu">
			    <div class="text-center col col-5">
			    	<!-- <span class="logo2"><?php get_site_name(); ?></span> -->
					<a class="logo" href="<?php get_site_url(); ?>">
						<img src="<?php get_theme_url(); ?>/images/manitos.png" alt="Pan Manitos" class="img-fluid">
					</a>
			    </div>
			    <div class="col col-7 d-flex justify-content-center">
			      	<nav class="navbar navbar-expand-lg navbar-light">
					  
					  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					    <span class="navbar-toggler-icon"></span>
					  </button>

					  <div class="collapse navbar-collapse " id="navbarSupportedContent">
					    <ul class="navbar-nav mr-auto nav" id="firstMenu">
							<?php get_navigation(return_page_slug()); ?>
					    </ul>
					    
					  </div>
					</nav>
			    </div>
			</div>
	<div class="container">
	<div id="header">
		
		<div class="col col-12">
		    	<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
		    		<ol class="carousel-indicators">
		    			<li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
		    			<li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
		    			<li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
		    		</ol>
		    		<div class="carousel-inner">
		    			<div class="carousel-item active">
		    				<img src="<?php get_theme_url(); ?>/images/ci1.jpg" class="d-block w-100" alt="Descripción 1">
		    				<div class="carousel-caption d-none d-md-block">
		    					<h5>First slide label</h5>
		    					<p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
		    				</div>
		    			</div>
		    			<div class="carousel-item">
		    				<img src="<?php get_theme_url(); ?>/images/ci2.jpg" class="d-block w-100" alt="Descripción 2">
		    				<div class="carousel-caption d-none d-md-block">
		    					<h5>Second slide label</h5>
		    					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
		    				</div>
		    			</div>
		    			<div class="carousel-item">
		    				<img src="<?php get_theme_url(); ?>/images/ci3.jpg" class="d-block w-100" alt="Descripción 3">
		    				<div class="carousel-caption d-none d-md-block">
		    					<h5>Third slide label</h5>
		    					<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
		    				</div>
		    			</div>
		    		</div>
		    		<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
		    			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    			<span class="sr-only">Previous</span>
		    		</a>
		    		<a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
		    			<span class="carousel-control-next-icon" aria-hidden="true"></span>
		    			<span class="sr-only">Next</span>
		    		</a>
		    	</div>
		    </div>
	</div><!-- end header -->
	
	<div id="content">
		<h1><?php get_page_title(); ?></h1>	
			<div id="page-content">
				<div class="page-text">
					<?php get_page_content(); ?>
					<p class="page-meta">Published on &nbsp;<span><?php get_page_date('F jS, Y'); ?></span></p>
				</div>
			</div>
	</div>	
		
	<div id="sidebar">
		
		<div class="section">
			<?php get_component('sidebar');	?>
		</div>

		<div class="section credits">
			<p><?php echo date('Y'); ?> - <strong><?php get_site_name(); ?></strong></p>
		</div>
	</div>
	
	<div class="clear"></div>
	
	<?php get_footer(); ?>
</div>
<!-- end wrapper -->
</body>
</html>
<script type="text/javascript">

	$( document ).ready(function() {
	    $('.carousel').carousel()
	});
</script>