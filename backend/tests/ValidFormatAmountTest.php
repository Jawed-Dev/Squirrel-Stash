<?php
    use PHPUnit\Framework\TestCase;
    require_once './utils/HandlerValidFormat.php';

   class ValidFormatAmountTest extends TestCase
   {
        public function testEmptyAmount()
        {
            $handler = new HandlerValidFormat();
            $this->assertFalse($handler->isValidThresholdAmount(''));
        }

        public function testStringAmount()
        {
            $handler = new HandlerValidFormat();
            $this->assertFalse($handler->isValidThresholdAmount('999999'));
        }

        public function testStringAmountWithLetters()
        {
            $handler = new HandlerValidFormat();
            $this->assertFalse($handler->isValidThresholdAmount('123abc'));
        }

        public function testfloatAmount()
        {
            $handler = new HandlerValidFormat();
            $this->assertFalse($handler->isValidThresholdAmount(100.23));
        }

        public function testTooHighfloatAmount()
        {
            $handler = new HandlerValidFormat();
            $this->assertFalse($handler->isValidThresholdAmount(1000000.23));
        }

        public function testAmountTooHighInString()
        {
            $handler = new HandlerValidFormat();
            $this->assertFalse($handler->isValidThresholdAmount('1000000'));
        }

        public function testValidAmountInInt()
        {
            $handler = new HandlerValidFormat();
            $this->assertTrue($handler->isValidThresholdAmount(999999));
        }
   }
   
?>