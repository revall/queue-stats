<?php
/*
   Copyright 2007, 2008 Nicolás Gudiño

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

$dayp[0] = "Domingo";
$dayp[1] = "Lunes";
$dayp[2] = "Martes";
$dayp[3] = "Miercoles";
$dayp[4] = "Jueves";
$dayp[5] = "Viernes";
$dayp[6] = "Sabado";

$yearp[0] = "Enero";
$yearp[1] = "Febrero";
$yearp[2] = "Marzo";
$yearp[3] = "Abril";
$yearp[4] = "Mayo";
$yearp[5] = "Junio";
$yearp[6] = "Julio";
$yearp[7] = "Agosto";
$yearp[8] = "Septiembre";
$yearp[9] = "Octubre";
$yearp[10]= "Noviembre";
$yearp[11]= "Diciembre";

$lang['es']['menu_home']         = "Inicio";
$lang['es']['menu_answered']     = "Atendidas";
$lang['es']['menu_unanswered']   = "Sin atender";
$lang['es']['menu_distribution'] = "Distribuci&oacute;n";
$lang['es']['ALL']               = "TODAS";
$lang['es']['lower']             = "Menor  ...";
$lang['es']['higher']            = "Mayor ...";
$lang['es']['select_queue']      = "Elija Cola";
$lang['es']['select_agent']      = "Elija Agentes";
$lang['es']['select_timeframe']  = "Elija Intervalo de Tiempo";
$lang['es']['queue']   	         = "Cola";
$lang['es']['start']   	         = "Inicio";
$lang['es']['end']   	         = "Final";
$lang['es']['display_report']    = "Mostrar Reporte";
$lang['es']['shortcuts']         = "Atajos";
$lang['es']['today']             = "Hoy";
$lang['es']['this_week']         = "Esta semana";
$lang['es']['this_month']        = "Este mes";
$lang['es']['last_three_months'] = "Ultimos 3 meses";
$lang['es']['available']         = "Disponibles";
$lang['es']['selected']          = "Seleccionados";
$lang['es']['invaliddate']       = "Rango de fecha invalido";

// tooltips
$lang['es']['pdfhelp'] = "Exporta los datos a un archivo .pdf";
$lang['es']['csvhelp'] = "Exporta los datos a un archivo separado por comas, para ser le&iacute;do con su planilla de c&aacute;lculo";
$lang['es']['gotop']   = "Ir al comienzo de la pagina";

// Answered page
$lang['es']['answered_calls_by_agent'] = "Llamadas Atendidas por Agente";
$lang['es']['answered_calls_by_queue'] = "Llamadas Atendidas por Cola";
$lang['es']['anws_unanws_by_hour']     = "Atendidas/Desatendidas por Hora";
$lang['es']['report_info']       = "Detalles del Reporte";
$lang['es']['period']            = "Periodo";
$lang['es']['answered_calls']    = "Llamadas Atendidas";
$lang['es']['transferred_calls'] = "Llamadas Transferidas";
$lang['es']['secs']              = "segs";
$lang['es']['minutes']           = "min";
$lang['es']['hours']             = "hs";
$lang['es']['calls']             = "llamadas";
$lang['es']['Calls']             = "Llamadas";
$lang['es']['agent']             = "Agente";
$lang['es']['avg']               = "Promedio";
$lang['es']['avg_calltime']      = "Duracion media";
$lang['es']['avg_holdtime']      = "Espera media";
$lang['es']['percent']           = "%";
$lang['es']['total']             = "Total";
$lang['es']['calltime']          = "Dur. Llamada";
$lang['es']['holdtime']          = "Dur. Espera";
$lang['es']['total_time_agent']  = "Tiempo Total por Agente (segundos)";
$lang['es']['no_calls_agent']    = "Cantidad de Llamadas por Agente";
$lang['es']['call_response']     = "Nivel de Servicio";
$lang['es']['within']            = "Dentro de ";
$lang['es']['answer']            = "Atendida";
$lang['es']['count']             = "Nro";
$lang['es']['delta']             = "Delta";
$lang['es']['disconnect_cause']  = "Causa de Desconexion";
$lang['es']['cause']             = "Causa";
$lang['es']['agent_hungup']      = "Corto el agente";
$lang['es']['caller_hungup']     = "Corto el usuario";
$lang['es']['caller']            = "Usuario";
$lang['es']['transfers']         = "Transferencias";
$lang['es']['to']                = "Hacia";

// Unanswered page
$lang['es']['unanswered_calls']    = "Llamadas sin Atender";
$lang['es']['number_unanswered']   = "Nro de llamadas sin atender";
$lang['es']['avg_wait_before_dis'] = "Promedio de espera antes de desconectar";
$lang['es']['avg_queue_pos_at_dis']= "Posicion promedio en cola al desconectar";
$lang['es']['avg_queue_start']     = "Posicion inicial promedio en cola";
$lang['es']['user_abandon']        = "Abandonada por Usuario";
$lang['es']['abandon']             = "Abandonada";
$lang['es']['timeout']             = "Expirado";
$lang['es']['unanswered_calls_qu'] = "Llamadas sin Atender por Cola";

// Distribution
$lang['es']['totals']              = "Totales";
$lang['es']['number_answered']     = "Numero de llamadas atendidas";
$lang['es']['number_unanswered']   = "Numero de llamadas sin atender";
$lang['es']['agent_login']         = "Ingresos de Agentes";
$lang['es']['agent_logoff']        = "Egresos de Agentes";
$lang['es']['call_distrib_day']    = "Distribucion de Llamados por dia";
$lang['es']['call_distrib_hour']   = "Distribucion de Llamados por hora";
$lang['es']['call_distrib_week']   = "Distribucion de Llamados por dia de semana";
$lang['es']['date']                = "Fecha";
$lang['es']['day']                 = "Dia";
$lang['es']['days']                = "dias";
$lang['es']['hour']                = "Hora";
$lang['es']['answered']            = "Atendidas";
$lang['es']['unanswered']          = "Sin atender";
$lang['es']['percent_answered']    = "% Atend";
$lang['es']['percent_unanswered']  = "% Desat";
$lang['es']['login']               = "Ingresos";
$lang['es']['logoff']              = "Egresos";
$lang['es']['answ_by_day']         = "Llamadas Atendidas por dia de semana";
$lang['es']['unansw_by_day']       = "Llamadas sin Atender por dia de semana";
$lang['es']['avg_call_time_by_day']= "Duracion promedio de llamadas por dia de semana";
$lang['es']['avg_hold_time_by_day']= "Duracion promedio de espera por dia de semana";
$lang['es']['answ_by_hour']        = "Llamadas Atendidas por hora";
$lang['es']['unansw_by_hour']      = "Llamadas sin Atender por hora";
$lang['es']['avg_call_time_by_hr'] = "Duracion promedio de llamadas por hora";
$lang['es']['avg_hold_time_by_hr'] = "Duracion promedio de espera por hora";
$lang['es']['page']                = "Pagina";
$lang['es']['export']              = "Exportar tabla:";

$lang['es']['server_time']         = "Hora en el Servidor:";
$lang['es']['php_parsed']          = "PHP genero la pagina en ";
$lang['es']['seconds']             = "segundos";
$lang['es']['current_agent_status'] = "Panorama Actual";
$lang['es']['hide_loggedoff']       = "Ocultar agentes deslogeados";
$lang['es']['agent_status']         = "Estado de Agentes";
$lang['es']['state']                = "Estado";
$lang['es']['durat']                = "Durac.";
$lang['es']['clid']                 = "CLID";
$lang['es']['last_in_call']         = "Ultimo llamado";
$lang['es']['not_in_use']           = "Libre";
$lang['es']['paused']               = "Pausa";
$lang['es']['busy']                 = "Ocupado";
$lang['es']['unavailable']          = "No disponible";
$lang['es']['unknown']              = "Desconocido";
$lang['es']['dialout']              = "Llamada Saliente";
$lang['es']['no_info']              = "no hay datos";
$lang['es']['min_ago']              = "min.";
$lang['es']['queue_summary']        = "Resumen de Colas";
$lang['es']['staffed']              = "Disponibles";
$lang['es']['talking']              = "Hablando";
$lang['es']['paused']               = "En Pausa";
$lang['es']['calls_waiting']        = "Llamadas en espera";
$lang['es']['oldest_call_waiting']  = "Llamada mas antigua en espera";
$lang['es']['calls_waiting_detail'] = "Detalle de Llamados en Espera";
$lang['es']['position']             = "Posicion";
$lang['es']['callerid']             = "Callerid";
$lang['es']['wait_time']            = "Espera";
?>
