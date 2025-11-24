<?php
/**
 * Job listing in the loop.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $post;
$job_salary   = get_post_meta( get_the_ID(), '_job_salary', true );
$job_featured = get_post_meta( get_the_ID(), '_featured', true );
$company_name = get_post_meta( get_the_ID(), '_company_name', true );

// --- LOGIC MỚI: RÚT GỌN ĐỊA CHỈ ---
$raw_location = get_post_meta( get_the_ID(), '_job_location', true );
$city_location = $raw_location; // Mặc định lấy gốc
if ( ! empty( $raw_location ) && strpos( $raw_location, ',' ) !== false ) {
    $parts = explode( ',', $raw_location ); // Cắt chuỗi bằng dấu phẩy
    $city_location = trim( end( $parts ) ); // Lấy phần cuối cùng và xóa khoảng trắng thừa
}

?>
<article class="job-cards" <?php job_listing_class(); ?> data-longitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_long ); ?>">

    <div class="job-card-head">
        <figure class="company-logo">
            <?php the_company_logo( 'thumbnail' ); ?>
        </figure>

        <div class="job-title-wrap">
        
        <h2 class="entry-title">
            <a href="<?php the_job_permalink(); ?>"><?php wpjm_the_job_title(); ?></a>
        </h2>
        
        <div class="job-posted-date">
            Created: <?php echo get_the_date( 'M d, Y' ); ?>
        </div>
        
        <div class="entry-meta">
            <?php 
                do_action( 'job_listing_meta_start' ); 

                // Salary
                if( $job_salary ){
                    echo '<div class="salary-amt item-meta">' . esc_html( $job_salary ) . '</div>';
                }
                // Job Types
                if ( get_option( 'job_manager_enable_types' ) ) { 
					$types = wpjm_get_the_job_types(); 
                    if ( ! empty( $types ) ) : foreach ( $types as $jobtype ) : ?>
                        <div class="job-type item-meta <?php echo esc_attr( sanitize_title( $jobtype->slug ) ); ?>">
							<?php echo esc_html( $jobtype->name ); ?>
                        </div>
						<?php endforeach; endif; 
                }

				// Location (Dùng biến $city_location đã rút gọn)
				if ( ! empty( $city_location ) ) {
					echo '<div class="company-address item-meta">' . esc_html( $city_location ) . '</div>';
				}
                do_action( 'job_listing_meta_end' ); 
            ?>
        </div>
        
        
        </div>
    </div>
	<div class="entry-content-desc">
		<?php echo wp_kses_post( get_the_excerpt() ); ?>
	</div>

</article>