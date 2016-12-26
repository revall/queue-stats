<?php
/*
   This file is part of Asternic Call Center Stats.

    Asternic Call Center Stats is free software: you can redistribute it 
    and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 3 of the 
    License, or (at your option) any later version.

    Asternic Call Center Stats is distributed in the hope that it will be 
    useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Asternic Call Center Stats.  If not, see 
    <http://www.gnu.org/licenses/>.
*/
// French Translation
// Sylvain Boily
// sboily@proformatique.com

$dayp[0] = "Dimanche";
$dayp[1] = "Lundi";
$dayp[2] = "Mardi";
$dayp[3] = "Mercredi";
$dayp[4] = "Jeudi";
$dayp[5] = "Vendredi";
$dayp[6] = "Samedi";

$yearp[0] = "Janvier";
$yearp[1] = "F&eacute;vrier";
$yearp[2] = "Mars";
$yearp[3] = "Avril";
$yearp[4] = "Mai";
$yearp[5] = "Juin";
$yearp[6] = "Juillet";
$yearp[7] = "Aôut";
$yearp[8] = "Septembre";
$yearp[9] = "Octobre";
$yearp[10]= "Novembre";
$yearp[11]= "D&eacute;cembre";

// Menu options
$lang['fr']['menu_home']         = "Accueil";
$lang['fr']['menu_answered']     = "R&eacute;pondu";
$lang['fr']['menu_unanswered']   = "Non r&eacute;pondu";
$lang['fr']['menu_distribution'] = "R&eacute;partition";
$lang['fr']['menu_realtime']     = "Temps r&eacute;el";

// tooltips
$lang['fr']['pdfhelp'] = "Exporter les donn&eacute;es dans un fichier .pdf";
$lang['fr']['csvhelp'] = "Exporter les donn&eacute;es dans un fichier avec un s&eacute;parateur afin de les lire dans un tableur";
$lang['fr']['gotop']   = "Allez au d&eacute;but de la page";

// Index page
$lang['fr']['ALL']               = "TOUS";
$lang['fr']['lower']             = "Plus petit  ...";
$lang['fr']['higher']            = "Plus grand ...";
$lang['fr']['select_queue']      = "S&eacute;l&eacute;ctionner une file";
$lang['fr']['select_agent']      = "S&eacute;l&eacute;ctionner un agent";
$lang['fr']['select_timeframe']  = "S&eacute;l&eacute;ctionner un interval de temps";
$lang['fr']['queue']             = "File";
$lang['fr']['start']             = "Date de d&eacute;but";
$lang['fr']['end']               = "Date de fin";
$lang['fr']['display_report']    = "Voir le rapport";
$lang['fr']['shortcuts']         = "Raccourci";
$lang['fr']['today']             = "Aujourd'hui";
$lang['fr']['this_week']         = "Cette semaine";
$lang['fr']['this_month']        = "Ce mois";
$lang['fr']['last_three_months'] = "Derniers 3 mois";
$lang['fr']['available']         = "Disponible";
$lang['fr']['selected']          = "Selectionn&eacute;";
$lang['fr']['invaliddate']       = "Interval des dates invalides";

// Answered page
$lang['fr']['answered_calls_by_agent'] = "Appel r&eacute;pondu par agent";
$lang['fr']['answered_calls_by_queue'] = "Appel r&eacute;pondu par file d'attente";
$lang['fr']['anws_unanws_by_hour']     = "R&eacute;pondu/non-r&eacute;pondu par heure";
$lang['fr']['report_info']       = "Information des rapports";
$lang['fr']['period']            = "P&eacute;riode";
$lang['fr']['answered_calls']    = "Appel r&eacute;pondu";
$lang['fr']['transferred_calls'] = "Appel transf&eacute;rer";
$lang['fr']['secs']              = "secs";
$lang['fr']['minutes']           = "min";
$lang['fr']['hours']             = "hs";
$lang['fr']['calls']             = "appels";
$lang['fr']['Calls']             = "Appels";
$lang['fr']['agent']             = "Agent";
$lang['fr']['avg']               = "Moy.";
$lang['fr']['avg_calltime']      = "Moy. dur&eacute;e";
$lang['fr']['avg_holdtime']      = "Moy. attente";
$lang['fr']['percent']           = "%";
$lang['fr']['total']             = "Total";
$lang['fr']['calltime']          = "Temps d'appel";
$lang['fr']['holdtime']          = "Temps d'attente";
$lang['fr']['total_time_agent']  = "Temps total par agent (secs)";
$lang['fr']['no_calls_agent']    = "Nombre d\'appels par agent";
$lang['fr']['call_response']     = "Niveau de service";
$lang['fr']['within']            = "Avant ";
$lang['fr']['answer']            = "R&eacute;pondre";
$lang['fr']['count']             = "Compteur";
$lang['fr']['delta']             = "Diff&eacute;rence";
$lang['fr']['disconnect_cause']  = "Raison de d&eacute;connexion";
$lang['fr']['cause']             = "Raison";
$lang['fr']['agent_hungup']      = "Agent raccroch&eacute;";
$lang['fr']['caller_hungup']     = "Appelant raccroch&eacute;";
$lang['fr']['caller']            = "Appelant";
$lang['fr']['transfers']         = "Transfert";
$lang['fr']['to']                = "Pour";

