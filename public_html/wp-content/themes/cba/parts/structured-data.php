<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<span class="structured-data hide">
	<?php if ( has_post_thumbnail() ) { 
		$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID, "full" )  );
		?>
		<span class="av-structured-data" itemscope="itemscope" itemtype="https://schema.org/ImageObject" itemprop="image"> 
			<span itemprop="url"><?php echo $img[0]; ?></span> 
			<span itemprop="height"><?php echo $img[1]; ?></span> 
			<span itemprop="width"><?php echo $img[2]; ?></span> 
		</span>
	<?php } ?>
	<span itemprop="author" itemscope="itemscope" itemtype="https://schema.org/Person"><span itemprop="name"><?php echo get_the_author( ); ?></span></span>
	<span class="av-structured-data" itemprop="mainEntityOfPage" itemtype="https://schema.org/mainEntityOfPage">
		<span itemprop="name"><?php the_title(); ?></span>
	</span>
	<?php 
	$post_published = get_the_date( "Y-m-d" ); 
	$post_modified = get_the_modified_date("Y-m-d" );


	if ( !empty($post_published) ) { ?>
		<span class="av-structured-data" itemprop="datePublished" datetime="<?php echo $post_published; ?>"><?php echo $post_published; ?></span>
	<?php } 

	if ( !empty( $post_modified ) ) { ?>
		<span class="av-structured-data" itemprop="dateModified" itemtype="https://schema.org/dateModified"><?php echo $post_modified; ?></span>
	<?php } ?>
	<span class="av-structured-data" itemprop="publisher" itemtype="https://schema.org/Organization" itemscope="itemscope"> 
		<span itemprop="name"></span> 
		<?php if ( !empty( $img ) ) { ?>
			<span itemprop="logo" itemscope="" itemtype="http://schema.org/ImageObject"> 
			<span itemprop="url"><?php echo $img[0]; ?></span> 
		</span> 
		<?php } ?>
	</span>
</span>