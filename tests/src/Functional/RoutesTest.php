<?php

namespace Drupal\Tests\collaborative_editors\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;

/**
 * Check if our defined routes are working correctly or not.
 *
 * @group collaborative_editors
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class RoutesTest extends BrowserTestBase {
  /**
   * An admin user used for this test.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $adminUser;
  /**
   * A user without admin permission.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $unauthorizedUser;
  /**
   * The permissions of the admin user.
   *
   * @var string[]
   */
  protected $adminUserPermissions = [
    'access administration pages',
  ];
  /**
   * {@inheritdoc}
   */
  public static $modules = ['collaborative_editors'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser($this->adminUserPermissions);
    $this->unauthorizedUser = $this->drupalCreateUser();
  }

  /**
   * Test that the availability of collaborative_editors.overview route.
   */
  public function testOverviewRoutes() {
    // For admin.
    $this->drupalLogin($this->adminUser);
    $this->drupalGet(URL::fromRoute('collaborative_editors.overview'));
    $this->assertResponse(200);
    // For user without admin permissions.
    $this->drupalLogin($this->unauthorizedUser);
    $this->drupalGet(URL::fromRoute('collaborative_editors.overview'));
    $this->assertResponse(403);
  }

}
