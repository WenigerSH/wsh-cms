Feature: Login to admin panel
  As a guest
  I want to login into system as admin
  So then I will be God and do whatever I want in system

@logout
Scenario: going to admin login page will show login form
  When I am on "sonata_admin_dashboard" route
  Then I should see "Username"
  And I should see "Password"
  And I should see "Remember me"

@logout
Scenario: login in to system as Admin - success
  Given user "admin@example.com" with role "Admin" and password "123" exists
  And I am on "sonata_admin_dashboard" route
  When I fill in "username" with "admin@example.com"
  And I fill in "password" with "123"
  And I press "Login"
  Then I should see "Logout"
  And I should see "admin@example.com"
  And I should be on "/admin"

@logout
Scenario: Wrong admin password - should be error message
  When I am on "sonata_admin_dashboard" route
  And I fill in "username" with "admin@exmaple.com"
  And I fill in "password" with "WrongPassword"
  And I press "Login"
  Then I should be on "/admin/login"
  And I should see "Bad credentials"

@logout
Scenario: Wrong admin username - should be error message
  When I am on "sonata_admin_dashboard" route
  And I fill in "username" with "hacker@exmaple.com"
  And I fill in "password" with "123"
  And I press "Login"
  And I should see "Bad credentials"
  And I should be on "/admin/login"