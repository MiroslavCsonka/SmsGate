<?php
    namespace SmsSender;

    class FormatCoder {
        public function generatePDU($phoneNumber, $message) {
            $lengthOfTelephoneNumber = $this->computeLengthOfPhoneNumber($phoneNumber);
            $codedPhoneNumber = $this->codeTelephoneNumber($phoneNumber);
            $messageLength = $this->computeLengthOfMessage($message);
            $codedMessage = $this->convertMessageToHexadecimal($message);

            $pdu = "000102{$lengthOfTelephoneNumber}81{$codedPhoneNumber}0000{$messageLength}{$codedMessage}";
            return $pdu;
        }

        private function computeLengthOfPhoneNumber($phoneNumber) {
            return "0" . strlen($phoneNumber);
        }

        public function codeTelephoneNumber($phoneNumber) {
            $phoneNumber = (string)$phoneNumber . 'F';
            $newNumber = '';
            for ($i = 0; $i < (strlen($phoneNumber) / 2); $i++) {
                $newNumber .= $phoneNumber[($i * 2) + 1];
                $newNumber .= $phoneNumber[($i * 2)];
            }
            return $newNumber;
        }

        public function computeLengthOfMessage($message) {
            $length = strlen($message);
            $hex = dechex($length);
            $hexLength = strlen($hex);
            if ($hexLength < 2) {
                $hex = '0' . $hex;
            }
            return strtoupper($hex);
        }

        private function convertMessageToHexadecimal($message) {
            return $this->convert7BitsArrayToHexadecimal($this->convertStringTo7BitsArray($message));
        }

        public function computeLengthOfPDU($pdu) {
            return (strlen($pdu) / 2) - 1;
        }

        public function computeCheckSum($pdu) {
            $total = 0;
            for ($i = 0; $i < strlen($pdu); $i += 2) {
                $total += hexdec($pdu[$i] . $pdu[$i + 1]);
            }
            $checkSum = dechex($total);
            return substr($checkSum, -2);
        }

        /**
         * Converts string to array of characters in binary representation
         * @param string $message
         * @return string[]
         */
        private function convertStringTo7BitsArray($message) {
            $characters = str_split(trim($message));
            $arrayOfBits = array();
            foreach ($characters as $character) {
                $arrayOfBits[] = $this->convertCharacterToBinary($character);
            }
            return $arrayOfBits;
        }

        private function convertCharacterToBinary($character) {
            $asciiNumber = ord($character);
            $binary = decbin($asciiNumber);
            $bits = str_pad($binary, 7, '0', STR_PAD_LEFT);
            return $bits;
        }

        private function convert7BitsArrayToHexadecimal($bits) {
            $hexOutput = '';
            $running = true;
            for ($i = 0; $running; $i++) {
                if (count($bits) === ($i + 1)) {
                    $running = false;
                }
                $characterInBinary = $bits[$i];
                if ($characterInBinary == '') {
                    continue;
                }
                $hasNextCharacter = isset($bits[$i + 1]);
                if ($hasNextCharacter) {
                    $nextCharacter = $bits[$i + 1];
                    $needBinaryLength = 8 - strlen($characterInBinary);

                    $partFromNext = substr($nextCharacter, -$needBinaryLength);
                    $restFromNext = substr($nextCharacter, 0, strlen($nextCharacter) - $needBinaryLength);

                    $bits[$i + 1] = $restFromNext;
                    $characterInBinary = $partFromNext . $characterInBinary;
                }
                $new = str_pad($characterInBinary, 8, '0', STR_PAD_LEFT);
                $hexOutput .= $this->convertBytesToHexadecimal($new);
            }
            return $hexOutput;
        }

        private function convertBytesToHexadecimal($bytes) {
            $decimal = 0;
            $size = strlen($bytes) - 1;
            for ($i = 0; $i <= $size; $i++) {
                $decimal += $bytes[$size - $i] * pow(2, $i);
            }
            $hex = dechex($decimal);
            if (strlen($hex) < 2) {
                $hex = '0' . $hex;
            }
            $hex = strtoupper($hex);
            return $hex;
        }


    }