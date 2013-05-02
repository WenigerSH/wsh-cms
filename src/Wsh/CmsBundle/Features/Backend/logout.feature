Feature: Logging out of the system
  As a user
  I want to log out from the system
  So that no unauthorized people can access the system in the same browser session

Scenario: Log out from the system
  Given I am logged in as "admin@example.com" with role "Admin"
  When I am on "sonata_admin_dashboard" route
  And I follow "Logout"
  Then I should be on "/"