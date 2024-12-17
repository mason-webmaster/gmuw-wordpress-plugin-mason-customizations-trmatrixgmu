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
    //if (!empty($mycourse)) { $mycourseexist = true; }
    $mystateexist = !empty($mystate);
    $myschoolexist = !empty($myschool);
    $mycourseexist = !empty($mycourse);

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
        $mystatesql = "SELECT DISTINCT SZBTATC_SBGI_STAT_CODE FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE IS NOT NULL AND SZBTATC_SBGI_STAT_CODE<>'' AND SZBTATC_SBGI_STAT_CODE NOT IN ('ab','bc','mb','nb','nl','ns','on','pe','qc','sk','yt') AND SZBTATC_SBGI_STAT_CODE NOT IN ('cz') ORDER BY SZBTATC_SBGI_STAT_CODE;";
        $myschoolsql = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_DESC,SZBTATC_SBGI_STAT_CODE FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE<>'ZZZ000' ORDER BY SZBTATC_SBGI_DESC;";
        $mycoursesql = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_STAT_CODE, SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE  FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE = '" . $myschool . "' AND SZBTATC_SUBJ_INST NOT LIKE '%ADEC%' ORDER BY SHRTATC_SUBJ_TRNS;";

    //output
    //$content.='<p>State Exist: '. $mystateexist .'</p>';
    //$content.='<p>School Exist: '. $myschoolexist .'</p>';
    //$content.='<p>Course Exist: '. $mycourseexist .'</p>';
    //$content.='<p>Mode: '. $mymode .'</p>';
    //$content.='<p>State: '. $mystate .'</p>';
    //$content.='<p>School: '. $myschool .'</p>';
    //$content.='<p>Course: '. $mycourse .'</p>';
    //$content.='<p>Dept Code: '. $mydeptcode .'</p>';
    //$content.='<p>SQL State: '. $mystatesql .'</p>';
    //$content.='<p>SQL School: '. $myschoolsql .'</p>';
    //$content.='<p>SQL Coure: '. $mycoursesql .'</p>';

    //form
    $content .= '<form name="transferCreditSearchForm" method="get" action="#transfer_credit_search_form">';

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
                $content .= ($mycourse == $course->SHRTATC_SUBJ_TRNS ? '<option selected ' : '<option ') . 'value="' . $course->SHRTATC_SUBJ_TRNS . '">' . $course->SHRTATC_SUBJ_TRNS . ' - ' . $course->SHRTATC_TRNS_TITLE . '</option>';
            }
        }

    }

    $content .= '              </select>&nbsp;';
    $content .= '          </td>';
    $content .= '      </tr>';
    $content .= '  <tr><th colspan="2" style="text-align:center;""><a href="/#transfer_credit_search_form">CLEAR FORM</a></th></tr>';
    $content .= '  </table>';
    $content .= '</form>';

    //return value
    return $content;

}

