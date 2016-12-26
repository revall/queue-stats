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

// AMI Class based on phpagi-asmanager.php by 
// Matthew Asham <matthewa@bcwireless.net>

class AsteriskManager
{
    var $socket = NULL;
    var $server;
    var $port;
    
    function AsteriskManager() { }
    
    function send_request($action, $parameters=array()) {
        $command = "Action: $action\r\n";
        foreach($parameters as $var=>$val) {
            $command .= "$var: $val\r\n";
        }
        $command .= "\r\n";
        fwrite($this->socket, $command);
        return $this->get_response(true);
    }
    
    function get_response($allow_timeout=false) {
        $timeout = false;
        do {
            $type = NULL;
            $parameters = array();
        
            if (feof($this->socket)) {
                return false;
            }
            $buffer = trim(fgets($this->socket, 4096));
            while($buffer != '') {
                $a = strpos($buffer, ':');
                if($a) {
                    if(!count($parameters)) { // first line in a response?
                        $type = strtolower(substr($buffer, 0, $a));
                        if(substr($buffer, $a + 2) == 'Follows') {
                            // A follows response means there is a multiline field that follows.
                            $parameters['data'] = '';
                            $buff = fgets($this->socket, 4096);
                            while(substr($buff, 0, 6) != '--END ') {
                                $parameters['data'] .= $buff;
                                $buff = fgets($this->socket, 4096);
                            }
                        }
                    }
        
                // store parameter in $parameters
                $parameters[substr($buffer, 0, $a)] = substr($buffer, $a + 2);
                }
                $buffer = trim(fgets($this->socket, 4096));
            }
        
            // process response
            switch($type) {
                case '': // timeout occured
                    $timeout = $allow_timeout;
                    break;
                case 'event':
                    // Process event with $parameters ?
                    break;
                case 'response':
                    break;
                default:
                    // $this->log('Unhandled response packet from Manager: ' . print_r($parameters, true));
                    break;
            }
        } while($type != 'response' && !$timeout);
        return $parameters;
    }
    
    function connect($server='localhost', $username='admin', $secret='amp111') {
        // Extract port if specified
        if(strpos($server, ':') !== false) {
            $parts = explode(':', $server);
            $this->server = $parts[0];
            $this->port   = $parts[1];
        } else {
            $this->server = $server;
            $this->port = "5038";
        }
        
        $errno = $errstr = NULL;
        $this->socket = @fsockopen($this->server, $this->port, $errno, $errstr);
        if(!$this->socket) {
            $this->log("Unable to connect to manager {$this->server}:{$this->port} ($errno): $errstr");
            return false;
        }
        
        // read the header
        $str = fgets($this->socket);
        if($str == false) {
            $this->log("Asterisk Manager header not received.");
            return false;
        } 
        // login
        $res = $this->send_request('login', array('Username'=>$username, 'Secret'=>$secret));

        if(false) {
            $this->log("Failed to login.");
            $this->disconnect();
            return false;
        }
        return true;
    }
    
    function disconnect() {
        $this->logoff();
        fclose($this->socket);
    }
    
    function Command($command) {    
        return $this->send_request('Command', array('Command'=>$command));
    }
    
    function ExtensionState($exten, $context='', $actionid='') {
        return $this->send_request('ExtensionState', array('Exten'=>$exten, 'Context'=>$context, 'ActionID'=>$actionid));
    }
    
    function Hangup($channel) {
        return $this->send_request('Hangup', array('Channel'=>$channel));
    }
    
    function Logoff() {
        return $this->send_request('Logoff');
    }
    
    function log($message, $level=1)
    {
        error_log(date('r') . ' - ' . $message);
    }
}
?>
