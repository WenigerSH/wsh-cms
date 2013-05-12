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
  When I follow "Edit login and password"
  And I fill in "fos_user_change_password_form_new_first" with "1234"
  And I fill in "fos_user_change_password_form_new_second" with "1234"
  And I fill in "fos_user_change_password_form_current_password" with "123"
  And I press "Change password"
  Then I should see "Your password has been changed"
  And I should be on "/admin/profile"
  When I follow "Logout"
  Given I am on "/admin"
  When I fill in "_username" with "admin@example.com"
  And I fill in "password" with "1234"
  When I press "Login"
  Then I should be on "/admin"

Scenario: Admin can change he's firstname and lastname
  Given I am logged in as "admin@example.com" with role "Admin"
  And I am on "sonata_admin_dashboard" route
  Then I should see "Profile"
  When I follow "Profile"
  Then I should be on "/admin/profile/"
  When I follow "Edit profile"
  And I fill in "sonata_user_profile_form_firstname" with "Bartosz"
  And I fill in "sonata_user_profile_form_lastname" with "Rychlicki"
  And I press "Save changes"
  Then I should be on "/admin/profile/"
  And I should see "Your profile has been updated"
  And I should see "Bartosz Rychlicki"
