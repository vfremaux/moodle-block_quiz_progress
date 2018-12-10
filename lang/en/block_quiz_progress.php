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
 * @package   block_quiz_progress
 * @copyright 2015 Valery Fremaux
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Capabilities.
$string['quiz_progress:addinstance'] = 'Can add an instance';

// Privacy.
$string['privacy:metadata'] = 'The Group Quiz Progress block does not directly store any personal data about any user.';

$string['bar'] = 'Bargraph';
$string['blockname'] = 'Quiz progress';
$string['pluginname'] = 'Quiz progress';
$string['configgraphsize'] = 'Graph dimension';
$string['configgraphtype'] = 'Graph type';
$string['configquiztype'] = 'Quiz type';
$string['blockquizprogressmodules'] = 'Installed quiz modules';
$string['configblockquizprogressmodules'] = 'Choose the module types that are known being reportable quizzes in this Moodle. (Use a comma separated list).';
$string['configselectquiz'] = 'Quiz instance';
$string['erroremptyquizrecord'] = 'This quiz module seems not exisiting in database';
$string['errornojqplot'] = 'JQPlot is not installed in this Moodle. Please contact administrator.';
$string['errornoquiz'] = 'There is no quiz in this course this block might monitor progress on';
$string['errornoquizselected'] = 'No quiz to report on. Please select one.';
$string['errornoquestions'] = 'The quiz choosen seems having no evaluated questions.';
$string['errornonexistantcoursemodule'] = 'The configured course module is not existant or may have been deleted.';
$string['height'] = 'Graph height';
$string['noresultsyet'] = 'You have no results on this quiz. Nothing can be reported yet.';
$string['quizprogress'] = 'Your progress';
$string['time'] = 'Date based progress line';
$string['width'] = 'Graph width';
