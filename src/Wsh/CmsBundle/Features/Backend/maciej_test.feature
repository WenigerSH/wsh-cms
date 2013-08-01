Feature: Admin dashboard
  As an administrator
  I want to see the dashboard
  So I can manage all features

Scenario: Redirect to static version of cms
  Given I am logged in as "admin@example.com" with role "Admin"
  And I am on "sonata_admin_dashboard" route
  And I follow "Dashboard"
  And I should be on "/html/dashboard"
  And I should see "Weniger SH"
  And I should see "Getting started with WSH-CMS"