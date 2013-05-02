Feature: Admin dashboard
  As an administrator
  I want to see the dashboard
  So I can quickly assess the state of the system

@logout
Scenario: Admin sees the dashboard elements
  Given I am logged in as "admin@example.com" with role "Admin"
  And I am on "sonata_admin_dashboard" route
  Then I should see "Users"
  And I should see "Logout"