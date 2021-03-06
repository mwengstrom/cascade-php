<?php
/**
 * Created by PhpStorm.
 * User: ces55739
 * Date: 8/1/14
 * Time: 2:59 PM
 */

/*
 * This file will be used to 'import' Cascade functions into PHP.
 *
 * It also will include functions that are used in multiple files.
 *
 *
 */

    // $xml is the possible items that are being checked.
    // $categories is the page's metadata.
    function match_robust_metadata($xml, $categories)
    {
        // The first part is to build the metadata into an array for the xml.
        // This array mimics the $categories array.
        $xmlCategories = array(array(), array(), array(), array(), array(), array());
        foreach( $xml->{'dynamic-metadata'} as $md ){
            $name = $md->name;
            foreach($md->value as $value ){
                if($value == "Select" || $value == "none"){
                    continue;
                }

                if( $name == "school")
                {
                    array_push($xmlCategories[0], $value);
                }
                elseif( $name == "topic")
                {
                    array_push($xmlCategories[1], $value);
                }
                elseif( $name == "department")
                {
                    array_push($xmlCategories[2], $value);
                }
                elseif( $name == "adult-undergrad-program")
                {
                    array_push($xmlCategories[3], $value);
                }
                elseif( $name == "graduate-program")
                {
                    array_push($xmlCategories[4], $value);
                }
                elseif( $name == "seminary-program")
                {
                    array_push($xmlCategories[5], $value);
                }
            }
        }

        // compare the 2 sets of metadata
        for( $i=0; $i < 6; $i++){
            foreach($categories[$i] as $value){
                if($value == "Select" || $value == "none"){
                    continue;
                }
                if( !in_array($value, $xmlCategories[$i])){
                    return "No";
                }
            }
        }
        return "Yes";
    }


    function display_x_elements_from_array( $array, $numToFind, $shuffle=true)
    {
        if($shuffle){
            shuffle($array);
        }
        while($element = array_pop($array)){
            echo $element;
            $numToFind--;
            if( $numToFind == 0)
                break;
        }
    }
?>