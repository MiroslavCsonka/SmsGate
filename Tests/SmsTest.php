<?php

    namespace SmsSender;


    class SmsTest extends \PHPUnit_Framework_TestCase {
        const VALID_PHONE_NUMBER = '724355315';

        public function test_sms_can_be_created() {
            $message = 'Ahoj, jak se mas?';
            $smses = $this->createSmses($message);

            $this->assertInstanceOf('SmsSender\Sms', $smses[0]);
            $this->assertCount(1, $smses);
            $this->assertSame($message, $smses[0]->getMessage());
            $this->assertSame(self::VALID_PHONE_NUMBER, $smses[0]->getPhoneNumber());
        }

        public function test_message_is_cleaned() {
            $message = 'příliš žluťoučký kůň úpěl ďábelské ódy';
            $smses = $this->createSmses($message);

            $this->assertSame($smses[0]->getMessage(), 'prilis zlutoucky kun upel dabelske ody');
        }

        public function test_too_long_message_is_divided() {
            $message = str_repeat('a', 300);
            $smses = $this->createSmses($message);

            $this->assertCount(2, $smses);
            $this->assertSame(str_repeat('a', 160), $smses[0]->getMessage());
            $this->assertSame(str_repeat('a', 140), $smses[1]->getMessage());
        }

        public function test_max_sized_message() {
            $message = str_repeat('a', 160);
            $smses = $this->createSmses($message);

            $this->assertCount(1, $smses);
            $this->assertSame(str_repeat('a', 160), $smses[0]->getMessage());
        }

        private function createSmses($message) {
            return Sms::make(self::VALID_PHONE_NUMBER, $message);
        }


    }
 