Feature: Deleting a page
  As an administrator
  I want to delete a page
  To remove it from the system entirely

  Background: Admin is logged in and some pages are in place
    Given I am logged in as "admin@example.com" with role "Admin"
    And the following languages exist
      | code | name    |
      | en   | English |
      | de   | German  |
      | pl   | Polish  |
    And the following pages exist
      | title | body       |
      | test1 | test1 body |
      | test2 | test2 body |
      | test3 | test3 body |
      | test4 | test4 body |
      | test5 | test5 body |

  Scenario: Deleting a page will succeed if confirmed
    Given I am on "sonata.admin.page" "edit" route of object with "title" "test1"
    And I follow "Delete"
    Then I should see "Confirm deletion"
    And I should be on "sonata.admin.page" "delete" route of object with "title" "test1"
    When I press "Yes, delete"
    Then I should be on "sonata.admin.page" "list" route
    And I should see "Item has been deleted successfully."
    And I should not see "test1"

  Scenario: Cancelling a delete will return to edit
    Given I am on "sonata.admin.page" "edit" route of object with "title" "test1"
    And I follow "Delete"
    Then I should see "Confirm deletion"
    And I should be on "sonata.admin.page" "delete" route of object with "title" "test1"
    When I follow "Edit"
    Then I should be on "sonata.admin.page" "edit" route of object with "title" "test1"