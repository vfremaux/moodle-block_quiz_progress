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
 * Version details
 *
 * @package    block_quiz_progress
 * @category   blocks
 * @author Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2015032200;        // The current plugin version (Date: YYYYMMDDXX).
<<<<<<< HEAD
$plugin->requires  = 2018042700;        // Requires this Moodle version.
$plugin->component = 'block_quiz_progress'; // Full name of the plugin (used for diagnostics).
$plugin->release = '3.5.0 (Build 2015032200)';
=======
$plugin->requires  = 2018112800;        // Requires this Moodle version.
$plugin->component = 'block_quiz_progress'; // Full name of the plugin (used for diagnostics).
$plugin->release = '3.6.0 (Build 2015032200)';
>>>>>>> MOODLE_36_STABLE
$plugin->maturity = MATURITY_STABLE;
$plugin->dependencies = array('local_vflibs' => 2015101800);

// Non moodle attributes.
<<<<<<< HEAD
$plugin->codeincrement = '3.5.0000';
=======
$plugin->codeincrement = '3.6.0000';
>>>>>>> MOODLE_36_STABLE
