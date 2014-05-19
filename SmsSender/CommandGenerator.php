<?php

    namespace SmsSender;


    class CommandGenerator {
        /** @var FormatCoder */
        private $coder;

        public function __construct(FormatCoder $coder) {
            $this->coder = $coder;
        }

        public function generateSendSmsCommand(Sms $sms, $simCardSlot = 0) {
            $pdu = $this->coder->generatePDU($sms->getPhoneNumber(), $sms->getMessage());
            $length = $this->coder->computeLengthOfPDU($pdu);
            $partOfCheckSum = $this->coder->computeCheckSum($pdu);
            return strtoupper("AT^SM={$simCardSlot},{$length},{$pdu},{$partOfCheckSum}");
        }

        public function generateActivateConnectionCommand() {
            return 'AT!G=A6';
        }


    }