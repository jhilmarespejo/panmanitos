<div class="container text-center my-3">
    <h2 class="font-weight-light component">Atención Integral y Cuidado Diario Desde la familia a Niñas y Niños </h2>
 <h2 class="font-weight-light component">Ejes temáticos</h2>
    <div class="row mx-auto my-auto">
        <div id="temathicCarousel" class="carousel temathic-carousel slide w-100" data-ride="carousel">
            <div class="carousel-inner w-100" role="listbox">
                <div class="carousel-item active">
                    <div class="col-md-4">
                       <div class="card card-body">
                            <a href="<?php get_site_url(); ?>/eje-tematico-1"><img class="img-fluid" src="<?php get_theme_url(); ?>/images/temathic/temathic1.jpg"></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-4">
                        <div class="card card-body">
                            <a href="<?php get_site_url(); ?>/eje-tematico-2"><img class="img-fluid" src="<?php get_theme_url(); ?>/images/temathic/temathic2.jpg"></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-4">
                        <div class="card card-body">
                            <a href="<?php get_site_url(); ?>/#"><img class="img-fluid" src="<?php get_theme_url(); ?>/images/temathic/temathic3.jpg"></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-4">
                        <div class="card card-body">
                            <a href="<?php get_site_url(); ?>/#"><img class="img-fluid" src="<?php get_theme_url(); ?>/images/temathic/temathic4.jpg"></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-4">
                        <div class="card card-body">
                            <a href="<?php get_site_url(); ?>/#"><img class="img-fluid" src="<?php get_theme_url(); ?>/images/temathic/temathic5.jpg"></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-4">
                        <div class="card card-body">
                            <a href="<?php get_site_url(); ?>/#"><img class="img-fluid" src="<?php get_theme_url(); ?>/images/temathic/temathic6.jpg"></a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="col-md-4">
                        <div class="card card-body">
                            <a href="<?php get_site_url(); ?>/#"><img class="img-fluid" src="<?php get_theme_url(); ?>/images/temathic/temathic7.jpg"></a>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev w-auto" href="#temathicCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon bg-dark border border-dark rounded-circle" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next w-auto" href="#temathicCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon bg-dark border border-dark rounded-circle" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#temathicCarousel').carousel({
      interval: 4000
    })

    $('.temathic-carousel .carousel-item').each(function(){
        var minPerSlide = 3;
        var next = $(this).next();
        if (!next.length) {
        next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));
        
        for (var i=0;i<minPerSlide;i++) {
            next=next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            
            next.children(':first-child').clone().appendTo($(this));
          }
    });

</script>

<style type="text/css">
    @media (max-width: 768px) {
    .carousel-inner .carousel-item > div {
        display: none;
    }
    .carousel-inner .carousel-item > div:first-child {
        display: block;
    }
}

.carousel-inner .carousel-item.active,
.carousel-inner .carousel-item-next,
.carousel-inner .carousel-item-prev {
    display: flex;
}

/* display 3 */
@media (min-width: 768px) {
    
    .carousel-inner .carousel-item-right.active,
    .carousel-inner .carousel-item-next {
      transform: translateX(33.333%);
    }
    
    .carousel-inner .carousel-item-left.active, 
    .carousel-inner .carousel-item-prev {
      transform: translateX(-33.333%);
    }
}

.carousel-inner .carousel-item-right,
.carousel-inner .carousel-item-left{ 
  transform: translateX(0);
}


</style>