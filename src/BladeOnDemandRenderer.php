<?php

namespace ProtoneMedia\BladeOnDemand;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Mail\Markdown;
use Illuminate\Support\HtmlString;
use Illuminate\View\Factory as ViewFactory;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class BladeOnDemandRenderer
{
    /**
     * @var \Illuminate\View\Factory
     */
    private $viewFactory;

    /**
     * @var \Illuminate\Mail\Markdown
     */
    private $markdown;

    /**
     * @var \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles
     */
    private $cssInliner;

    public function __construct(ViewFactory $viewFactory, Markdown $markdown, CssToInlineStyles $cssInliner)
    {
        $this->viewFactory = $viewFactory;
        $this->markdown    = $markdown;
        $this->cssInliner  = $cssInliner;
    }

    /**
     * Renders the content with the given data.
     *
     * @param string $contents
     * @param array $data
     * @return string
     */
    public function render(string $contents, array $data = []): string
    {
        file_put_contents(
            $path = tempnam(sys_get_temp_dir(), 'blade-on-demand') . '.blade.php',
            $contents
        );

        $this->viewFactory->flushFinderCache();

        return tap($this->viewFactory->file($path, $data)->render(), function () use ($path) {
            unlink($path);
        });
    }

    /**
     * Renders the markdown content to a HTML mail.
     *
     * @param string $contents
     * @param array $data
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function renderMarkdownMail(string $contents, array $data = []): Htmlable
    {
        $this->viewFactory->replaceNamespace('mail', $this->markdown->htmlComponentPaths());

        $rendered = $this->render($contents, $data);

        $renderedWithInlineCss = $this->cssInliner->convert(
            $rendered,
            $this->viewFactory->make('mail::themes.' . config('mail.markdown.theme', 'default'))->render()
        );

        return new HtmlString($renderedWithInlineCss);
    }

    /**
     * Renders the markdown content to a Text mail.
     *
     * @param string $contents
     * @param array $data
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function renderMarkdownText(string $contents, array $data = []): Htmlable
    {
        $this->viewFactory->replaceNamespace('mail', $this->markdown->textComponentPaths());

        $rendered = $this->render($contents, $data);

        return new HtmlString(
            html_entity_decode(preg_replace("/[\r\n]{2,}/", "\n\n", $rendered), ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Parses the markdown content.
     *
     * @param string $contents
     * @param array $data
     * @return Htmlable
     */
    public function parseMarkdownText(string $contents, array $data = []): Htmlable
    {
        return $this->markdown->parse($this->renderMarkdownText($contents, $data));
    }
}
