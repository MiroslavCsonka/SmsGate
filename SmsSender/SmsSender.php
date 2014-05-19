<?php
    namespace SmsSender;

    class SmsSender {
        /** @var  TelnetServer */
        private $server;

        /** @var CommandGenerator */
        private $commandGenerator;

        private $activatedConnection = false;

        public function __construct(TelnetServer $server, CommandGenerator $commandGenerator) {
            $this->server = $server;
            $this->commandGenerator = $commandGenerator;
        }

        private function sendSms(Sms $sms) {
            $this->activateConnection();
            $command = $this->commandGenerator->generateSendSmsCommand($sms);
            $this->server->write($command);
            $this->server->read(); // Written command
            $response = $this->server->read();
            list($responseMessage, $codes) = explode(' ', $response);
            if ($responseMessage === '*smsout:') {
                return true;
            }
            return false;
        }

        /**
         * Sends multiple smses
         * @param Sms[] $smses
         * @return bool
         */
        public function sendSmses(array $smses) {
            $totalStatus = true;
            foreach ($smses as $sms) {
                $status = $this->sendSms($sms);
                $totalStatus = $status ? : false;
            }
            return $totalStatus;
        }

        private function activateConnection() {
            if (!$this->activatedConnection) {
                $activateConnectionCommand = $this->commandGenerator->generateActivateConnectionCommand();
                $this->server->write($activateConnectionCommand);

                $this->server->read(); // Written command
                $this->server->read(); // Blank line
                $this->server->read(); // OK response, connection was activated

                $this->activatedConnection = true;
            }
        }

        public static function buildInstance(TelnetServer $server) {
            return new SmsSender($server, new CommandGenerator(new FormatCoder()));
        }


    }