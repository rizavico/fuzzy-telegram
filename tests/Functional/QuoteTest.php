<?php

namespace Tests\Functional;

class QuoteTest extends BaseTestCase
{
    public function testNumberOfCategories(){
        $quote = new \Quote(null, null);
        $categories = $quote->getCategories();
        $this->assertEquals(5, sizeof($categories));
    }

    public function testCategoryListContainsManagement(){
        $quote = new \Quote(null, null);
        $categories = $quote->getCategories();
        $this->assertTrue(in_array("management", $categories));
    }

    public function testQuoteClientFailsForInvalidCategory(){
        try{
            $quote = new \Quote("Invalid Category", null);
            $this->fail("Shouldn't have come here.");
        }catch(\Exception $e){
            // expected
        }
    }
}