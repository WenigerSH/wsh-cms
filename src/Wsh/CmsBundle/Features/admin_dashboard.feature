Feature:
  As a editor
  I want to see dashboard after login
  So that I can  read quick guide and see status of my support tickets

Scenario:
	Given I am on "/admin/logout"
	And I am on "/admin"
	When I fill in "username" with "admin@example.com"
	And I fill in "password" with "terramarda"
	And I press "Login"
	Then I should be on "/admin/"
	And I should see "Getting started"
	And I should see "Support tickets"
	And I should not see "Bad credentials"
