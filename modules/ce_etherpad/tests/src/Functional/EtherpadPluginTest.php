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
class EtherpadPluginTest extends BrowserTestBase {
  /**
   * An admin user used for this test.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $adminUser;
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
  public static $modules = [
    'collaborative_editors',
    'ce_etherpad',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser($this->adminUserPermissions);
    // For admin.
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Test the availability of ce_etherpad on overview page.
   */
  public function testEtherpadPlugin() {
    $this->drupalGet(URL::fromRoute('collaborative_editors.overview'));
    $this->assertResponse(200);
    $this->assertSession()->pageTextContains('Plugin provided by ce_etherpad.');
  }

}
