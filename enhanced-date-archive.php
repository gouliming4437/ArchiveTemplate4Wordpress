<?php

?>
<style>
  .archive-container { 
    max-width: 800px; 
    margin: 0 auto; 
    padding: 20px; 
    font-family: system-ui; 
  }
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
  }
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
  }
  .post-count { 
    color: #666; 
    font-size: 0.9em; 
  }
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
  .post-item { 
    margin-bottom: 15px; 
    padding-left: 20px; 
  }
  .post-date { 
    color: #888; 
    font-size: 14px; 
    display: inline-block; 
    width: 100px; 
  }
  .post-title { 
    color: #444; 
    text-decoration: none; 
  }
  .post-title:hover {
    color: #0066cc;
  }
  .post-meta { 
    color: #666; 
    font-size: 0.9em; 
    margin-left: 120px; 
  }
  .category-tag { 
    background: #eee; 
    padding: 2px 8px; 
    border-radius: 12px; 
    font-size: 0.8em; 
    margin-right: 5px; 
    display: inline-block;
  }
  .toggle-icon {
    font-weight: bold;
    font-size: 1.2em;
    margin-right: 10px;
  }
  .year-content {
    display: block;
  }
  .year-content.collapsed {
    display: none;
  }
  @media screen and (max-width: 600px) {
    .filter-row {
      flex-direction: column;
      gap: 10px;
    }
    
    .filter-select {
      width: 100%;
      min-width: unset;
    }
    
    .filters {
      padding: 15px;
    }
  }
</style>


<?php
/*
Template Name: Enhanced Date Archive
*/

get_header(); ?>

<?php
echo "<!-- Debug Category Info:\n";
$debug_categories = get_categories();
foreach($debug_categories as $cat) {
    echo "Category: {$cat->name} (ID: {$cat->term_id})\n";
}
echo "-->\n";
?>

