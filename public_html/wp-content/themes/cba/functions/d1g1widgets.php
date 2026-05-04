<?php 

if (function_exists('register_sidebar')) {

    $widgets = [
        ['Základní lišta', 'sidebar-widgets', 'Widget Area', ''],
        ['Záhlaví', 'header-widgets', 'Header widget Area', 'header-widget'],
        ['Zápatí 1', 'footer-1', 'Widget area for footer', 'footer-widget'],
        ['Zápatí 2', 'footer-2', 'Widget area for footer', 'footer-widget'],
        ['Zápatí 3', 'footer-3', 'Widget area for footer', 'footer-widget']
    ];

    if ( !empty( $widgets ) ) {

        foreach ($widgets as $ct => $widget) {
            
            register_sidebar(array(
                'name' => $widget[0],
                'id'   => $widget[1],
                'description'   => $widget[2],
                'before_widget' => '<div class="widget '.$widget[3].'" id="widget-%1$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<p class="h4">',
                'after_title'   => '</p>'
            ));

        }

    }

}
