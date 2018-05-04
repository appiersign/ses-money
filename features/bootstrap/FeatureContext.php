<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given there is a :arg1, which costs $:arg2
     */
    public function thereIsAWhichCosts($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I add :arg2 :arg1 to the basket
     */
    public function iAddToTheBasket($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then I should have :arg1 product in the basket
     */
    public function iShouldHaveProductInTheBasket($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then the overall price of the basket should be $:arg1
     */
    public function theOverallPriceOfTheBasketShouldBe($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given there is a :arg1, which cost $:arg2
     */
    public function thereIsAWhichCost($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then I should have :arg1 products in the basket
     */
    public function iShouldHaveProductsInTheBasket($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then the overall basket price should be $:arg1
     */
    public function theOverallBasketPriceShouldBe($arg1)
    {
        throw new PendingException();
    }
}