<div class="archive-container">
    <h1><?php the_title(); ?></h1>

    <!-- Search and Filters -->
    <div class="filters">
        <div class="filter-row">
            <?php
            // Categories dropdown
            $categories = get_categories();
            echo '<select class="filter-select" id="category-filter">';
            echo '<option value="">All Categories</option>';
            foreach($categories as $category) {
                echo '<option value="' . esc_attr(strval($category->term_id)) . '">' . 
                    esc_html($category->name) . '</option>';
            }
            echo '</select>';

            // Tags dropdown
            $tags = get_tags();
            echo '<select class="filter-select" id="tag-filter">';
            echo '<option value="">All Tags</option>';
            foreach($tags as $tag) {
                echo '<option value="' . $tag->term_id . '">' . $tag->name . '</option>';
            }
            echo '</select>';
            ?>
        </div>
    </div>

    <div id="archive-content">
    <?php
    // Get all posts
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $posts = get_posts($args);
    $posts_by_year_month = array();
    
    // Group posts by year and month
    foreach($posts as $post) {
        $year = date('Y', strtotime($post->post_date));
        $month = date('F', strtotime($post->post_date));
        $posts_by_year_month[$year][$month][] = $post;
    }
    
    // Display posts
    foreach($posts_by_year_month as $year => $months) {
        $year_post_count = 0;
        foreach($months as $month_posts) {
            $year_post_count += count($month_posts);
        }
        
        echo '<section class="year-section">';
        echo '<h2 class="year-heading">';
        echo '<span><span class="toggle-icon">+</span>' . $year . '</span>';
        echo '<span class="post-count">' . $year_post_count . ' posts</span>';
        echo '</h2>';
        echo '<div class="year-content">';
        
        foreach($months as $month => $month_posts) {
            echo '<div class="month-section">';
            echo '<h3 class="month-heading">';
            echo $month;
            echo '<span class="post-count">' . count($month_posts) . ' posts</span>';
            echo '</h3>';
            
            foreach($month_posts as $post) {
                setup_postdata($post);
                ?>
                <div class="post-item">
                    <span class="post-date"><?php echo get_the_date('M d'); ?></span>
                    <a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a>
                    <div class="post-meta">
                        <?php
                        // Display categories and tags
                        $categories = get_the_category();
                        $tags = get_the_tags();
                        
                        foreach($categories as $category) {
                            echo '<span class="category-tag" data-category-id="' . esc_attr(strval($category->term_id)) . '">' . 
                                esc_html($category->name) . '</span>';
                        }
                        if($tags) {
                            foreach($tags as $tag) {
                                echo '<span class="category-tag" data-tag-id="' . esc_attr($tag->term_id) . '" data-tag-slug="' . esc_attr($tag->slug) . '">' 
                                    . esc_html($tag->name) . '</span>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            echo '</div>'; // .month-section
        }
        echo '</div>'; // .year-content
        echo '</section>'; // .year-section
    }
    wp_reset_postdata();
    ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize all year sections as collapsed
    $('.year-heading').each(function() {
        $(this).next('.year-content').addClass('collapsed');
        $(this).find('.toggle-icon').text('+');
    });

    // Toggle functionality
    $('.year-heading').on('click', function() {
        toggleYearSection($(this));
    });

    function toggleYearSection($heading) {
        var content = $heading.next('.year-content');
        var icon = $heading.find('.toggle-icon');
        
        content.toggleClass('collapsed');
        
        if(content.hasClass('collapsed')) {
            icon.text('+');
        } else {
            icon.text('-');
        }
    }

    // Category and tag filtering
    $('.filter-select').on('change', function() {
        var selectedCategory = $('#category-filter').val();
        var selectedTag = $('#tag-filter').val();

        if (!selectedCategory && !selectedTag) {
            // Reset to show all posts and sections
            $('.post-item').show();
            $('.month-section').show();
            $('.year-section').show();
            $('.year-content').removeClass('collapsed'); // Show the content
            $('.toggle-icon').text('-'); // Change icon to minus
            
            // Make sure post counts are correct
            updateCounts();
            return;
        }
        
        // If we have filters, proceed with filtering
        $('.post-item').each(function() {
            var $post = $(this);
            var show = true;

            if (selectedCategory) {
                var categoryFound = false;
                $post.find('.category-tag[data-category-id]').each(function() {
                    if ($(this).attr('data-category-id') === selectedCategory) {
                        categoryFound = true;
                        return false;
                    }
                });
                if (!categoryFound) {
                    show = false;
                }
            }

            if (show && selectedTag) {
                var tagFound = false;
                $post.find('.category-tag[data-tag-id]').each(function() {
                    if ($(this).attr('data-tag-id') === selectedTag) {
                        tagFound = true;
                        return false;
                    }
                });
                if (!tagFound) {
                    show = false;
                }
            }

            if (show) {
                // Show the post
                $post.show();
                
                // Show its parent month section
                var $monthSection = $post.closest('.month-section');
                $monthSection.show();
                
                // Show its parent year section and content
                var $yearSection = $monthSection.closest('.year-section');
                $yearSection.show();
                
                // Show and expand the year content
                var $yearContent = $monthSection.closest('.year-content');
                $yearContent.removeClass('collapsed');
                $yearSection.find('.toggle-icon').text('-');
            } else {
                $post.hide();
            }
        });

        // Hide empty sections
        $('.month-section').each(function() {
            var $month = $(this);
            if ($month.find('.post-item:visible').length === 0) {
                $month.hide();
            }
        });

        $('.year-section').each(function() {
            var $year = $(this);
            if ($year.find('.post-item:visible').length === 0) {
                $year.hide();
            }
        });

        updateCounts();
    });

    function updateCounts() {
        $('.year-section').each(function() {
            var yearCount = $(this).find('.post-item:visible').length;
            $(this).find('.year-heading .post-count').text(yearCount + ' posts');
            
            $(this).find('.month-section').each(function() {
                var monthCount = $(this).find('.post-item:visible').length;
                $(this).find('.month-heading .post-count').text(monthCount + ' posts');
                $(this).toggle(monthCount > 0);
            });
        });
    }
});
</script>

<?php get_footer(); ?>