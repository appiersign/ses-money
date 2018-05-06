<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


/**
 * Created by PhpStorm.
 * User: Appier-Sign
 * Date: 5/6/2018
 * Time: 6:07 AM
 */

class MerchantContext extends TestCase implements \Behat\Behat\Context\Context
{
    use RefreshDatabase;

    private $merchant;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->merchant = new \App\Merchant();
    }


    /**
     * @Given I am on the merchant registration page
     */
    public function iAmOnTheMerchantRegistrationPage()
    {
        $this->merchant->setRoute();
    }

    /**
     * @Then I should see :header
     */
    public function iShouldSee($header)
    {
        $this->assertSame($header, $this->merchant->getRoute());
    }

    /**
     * @When I type :name as name, :email as email, :telephone as telephone and :address as address
     */
    public function iTypeAsNameAsEmailAsTelephoneAndAsAddress($name, $email, $telephone, $address)
    {
        $this->merchant->setNameAttribute($name);
        $this->merchant->setEmailAttribute($email);
        $this->merchant->setTelephoneAttribute($telephone);
        $this->merchant->setAddressAttribute($address);
    }

    /**
     * @When I click :button
     */
    public function iClick($button)
    {
        PHPUnit\Framework\Assert::assertTrue(true);
    }

    /**
     * @Then I should see :merchant created
     */
    public function iShouldSeeCreated($merchant)
    {
        PHPUnit\Framework\Assert::assertSame($merchant, $this->merchant->create());
    }
}
