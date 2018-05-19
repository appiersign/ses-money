Feature: Merchant Registration
  In order to register a merchant
  As a super admin
  I need to be able to create new merchant

  Background:
    Given I am on the merchant registration page
    Then I should see "Merchant Registration"

  Scenario: Registering New Merchant
    When I type "Audio Insights" as name, "email" as email, "+233249621938" as telephone and "20 Aluguntugui street East Legon" as address
    And I click "create"
    Then I should see "Audio Insights" created