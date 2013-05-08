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
  And I should see "admin@example.com" in the "h1" element
  And I should see "Last login time"
  And I should see "Edit profile"
  When I follow "Change password"
  And I fill in "new_password" with "1234"
  And I fill in "new_password_confirm" with "1234"
  And I fill in "password" with "123"
  And I press "Change password"
  Then I should see "Your password has been changed"
  And I should be on "/admin/profile/"
