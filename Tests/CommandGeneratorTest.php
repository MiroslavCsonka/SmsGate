<?php

    namespace SmsSender;

    class CommandGeneratorTest extends \PHPUnit_Framework_TestCase {

        /** @var  CommandGenerator */
        private $generator;

        public function setup() {
            $this->generator = new CommandGenerator(new FormatCoder());
        }

        public function test_can_generate_send_sms_command() {
            $command = 'AT^SM=0,16,000102098127345513F500000441F45B0D,E6';
            $smses = Sms::make('724355315', 'Ahoj');
            $this->assertSame($command, $this->generator->generateSendSmsCommand($smses[0], 0));

            $smses = Sms::make('724355315', str_repeat('a', 150));
            $command = 'AT^SM=0,144,000102098127345513F5000096E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E87C3E170381C0E03,5B';
            $this->assertSame($command, $this->generator->generateSendSmsCommand($smses[0], 0));
        }
    }
 