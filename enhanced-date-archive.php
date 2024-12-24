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
    padding: 15px; 
    background: #ffffff; 
    border-radius: 12px; 
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 1px solid #eee;
}

.filter-row { 
    display: flex; 
    gap: 15px; 
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.filter-select { 
    padding: 12px 16px; 
    border-radius: 8px; 
    border: 1px solid #ddd;
    flex: 1;
    min-width: 200px;
    max-width: 100%;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    touch-action: manipulation;
    background-color: #f8f9fa;
    color: #333;
    font-size: 15px;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.filter-select:hover {
    border-color: #bbb;
}

.filter-select:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

/* Year Sections */
.year-section { 
    margin-bottom: 40px; 
}

.year-heading { 
    color: #333; 
    border-bottom: 2px solid #333; 
    padding: 10px 0; 
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
    min-height: 44px;
    align-items: center;
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
        padding: 12px;
        margin: 0 12px 20px 12px;
        background: #ffffff;
    }
    
    .filter-row {
        flex-direction: column;
        gap: 12px;
    }
    
    .filter-select {
        width: 100%;
        min-width: unset;
        height: 56px;
        font-size: 16px;
        padding: 0 40px 0 16px;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 4px 0;
    }
    
    .year-heading {
        padding: 15px 5px;
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

    .toggle-icon {
        padding: 15px 10px;
        margin: -15px 0;
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
document.addEventListener('DOMContentLoaded', function() {
    // Helper function to get elements
    const $ = selector => document.querySelectorAll(selector);
    
    // Initialize collapsed state
    function initializeCollapsedState() {
        $('.year-content').forEach(content => content.classList.add('collapsed'));
        $('.toggle-icon').forEach(icon => icon.textContent = '+');
    }
    
    initializeCollapsedState();

    // Toggle handler function
    function handleToggle(heading) {
        const content = heading.nextElementSibling;
        const icon = heading.querySelector('.toggle-icon');
        const isCollapsed = content.classList.contains('collapsed');
        
        // On mobile, collapse all other sections first
        if ('ontouchstart' in window && !isCollapsed) {
            $('.year-content').forEach(otherContent => {
                if (otherContent !== content) {
                    otherContent.classList.add('collapsed');
                    otherContent.previousElementSibling.querySelector('.toggle-icon').textContent = '+';
                }
            });
        }

        // Toggle current section
        content.classList.toggle('collapsed');
        icon.textContent = isCollapsed ? '-' : '+';
    }

    // Handle year section toggling
    $('.year-heading').forEach(heading => {
        let touchStartY = 0;
        let touchEndY = 0;

        // Touch start handler
        heading.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        // Touch end handler
        heading.addEventListener('touchend', function(e) {
            e.preventDefault();
            touchEndY = e.changedTouches[0].clientY;
            
            // Only trigger if it's a tap (not a scroll)
            if (Math.abs(touchEndY - touchStartY) < 10) {
                handleToggle(this);
            }
        }, { passive: false });

        // Click handler for desktop
        heading.addEventListener('click', function(e) {
            if (!('ontouchstart' in window)) {
                e.preventDefault();
                handleToggle(this);
            }
        });
    });

    // Filter handling
    function updateFilters() {
        const archiveContent = document.getElementById('archive-content');
        const selectedCategory = document.getElementById('category-filter').value;
        const selectedTag = document.getElementById('tag-filter').value;
        
        archiveContent.style.opacity = '0.6';
        
        // Reset if no filters
        if (!selectedCategory && !selectedTag) {
            $('.post-item').forEach(item => item.style.display = '');
            $('.month-section').forEach(section => section.style.display = '');
            $('.year-section').forEach(section => section.style.display = '');
            updateCounts();
            archiveContent.style.opacity = '1';
            return;
        }

        // Filter posts
        $('.post-item').forEach(post => {
            let showPost = true;

            if (selectedCategory) {
                const categoryMatch = post.querySelector(
                    `.category-tag[data-category-id="${selectedCategory}"]`
                );
                showPost = !!categoryMatch;
            }

            if (showPost && selectedTag) {
                const tagMatch = post.querySelector(
                    `.category-tag[data-tag-id="${selectedTag}"]`
                );
                showPost = !!tagMatch;
            }

            if (showPost) {
                post.style.display = '';
                const monthSection = post.closest('.month-section');
                const yearSection = post.closest('.year-section');
                const yearContent = yearSection.querySelector('.year-content');
                const toggleIcon = yearSection.querySelector('.toggle-icon');
                
                monthSection.style.display = '';
                yearSection.style.display = '';
                yearContent.classList.remove('collapsed');
                toggleIcon.textContent = '-';
            } else {
                post.style.display = 'none';
            }
        });

        // Hide empty sections
        $('.month-section').forEach(section => {
            const visiblePosts = section.querySelectorAll('.post-item[style="display: none;"]').length;
            section.style.display = visiblePosts === section.querySelectorAll('.post-item').length ? 'none' : '';
        });

        $('.year-section').forEach(section => {
            const visiblePosts = section.querySelectorAll('.post-item[style="display: none;"]').length;
            section.style.display = visiblePosts === section.querySelectorAll('.post-item').length ? 'none' : '';
        });

        updateCounts();
        archiveContent.style.opacity = '1';
    }

    // Update post counts
    function updateCounts() {
        $('.year-section').forEach(section => {
            const visiblePosts = Array.from(section.querySelectorAll('.post-item'))
                .filter(post => post.style.display !== 'none').length;
            section.querySelector('.year-heading .post-count')
                .textContent = `${visiblePosts} posts`;
            
            section.querySelectorAll('.month-section').forEach(monthSection => {
                const monthVisiblePosts = Array.from(monthSection.querySelectorAll('.post-item'))
                    .filter(post => post.style.display !== 'none').length;
                monthSection.querySelector('.month-heading .post-count')
                    .textContent = `${monthVisiblePosts} posts`;
            });
        });
    }

    // Add filter event listeners
    ['category-filter', 'tag-filter'].forEach(filterId => {
        const filter = document.getElementById(filterId);
        filter.addEventListener('change', updateFilters);
        
        // Add touch event handling for mobile
        filter.addEventListener('touchend', function(e) {
            e.stopPropagation();
        }, { passive: true });
    });
});
</script>

<?php get_footer(); ?>