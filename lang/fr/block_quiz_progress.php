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
$string['quiz_progress:addinstance'] = 'Peut ajouter une instance';

// Privacy.
$string['privacy:metadata'] = 'Le bloc Progression de Quiz ne détient directement aucune donnée relative aux utilisateurs.';

$string['bar'] = 'Bargraph';
$string['blockname'] = 'Progression de test';
$string['pluginname'] = 'Progression de test';
$string['configgraphsize'] = 'Dimensions';
$string['configgraphtype'] = 'Type de graphe';
$string['configquiztype'] = 'Type de test';
$string['blockquizprogressmodules'] = 'Modules de test installés';
$string['configblockquizprogressmodules'] = 'Indiquez les noms de modules de Moodle qui sont des tests. (Liste à virgule).';
$string['configselectquiz'] = 'Instance de test';
$string['erroremptyquizrecord'] = 'Ce module ne semble pas exister.';
$string['errornojqplot'] = 'JQPlot n\'est pas installé dans ce Moodle. Contactez l\'administrateur.';
$string['errornoquiz'] = 'Aucun module de test dans ce cours';
$string['errornoquizselected'] = 'Pas de module test à tracer. Choisissez en un.';
$string['errornoquestions'] = 'Le test ne semble avoir aucune question.';
$string['errornonexistantcoursemodule'] = 'Le module choisi n\'existe probablement plus.';
$string['height'] = 'Hauteur';
$string['noresultsyet'] = 'Vous n\'avez pas de résultats dans ce test. Aucun rapport ne peut être affiché.';
$string['quizprogress'] = 'Vos progrès';
$string['time'] = 'Courbe de progression datée';
$string['width'] = 'Largeur';
