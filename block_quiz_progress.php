<?php

global $PAGE;

class block_quiz_progress extends block_base {

    function init() {
        global $CFG, $PAGE;

        $this->title = get_string('blockname', 'block_quiz_progress');
    }

    function applicable_formats() {
        return array('course' => true, 'mod-quiz' => true);
    }

    function has_config() {
        return true;
    }

    function get_content() {
        global $USER, $CFG, $COURSE, $DB;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }

        if (!isset($this->config)) {
            $this->config = new StdClass();
        }

        // preconfigures the quiz type to default standard quiz
        if (empty($this->config->quiztype)) {
            $this->config->quiztype = $DB->get_field('modules', 'id', array('name' => 'quiz'));
            $this->instance_config_commit();
        }

        if (empty($this->config->graphtype)) {
            $this->config->graphtype = 'bar';
            $this->instance_config_commit();
        }

        if (empty($this->config->width)) {
            $this->config->width = 550;
            $this->instance_config_commit();
        }

        if (empty($this->config->height)){
            $this->config->height = 350;
            $this->instance_config_commit();
        }

        $quiztypename = $DB->get_field('modules', 'name', array('id' => $this->config->quiztype));

        // Preconfigures the block if possible.
        if (empty($this->config->quizid)) {
            // How many instances do we have ?
            $qinstances = $DB->count_records($quiztypename, array('course' => $COURSE->id));
            if ($qinstances == 0){
                $this->content->text .= '<span class="error">'.get_string('errornoquiz', 'block_quiz_progress'). '</span>';
                return $this->content;
            } elseif ($qinstances == 1) {
                $this->config->quizid = $DB->get_field($quiztypename, 'id', array('course' => $COURSE->id));
                $this->instance_config_commit();
            } else {
                $this->content->text .= '<span class="error">'.get_string('errornoquizselected', 'block_quiz_progress'). '</span>';
                return $this->content;
            }
        }

        $context = context_block::instance($this->instance->id);
        $cm = $DB->get_record('course_modules', array('module' => $this->config->quiztype, 'instance' => $this->config->quizid));
        if (!$cm) {
            $this->content->text = get_string('errornonexistantcoursemodule', 'block_quiz_progress');
            return $this->content;
        }
        $modcontext = context_module::instance($cm->id);

        // Get the quiz record.
        $quiz = $DB->get_record($quiztypename, array('id' => $this->config->quizid));
        if (empty($quiz)) {
            $this->content->text = get_string('erroremptyquizrecord', 'block_quiz_progress');
            return $this->content;
        }

        if (empty($quiz->sumgrades)) {
            $this->content->text = get_string('errornoquestions', 'block_quiz_progress');
            return $this->content;
        }

        // Get the grades for this quiz.
        $attemptgrades = $DB->get_records_select($quiztypename.'_attempts', " {$quiztypename} = ? AND timefinish != 0 AND userid = ? ", array($this->config->quizid, $USER->id), 'timefinish ASC', 'id, sumgrades, timefinish');

        if (empty($attemptgrades)) {
            // No grades, sorry.
            // The block will hide itself in this case.
            $this->content->text .= "<span class=\"notify\">".get_string('noresultsyet', 'block_quiz_progress')."</span>";
            return $this->content;
        }

        $data = array();
        foreach ($attemptgrades as $attemptgrade) {
            // Take grades ranged to 100.
            $data[$attemptgrade->timefinish] = $attemptgrade->sumgrades / $quiz->sumgrades * 100;
        }

        $dataset = array();
        if (!empty($data)){
            $dataset[0] = array_keys($data);
            $dataset[1] = array_values($data);
        }

