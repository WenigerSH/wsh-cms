Feature: List of pages
  As an administrator
  I want to see the list of pages
  So that I can manage the content of the site

@logout
Scenario: Viewing the list of pages
  Given I am logged in as "admin@example.com" with role "Admin"
  And I am on "sonata_admin_dashboard" route
  When I follow "Pages"
  Then I should see "Pages"
  And I should be on "backend_pages_list" route
