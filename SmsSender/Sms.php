<?php

    namespace SmsSender;


    class Sms {

        /** @var  string Telefonní číslo na které se zpráva bude posílat */
        private $phoneNumber;

        /** @var  string Vyčistěný string (bez diakritiky, maximálně 160 znaků) */
        private $message;

        public function getPhoneNumber() {
            return $this->phoneNumber;
        }

        public function getMessage() {
            return $this->message;
        }

        private function __construct($phoneNumber, $message) {
            $this->phoneNumber = $phoneNumber;
            $this->message = $message;
        }

        /**
         * Připraví zprávy před odesláním
         * @param int|string $phoneNumber
         * @param string     $message
         * @return Sms[]
         * @throws \InvalidArgumentException Pokud nesedí telefonní číslo
         */
        public static function make($phoneNumber, $message) {
            $phoneNumber = (string)$phoneNumber;
            if (strlen($phoneNumber) !== 9) {
                throw new \InvalidArgumentException('Invalid phone number.');
            }
            $message = self::removeDiacritics($message);
            return self::splitMessageIntoMultiple($phoneNumber, $message);
        }

        private static function splitMessageIntoMultiple($phoneNumber, $message) {
            $messages = str_split($message, 160);
            return array_map(function ($message) use ($phoneNumber) {
                return new Sms($phoneNumber, $message);
            }, $messages);

        }

        private static function removeDiacritics($message) {
            return Str_Replace(
                Array("á", "č", "ď", "é", "ě", "í", "ľ", "ň", "ó", "ř", "š", "ť", "ú", "ů", "ý ", "ž", "Á", "Č", "Ď", "É", "Ě", "Í", "Ľ", "Ň", "Ó", "Ř", "Š", "Ť", "Ú", "Ů", "Ý", "Ž"),
                Array("a", "c", "d", "e", "e", "i", "l", "n", "o", "r", "s", "t", "u", "u", "y ", "z", "A", "C", "D", "E", "E", "I", "L", "N", "O", "R", "S", "T", "U", "U", "Y", "Z"),
                $message);
        }

    }