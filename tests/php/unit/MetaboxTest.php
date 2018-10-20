<?php

namespace Dinamiko\DKPDF\Tests\Unit;

use Dinamiko\DKPDF\MetaBox;

class MetaboxTest extends TestCase
{

    protected $metabox;

    protected function setUp()
    {
        parent::setUp();
        $this->metabox = new MetaBox();
    }

    public function testMetaboxSetup()
    {
        \Brain\Monkey\Functions\when('get_option')
            ->justReturn(['post']);

        \Brain\Monkey\Functions\when('__')
            ->justReturn(1);

        \Brain\Monkey\Functions\expect('add_meta_box')
            ->once()
            ->withAnyArgs();

        $this->metabox->metaboxSetup();

        $this->assertTrue(true);
    }

    public function testMetaboxSave()
    {
        $_POST['dkpdf_nonce'] = 'foo';
        \Brain\Monkey\Functions\when('wp_verify_nonce')
            ->justReturn(true);

        \Brain\Monkey\Functions\when('current_user_can')
            ->justReturn(true);

        \Brain\Monkey\Functions\when('__')
            ->justReturn(1);

        \Brain\Monkey\Functions\when('get_post_meta')
            ->justReturn('on');

        \Brain\Monkey\Functions\when('update_post_meta')
            ->justReturn(true);

        $this->assertTrue($this->metabox->metaboxSave(1));
    }

    public function testMetaboxSaveNonce()
    {
        $_POST['dkpdf_nonce'] = 'foo';
        \Brain\Monkey\Functions\when('wp_verify_nonce')
            ->justReturn(false);

        $this->assertFalse($this->metabox->metaboxSave(1));
    }

    public function testMetaboxUserCan()
    {
        $_POST['dkpdf_nonce'] = 'foo';
        \Brain\Monkey\Functions\when('wp_verify_nonce')
            ->justReturn(true);

        \Brain\Monkey\Functions\when('current_user_can')
            ->justReturn(false);

        $this->assertFalse($this->metabox->metaboxSave(1));
    }

    public function testCreateHtmlEmptyParameters()
    {
        $this->assertEquals('', $this->metabox->createHtml([], []));
    }

    public function testCreateHtmlReturnsHtml()
    {
        $fieldData = [
            '_hide_pdfbutton' => [
                'name' => 'Name',
                'type' => 'checkbox',
                'default' => '',
                'description' => '',
            ],
        ];

        $fields = [
            '_hide_pdfbutton' => ['on'],
        ];

        \Brain\Monkey\Functions\stubs([
            'esc_attr' => '_hide_pdfbutton',
            'checked' => true,
            'wp_nonce_field' => '',
        ]);

        $html = $this->metabox->createHtml($fieldData, $fields);

        $this->assertContains('<input name="_hide_pdfbutton" type="checkbox"', $html);
    }

    public function testMetaboxContent()
    {
        \Brain\Monkey\Functions\stubs([
            'get_post_custom' => [],
            '__' => 1,
            'wp_create_nonce' => 'nonce',
            'esc_attr' => 1,
            'checked' => true,
            'wp_kses' => '',
            'wp_nonce_field' => '',
        ]);

//        \Brain\Monkey\Functions\expect('createHtml')
//            ->once()
//            ->with(\Mockery::type('array'), \Mockery::type('array'));

        $this->metabox->metaboxContent();

        $this->assertTrue(true);
    }
}
