<?php

/**
 * Summary: php file which implements the custom shortcodes
 */


// Add shortcodes on init
add_action('init', function(){

    // Add shortcodes. Add additional shortcodes here as needed.

    // Add example shortcode
    add_shortcode(
        'gmuw_trmatrixgmu_shortcode', //shortcode label (use as the shortcode on the site)
        'gmuw_trmatrixgmu_shortcode' //callback function
    );

    // Add shortcodes
    //search
    add_shortcode(
        'display_matrix_search_form', //shortcode label (use as the shortcode on the site)
        'gmuw_trmatrixgmu_display_matrix_search_form' //callback function
    );
    //results
    add_shortcode(
        'display_matrix_search_results', //shortcode label (use as the shortcode on the site)
        'gmuw_trmatrixgmu_display_matrix_search_results' //callback function
    );

});

// Define shortcode callback functions. Add additional shortcode functions here as needed.

// Define example shortcode
function gmuw_trmatrixgmu_shortcode(){

    // Determine return value
    $content='set what the shortcode will do/say...';

    // Return value
    return $content;

}

// shortcode for matrix search form
function gmuw_trmatrixgmu_display_matrix_search_form(){

    //get globals
    global $wpdb;

    //set table name, using the database prefix
    $mytable = $wpdb->prefix . "gmuw_trmatrixgmu_coursedata";

    //set default parameters
    $mymode='';
    $mystate='';
    $myschool='';
    $mycourse='';
    $mydeptcode='';

    //get parameters
    if (isset($_GET['mode'])) { $mymode = sanitize_text_field($_GET['mode']); }
    if (isset($_GET['state'])) { $mystate = sanitize_text_field($_GET['state']); }
    if (isset($_GET['school'])) { $myschool = sanitize_text_field($_GET['school']); }
    if (isset($_GET['course'])) { $mycourse = sanitize_text_field($_GET['course']); }
    if (isset($_GET['deptCode'])) { $mydeptcode = sanitize_text_field($_GET['deptCode']); }

    //check parameters
    //if (!empty($mystate)) { $mystateexist = true; }
    //if (!empty($myschool)) { $myschoolexist = true; }
    //if (!empty($mycourse)) { $mycourseExist = true; }
    $mystateexist = !empty($mystate);
    $myschoolexist = !empty($myschool);
    $mycourseExist = !empty($mycourse);


    // set return value
    $content='';

    //javascript
    $content.='<script type="text/javascript">';
    $content.='function fnDisableFields() {';
    $content.='document.getElementById("state").readonly = true;';
    $content.='document.getElementById("school").readonly = true;';
    $content.='document.getElementById("course").readonly = true;';
    $content.='}';
    $content.='</script>';

    //Set SQL strings
        $mystatesql = "SELECT DISTINCT SZBTATC_SBGI_STAT_CODE FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE IS NOT NULL AND SZBTATC_SBGI_STAT_CODE<>'' ORDER BY SZBTATC_SBGI_STAT_CODE;";
        $myschoolsql = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_DESC,SZBTATC_SBGI_STAT_CODE FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE<>'ZZZ000' ORDER BY SZBTATC_SBGI_DESC;";
        $mycoursesql = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_STAT_CODE, SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE  FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE = '" . $myschool . "' ORDER BY SHRTATC_SUBJ_TRNS;";

    //output
    //$content.='<p>State Exist: '. $mystateexist .'</p>';
    //$content.='<p>School Exist: '. $myschoolexist .'</p>';
    //$content.='<p>Course Exist: '. $mycourseExist .'</p>';
    //$content.='<p>Mode: '. $mymode .'</p>';
    //$content.='<p>State: '. $mystate .'</p>';
    //$content.='<p>School: '. $myschool .'</p>';
    //$content.='<p>Course: '. $mycourse .'</p>';
    //$content.='<p>Dept Code: '. $mydeptcode .'</p>';
    //$content.='<p>SQL State: '. $mystatesql .'</p>';
    //$content.='<p>SQL School: '. $myschoolsql .'</p>';
    //$content.='<p>SQL Coure: '. $mycoursesql .'</p>';

    //form
    $content .= '<form name="transferCreditSearchForm" method="get" action="">';

    //hidden fields
    if ($mymode == 'groups') {
        $content .= '<input type="hidden" name="mode" value="groups" />';
    }

    //table
    $content .= '<table>';

    //state select
    $content .= '      <tr>';
    $content .= '          <th><label for="state">State:</label></th>';
    $content .= '          <td>';
    $content .= '              <select size="1" name="state" id="state" onchange="javascript:fnDisableFields();document.transferCreditSearchForm.submit();">';
    $content .= '                  <option value="">-</option>';

    //pull states from db and loop
    if ($states = $wpdb->get_results($mystatesql)) {
        foreach($states as $state){
            //output option tag, taking into account whether this is the currently selected option
            $content .= ($mystate == $state->SZBTATC_SBGI_STAT_CODE ? '<option selected>' : '<option>') . $state->SZBTATC_SBGI_STAT_CODE . '</option>';
        }
    }

    $content .= '              </select>';
    $content .= '          </td>';
    $content .= '      </tr>';

    //school select
    $content .= '      <tr>';
    $content .= '          <th><label for="school">School:</label></th>';
    $content .= '          <td>';
    $content .= '              <select size="1" name="school" id="school" onchange="javascript:fnDisableFields();document.transferCreditSearchForm.submit();">';
    $content .= '                  <option value="">-</option>';

    if ($mystateexist) {

        //pull schools from db and loop
        if ($schools = $wpdb->get_results($myschoolsql)) {
            foreach($schools as $school){
                //output option tag, taking into account whether this is the currently selected option
                $content .= ($myschool == $school->SHRTATC_SBGI_CODE ? '<option selected ' : '<option ') . 'value="' . $school->SHRTATC_SBGI_CODE . '">' . $school->SZBTATC_SBGI_DESC . '</option>';
            }
        }

    }

    $content .= '              </select>&nbsp;';
    $content .= '          </td>';
    $content .= '      </tr>';

    //course select
    $content .= '      <tr>';
    $content .= '          <th><label for="course">Course:</label></th>';
    $content .= '          <td>';
    $content .= '              <select size="1" name="course" id="course" onchange="javascript:fnDisableFields();document.transferCreditSearchForm.submit();">';
    $content .= '                  <option value="">-</option>';

    if ($mystateexist && $myschoolexist) {

        //view all option
        if ($mycourse != 'View All') {
            $content .= '<option>';
        } else {
            $content .= '<option selected>';
        }
        $content .= 'View All</option>';

        //pull courses from db and loop
        if ($courses = $wpdb->get_results($mycoursesql)) {

            foreach($courses as $course){
                //output option tag, taking into account whether this is the currently selected option
                $content .= ($mycourse == $course->SHRTATC_SUBJ_TRNS ? '<option selected ' : '<option ') . 'value="' . $course->SHRTATC_SUBJ_TRNS . '">' . $course->SHRTATC_TRNS_TITLE . '</option>';
            }
        }

    }

    $content .= '              </select>&nbsp;';
    $content .= '          </td>';
    $content .= '      </tr>';
    $content .= '  <tr><th colspan="2" style="text-align:center;""><a href="/">CLEAR FORM</a></th></tr>';
    $content .= '  </table>';
    $content .= '</form>';

    //return value
    return $content;

}

// shortcode for matrix search results
function gmuw_trmatrixgmu_display_matrix_search_results(){

    // Determine return value
    $content='<p>[matrix search results]</p>';

    // Return value
    return $content;

}
