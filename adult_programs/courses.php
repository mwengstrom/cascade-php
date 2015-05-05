<?php
/**
 * Created by PhpStorm.
 * User: ejc84332
 * Date: 4/27/15
 * Time: 8:42 AM
 */


function course_catalog($code, $values){

    $data = array('options' => $values);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $url = "http://wsapi.bethel.edu/courses/course-catalog/$code";
    $context  = stream_context_create($options);
    return file_get_contents($url, false, $context);

}

function individual_courses($code, $values){

    $url = "http://wsapi.bethel.edu/courses/open-enrollment-courses/$code";
    return file_get_contents($url, false);
}

function load_course_catalog ($values, $code) {

    $content = autoCache('course_catalog', array($code, $values), $code . $_SERVER['REQUEST_URI'], 0);
    $content = json_decode($content, true);
    echo $content['data'];
}


//called by cas-summer-courses format so it can use autoCache
function summer_courses($code){

    $url = "http://wsapi.bethel.edu/courses/$code";
    return file_get_contents($url);
}
