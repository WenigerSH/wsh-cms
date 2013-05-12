Feature: Updating a page
  As an administrator
  I want to update a page
  To fix a typo

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

  Scenario: Viewing the page edit form
    Given I am on "sonata.admin.page" "list" route
    When I follow "test1"
    Then I should be on "sonata.admin.page" "edit" route of object with "title" "test1"
    And the "Title English" field should contain "test1"
    And the "Title German" field should contain "test1 German"
    And the "Title Polish" field should contain "test1 Polish"
    And the "Body English" field should contain "test1 body"
    And the "Body German" field should contain "test1 body German"
    And the "Body Polish" field should contain "test1 body Polish"
    And the "Meta Title" field should contain "test1"
    And the "Meta Description" field should contain "test1 body"
    And I should see "Meta Keywords"
    And the "Slug" field should contain "test1"
    And I should see an "input[value='Update']" element
    And I should see an "input[value='Update and close']" element
    And I should see an "input[value='Update and publish']" element
    And I should see "Delete"

  Scenario: Empty body or title in default locale will result in an error
    Given I am on "sonata.admin.page" "edit" route of object with "title" "test1"
    When I fill in the following:
      | Title English |          |
      | Body English  |          |
    And I press "Update"
    Then I should see "An error has occurred during item update."
    And I should see "Field 'title' for locale 'en' cannot be blank"
    And I should see "Field 'body' for locale 'en' cannot be blank"
    And I should be on "sonata.admin.page" "edit" route of object with "title" "test1"

  Scenario: Successful update without changing published status
    Given I am on "sonata.admin.page" "edit" route of object with "title" "test1"
    When I fill in the following:
      | Title English | changed title |
      | Body English  | new body      |
    And I press "Update"
    Then I should see "Item has been successfully updated."
    And I should be on "sonata.admin.page" "edit" route of object with "title" "changed title"
    And the "Title English" field should contain "changed title"
    And the "Body English" field should contain "new body"
    And the "Slug" field should contain "changed-title"
    And the "Meta Title" field should contain "test1"
    And the "Meta Description" field should contain "test1 body"

  Scenario: Successful update and back to list
    Given I am on "sonata.admin.page" "edit" route of object with "title" "test2"
    When I fill in the following:
      | Title English | test2 update  |
      | Body English  | new body      |
    And I press "Update and close"
    Then I should see "Item has been successfully updated."
    And I should be on "sonata.admin.page" "list" route
    And I should see "test2 update"

  Scenario: Successful update and publish
    Given I am on "sonata.admin.page" "edit" route of object with "title" "test3"
    When I press "Update and publish"
    Then I should see "Item has been successfully updated."
    And I should be on "sonata.admin.page" "edit" route of object with "title" "test3"
    And page with title "test3" should be published
    And I should see an "input[value='Update and hide']" element

  Scenario: Successful update and hide
    Given page with title "test3" is published
    And I am on "sonata.admin.page" "edit" route of object with "title" "test3"
    When I press "Update and hide"
    Then I should see "Item has been successfully updated."
    And I should be on "sonata.admin.page" "edit" route of object with "title" "test3"
    And page with title "test3" should not be published
    And I should see an "input[value='Update and publish']" element