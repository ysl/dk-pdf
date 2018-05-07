<?php # -*- coding: utf-8 -*-
declare(strict_types=1);

namespace Dinamiko\DKPDF;

class TemplateLoader
{

    /**
     * @var string
     */
    protected $filterPrefix = 'dkpdf';

    /**
     * @var string
     */
    protected $themeTemplateDirectory = 'dkpdf';

    /**
     * @var string
     */
    protected $pluginDirectory = DKPDF_PLUGIN_DIR;

    /**
     * @var string
     */
    protected $pluginTemplateDirectory = 'templates';

    /**
     * Get template part.
     * @param string $slug
     * @param string $name
     * @param bool $load
     * @return bool|string
     */
    public function templatePart(string $slug, string $name = '', bool $load = true)
    {
        do_action('get_template_part_' . $slug, $slug, $name);

        $templates = $this->templateFileNames($slug, $name);

        return $this->locateTemplate($templates, $load, false);
    }

    /**
     * Get template file names.
     * @param string $slug
     * @param string $name
     * @return array
     */
    protected function templateFileNames(string $slug, string $name)
    {
        $templates = [];

        if (isset($name)) {
            $templates[] = $slug . '-' . $name . '.php';
        }

        $templates[] = $slug . '.php';

        return apply_filters($this->filterPrefix . '_get_template_part', $templates, $slug, $name);
    }

    /**
     * Locate template.
     * @param array $templateNames
     * @param bool $load
     * @param bool $requireOnce
     * @return bool|string
     */
    public function locateTemplate(
        array $templateNames,
        bool $load = false,
        bool $requireOnce = true
    )
    {
        $located = false;

        // Remove empty entries
        $templateNames = array_filter((array)$templateNames);
        $templatePaths = $this->getTemplatePaths();

        // Try to find a template file
        foreach ($templateNames as $templateName) {

            // Trim off any slashes from the template name
            $templateName = ltrim($templateName, '/');

            // Try locating this template file by looping through the template paths
            foreach ($templatePaths as $template_path) {

                if (file_exists($template_path . $templateName)) {

                    $located = $template_path . $templateName;
                    break 2;

                }

            }
        }

        if ($load && $located) {
            load_template($located, $requireOnce);
        }

        return $located;
    }

    /**
     * Get template paths.
     * @return array
     */
    protected function getTemplatePaths(): array
    {
        $themeDirectory = trailingslashit($this->themeTemplateDirectory);

        $filePaths = [
            10 => trailingslashit(get_template_directory()) . $themeDirectory,
            100 => $this->getTemplatesDir(),
        ];

        // Only add this conditionally, so non-child themes don't redundantly check active theme twice.
        if (is_child_theme()) {
            $filePaths[1] = trailingslashit(get_stylesheet_directory()) . $themeDirectory;
        }

        $filePaths = apply_filters($this->filterPrefix . '_template_paths', $filePaths);

        // sort the file paths based on priority
        ksort($filePaths, SORT_NUMERIC);

        return array_map('trailingslashit', $filePaths);
    }

    /**
     * Get templates directory.
     * @return string
     */
    protected function getTemplatesDir(): string
    {
        return trailingslashit($this->pluginDirectory) . $this->pluginTemplateDirectory;
    }
}
