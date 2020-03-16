<?php

namespace ProtoneMedia\BladeOnDemand;

use Illuminate\Mail\Markdown;
use Illuminate\Support\Str;
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

    /**
     * Wether to fill the missing variables from the template.
     *
     * @var boolean
     */
    private $fillMissingVariables = false;

    /**
     * The current theme being used when generating emails.
     *
     * @var string
     */
    private $theme = 'default';

    public function __construct(ViewFactory $viewFactory, Markdown $markdown, CssToInlineStyles $cssInliner)
    {
        $this->viewFactory = $viewFactory;
        $this->markdown    = $markdown;
        $this->cssInliner  = $cssInliner;

        $this->theme = config('mail.markdown.theme', 'default');
    }

    /**
     * Fills the missing variables in the template
     *
     * @param callable $callback
     * @return $this
     */
    public function fillMissingVariables(callable $callback = null)
    {
        $this->fillMissingVariables = $callback ?: true;

        return $this;
    }

    /**
     * Set the default theme to be used.
     *
     * @param  string  $theme
     * @return $this
     */
    public function theme($theme)
    {
        $this->theme = $theme;

        return $this;
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
        if ($this->fillMissingVariables) {
            $data = $this->addMissingVariables($contents, $data);
        }

        file_put_contents(
            $path = tempnam(sys_get_temp_dir(), 'blade-on-demand') . '.blade.php',
            $contents
        );

        $this->viewFactory->flushFinderCache();

        return tap($this->viewFactory->file($path, $data)->render(), function () use ($path) {
            unlink($path);

            $this->fillMissingVariables = false;
            $this->theme = config('mail.markdown.theme', 'default');
        });
    }

    /**
     * Finds all missing variables.
     * Source: https://stackoverflow.com/a/19563063
     *
     * @param string $contents
     * @param array $data
     * @return array
     */
    public function getMissingVariables(string $contents, array $data = []): array
    {
        $pattern = '/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/';

        preg_match_all($pattern, $contents, $matches);

        return $matches[1] ?? [];
    }

    /**
     * Makes sure each variable is present in the data array.
     *
     * @param string $contents
     * @param array $data
     * @return array
     */
    private function addMissingVariables(string $contents, array $data = []): array
    {
        foreach (static::getMissingVariables($contents, $data) as $variable) {
            if (array_key_exists($variable, $data)) {
                continue;
            }

            if (!is_callable($this->fillMissingVariables)) {
                $data[$variable] = $variable;
                continue;
            }

            $data[$variable] = call_user_func_array($this->fillMissingVariables, [$variable]);
        }

        return $data;
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

        $theme = Str::contains($this->theme, '::')
            ? $this->theme
            : 'mail::themes.' . $this->theme;

        $rendered = $this->render($contents, $data);

        return $this->cssInliner->convert(
            $rendered,
            $this->viewFactory->make($theme)->render()
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
