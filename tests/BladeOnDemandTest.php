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

        $rendered = BladeOnDemand::render(
            '@if($hello) Hello @else Bye @endif{{ $name }}',
            ['name' => 'Protone Media', 'hello' => false]
        );

        $this->assertEquals('Bye Protone Media', $rendered);
    }

    /** @test */
    public function it_can_fill_the_missing_variables_in_the_template()
    {
        $rendered = BladeOnDemand::fillMissingVariables()->render(
            'Hello {{ $name }}'
        );

        $this->assertEquals('Hello name', $rendered);
    }

    /** @test */
    public function it_can_fill_the_missing_variables_in_the_template_with_a_custom_value()
    {
        $rendered = BladeOnDemand::fillMissingVariables(function ($variable) {
            return "_MISSING_{$variable}_MISSING_";
        })->render(
            'Hello {{ $name }}',
        );

        $this->assertEquals('Hello _MISSING_name_MISSING_', $rendered);
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
