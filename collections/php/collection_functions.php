<?php
/**
 * Created by PhpStorm.
 * User: ces55739
 * Date: 9/2/14
 * Time: 11:28 AM
 */


include_once $_SERVER["DOCUMENT_ROOT"] . "/code/php_helper_for_cascade.php";
//todo Do we need Destination name?
global $destinationName;
// Destination name makes it easier to modify the url from "www.bethel.edu" and "staging.bethel.edu"
if( strstr(getcwd(), "staging/public") ){
    $destinationName = "staging";
}
else{ // Live site.
    $destinationName = "www";
}
function show_profile_story_collection($numItems, $School, $Topic, $CAS, $CAPS, $GS, $SEM){
    $categories = array( $School, $Topic, $CAS, $CAPS, $GS, $SEM );
    $collectionArray = get_xml_collection($_SERVER["DOCUMENT_ROOT"] . "/_shared-content/xml/profile-stories.xml", $categories);
    if( strstr(getcwd(), "cms.pub") ){
        $shuffle = true;
    }else{
        $shuffle = false;
    }
    display_x_elements_from_array($collectionArray, $numItems, $shuffle);
    return;
}
function show_quote_collection($numItems, $School, $Topic, $CAS, $CAPS, $GS, $SEM){
    include_once $_SERVER["DOCUMENT_ROOT"] . "/code/quotes/php/get-quotes.php";
    $categories = array( $School, $Topic, $CAS, $CAPS, $GS, $SEM );
    $collectionArray = get_xml_collection($_SERVER["DOCUMENT_ROOT"] . "/_shared-content/xml/quotes.xml", $categories);
    display_x_elements_from_array($collectionArray, $numItems);
    return;
}
function show_proof_point_collection($numItems, $School, $Topic, $CAS, $CAPS, $GS, $SEM){
    include_once $_SERVER["DOCUMENT_ROOT"] . "/code/proof-points/php/get-proof-points.php";
    global $numberOfItems;
    $numberOfItems = $numItems;
    $categories = array( $School, $Topic, $CAS, $CAPS, $GS, $SEM );
    $collectionArray = get_xml_collection($_SERVER["DOCUMENT_ROOT"] . "/_shared-content/xml/proof-points.xml", $categories);
    echo '<div class="grid  proof-points">';
    display_x_elements_from_array($collectionArray, $numItems);
    echo '</div>';
    return;
}
// Converts and xml file to an array of profile stories
function get_xml_collection($fileToLoad, $categories ){
    $xml = simplexml_load_file($fileToLoad);
    $collection = array();
    $collection = traverse_folder_collection($xml, $collection, $categories);
    return $collection;
}
// Traverse through the xml structure.
function traverse_folder_collection($xml, $collection, $categories){
    foreach ($xml->children() as $child) {
        $name = $child->getName();
        if ($name == 'system-folder'){
            $collection = traverse_folder_collection($child, $collection, $categories);
        }elseif ($name == 'system-page' || $name == 'system-block'){
            // Set the page data.
            $collectionElement = inspect_page_collection($child, $categories);
            if( $collectionElement['display'] == "Yes")
            {
                array_push($collection, $collectionElement['html']);
            }
        }
    }
    return $collection;
}
// Gathers the info/html of the page.
function inspect_page_collection($xml, $categories){

    $page_info = array(
        "display-name" => $xml->{'display-name'},
        "published" => $xml->{'last-published-on'},
        "description" => $xml->{'description'},
        "path" => $xml->path,
        "md" => array(),
        "html" => "",
        "display" => "No",
    );
    $ds = $xml->{'system-data-structure'};
    $dataDefinition = $ds['definition-path'];
    ## This is a carousel
    if( $dataDefinition == "Profile Story")
    {
        $page_info['display'] = match_robust_metadata($xml, $categories);
        if( $page_info['display'] == "Yes")
        {
            $page_info['html'] = get_profile_stories_html($xml);
        }
    }
    ## This is a carousel
    else if( $dataDefinition == "Blocks/Quote")
    {
        $page_info['display'] = match_robust_metadata($xml, $categories);
        if( $page_info['display'] == "Yes" )
        {
            // Code to make it a carousel
//            $html = '<div class="slick-item">';
//            $html .= '<div class="pa1  quote  grayLighter">';
//            $html .= get_quote_html($xml);
//            $html .= '</div></div>';

            //Twig version
            //todo hasn't been tested yet
            $twig = makeTwigEnviron('/code/general-cascade/twig');
            $html = $twig->render('slick-item.html', array(
                'html' => get_quote_html($xml)));

            $page_info['html'] = $html;

        }
    }
    ## This is a column block
    else if( $dataDefinition == "Blocks/Proof Point")
    {
        $page_info['display'] = match_robust_metadata($xml, $categories);
        if( $page_info['display'] == "Yes" )
        {
            // Code to make it a column block
            global $numberOfItems;
//            $html = "<div class='grid-cell  u-medium-1-".$numberOfItems."'><div class='grid-pad-1x'>";
//            $html .= get_proof_point_html($xml);
//            $html .= '</div></div>';

            //twig version
            //todo test then delete above version
            $twig = makeTwigEnviron('/code/collections/twig');
            $html = $twig->render('inspect_page_collection_2.html', array(
                'numberOfItems' => $numberOfItems,
                'proof_points' => get_proof_point_html($xml)));

            $page_info['html'] = $html;
        }
    }
    return $page_info;
}
// Returns the profile stories html
function get_profile_stories_html( $xml){
    //todo put this is metadata-check
    $twig = makeTwigEnviron('/code/collections/twig');

    global $destinationName;
    $ds = $xml->{'system-data-structure'};
    // The image that shows up in the 'column' view.
    $imagePath = $ds->{'images'}->{'homepage-image'}->path;
    $viewerTeaser = $ds->{'viewer-teaser'};
    $homepageTeaser = $ds->{'homepage-teaser'};
    if($viewerTeaser == "")
    {
        $teaser = $homepageTeaser;
    }
    else
    {
        $teaser = $viewerTeaser;
    }
    $quote = $ds->{'quote'};
    $html = "<div class='slick-item' style='width:100%'>";
    $html .= '<a href="http://bethel.edu'.$xml->path.'">';
    $html .= srcset($imagePath, false);
    $html .= '<figure class="feature__figure">';
    $html .= '<blockquote class="feature__blockquote">'.$quote.'</blockquote>';
    $html .= '<figcaption class="feature__figcaption">'.$teaser.'</figcaption>';
    $html .= '</figure>';
    $html .= '</a>';
    $html .= "</div>";

    //twig version
    //todo test and delete above version
//    $html = $twig->render('get_profile_stories_html.html', array(
//        'quote' => $quote,
//        'teaser' => $teaser,
//        'image' => srcset($imagePath, false)));


    return $html;
}
?>