<?php

    namespace SmsSender;

    class TelnetServer {
        private $host;
        private $port;
        private $user;
        private $password;

        private $server;

        function __construct($host, $user, $password, $port = 23) {
            $this->host = $host;
            $this->port = (int)$port;
            $this->user = $user;
            $this->password = $password;

            $this->connect();
        }

        public function write($command) {
            fputs($this->server, "$command\r\n");
        }

        public function read() {
            return trim(fgets($this->server));
        }

        private function connect() {
            $this->server = fsockopen($this->host, $this->port);
            $this->read(); // Version of device
            $this->read(); // Some datetime
            $this->read(); // Device serial number
            $this->write($this->user);
            $this->write($this->password);
            $this->read(); // Blank line
            $this->read(); // Written login
            $this->read(); // Written password

            $this->read(); // Blank line
            $this->read(); // Login was successful
        }

    }