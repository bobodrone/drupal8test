<?php

namespace Drupal\Tests\ddd\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Example functional test.
 *
 * @group ddd
 */
class BrowserTest extends BrowserTestBase {

  protected $user;

  public static $modules = ['block', 'node', 'datetime'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalCreateContentType(['type' => 'page', 'name' => 'Basic page']);
    $this->user = $this->drupalCreateUser(['edit own page content', 'create page content']);
    $this->drupalPlaceBlock('local_tasks_block');
  }

  /**
   * {@inheritdoc}
   */
  public function testNodeCreate() {
    $this->drupalLogin($this->user);
    $title = $this->randomString();
    $body = $this->randomString(32);
    $edit = [
      'Title' => $title,
      'Body' => $body,
    ];
    $this->drupalPostForm('node/add/page', $edit, t('Save'));

    // Test that the title is the same.
    $node = $this->drupalGetNodeByTitle($title);
    $this->assertTrue($node);
    $this->assertEquals($title, $node->getTitle());
    $this->assertEquals($body, $node->body->value);
  }

  /**
   * {@inheritdoc}
   */
  public function testDrupalGet() {
    $this->drupalGet('user/register');
    $this->assertSession()->pageTextContains('Create new account');
    $this->assertSession()->fieldExists('Email address');
    $this->assertSession()->fieldExists('Username');
    $this->assertSession()->buttonExists('Create new account');
    $this->assertSession()->pageTextNotContains('Joomla');
  }

}
