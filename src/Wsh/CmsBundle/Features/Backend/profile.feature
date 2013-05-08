Feature: Profile edit and view
  As an administrator
  I want to see my profile page and make some changes to my profile and credentials
  So I can be sure about my password and information about me that other users see

@logout
Scenario: Admin can change he's password from profile
  Given I am logged in as "admin@example.com" with role "Admin"
  And I am on "sonata_admin_dashboard" route
  Then I should see "Profile"
  When I follow "Profile"
  Then I should be on "/admin/profile/"
  Then I should see "admin@example.com profile" in the "h1" element