        $this->content->text .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$CFG->wwwroot}/blocks/quiz_progress/js/jqplot/jquery.jqplot.css\" />";
        $this->content->text .= "
        <style type=\"text/css\" media=\"screen\">
        .jqplot-axis {
            font-size: 0.85em;
        }
        .jqplot-point-label {
            border: 1.5px solid #aaaaaa;
            padding: 1px 3px;
            background-color: #eeccdd;
        }
        </style>";

        if ($this->config->graphtype == 'time') {
            $this->jqplot_print_timecurve_graph($this->content, $dataset, get_string('quizprogress', 'block_quiz_progress'), 'quizprogress_'.$this->config->quizid, $this->config->width, $this->config->height);
        } else {
            $this->jqplot_print_bargraph($this->content, $dataset, get_string('quizprogress', 'block_quiz_progress'), 'quizprogress_'.$this->config->quizid, $this->config->width, $this->config->height);
        }

        return $this->content;
    }

    function instance_allow_multiple() {
        return true;
    }

    function print_jqplot_rawline(&$data, $varname, $return = false, $xfactor = 1) {
        $str = "$varname = ";

        foreach ($data as $x => $y) {
            $points[] = "[".($x*$xfactor).",$y]";
        }
        $str .= '['.implode(',', $points).']';
        $str .= ';';
        if (!$return) {
            echo $str;
        }
        return $str;
    }

    function print_jqplot_barline(&$data, $varname, $return = false) {

        $str = "$varname = ";

        $i = 1;
        foreach ($data as $datum) {
            $points[] = '['.$i.','.$datum.']';
            $i++;
        }
        $str .= '['.implode(',', $points).']';
        $str .= ';';
        if (!$return) {
            echo $str;
        }
        return $str;
    }

    function jqplot_print_timecurve_graph(&$content, &$data, $title, $htmlid, $plotwidth = 550, $plotheight = 350) {
        global $PLOTID;
        static $instance = 0;

        $htmlid = $htmlid.'_'.$instance;
        $instance++;

        $content->text .= "<div id=\"$htmlid\" style=\"margin-top:20px; margin-left:20px; width:{$plotwidth}px; height:{$plotheigth}px;\"></div>";
        $content->text .= "<script type=\"text/javascript\">\n";

        $title = addslashes($title);

        // make curves from each x, yi pair and print them to Javscript
        $xserie = $data[0];
        $varset = array();
        for ($i = 1 ; $i < count($data) ; $i++) {
            $yserie = $data[$i];
            $curvedata = array_combine($xserie, $yserie);
            $content->text .= $this->print_jqplot_rawline($curvedata, 'data'.$PLOTID.'_'.$i, true, 1000);
            $content->text .="\n";
            $varset[] = 'data'.$i;
        }
        $varsetlist = implode(',', $varset);

        $content->text .= "
            $.jqplot.config.enablePlugins = true;

            yticks = [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];

            plot{$PLOTID}_{$instance} = $.jqplot(
                '{$htmlid}', 
                [data{$PLOTID}_1], 
                {
                    title: '{$title}',
                    axes: {
                        xaxis: {
                            renderer: $.jqplot.DateAxisRenderer,
                            numberTicks: 7
                       },
                        yaxis: {
                        ticks:yticks
                        }
                },
                series:[
                    {lineWidth:3, color:'#0000E0', showMarker:true}]
                }
            );
        ";
        $content->text .= "</script>";

        $PLOTID++;
    }

    function jqplot_print_bargraph(&$content, &$data, $title, $htmlid, $plotwidth = 550, $plotheigth = 350) {
        global $PLOTID;
        static $instance = 0;

        $htmlid = $htmlid.'_'.$instance;
        $instance++;

        $content->text .= "<center><div id=\"$htmlid\" style=\"margin-top:20px; margin-left:20px; width:{$plotwidth}px; height:{$plotheigth}px;\"></div></center>";
        $content->text .= "<script type=\"text/javascript\">\n";

        $curvedata = $data[1];
        $content->text .= $this->print_jqplot_barline($curvedata, 'data'.$PLOTID.'_1', true);

        $labels = array();
        foreach ($data[0] as $datum) {
            $labels[] = userdate($datum);
        }
        $labellist = "'".implode("','", $labels)."'";

        $title = addslashes($title);

        $content->text .= "
            $.jqplot.config.enablePlugins = true;

            yticks = [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];

            plot{$PLOTID}_{$instance} = $.jqplot(
                '{$htmlid}', 
                [data{$PLOTID}_1], 
                {
                    title:'{$title}',    
                    seriesDefaults: {renderer: $.jqplot.BarRenderer},
                    series:[
                        {pointLabels:{labels:[$labellist]}}
                    ],
                    axes:{
                        xaxis:{renderer:$.jqplot.CategoryAxisRenderer},
                        yaxis:{ticks:yticks,padMax:1.3}
                    }
                }
            );
        ";
        $content->text .= "</script>";

         $PLOTID++;
    }

    static function check_jquery() {
        global $PAGE, $OUTPUT, $CFG;

        if ($CFG->version >= 2013051400) return; // Moodle 2.5 natively loads JQuery

        $current = '1.9.1';

        if (empty($OUTPUT->jqueryversion)) {
            $OUTPUT->jqueryversion = '1.9.1';
            $PAGE->requires->js('/blocks/quiz_progress/js/jqplot/jquery-'.$current.'.min.js', true);
        } else {
            if ($OUTPUT->jqueryversion < $current) {
                debugging('the previously loaded version of jquery is lower than required. This may cause issues to quiz_progress. Programmers might consider upgrading JQuery version in the component that preloads JQuery library.', DEBUG_DEVELOPER, array('notrace'));
            }
        }
    }

    static function check_jqplot() {
        global $JQPLOT, $PAGE;

        if (empty($JQPLOT)) {
            $JQPLOT = array();
        }

        if (!in_array('jquery', $JQPLOT)) {
            $PAGE->requires->js('/blocks/quiz_progress//js/jqplot/jquery.jqplot.js', true);
            $JQPLOT[] = 'jquery';
        }

        if (!in_array('excanvas', $JQPLOT)) {
            $PAGE->requires->js('/blocks/quiz_progress//js/jqplot/excanvas.js', true);
            $JQPLOT[] = 'excanvas';
        }

        if (!in_array('dateAxisRenderer', $JQPLOT)) {
            $PAGE->requires->js('/blocks/quiz_progress//js/jqplot/plugins/jqplot.dateAxisRenderer.js', true);
            $JQPLOT[] = 'dateAxisRenderer';
        }

        if (!in_array('barRenderer', $JQPLOT)) {
            $PAGE->requires->js('/blocks/quiz_progress//js/jqplot/plugins/jqplot.barRenderer.min.js', true);
            $JQPLOT[] = 'barRenderer';
        }

        if (!in_array('categoryAxisRenderer', $JQPLOT)) {
            $PAGE->requires->js('/blocks/quiz_progress//js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js', true);
            $JQPLOT[] = 'categoryAxisRenderer';
        }
    }
}

block_quiz_progress::check_jquery();
block_quiz_progress::check_jqplot();
