Feature: List of pages
  As an administrator
  I want to see the list of pages
  So that I can manage the content of the site

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

Scenario: Viewing the list of pages
  Given I am on "sonata_admin_dashboard" route
  When I follow "Pages"
  Then I should see "Pages"
  And I should see "test1"
  And I should see "test2"
  And I should see "test3"
  And I should see "test4"
  And I should see "test5"
  And I should be on "sonata.admin.page" "list" route
