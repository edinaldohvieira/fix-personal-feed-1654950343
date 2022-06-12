<?php
/**
 * Plugin Name:     Fix Personal FEED 1654950343
 * Plugin URI:      https://github.com/edinaldohvieira/fix-personal-feed-1654950343
 * Description:     Recursos personalizados - Personal FEED para Mídia Indoor
 * Author:          edinaldohvieira
 * Author URI:      https://github.com/edinaldohvieira/
 * Text Domain:     fix-personal-feed-1654950343
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package         Fix_Personal_Feed_1654950343
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'parse_request', 'fix1654646345_parse_request');
function fix1654646345_parse_request( &$wp ) {
    if( substr($wp->request, 0,15)  == 'xml/midiaindoor'){
        $categoria = substr($wp->request, 16);
        fix1654644629($categoria);
        exit;
    }
}


function fix1654644629($categoria){
    $args = array();
    $args['showposts'] = 10;
    if($categoria){
        $args['category_name'] = $categoria;
    }
    $posts = query_posts($args);

    header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
    echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

?><rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    <?php do_action('rss2_ns'); ?>>
    <channel>
        <title><?php bloginfo_rss('name'); ?></title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss('description') ?></description>
        <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s 0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
        <language>pt-BR</language>
        <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
        <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
        <?php foreach ($posts as $post) { 
        $post_date = mysql2date('d/M/Y H:i:s.000', get_post_time( 'Y-m-d H:i:s', false, $post, false ), false);
        $post_modified = mysql2date('d/M/Y H:i:s.000', get_post_modified_time('Y-m-d H:i:s', false, $post), false);
        $categoria = preg_replace("/-/", " ", $categoria);
        $categoria = strtoupper($categoria);
        if(!$categoria) $categoria = "NOTICIAS";
        // $image = get_the_post_thumbnail_url($post->ID,'large');
        // $image = get_the_post_thumbnail_url($post->ID,array( 300));
        // $image = get_the_post_thumbnail_url($post->ID,'medium');
        $image = get_the_post_thumbnail_url($post->ID,'large');
        $image = preg_replace("/\&/", "&amp;", $image);

        $id = str_pad($post->ID, 6, "0", STR_PAD_LEFT);
        $post_date_unix = get_post_time( 'U', false, $post, false );
        $server_name = $_SERVER['SERVER_NAME'];
        ?>
        <item>
            <id><?php echo $server_name ?>-<?php echo $post_date_unix ?>-<?php echo $id ?></id>
            <title>
                <![CDATA[ <?php echo $categoria ?> ]]>
            </title>
            <description>
                <![CDATA[ <?php echo $post->post_title ?> ]]>
            </description>
            <linkfoto><?php echo $image ?></linkfoto>
            <creditfoto>
                <![CDATA[  ]]>
            </creditfoto>
            <pubdate><?php echo $post_date ?></pubdate>
            <atualizacao><?php echo $post_modified ?></atualizacao>
        </item>
        <?php } ?>
    </channel>
</rss><?php
}