<?php
/**
 * Created by PhpStorm.
 * User: ejc84332
 * Date: 8/28/14
 * Time: 2:39 PM
 */

    require_once 'news_helper.php';
    $xml = get_news_xml();
    $news = "";
    //$pages = array_slice($xml, 0, 4);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title> Bethel News </title>
</head>
<body>
<div class="silva-channel">
    <div class="channel-section">

        <div class="uportal-cms-block" id="uportal-cms-block">

            <?php
                $count = 0;
                $resp = "";
                foreach($xml as $page){
                    if( $count == 4){
                        break;
                    }

                    $image = "https://www.bethel.edu" . $page['image'][0];
                    $title = $page['title'][0];
                    $teaser = $page['teaser'][0];
                    $title = str_replace("&", "&amp;", $title);
                    $teaser = str_replace("&", "&amp;", $teaser);
                    $link = "https://www.bethel.edu" . $page['path'][0];

                    $resp .= '<div class="media-box pb1">';
                        $resp .= "<a href='$link'>";
                            $resp .= "<img class='media-box-img'
                                src='$image' alt='$title' title='$title'/>";
                        $resp .= '</a>';
                        $resp .= '<div class="media-box-body">';
                            $resp .= '<h2 class="h5">';
                            $resp .= "<a href='$link'>$title</a></h2>";
                            $resp .= "<p>$teaser</p>";
                        $resp .= '</div>';
                    $resp .= '</div>';
                    $count++;
                }

                echo $resp;
        ?>
        </div>
    </div>
    <div class="channel-section">

        <div class="uportal-cms-block section-center" id=""><p><a href="https://blink-cms.bethel.edu/channels/news/bethel-news/viewer/rss.xml"><img class="float-right" title="Syndication Feed" src="https://blink-cms.bethel.edu/channels/news-events/bethel-news/feed-icon-14x14.png" alt="" width="14" height="14"></a><a href="mailto:gotnews@bethel.edu">Submit News</a> | <a href="https://bethelnet.bethel.edu/e-announcements/add-ann/">Submit E-Announcement</a> | <a href="https://editor.its.bethel.edu/silva/luminis-content/channels/news/bethel-news/viewer">Archive</a></p></div>


    </div>
</div>
</body>
</html>