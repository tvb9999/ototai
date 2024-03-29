<?php

  /*
  ------------------------------------------------------------------------------
  
  Title       :     Email
  Version     :     2.0.0
  Author      :     Jason Jacques <jtjacques@users.sourceforge.net>
  URL         :     http://poss.sourceforge.net/email
  
  Description :     PHP mail() clone with build in MTA
                    Returns TRUE or FALSE depending on delivery status.

  Usage       :     See documentation for usage information.
                    
                    Depreciated:
                    email(to, subject, message [, headers [, parameters]])
                      
  Copyright   :     2005-2007 Jason Jacques
  License     :     MIT License
  
  Created     :     2005-06-15
  Modified    :     2007-12-30
  
  Key Updates :     * Object oriented version
                    + Fixed missing crlf after headers
                    + Added RFC 2821 compliant HELO for IP address
  
                    + Change default DNS provider to OpenDNS
                    + Fixed various bugs 
                    
  Todo        :     - Get user feedback on new implimentation, requested
                      features, changes, etc.
                    - Investigate and impliment other applicable
                      "additional_parameters".
                    
  Notes       :     The default DNS server is 208.67.222.222 provided by
                    OpenDNS. http://www.opendns.com/
  
  ------------------------------------------------------------------------------
  */


  class Email {
  
    // Set version information
    var $emailVersionMajor  = 2;
    var $emailVersionMinor  = 0;
    var $emailVersionPatch  = 0;
    var $emailVersionString = "Final";
    var $emailVersion       = null;
    
    // Define other variables
    var $dnsServer = null;    // Default is set in the mxQuery class
    var $help      = false;
    var $status    = array();
    var $greeting  = null;
    
    // Define variables to store user data
    var $recipients    = array();
    var $headers       = array();
    var $message       = null;    
    var $announceEmail = null;
    
    
    // Constructor, allow DNS server to be set
    function email($dns = null) {
      // Import global variables
      global $HTTP_SERVER_VARS;
      if(!@$HTTP_SERVER_VARS['SERVER_NAME']) {
        $HTTP_SERVER_VARS['SERVER_NAME'] = "127.0.0.1";
      }
      
      // Check for IP address and encapsulate, RFC 2821.
      $domain = explode('.', $HTTP_SERVER_VARS['SERVER_NAME']);
      if(is_numeric($domain[(count($domain)-1)])) {
      	$this->greeting = '[' . $HTTP_SERVER_VARS['SERVER_NAME'] . ']';
      } else {
      	$this->greeting = $HTTP_SERVER_VARS['SERVER_NAME'];
      }
      
      // Generate full version information
      $this->emailVersion = "Email " . $this->emailVersionMajor . "." .
              $this->emailVersionMinor . "." . $this->emailVersionPatch . " " .
              $this->emailVersionString;
      
      // Set DNS server if specified
      if($dns) {
        // If can't set server address, return false instead of object
        if(!$this->setDNS($dns)) {
          return false;
        }
      }
      
      // Generate Default Header Set
      $this->addHeader('To',       '');
      $this->addHeader('Subject',  '');
      $this->addHeader('From',     'feedback@' . $HTTP_SERVER_VARS['SERVER_NAME']);
      $this->addHeader('Date',     date("D, d M Y H:i:s O"));
      $this->addHeader('X-Mailer', $this->emailVersion);
    }
    
    
    // Set DNS server
    function setDNS($dns = null) {
      if($dns) {
        $this->dnsServer = $dns;
        return true;
      } else {
        return false;
      }
    }
    
    
    // Add recipient
    function addRecipient($name, $email = false) {
      // Check one parameter is an email address
      if((strpos($name, "@") == false) && (strpos($email, "@") == false)) {
        return false;
      }
      
      // Set name to email address if it was not set
      if(!$email) {
        $email = $name;
      }
      
      array_push($this->recipients, array('name' => $name, 'email' => $email));
      
      return true;
    }
    
    
    // Set Subject
    function setSubject($subject = null) {
    	return $this->addHeader('Subject', $subject);
    }
    
    
    // Add/overwrite header
    function addHeader($header, $content = null) {
      // Create content and header name if only header name
      if(!$content) {
        $tmp = explode(":", $header, 2);
        $header  = @trim($tmp[0]);
        $content = @trim($tmp[1]);
      }
      
      // Prevent multiple headers being added/spoofed at once
      $header  = str_replace("\r\n", "", $header);
      $content = str_replace("\r\n", "", $content);
      
      // Check for some content
      if((!$header) && (!$content)) {
        return false;
      }
    
      // Check for presence and overwrite
      $tmp = false;
      for($i=0;$i<count($this->headers);$i++) {
        if($this->headers[$i]['name'] == $header) {
          $this->headers[$i]['value'] = $content;
          $tmp = true;
        }
      }
      
      // Else add header
      if(!$tmp) {
        array_push($this->headers, array('name'  => $header,
                                         'value' => $content));
      }
      
      return true;
    }
    
    
    // Set message content
    function setMessage($message) {
      $this->message = $message;
      return true;
    }
    
    
    // Set Announce Email Address
    function setAnnounceEmail($email) {
      if(strpos($email, '@')) {
        $this->announceEmail = $email;
        return true;
      } else {
        return false;
      }
    }
    
    
    // Send Message
    function send() {
      // Iterate through each recipient
      for($i=0;$i<count($this->recipients);$i++) {
        
        // Get domain from recipient address
        $address = explode('@', $this->recipients[$i]['email'], 2);
        $domain  = @$address[1];
        
        // Get MX address
        $mxQuery   = new mxQuery($this->dnsServer);
        $mxAddress = $mxQuery->getmxr($domain);
        
        // Generate headers
        $headers = null;
        for($n=0;$n<count($this->headers);$n++) {
          // Get from address from header
          if($this->headers[$n]['name'] == 'From') {
            $fromAddress = $this->headers[$n]['value']; 
          }
          
          if($this->headers[$n]['name'] == 'To') {
            // If no specifed To header, automatically generate
            if(!$headers[$n]['value']) {
              $headers .= $this->headers[$n]['name'] . ': "' .
                    $this->recipients[$i]['name'] . '" <' .
                    $this->recipients[$i]['email'] . ">\r\n";
            }
          } else {
            // Add headers to headers string
            $headers .= $this->headers[$n]['name'] . ': ' .
                    $this->headers[$n]['value'] . "\r\n";
          }
        }
        // Terminate headers to prevent injection from body
        $headers .= "\r\n";
        
        // Set from address to announceEmail if specified
        if($this->announceEmail) {
          $fromAddress = $this->announceEmail;
        }
        
        // Generate main message
        $message = $this->message;
        
        // Set status messages
        $this->status[$i]['name']        = $this->recipients[$i]['name'];
        $this->status[$i]['address']     = $this->recipients[$i]['email'];
        $this->status[$i]['domain']      = $domain;
        $this->status[$i]['mxAddress']   = $mxAddress;
        $this->status[$i]['fromAddress'] = $fromAddress;
        $this->status[$i]['headers']     = $headers;
        $this->status[$i]['message']     = $message;
        // Done seperately to avoid bug in PHP < 4.3.0
        $this->status[$i]['message']     = str_rot13($this->status[$i]['message']);
        
        // Open connection to reciving server
        $mxServer = @fsockopen($mxAddress, 25, $null1, $null2, 5);
        
        if($mxServer) {
          // Update status, connection success
          $this->status[$i]['connected'] = true;
          
          // Prevent a blocked socket holding up script
          socket_set_timeout($mxServer, 5);
          
          // Add status array for server response
          $this->status[$i]['mxResponse'] = array();
          
          // Handshake with MX server. Store all responses in status array
          array_push($this->status[$i]['mxResponse'], fgets($mxServer));
          fwrite($mxServer, "HELO " . $this->greeting . "\r\n");
          array_push($this->status[$i]['mxResponse'], fgets($mxServer));
          fwrite($mxServer, "MAIL FROM:<" . $fromAddress . ">\r\n");
          array_push($this->status[$i]['mxResponse'], fgets($mxServer));
          
          // Inform MX server of destination email address
          fwrite($mxServer, "RCPT TO:<" . $this->recipients[$i]['email'] . ">\r\n");
          array_push($this->status[$i]['mxResponse'], fgets($mxServer));
          
          // Send message data
          fwrite($mxServer, "DATA\r\n");
          array_push($this->status[$i]['mxResponse'], fgets($mxServer));
          fwrite($mxServer, $headers);			// Note: header termination is
          fwrite($mxServer, $message);			// done during header creation.
          fwrite($mxServer, "\r\n.\r\n");
          array_push($this->status[$i]['mxResponse'], fgets($mxServer));

          // Close connection
          fwrite($mxServer, "QUIT\r\n");
          array_push($this->status[$i]['mxResponse'], fgets($mxServer));
          fclose($mxServer);
        } else {
          // Update status,connetion failure
          $this->status[$i]['connected'] = false;
        } 
      }
      
      // Return status
      $status = true;
      for($n=0;$n<count($this->status);$n++) {
        if(!$this->status[$n]['connected']) {
          $status = false;
        }
      }
      
      return $status;
    }
    
    
    // Get Status Array
    function getStatus() {
    	return $this->status;
    }
    
    
    // Turn help on or off
    function help($choice = null) {
      if($choice) {
        $this->help = true;
      } else {
        $this->help = false;
      }
      return true;
    }
    
    
    // Check value of any variable
    function checkValue($variable) {
      return $this->$variable;
    }
    
    
  }
  
  
  
  class mxQuery {

    // Set OpenDNS as default DNS server
    var $dnsServer    = "208.67.222.222";
    
    var $status       = array();    
    var $dnsResponse  = null;
  
    function mxQuery($dns = null) {
      // Set DNS server if specified
      if($dns) {
        $this->dnsServer = $dns;
      }
    }
    
    
    // Get the 1 best mx record for domain
    function getmxr($domain = null) {
      $tC = 0; $anCount = 0; $pointer = 0; $domains = array();
      
      // Check domain supplied
      if(!$domain) {
       return false;
      }
      
        // Log data for status review
        $this->status['domain']      = $domain;
      
      // Generate DNS query, see RFC 1035 for more information
      // - Header 
      $query  = chr(0) . chr(1) .    // ID: 1
                chr(1) . chr(0) .    // QR: 0, OPCODE: 0, AA: 0, TC: 0, RD: 1;
                                     // RA: 0, Z: 0, RCODE: 0
                chr(0) . chr(1) .    // QDCOUNT: 1
                chr(0) . chr(0) .    // ANCOUNT: 0
                chr(0) . chr(0) .    // NSCOUNT: 0
                chr(0) . chr(0) ;    // ARCOUNT: 0
                
      // - Domain breakdown
      $domain = explode('.', strtolower(trim($domain)));
      for($i=0; $i<count($domain); $i++) {
        $query .= chr(strlen($domain[$i])) . $domain[$i];
      }
      $query .= chr(0); // Close string
      
      // - DNS query settings
      $query .= chr(0) . chr(15) .    // QTYPE: 15, MX lookup
                chr(0) . chr(1)  ;    // QCLASS: 1, the Internet
      

        $this->status['queryPacket'] = base64_encode($query);
        $this->status['dnsServer']   = $this->dnsServer;
      
      // Open connection to DNS server
      $dnsConnection = fsockopen("udp://" . $this->dnsServer, 53);
      if($dnsConnection) {      
        socket_set_timeout($dnsConnection, 10);
          $this->status['connection'] = "true";
      } else {
          $this->status['connection'] = "false";
      }
      
      // Send query
      fwrite($dnsConnection, $query);
      
      // Get 512 byte reply (maximum packet size over UDP connection)
      $this->dnsResponse = fread($dnsConnection, 512);
      
        $this->status['dnsResponse'] = base64_encode($this->dnsResponse);
      
      // Close connection
      fclose($dnsConnection);
      
      // Check if response is truncated;
      $tC = decbin(ord(substr($this->dnsResponse, 2, 1)));
      @$tC = $tC[6];
      
        if($tC) {
          $this->status['truncated'] = "true";
        } else {
          $this->status['truncated'] = "false";
        }
      
      // Count number of responses
      // -  This is lazy, RFC 1035 specifies a 16bit number, this uses 8bits
      $anCount = ord(substr($this->dnsResponse, 7, 1));
      
        $this->status['answerCount'] = $anCount;
      
      // If no results give benifit of the doubt
      if($anCount == 0) {
          $this->status['mxServer'][0]['domain']   = implode('.', $domain);
          $this->status['mxServer'][0]['priority'] = 0;
        return implode('.', $domain);
      } else {
      
        // If truncation occured use first address
        if($tC) {
          $anCount = 1;
        }
      
        // Skip through header
        $pointer += 12;
        $pointer += $this->labelLength($pointer);
        $pointer += 4;
      
          $this->status['mxServer'] = array();
      
        // Iterate through each given response
        for($i=0;$i<$anCount;$i++) {
          $pointer += $this->labelLength($pointer);
          // Double check we are dealing with a MX response
          if($this->dnsResponse[$pointer+1] == chr(15)) {
            // Skip RDATA header
            $pointer += 10;
            
            // This is lazy, RFC 1035 specifies a 16bit priority, this is 8bit
            //$domains[$i]['priority'] = ord($this->dnsResponse[$pointer+1]);
            //$pointer += 2;
            //$domains[$i]['mxrecord']   = $this->readLabels($pointer);
            
              array_push($this->status['mxServer'],
                      array('domain'   => $this->readLabels($pointer+2),
                            'priority' => ord($this->dnsResponse[$pointer+1])));
            
            // Sneaky cheat: Automatically scrub over responses with the same priority
            $domains[ord($this->dnsResponse[$pointer+1])] = $this->readLabels($pointer+2);
            $pointer += 2;
            $pointer += $this->labelLength($pointer);
          } else {
            break;
          }
        }
        
        // Order by lowest priority first
        ksort($domains);
        $mxDomain = array_shift($domains);
        
        if((!count($domains)) && (!$mxDomain)) {
          $mxDomain = implode('.', $domain);
        }
      
        return $mxDomain;
      }
      
    }
    
    
    // Read the next label set and proccess into a domain
    function readLabels($pointer) {
      $domain = null; $length = 0;
      
      // If we are not at the end of this string of labels
      while($this->dnsResponse[$pointer] != chr(0)) {
        $length = ord($this->dnsResponse[$pointer]);
        $pointer++;
        
        // If this is not a pointer
        if($length < 192) {
          
          // Add next label to domain
          $domain .= substr($this->dnsResponse, $pointer, $length) . '.';
          $pointer += $length;
          
        } else {
        
          // Calculate pointer value
          $goTo = ord($this->dnsResponse[$pointer]);
        
          // Read from the pointer position
          return $domain . $this->readLabels($goTo);
        }

      }
      return $domain;
    }
    
    
    // Count the uncompressed length of the next label set
    function labelLength($pointer) {
      $oldPointer = $pointer;
      
      // See how many uncompressed bytes to next end of label/pointer
      while(($this->dnsResponse[$pointer] != chr(0)) &&
              (ord($this->dnsResponse[$pointer]) < 192)) {
        $pointer++;
      }
      // If pointer include next ocet as well
      if(ord($this->dnsResponse[$pointer]) >= 192) {
        $pointer++; 
      }
      // Count the ocet we are reading
      $pointer++;
      return $pointer - $oldPointer;
    }
    
    
    // Check value of any variable
    function checkValue($variable) {
      return $this->$variable;
    }
  
  }
  
  
  // Depriciated, function access for users enabling (e)mail in applications
  //              on non-supported/operating system configurations.
  function email($to, $subject, $message, $headers = null, $params = null) {
    // Basic validation for to address(es)
    if(!strpos($to, "@")) {
       return false;
    }

    // Create new instance of email class
    $email = new Email();
   
    // Generate recipient details
    $recipients = explode(",", $to);
    // Proccess multiple addresses
    for($i=0;$i<count($recipients);$i++) {
      // Check for name and email combination and add apropriately
      if(strpos($recipients[$i], '<')) {
        $recipient = explode("<", $recipients[$i], 2);
        $email->addRecipient(trim(str_replace('"', '', $recipient[0])),
                             trim(str_replace('>', '', @$recipient[1])));
      } else {
        $email->addRecipient(trim($recipients[$i]));
      }
    }
    
    // Set subject
    $email->setSubject($subject);
    
    // Generate Headers
    $headers = explode("\r\n", $headers);
    for($i=0;$i<count($headers);$i++) {
      $email->addHeader($headers[$i]);
    }

    // Set message content
    $email->setMessage($message);
    
    // Check for specified sender
    if(strpos($params, "-f") !== false) {
      $sender = explode('-f', $params);
      $sender = explode(' ', @$sender[1]);
      $sender = trim($sender[0]);
      $email->setAnnounceEmail($sender);
    }
    
    // Send message
    return $email->send();    
  }
  


?>
