<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * minimalistic edit form
 *
 * @package   block_quiz_progress
 * @copyright 2013 Valery Fremaux / valery.fremaux@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');

class block_quiz_progress_edit_form extends block_edit_form {
    /**
     * @param MoodleQuickForm $mform
     */
    protected function specific_definition($mform) {
        global $CFG, $DB, $COURSE;

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

		$quiztypestr = get_string('configquiztype', 'block_quiz_progress');

    	if (empty($CFG->block_quiz_progress_modules)) {
            set_config('block_quiz_progress_modules', 'quiz');
        }
    	$quizmodules = explode(',', $CFG->block_quiz_progress_modules);
        $options = array();
        foreach ($quizmodules as $qmname) {
            if ($quizmodule = $DB->get_record('modules', array('name' => $qmname))) {
               $options["$quizmodule->id"] = get_string('modulename', $qmname);
            }
        }

        $mform->addElement('select', 'config_quiztype', $quiztypestr, $options);

		$config = unserialize(base64_decode(@$this->block->instance->configdata));
		
		if (!empty($config->quiztype)){

			$quizidstr = get_string('configselectquiz', 'block_quiz_progress');
	    	$quiztype = $DB->get_record('modules', array('id' => $config->quiztype));

	        $quizzes = $DB->get_records($quiztype->name, array('course' => $COURSE->id), '', 'id, name');
	        if(empty($quizzes)) {
	        	$mform->addElement('static', 'config_quizid_static', $quizidstr, get_string('config_no_quizzes_in_course', 'block_quiz_results'));
	        	$mform->addElement('hidden', 'config_quizid', 0);
	        	$mform->setType('config_quizid', PARAM_INT);
	        } else {
	            $options = array();
	            foreach($quizzes as $quiz) {
	            	$cmidnumber = $DB->get_field('course_modules', 'idnumber', array('module' => $quiztype->id, 'instance' => $quiz->id));
	                $options[$quiz->id] = (empty($cmidnumber)) ? $quiz->name : $quiz->name.' ('.$cmidnumber.')' ;
	            }
	        	$mform->addElement('select', 'config_quizid', $quizidstr, $options);
	        }
	    }

		$graphtypestr = get_string('configgraphsize', 'block_quiz_progress');
        $options = array('bar' => get_string('bar', 'block_quiz_progress'), 'time' => get_string('time', 'block_quiz_progress'));
    	$mform->addElement('select', 'config_graphtype', $graphtypestr, $options);

    	$mform->addElement('text', 'config_width', get_string('width', 'block_quiz_progress'), array('size' => '4'));
    	$mform->setType('config_width', PARAM_INT);

    	$mform->addElement('text', 'config_height', get_string('height', 'block_quiz_progress'), array('size' => '4'));
    	$mform->setType('config_height', PARAM_INT);

    }
}