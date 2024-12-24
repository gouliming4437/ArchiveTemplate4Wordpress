<?php
/*
Template Name: Enhanced Date Archive
*/

get_header(); 
?>

<style>
/* Base Container */
.archive-container { 
    max-width: 800px; 
    margin: 0 auto; 
    padding: 20px; 
    font-family: system-ui; 
}

/* Filter Section */
.filters { 
    margin-bottom: 30px; 
    padding: 20px; 
    background: #f5f5f5; 
    border-radius: 8px; 
}

.filter-row { 
    display: flex; 
    gap: 15px; 
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.filter-select { 
    padding: 8px; 
    border-radius: 4px; 
    border: 1px solid #ddd;
    flex: 1;
    min-width: 200px;
    max-width: 100%;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    touch-action: manipulation;
}

/* Year Sections */
.year-section { 
    margin-bottom: 40px; 
}

.year-heading { 
    color: #333; 
    border-bottom: 2px solid #333; 
    padding-bottom: 10px; 
    margin-bottom: 20px; 
    display: flex; 
    justify-content: space-between; 
    cursor: pointer;
    position: relative;
    z-index: 1;
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    user-select: none;
}

.year-heading span {
    display: flex;
    align-items: center;
    gap: 15px;
}

.toggle-icon {
    display: inline-block;
    width: 30px;
    text-align: center;
    font-weight: bold;
    margin-right: 10px;
}

.year-content {
    display: block;
    transition: none;
}

.year-content.collapsed {
    display: none;
}

/* Month Sections */
.month-section { 
    margin-bottom: 30px; 
}

.month-heading { 
    color: #666; 
    border-bottom: 1px solid #eee; 
    padding-bottom: 5px; 
    margin-bottom: 15px; 
    display: flex; 
    justify-content: space-between; 
}

/* Post Items */
.post-item { 
    margin-bottom: 15px; 
    padding-left: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: baseline;
}

.post-date { 
    color: #888; 
    font-size: 14px;
    flex: 0 0 80px;
}

.post-title { 
    color: #444; 
    text-decoration: none;
    flex: 1 1 200px;
}

.post-title:hover {
    color: #0066cc;
}

.post-meta { 
    color: #666; 
    font-size: 0.9em;
    flex: 0 1 100%;
    margin-left: 90px;
}

.post-count { 
    color: #666; 
    font-size: 0.9em; 
}

.category-tag { 
    background: #eee; 
    padding: 2px 8px; 
    border-radius: 12px; 
    font-size: 0.8em; 
    margin-right: 5px; 
    display: inline-block;
}

/* Mobile Styles */
@media screen and (max-width: 600px) {
    .filters {
        display: none;
    }
    
    .filter-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .filter-select {
        width: 100%;
        min-width: unset;
        height: 44px;
        font-size: 16px;
    }
    
    .year-heading {
        padding: 15px 0;
    }
    
    .post-item {
        padding-left: 10px;
        gap: 8px;
    }

    .post-date {
        flex: 0 0 60px;
        font-size: 13px;
    }

    .post-title {
        flex: 1 1 150px;
    }

    .post-meta {
        margin-left: 68px;
    }
}

@media (hover: none) and (pointer: coarse) {
    .toggle-icon {
        padding: 15px;
        margin: -15px;
    }
}
</style>

<div class="archive-container">
    <h1><?php the_title(); ?></h1>

    <div class="filters">
        <div class="filter-row">
            <?php
            // Categories dropdown
            $categories = get_categories();
            echo '<select class="filter-select" id="category-filter">';
            echo '<option value="">All Categories</option>';
            foreach($categories as $category) {
                printf(
                    '<option value="%s">%s</option>',
                    esc_attr($category->term_id),
                    esc_html($category->name)
                );
            }
            echo '</select>';

            // Tags dropdown
            $tags = get_tags();
            echo '<select class="filter-select" id="tag-filter">';
            echo '<option value="">All Tags</option>';
            foreach($tags as $tag) {
                printf(
                    '<option value="%s">%s</option>',
                    esc_attr($tag->term_id),
                    esc_html($tag->name)
                );
            }
            echo '</select>';
            ?>
        </div>
    </div>

    <div id="archive-content">
        <?php
        // Get all posts
        $posts = get_posts([
            'post_type' => 'post',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        ]);
        
        $posts_by_year_month = [];
        
        // Group posts by year and month
        foreach($posts as $post) {
            $year = date('Y', strtotime($post->post_date));
            $month = date('F', strtotime($post->post_date));
            $posts_by_year_month[$year][$month][] = $post;
        }
        
        // Display posts
        foreach($posts_by_year_month as $year => $months) {
            $year_post_count = array_sum(array_map('count', $months));
            ?>
            <section class="year-section">
                <h2 class="year-heading">
                    <span><span class="toggle-icon">+</span><?php echo esc_html($year); ?></span>
                    <span class="post-count"><?php echo esc_html($year_post_count); ?> posts</span>
                </h2>
                <div class="year-content">
                    <?php foreach($months as $month => $month_posts): ?>
                        <div class="month-section">
                            <h3 class="month-heading">
                                <?php echo esc_html($month); ?>
                                <span class="post-count"><?php echo count($month_posts); ?> posts</span>
                            </h3>
                            
                            <?php foreach($month_posts as $post): 
                                setup_postdata($post); ?>
                                <div class="post-item">
                                    <span class="post-date"><?php echo get_the_date('M d'); ?></span>
                                    <a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a>
                                    <div class="post-meta">
                                        <?php
                                        $categories = get_the_category();
                                        $tags = get_the_tags();
                                        
                                        foreach($categories as $category) {
                                            printf(
                                                '<span class="category-tag" data-category-id="%s">%s</span>',
                                                esc_attr($category->term_id),
                                                esc_html($category->name)
                                            );
                                        }
                                        
                                        if($tags) {
                                            foreach($tags as $tag) {
                                                printf(
                                                    '<span class="category-tag" data-tag-id="%s" data-tag-slug="%s">%s</span>',
                                                    esc_attr($tag->term_id),
                                                    esc_attr($tag->slug),
                                                    esc_html($tag->name)
                                                );
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php
        }
        wp_reset_postdata();
        ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize all year sections as collapsed
    function initializeCollapsedState() {
        $('.year-content').addClass('collapsed');
        $('.toggle-icon').text('+');
    }
    
    initializeCollapsedState();

    // Toggle handler function
    function handleToggle($heading) {
        const $content = $heading.next('.year-content');
        const $icon = $heading.find('.toggle-icon');
        
        if ($content.hasClass('collapsed')) {
            $content.removeClass('collapsed');
            $icon.text('-');
        } else {
            $content.addClass('collapsed');
            $icon.text('+');
        }
    }

    // Mobile vs Desktop handlers
    if ('ontouchstart' in window) {
        $('.year-heading').on('touchstart', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            $('.year-content').addClass('collapsed');
            $('.toggle-icon').text('+');
            
            handleToggle($(this));
            return false;
        });
    } else {
        $('.year-heading').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            handleToggle($(this));
            return false;
        });
    }

    // Filter handling
    $('.filter-select').on('change', function() {
        const selectedCategory = $('#category-filter').val();
        const selectedTag = $('#tag-filter').val();
        
        $('#archive-content').css('opacity', '0.6');
        
        // Reset if no filters
        if (!selectedCategory && !selectedTag) {
            $('.post-item, .month-section, .year-section').show();
            updateCounts();
            $('#archive-content').css('opacity', '1');
            return;
        }

        // Filter posts
        $('.post-item').each(function() {
            const $post = $(this);
            let showPost = true;

            if (selectedCategory) {
                showPost = $post.find(`.category-tag[data-category-id="${selectedCategory}"]`).length > 0;
            }

            if (showPost && selectedTag) {
                showPost = $post.find(`.category-tag[data-tag-id="${selectedTag}"]`).length > 0;
            }

            if (showPost) {
                $post.show()
                    .closest('.month-section').show()
                    .closest('.year-section').show()
                    .find('.year-content').removeClass('collapsed')
                    .closest('.year-section').find('.toggle-icon').text('-');
            } else {
                $post.hide();
            }
        });

        // Hide empty sections
        $('.month-section').each(function() {
            $(this).toggle($(this).find('.post-item:visible').length > 0);
        });

        $('.year-section').each(function() {
            $(this).toggle($(this).find('.post-item:visible').length > 0);
        });

        updateCounts();
        $('#archive-content').css('opacity', '1');
    });

    function updateCounts() {
        $('.year-section').each(function() {
            const $section = $(this);
            const visiblePosts = $section.find('.post-item:visible').length;
            $section.find('.year-heading .post-count').text(`${visiblePosts} posts`);
            
            $section.find('.month-section').each(function() {
                const monthVisiblePosts = $(this).find('.post-item:visible').length;
                $(this).find('.month-heading .post-count').text(`${monthVisiblePosts} posts`);
            });
        });
    }
});
</script>

<?php get_footer(); ?>