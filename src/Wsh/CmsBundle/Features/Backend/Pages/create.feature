Feature: Creating a page
  As an administrator
  I create a new page
  So that it will be visible in frontend

  Background: Admin is logged in and some pages are in place
    Given I am logged in as "admin@example.com" with role "Admin"
    And the following "WshCmsBundle:Language" exist
      | code | name    |
      | en   | English |
      | de   | German  |
      | pl   | Polish  |
    And the following "WshCmsBundle:Page" exist
      | title | body       |
      | test1 | test1 body |
      | test2 | test2 body |
      | test3 | test3 body |
      | test4 | test4 body |
      | test5 | test5 body |

  Scenario: Viewing the page creation form
    Given I am on "sonata.admin.page" "list" route
    When I follow "Add new"
    Then I should be on "sonata.admin.page" "create" route
    And I should see "Title English"
    And I should see "Title German"
    And I should see "Title Polish"
    And I should see "Body English"
    And I should see "Body German"
    And I should see "Body Polish"
    And I should see "Meta Title"
    And I should see "Meta Description"
    And I should see "Meta Keywords"
    And I should see "Slug"
    And I should see an "input[value='Save as draft']" element
    And I should see an "input[value='Save and preview']" element
    And I should see an "input[value='Publish']" element

  Scenario: Empty body or title in default locale will result in an error
    Given I am on "sonata.admin.page" "create" route
    When I fill in the following:
      | Title German  | title de |
      | Title Polish  | title pl |
      | Body German   | body de  |
      | Body Polish   | body pl  |
    And I press "Publish"
    Then I should see "An error has occurred during item creation."
    And I should see "Field 'title' for locale 'en' cannot be blank"
    And I should see "Field 'body' for locale 'en' cannot be blank"
    And I should be on "sonata.admin.page" "create" route

  Scenario: Correctly creating a draft page
    Given I am on "sonata.admin.page" "create" route
    And there is no "WshCmsBundle:Page"
    When I fill in the following:
      | Title English | title en |
      | Title German  | title de |
      | Title Polish  | title pl |
      | Body English  | body en  |
      | Body German   | body de  |
      | Body Polish   | body pl  |
    And I press "Save as draft"
    Then I should see "Item has been successfully created."
    And I should be on "sonata.admin.page" "edit" route of object with "title" "title en"
    And page with title "title en" should not be published

  Scenario: Correctly creating a published page
    Given I am on "sonata.admin.page" "create" route
    And there is no "WshCmsBundle:Page"
    When I fill in the following:
      | Title English | title en |
      | Title German  | title de |
      | Title Polish  | title pl |
      | Body English  | body en  |
      | Body German   | body de  |
      | Body Polish   | body pl  |
    And I press "Publish"
    Then I should see "Item has been successfully updated."
    And I should be on "sonata.admin.page" "edit" route of object with "title" "title en"
    And page with title "title en" should be published

  Scenario: Correctly creating a page and going to preview
    Given I am on "sonata.admin.page" "create" route
    And there is no "WshCmsBundle:Page"
    When I fill in the following:
      | Title English | title en |
      | Title German  | title de |
      | Title Polish  | title pl |
      | Body English  | body en  |
      | Body German   | body de  |
      | Body Polish   | body pl  |
    And I press "Save and preview"
    Then I should see "Item has been successfully created."
    And I should be on "sonata.admin.page" "show" route of object with "title" "title en"
    And page with title "title en" should not be published


