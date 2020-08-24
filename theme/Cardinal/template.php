<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }

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


    	<?php if(get_page_title(false) == 'Inicio') : ?>
			<div class="container text-center">
				<div class="col col-12">
					<div id="headerCarousel" class="carousel slide" data-ride="carousel">
					  <ol class="carousel-indicators">
					    <li data-target="#headerCarousel" data-slide-to="0" class="active"></li>
					    <li data-target="#headerCarousel" data-slide-to="1"></li>
					    <li data-target="#headerCarousel" data-slide-to="2"></li>
					    <li data-target="#headerCarousel" data-slide-to="3"></li>
					    <li data-target="#headerCarousel" data-slide-to="4"></li>
					    <li data-target="#headerCarousel" data-slide-to="5"></li>
					  </ol>
					  <div class="carousel-inner" id="header-carousel-inner">
					    <div class="carousel-item active">
					      <img src="<?php get_site_url(); ?>/data/uploads/headerslides/ci1.jpg" class="d-block w-100" alt="...">
					      <div class="carousel-caption d-none d-md-block">
					        <h5>First slide label</h5>
					        <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
					      </div>
					    </div>
					    <div class="carousel-item">
					      <img src="<?php get_site_url(); ?>/data/uploads/headerslides/ci2.jpg" class="d-block w-100" alt="...">
					      <div class="carousel-caption d-none d-md-block">
					        <h5>Second slide label</h5>
					        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
					      </div>
					    </div>
					    <div class="carousel-item">
					      <img src="<?php get_site_url(); ?>/data/uploads/headerslides/ci3.jpg" class="d-block w-100" alt="...">
					      <div class="carousel-caption d-none d-md-block">
					        <h5>Third slide label</h5>
					        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
					      </div>
					    </div>
					    <div class="carousel-item">
					      <img src="<?php get_site_url(); ?>/data/uploads/headerslides/ci4.jpg" class="d-block w-100" alt="...">
					      <div class="carousel-caption d-none d-md-block">
					        <h5>Fourth slide label</h5>
					        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
					      </div>
					    </div>
					    <div class="carousel-item">
					      <img src="<?php get_site_url(); ?>/data/uploads/headerslides/ci5.jpg" class="d-block w-100" alt="...">
					      <div class="carousel-caption d-none d-md-block">
					        <h5>Fifth slide label</h5>
					        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
					      </div>
					    </div>
					  </div>
					  <a class="carousel-control-prev" href="#headerCarousel" role="button" data-slide="prev">
					    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
					    <span class="sr-only">Previous</span>
					  </a>
					  <a class="carousel-control-next" href="#headerCarousel" role="button" data-slide="next">
					    <span class="carousel-control-next-icon" aria-hidden="true"></span>
					    <span class="sr-only">Next</span>
					  </a>
					</div>
				</div>
			</div><!-- end header -->
		<?php endif; ?>
	</div> <!-- <div class="row jumbotron jumbotron-fluid " id="headerMenu"> -->
	<?php if(get_page_title(false) != 'Inicio') : ?>		
		<div class="row jumbotron jumbotron-fluid " id="beadcumb">
			<?php get_microdata_breadcrumbs(); ?>
		</div>
	<?php endif; ?>

	<div class="container index">
		
		<div id="content">
			<h1><?php get_page_title(); ?></h1>	
				<div id="page-content">
					<div class="page-text">
						<?php get_page_content(); ?>
						<p class="page-meta">Fecha de publiación: &nbsp;<span><?php get_page_date('F jS, Y'); ?></span></p>
					</div>
				</div>
		</div>	
			
		<div id="sidebar">
			
			<?php if(get_page_title(false) == 'Inicio'): ?>
				<div class="section">
					<?php get_component('temathic');?>
					<?php get_component('mission_vision');?>

					<div class="container text-center">
					  <h2 class="font-weight-light">Galería de imagenes y videos</h2>
					  <div class="row">
					    <div class="col-sm">
					    	<?php get_component('index_video_gallery'); ?>
					    </div>
					    <div class="col-sm">
					    	<?php get_component('index_image_gallery'); ?>
					    </div>
					  </div>
					</div>
					<?php get_component('gallery'); ?>
				</div>
			<?php endif; ?>
			
		</div>

	<p></p>
	<p></p>
	<p></p>
		
		<div class="clear"></div>
		
		<?php get_footer(); ?>
	</div> <!-- end <div class="container"> -->

</body>
</html>
<script type="text/javascript">
	$( document ).ready(function() {
		$('.carousel').carousel({
			interval: 90000
		})

		$('.video-carousel').carousel({
			interval: 5000
		})

		$('#carouselExampleFade').carousel({
			interval: 2000
		})

		$('#galleryCarousel').carousel({
			interval: 1000
		})
	});
</script>