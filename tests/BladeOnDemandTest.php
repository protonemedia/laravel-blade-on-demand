<?php

namespace Protonemedia\BladeOnDemand\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use ProtoneMedia\BladeOnDemand\BladeOnDemandServiceProvider;
use ProtoneMedia\BladeOnDemand\Facades\BladeOnDemand;

class BladeOnDemandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BladeOnDemandServiceProvider::class];
    }

    /** @test */
    public function it_can_render_a_blade_template()
    {
        $rendered = BladeOnDemand::render(
            'Hello {{ $name }}',
            ['name' => 'Protone Media']
        );

        $this->assertEquals('Hello Protone Media', $rendered);
    }

    /** @test */
    public function it_can_a_markdown_mail()
    {
        $contents = implode(PHP_EOL, [
            '@component("mail::message")',
            '# Hello {{ $name }}',
            '@endcomponent',
        ]);

        $rendered = BladeOnDemand::renderMarkdownMailToHtml(
            $contents,
            ['name' => 'Protone Media']
        );

        $this->assertTrue(Str::contains($rendered, '<html'));
        $this->assertTrue(Str::contains($rendered, 'Hello Protone Media</h1>'));
    }

    /** @test */
    public function it_can_render_a_markdown_text()
    {
        $contents = implode(PHP_EOL, [
            '@component("mail::message")',
            '# Hello {{ $name }}',
            '@endcomponent',
        ]);

        $rendered = BladeOnDemand::renderMarkdownMailToText(
            $contents,
            ['name' => 'Protone Media']
        );

        $this->assertTrue(Str::contains($rendered, '# Hello Protone Media'));
        $this->assertFalse(Str::contains($rendered, '<html'));
    }

    /** @test */
    public function it_can_parse_markdown_text()
    {
        $contents = implode(PHP_EOL, [
            '@component("mail::message")',
            '# Hello {{ $name }}',
            '@endcomponent',
        ]);

        $rendered = BladeOnDemand::parseMarkdownMail(
            $contents,
            ['name' => 'Protone Media']
        );

        $this->assertTrue(Str::contains($rendered, '<h1>Hello Protone Media</h1>'));
        $this->assertFalse(Str::contains($rendered, '<html'));
    }
}