// shortcode for matrix search results
function gmuw_trmatrixgmu_display_matrix_search_results(){

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
    //if (!empty($mycourse)) { $mycourseexist = true; }
    $mystateexist = !empty($mystate);
    $myschoolexist = !empty($myschool);
    $mycourseexist = !empty($mycourse);

    // set return value
    $content='';

    //Set SQL strings
    if (empty($mymode)) {
        $myoutputsql        = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_STAT_CODE, SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE, SZBTATC_TRNS_CREDITS, SZBTATC_SUBJ_INST, SZBTATC_INST_TITLE, SHRTATC_INST_CREDITS_USED FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE = '" . $myschool . "' AND SHRTATC_SUBJ_TRNS = '" . $mycourse . "' ORDER BY SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE;";
        $myoutputallsql     = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_STAT_CODE, SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE, SZBTATC_TRNS_CREDITS, SZBTATC_SUBJ_INST, SZBTATC_INST_TITLE, SHRTATC_INST_CREDITS_USED FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE = '" . $myschool . "' AND SZBTATC_SUBJ_INST NOT LIKE '%ADEC%' ORDER BY SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE;";
        $myoutputalldeptsql = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_STAT_CODE, SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE, SZBTATC_TRNS_CREDITS, SZBTATC_SUBJ_INST, SZBTATC_INST_TITLE, SHRTATC_INST_CREDITS_USED FROM " . $mytable . " WHERE SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE = '" . $myschool . "' AND SHRTATC_SUBJ_TRNS LIKE '" . $mydeptcode . "%' ORDER BY SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE;";
    } elseif ($mymode == 'groups') {
        $myoutputsql        = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_STAT_CODE, SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE, SZBTATC_TRNS_CREDITS, SZBTATC_SUBJ_INST, SZBTATC_INST_TITLE, SHRTATC_INST_CREDITS_USED, SHRTATC_GROUP FROM " . $mytable . " WHERE SHRTATC_GROUP<>'' AND SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE = '" . $myschool . "' AND SHRTATC_SUBJ_TRNS = '" . $mycourse . "' ORDER BY SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE;";
        $myoutputallsql     = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_STAT_CODE, SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE, SZBTATC_TRNS_CREDITS, SZBTATC_SUBJ_INST, SZBTATC_INST_TITLE, SHRTATC_INST_CREDITS_USED, SHRTATC_GROUP FROM " . $mytable . " WHERE SHRTATC_GROUP<>'' AND SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE = '" . $myschool . "' AND SZBTATC_SUBJ_INST NOT LIKE '%ADEC%' ORDER BY SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE;";
        $myoutputalldeptsql = "SELECT DISTINCT SHRTATC_SBGI_CODE, SZBTATC_SBGI_STAT_CODE, SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE, SZBTATC_TRNS_CREDITS, SZBTATC_SUBJ_INST, SZBTATC_INST_TITLE, SHRTATC_INST_CREDITS_USED, SHRTATC_GROUP FROM " . $mytable . " WHERE SHRTATC_GROUP<>'' AND SZBTATC_SBGI_STAT_CODE = '" . $mystate . "' AND SHRTATC_SBGI_CODE = '" . $myschool . "' AND SHRTATC_SUBJ_TRNS LIKE '" . $mydeptcode . "%' ORDER BY SHRTATC_SUBJ_TRNS, SHRTATC_TRNS_TITLE;";
    }

    if (($mystateexist) && ($myschoolexist) && ($mycourseexist)) {
        $content.="<table>";
        $content.="  <thead>";
        $content.="      <tr>";
        if ($mymode == "groups") {
            $content.="<th>&nbsp;</th>";
        }
        $content.="          <th colspan='6' style='text-align:center;'>";
        if (($mycourse != "View All") && ($mycourse != "") && ($mydeptcode == "")) { $content.="Course Equivalent"; }
        if ($mydeptcode != "") { $content.="All Transferrable Courses in Department " . $mydeptcode; }
        if ($mycourse == "View All") { $content.="All Transferrable Courses"; }
        $content.="          </th>";
        $content.="      </tr>";
        $content.="      <tr>";
        if ($mymode == "groups") {
            $content.="<th>&nbsp;</th>";
        }
        $content.="          <th colspan='3' style='text-align:center;'>Transferring Institution</th>";
        $content.="          <th colspan='3' style='text-align:center;'>GMU Equivalent</th>";
        $content.="      </tr>";
        $content.="      <tr>";
        if ($mymode == "groups") {
            $content.="<th>Banner Group</th>";
        }
        $content.="          <th>Course Number</th>";
        $content.="          <th>Course Name</th>";
        $content.="          <th>Credits</th>";
        $content.="          <th>Course Number</th>";
        $content.="          <th>Course Name</th>";
        $content.="          <th>Credits</th>";
        $content.="      </tr>";
        $content.="  </thead>";
    }

    //SINGLE COURSE
    if (($mycourse != "View All") && (!empty($mycourse)) && (empty($mydeptcode))) {

        //pull courses from db and loop
        if ($courses = $wpdb->get_results($myoutputsql)) {

            foreach($courses as $course){

                //get department
                $mydepartmentcode = substr($course->SHRTATC_SUBJ_TRNS,0,strpos($course->SHRTATC_SUBJ_TRNS," "));

                //output data
                $content.="<tr>";
                if ($mymode == "groups") { $content.="<td>" . $course->SHRTATC_GROUP . "</td>"; }
                $content.=" <td>" . $course->SHRTATC_SUBJ_TRNS . "</td><td>" . $course->SHRTATC_TRNS_TITLE . "</td><td>" . $course->SZBTATC_TRNS_CREDITS . "</td><td>" . $course->SZBTATC_SUBJ_INST . "</td><td>" . $course->SZBTATC_INST_TITLE . "</td><td style='text-align:center;'>" . $course->SHRTATC_INST_CREDITS_USED . "</td>";
                $content.="</tr>";

            }

            //Display department link
            if ($mydeptcode=="") {
                $content.="<tfoot>";
                $content.="    <tr>";
                $content.="        <th colspan='6' style='text-align:center;'>";
                $content.="<a href='?state=".$mystate."&school=".$myschool."&course=".$mycourse."&deptCode=".$mydepartmentcode."#transfer_credit_search_form'>View All Transferrable Courses in This Department</a>";
                $content.="        </th>";
                $content.="    </tr>";
                $content.="</tfoot>";
            }

        }

    }

    //DEPARTMENT
    if ($mydeptcode != "") {

        //pull courses from db and loop
        if ($courses = $wpdb->get_results($myoutputalldeptsql)) {

            foreach($courses as $course){
                //output data

                $content.="<tr>";
                if ($mymode == "groups") { $content.="<td>" . $course->SHRTATC_GROUP . "</td>"; }
                $content.=" <td>" . $course->SHRTATC_SUBJ_TRNS . "</td><td>" . $course->SHRTATC_TRNS_TITLE . "</td><td>" . $course->SZBTATC_TRNS_CREDITS . "</td><td>" . $course->SZBTATC_SUBJ_INST . "</td><td>" . $course->SZBTATC_INST_TITLE . "</td><td style='text-align:center;'>" . $course->SHRTATC_INST_CREDITS_USED . "</td>";
                $content.="</tr>";

            }
        }

    }

    //VIEW ALL
    if ($mycourse == "View All") {

        //pull courses from db and loop
        if ($courses = $wpdb->get_results($myoutputallsql)) {

            foreach($courses as $course){
                //output data

                $content.="<tr>";
                if ($mymode == "groups") { $content.="<td>" . $course->SHRTATC_GROUP . "</td>"; }
                $content.="  <td>" . $course->SHRTATC_SUBJ_TRNS . "</td><td>" . $course->SHRTATC_TRNS_TITLE . "</td><td>" . $course->SZBTATC_TRNS_CREDITS . "</td><td>" . $course->SZBTATC_SUBJ_INST . "</td><td>" . $course->SZBTATC_INST_TITLE . "</td><td style='text-align:center;'>" . $course->SHRTATC_INST_CREDITS_USED . "</td>";
                $content.="</tr>";

            }
        }

    }

    if (($mystateexist) && ($myschoolexist) && ($mycourseexist)) {
        $content.="</table>";
    }

    // Return value
    return $content;

}
