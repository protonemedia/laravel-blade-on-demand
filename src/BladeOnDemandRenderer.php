<?php

namespace ProtoneMedia\BladeOnDemand;

use Illuminate\Mail\Markdown;
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
     * @return string
     */
    public function renderMarkdownMailToHtml(string $contents, array $data = []): string
    {
        $this->viewFactory->replaceNamespace('mail', $this->markdown->htmlComponentPaths());

        $rendered = $this->render($contents, $data);

        return $this->cssInliner->convert(
            $rendered,
            $this->viewFactory->make('mail::themes.' . config('mail.markdown.theme', 'default'))->render()
        );
    }

    /**
     * Renders the markdown content to a Text mail.
     *
     * @param string $contents
     * @param array $data
     * @return string
     */
    public function renderMarkdownMailToText(string $contents, array $data = []): string
    {
        $this->viewFactory->replaceNamespace('mail', $this->markdown->textComponentPaths());

        $rendered = $this->render($contents, $data);

        return html_entity_decode(preg_replace("/[\r\n]{2,}/", "\n\n", $rendered), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Parses the markdown content.
     *
     * @param string $contents
     * @param array $data
     * @return string
     */
    public function parseMarkdownMail(string $contents, array $data = []): string
    {
        return $this->markdown->parse($this->renderMarkdownMailToText($contents, $data));
    }
}
