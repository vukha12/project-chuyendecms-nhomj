<?php

/**
 * The template for displaying all single job posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package JobScout
 */
get_header();

if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
}

while (have_posts()) : the_post();
    global $post;

    // Get job meta data
    $company_name = get_post_meta(get_the_ID(), '_company_name', true);
    $company_logo_url = get_the_company_logo(get_the_ID(), 'full');
    $job_location = get_the_job_location();
    $created_date = get_the_date('M d, Y');

    // Get job categories
    $job_categories = get_the_terms(get_the_ID(), 'job_listing_category');
    $job_category_name = '';
    if ($job_categories && ! is_wp_error($job_categories)) {
        $job_category_name = $job_categories[0]->name;
    }

    // Get job types
    $job_types = wpjm_get_the_job_types();
    $job_type_name = '';
    if (! empty($job_types)) {
        $job_type_name = $job_types[0]->name;
    }

    // Get company rating (you can customize this)
    $company_rating = get_post_meta(get_the_ID(), '_company_rating', true);
    if (empty($company_rating)) {
        $company_rating = 4.0; // Default rating
    }

    // Get company photos (you can customize this)
    $company_photos = get_post_meta(get_the_ID(), '_company_photos', true);
?>

    <div id="job-detail-page" class="job-detail-page">
        <div class="container">
            <!-- Breadcrumb Navigation -->
            <nav class="job-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">Home</a>
                <span class="breadcrumb-separator">/</span>
                <a href="<?php echo esc_url(home_url('/jobs')); ?>" class="breadcrumb-link">All Jobs</a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current"><?php the_title(); ?></span>
            </nav>

            <!-- Job Header -->
            <div class="job-header-card">
                <div class="job-header-content">
                    <figure class="company-logo-large">
                        <?php if ($company_logo_url) : ?>
                            <img src="<?php echo esc_url($company_logo_url); ?>" alt="<?php echo esc_attr($company_name); ?>" />
                        <?php else : ?>
                            <div class="company-logo-fallback">
                                <?php if ($company_name) : ?>
                                    <div class="company-name-in-logo"><?php echo esc_html($company_name); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </figure>

                    <div class="job-header-info">
                        <h1 class="job-title"><?php the_title(); ?></h1>
                        <div class="job-meta-info">
                            <span class="created-date">Created: <?php echo esc_html($created_date); ?></span>
                        </div>
                        <div class="job-tags">
                            <?php if ($job_type_name) : ?>
                                <span class="job-tag job-type"><?php echo esc_html($job_type_name); ?></span>
                            <?php endif; ?>
                            <?php if ($job_category_name) : ?>
                                <span class="job-tag job-category"><?php echo esc_html($job_category_name); ?></span>
                            <?php endif; ?>
                            <?php if ($job_location) : ?>
                                <span class="job-tag job-location"><?php echo esc_html($job_location); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="job-header-actions">
                    <button class="btn-share">SHARE</button>
                    <button class="btn-apply">APPLY JOB</button>
                </div>
            </div>

            <!-- Job Content with Sidebar -->
            <div class="job-content-wrapper">
                <!-- Main Content -->
                <div class="job-main-content">
                    <section class="job-section">
                        <h2 class="section-title">Overview about Company</h2>
                        <div class="section-content">
                            <?php
                            $content = get_the_content();
                            if (! empty($content)) {
                                echo wpautop($content);
                            } else {
                                echo '<p>No company overview available.</p>';
                            }
                            ?>
                        </div>
                    </section>

                    <section class="job-section">
                        <h2 class="section-title">Our Key Skills</h2>
                        <div class="section-content">
                            <?php
                            $key_skills = get_post_meta(get_the_ID(), '_key_skills', true);
                            if (! empty($key_skills)) {
                                echo wpautop($key_skills);
                            } else {
                                echo '<p>No key skills information available.</p>';
                            }
                            ?>
                        </div>
                    </section>

                    <section class="job-section">
                        <h2 class="section-title">Why You'll Love Working Here</h2>
                        <div class="section-content">
                            <?php
                            $why_work = get_post_meta(get_the_ID(), '_why_work_here', true);
                            if (! empty($why_work)) {
                                echo wpautop($why_work);
                            } else {
                                echo '<p>No information available.</p>';
                            }
                            ?>
                        </div>
                    </section>

                    <section class="job-section">
                        <h2 class="section-title">Location</h2>
                        <div class="section-content">
                            <?php if ($job_location) : ?>
                                <p><?php echo esc_html($job_location); ?></p>
                            <?php else : ?>
                                <p>No location information available.</p>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>

                <!-- Sidebar -->
                <aside class="job-sidebar">
                    <!-- Staff Rating -->
                    <div class="sidebar-widget rating-widget">
                        <h3 class="widget-title">Staff Rating</h3>
                        <div class="rating-display">
                            <?php
                            $full_stars = floor($company_rating);
                            $half_star = ($company_rating - $full_stars) >= 0.5;
                            $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

                            for ($i = 0; $i < $full_stars; $i++) {
                                echo '<span class="star star-full">★</span>';
                            }
                            if ($half_star) {
                                echo '<span class="star star-half">★</span>';
                            }
                            for ($i = 0; $i < $empty_stars; $i++) {
                                echo '<span class="star star-empty">★</span>';
                            }
                            ?>
                            <span class="rating-number"><?php echo number_format($company_rating, 1); ?></span>
                        </div>
                    </div>

                    <!-- Company Photos -->
                    <div class="sidebar-widget photos-widget">
                        <h3 class="widget-title">Company Photos</h3>
                        <div class="company-photos-grid">
                            <?php if ($company_photos && is_array($company_photos)) : ?>
                                <?php foreach ($company_photos as $index => $photo_url) : ?>
                                    <?php if ($index < 4) : ?>
                                        <div class="photo-item <?php echo ($index === 3) ? 'photo-more' : ''; ?>">
                                            <img src="<?php echo esc_url($photo_url); ?>" alt="Company Photo" />
                                            <?php if ($index === 3 && count($company_photos) > 4) : ?>
                                                <div class="photo-overlay">+<?php echo count($company_photos) - 3; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="photo-item">
                                    <div class="photo-placeholder">No photos available</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- Other Jobs Section -->
    <div class="other-jobs-section">
        <h2 class="other-jobs-title">Other Jobs</h2>

        <div class="other-jobs-grid">
            <?php
            // Lấy category của job hiện tại để query các job liên quan
            $current_job_id = get_the_ID();
            $terms = get_the_terms($current_job_id, 'job_listing_category');
            $category_ids = [];
            if ($terms && ! is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $category_ids[] = $term->term_id;
                }
            }

            $args = [
                'post_type'      => 'job_listing',
                'posts_per_page' => 8,
                'post__not_in'   => [$current_job_id],
                'orderby'        => 'date',
                'order'          => 'DESC',
            ];

            if (! empty($category_ids)) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'job_listing_category',
                        'field'    => 'term_id',
                        'terms'    => $category_ids,
                    ]
                ];
            }

            $other_jobs = new WP_Query($args);

            if ($other_jobs->have_posts()) :
                while ($other_jobs->have_posts()) : $other_jobs->the_post();
                    $company_logo = get_the_company_logo(get_the_ID(), 'thumbnail');
                    $company_name = get_post_meta(get_the_ID(), '_company_name', true); // Lấy tên công ty
                    $job_location = get_the_job_location();
                    $job_types    = wpjm_get_the_job_types();
                    $job_type     = !empty($job_types) ? $job_types[0]->name : '';
                    $created_date = get_the_date('M d, Y'); // Lấy ngày tạo

                    // Lấy 1 category tên để hiển thị
                    $cats = get_the_terms(get_the_ID(), 'job_listing_category');
                    $cat_name = ($cats && !is_wp_error($cats)) ? $cats[0]->name : '';
            ?>
                    <div class="other-job-card">
                        <a href="<?php the_permalink(); ?>" class="other-job-link">
                            <div class="other-job-top">
                                <div class="other-job-logo-wrapper">
                                    <?php if ($company_logo) : ?>
                                        <img src="<?php echo esc_url($company_logo); ?>" alt="" class="other-job-logo">
                                    <?php else : ?>
                                        <div class="other-job-logo placeholder"><?php echo mb_substr(get_the_title(), 0, 1); ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="other-job-info">
                                    <h3 class="other-job-title"><?php the_title(); ?></h3>

                                    <div class="other-job-meta">
                                        <?php if ($company_name): ?>
                                            <span class="meta-company"><?php echo esc_html($company_name); ?></span>
                                        <?php endif; ?>
                                        <span class="meta-date">- Created: <?php echo esc_html($created_date); ?></span>
                                    </div>

                                    <div class="other-job-tags-row">
                                        <?php if ($job_type) : ?>
                                            <span class="oj-tag"><?php echo esc_html($job_type); ?></span>
                                        <?php endif; ?>

                                        <?php if ($cat_name) : ?>
                                            <span class="oj-tag"><?php echo esc_html($cat_name); ?></span>
                                        <?php endif; ?>

                                        <?php if ($job_location) : ?>
                                            <span class="oj-tag"><?php echo esc_html($job_location); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="other-job-desc">
                                <?php
                                // Lấy mô tả ngắn, cắt 15 từ
                                echo wp_trim_words(get_the_content(), 15, '...');
                                ?>
                            </div>
                        </a>
                    </div>
            <?php
                endwhile;
            else :
                echo '<p>No other jobs available.</p>';
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>


    <style>
        /* Container chính */
        .other-jobs-section {
            margin-top: 60px;
            background: transparent;
            /* Nền trong suốt để lộ màu xám của trang */
        }

        .other-jobs-title {
            text-align: center;
            font-size: 24px;
            font-weight: 800;
            /* Chữ đậm */
            text-transform: uppercase;
            margin-bottom: 30px;
            color: #333;
            letter-spacing: 1px;
        }

        /* Lưới 2 cột */
        .other-jobs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        /* Thẻ Job Card */
        .other-job-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 25px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .other-job-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-color: #ccc;
        }

        .other-job-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        /* --- Phần Trên: Logo + Info --- */
        .other-job-top {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        /* Logo vuông có viền */
        .other-job-logo-wrapper {
            flex-shrink: 0;
        }

        .other-job-logo,
        .other-job-logo.placeholder {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border: 1px solid #eee;
            padding: 5px;
            background: #fff;
            display: block;
        }

        .other-job-logo.placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: bold;
            color: #ccc;
            background: #f9f9f9;
        }

        /* Thông tin bên phải logo */
        .other-job-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .other-job-title {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            color: #000;
            line-height: 1.3;
        }

        .other-job-meta {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
            font-style: italic;
        }

        .meta-company {
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
        }

        /* Dòng Tags màu xám (Fulltime | Category | Location) */
        .other-job-tags-row {
            background: #f5f5f5;
            /* Nền xám nhạt */
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 0;
            /* Khoảng cách xử lý bằng padding */
            width: fit-content;
        }

        .oj-tag {
            font-size: 12px;
            color: #555;
            position: relative;
            padding: 0 10px;
            font-weight: 500;
        }

        /* Tạo đường gạch ngăn cách giữa các tag */
        .oj-tag:not(:last-child)::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 12px;
            background-color: #ccc;
        }

        .oj-tag:first-child {
            padding-left: 0;
        }

        /* --- Phần Dưới: Mô tả --- */
        .other-job-desc {
            border-top: 1px solid #f0f0f0;
            /* Gạch ngang mờ ngăn cách */
            padding-top: 15px;
            margin-top: 5px;
            font-size: 14px;
            color: #777;
            line-height: 1.6;
        }

        /* Giả lập dấu chấm đầu dòng cho mô tả */
        .other-job-desc::before {
            content: "• ";
            color: #999;
            margin-right: 5px;
        }

        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .other-jobs-grid {
                grid-template-columns: 1fr;
                /* 1 cột trên mobile */
            }

            .other-job-top {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* End OTHER JOBS */

        .entry-header {
            display: none;
        }

        .job-detail-page {
            padding: 40px 0 60px;
            background: #f5f5f5;
        }

        .job-detail-page .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Breadcrumb Navigation */
        .job-breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            padding: 15px 0;
            font-size: 14px;
        }

        .breadcrumb-link {
            color: #666;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-link:hover {
            color: #ff6b35;
        }

        .breadcrumb-separator {
            color: #999;
        }

        .breadcrumb-current {
            color: #333;
            font-weight: 500;
        }

        /* Job Header Card */
        .job-header-card {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
        }

        .job-header-content {
            display: flex;
            gap: 25px;
            flex: 1;
        }

        .company-logo-large {
            flex-shrink: 0;
            margin: 0;
            width: 130px;
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
        }

        .company-logo-large img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .company-logo-fallback {
            width: 100%;
            height: 100%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .company-name-in-logo {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            text-align: center;
            padding: 10px;
        }

        .job-header-info {
            flex: 1;
        }

        .job-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 10px 0;
            color: #333;
            line-height: 1.3;
        }

        .job-meta-info {
            margin-bottom: 15px;
        }

        .created-date {
            font-size: 14px;
            color: #999;
        }

        .job-tags {
            display: flex;
            flex-wrap: wrap;
        }

        .job-tag {
            display: inline-block;
            padding: 6px 14px;
            font-size: 13px;
            height: fit-content;
        }

        .job-tag.job-type {
            background: #e9ecef;
            color: #666;

        }

        .job-tag.job-category {
            background: #e9ecef;
            color: #666;
        }

        .job-tag.job-location {
            background: #e9ecef;
            color: #666;
        }

        .job-tag.job-type::after {
            content: "";
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);


            width: 1px;
            height: 12px;
            background-color: #ccc;
        }

        .job-header-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-share,
        .btn-apply {
            padding: 12px 35px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .btn-share {
            background: transparent;
            border: 1px solid #333;
            color: #333;
        }

        .btn-apply {
            background: transparent;
            border: 1px solid #ff6b35;
            color: #ff6b35;
        }

        /* Job Content with Sidebar */
        .job-content-wrapper {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }

        .job-main-content {
            background: #fff;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .job-section {
            margin-bottom: 40px;
        }

        .job-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 20px 0;
            color: #333;
        }

        .section-content {
            font-size: 15px;
            line-height: 1.8;
            color: #666;
        }

        .section-content p {
            margin-bottom: 15px;
        }

        /* Sidebar */
        .job-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sidebar-widget {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .widget-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 20px 0;
            color: #333;
        }

        /* Rating Widget */
        .rating-display {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .star {
            font-size: 28px;
            color: #ddd;
        }

        .star.star-full {
            color: #ff6b35;
        }

        .star.star-half {
            color: #ff6b35;
            opacity: 0.5;
        }

        .rating-number {
            font-size: 24px;
            font-weight: 700;
            color: #ff6b35;
            margin-left: 10px;
        }

        /* Company Photos */
        .company-photos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .photo-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-item.photo-more {
            position: relative;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 32px;
            font-weight: 700;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 13px;
            text-align: center;
            padding: 10px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .job-content-wrapper {
                grid-template-columns: 1fr;
            }

            .job-sidebar {
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .job-header-card {
                flex-direction: column;
            }

            .job-header-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .job-header-actions {
                width: 100%;
            }

            .btn-share,
            .btn-apply {
                width: 100%;
            }

            .job-tags {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .job-main-content {
                padding: 20px;
            }

            .job-title {
                font-size: 20px;
            }

            .section-title {
                font-size: 18px;
            }
        }
    </style>

<?php
endwhile; // End of the loop.

get_footer();
