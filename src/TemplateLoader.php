<?php

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
     * @return string
     */
    public function part($slug)
    {
        do_action('get_template_part_' . $slug, $slug);

        $templates = $this->templateFileNames($slug);

        return $this->locateTemplate($templates);
    }

    /**
     * Get template file names.
     * @param string $slug
     * @return array
     */
    protected function templateFileNames($slug)
    {
        $templates[] = $slug . '.php';

        return apply_filters($this->filterPrefix . '_get_template_part', $templates, $slug);
    }

    /**
     * Locate template.
     * @param array $templateNames
     * @return string
     */
    public function locateTemplate(array $templateNames) {

        $template = '';

        // Remove empty entries
        $templateNames = array_filter((array)$templateNames);
        $templatePaths = $this->getTemplatePaths();

        // Try to find a template file
        foreach ($templateNames as $templateName) {
            // Trim off any slashes from the template name
            $templateName = ltrim($templateName, '/');

            // Try locating this template file by looping through the template paths
            foreach ($templatePaths as $templatePath) {
                if (file_exists($templatePath . $templateName)) {
                    $template = $templatePath . $templateName;
                    break 2;
                }
            }
        }

        if ($template) {
            load_template($template);
        }

        return $template;
    }

    /**
     * Get template paths.
     * @return array
     */
    protected function getTemplatePaths()
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

        // Sort the file paths based on priority
        ksort($filePaths, SORT_NUMERIC);

        return array_map('trailingslashit', $filePaths);
    }

    /**
     * Get templates directory.
     * @return string
     */
    protected function getTemplatesDir()
    {
        return trailingslashit($this->pluginDirectory) . $this->pluginTemplateDirectory;
    }
}