// Unanswered page
$lang['fr']['unanswered_calls']    = "Appel non r&eacute;pondu";
$lang['fr']['number_unanswered']   = "Nombre d'appels non r&eacute;pondu";
$lang['fr']['avg_wait_before_dis'] = "Moy. du temps d'attente avant le raccroch&eacute;";
$lang['fr']['avg_queue_pos_at_dis']= "Moy. de la position dans la file lors de la d&eacute;connexion";
$lang['fr']['avg_queue_start']     = "Moy. lors de l'arriv&eacute; dans la file";
$lang['fr']['user_abandon']        = "Abandon des utilisateurs";
$lang['fr']['abandon']             = "Abandon";
$lang['fr']['timeout']             = "D&eacute;lai d&eacute;pass&eacute;";
$lang['fr']['unanswered_calls_qu'] = "Appel non-r&eacute;pondu par file";

// Distribution
$lang['fr']['totals']              = "Totals";
$lang['fr']['number_answered']     = "Nombre d'appel r&eacute;pondu";
$lang['fr']['number_unanswered']   = "Nombre d'appel non r&eacute;pondu";
$lang['fr']['agent_login']         = "Agent loggu&eacute;";
$lang['fr']['agent_logoff']        = "Agent d&eacute;loggu&eacute;";
$lang['fr']['call_distrib_day']    = "R&eacute;partition d'appels par jour";
$lang['fr']['call_distrib_hour']   = "R&eacute;partition d'appel par heure";
$lang['fr']['call_distrib_week']   = "R&eacute;partition d'appel par jour de semaine";
$lang['fr']['date']                = "Date";
$lang['fr']['day']                 = "Jour";
$lang['fr']['days']                = "jours";
$lang['fr']['hour']                = "Heure";
$lang['fr']['answered']            = "R&eacute;pondu";
$lang['fr']['unanswered']          = "Non r&eacute;pondu";
$lang['fr']['percent_answered']    = "% r&eacute;pondu";
$lang['fr']['percent_unanswered']  = "% non r&eacute;pondu";
$lang['fr']['login']               = "Loggu&eacute;";
$lang['fr']['logoff']              = "D&eacute;loggu&eacute;";
$lang['fr']['answ_by_day']         = "Appels r&eacute;pondu par jour de la semaine";
$lang['fr']['unansw_by_day']       = "Appels non r&eacute;pondu par jour de la semaine";
$lang['fr']['avg_call_time_by_day']= "Moyenne du temps d\'appels pour jour de la semaine";
$lang['fr']['avg_hold_time_by_day']= "Moyenne du temps d\'attente par jour de la semaine";
$lang['fr']['answ_by_hour']        = "Appels r&eacute;pondu par heure";
$lang['fr']['unansw_by_hour']      = "Appel non r&eacute;pondu par heure";
$lang['fr']['avg_call_time_by_hr'] = "Moyenne du temps d\'appel par heure";
$lang['fr']['avg_hold_time_by_hr'] = "Moyenne du temps d\'attente par heure";
$lang['fr']['page']                = "Page";
$lang['fr']['export']              = "Exporter la table:";

// Realtime
$lang['fr']['current_agent_status'] = "Gestion des agents";
$lang['fr']['agent_status']         = "Status des agents";
$lang['fr']['hide_loggedoff']       = "Cacher les agents non loggu&eacute;";
$lang['fr']['calls_waiting_detail'] = "D&eacute;tail des appels dans la file d'attente";
$lang['fr']['position']             = "Position";
$lang['fr']['callerid']             = "Nom appelant";
$lang['fr']['wait_time']            = "Temps attente";
$lang['fr']['state']                = "&Eacute;tat";
$lang['fr']['staffed']              = "En ligne";
$lang['fr']['talking']              = "En communication";
$lang['fr']['paused']               = "En pause";
$lang['fr']['durat']                = "Dur&eacute;e";
$lang['fr']['clid']                 = "CLID";
$lang['fr']['last_in_call']         = "Derni&egrave;re fois en appel";
$lang['fr']['queue_summary']        = "Liste des files d'attente";
$lang['fr']['calls_waiting']        = "Appel en attente";
$lang['fr']['oldest_call_waiting']  = "Ancien appel en attente";
$lang['fr']['seconds']              = "seconds";
$lang['fr']['not_in_use']           = "au repos";
$lang['fr']['busy']                 = "occup&eacute;e.";
$lang['fr']['unavailable']          = "indisponible";
$lang['fr']['php_parsed']           = "PHP analys&eacute; cette page";
$lang['fr']['unknown']              = "inconnu";
$lang['fr']['dialout']              = "dialout";
$lang['fr']['no_info']              = "s/i";
$lang['fr']['min_ago']              = "min. ago";

?>
