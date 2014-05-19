<?php

    namespace SmsSender;


    class FormatCoderTest extends \PHPUnit_Framework_TestCase {

        /** @var  FormatCoder */
        private $coder;

        public function setup() {
            $this->coder = new FormatCoder();
        }

        public function test_can_generate_valid_number() {
            $this->assertSame('27345513F5', $this->coder->codeTelephoneNumber(724355315));
            $this->assertSame('27345513F5', $this->coder->codeTelephoneNumber("724355315"));
        }

        public function test_can_compute_length_of_message() {
            $this->assertSame('02', $this->coder->computeLengthOfMessage('hi'));
            $this->assertSame('0A', $this->coder->computeLengthOfMessage('Hello, Joe'));
            $this->assertSame('23', $this->coder->computeLengthOfMessage('This sentence is 35 characters long'));
        }
    }
 