<?php

namespace App\TwigExtensions;

use Exception;
use Timber\Twig_Filter;
use Timber\Twig_Function;
use Timber\URLHelper;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

class ImageExtension extends AbstractExtension
{
    /**
     * Adds functionality to Twig.
     *
     * @param Environment $twig The Twig environment.
     * @return Environment
     */
    public static function register(Environment $twig): Environment
    {
        $twig->addFunction(new Twig_Function("getAspectRatio", [__CLASS__, 'getAspectRatio']));
        $twig->addFunction(new Twig_Function("determineMediaAspectRatio", [__CLASS__, 'determineMediaAspectRatio']));
        $twig->addFilter(new Twig_Filter("svg", [__CLASS__, 'getSvg']));
        return $twig;
    }

    public static function getAspectRatio($aspect_ratio_string): ?array
    {
        if (!is_string(value: $aspect_ratio_string || empty($aspect_ratio_string))) {
            return null;
        }

        $parts = explode('/', $aspect_ratio_string);
        $width = trim($parts[0]);
        $height = trim($parts[1]);

        return count($parts) === 2 && is_numeric($width) && is_numeric($height) && $width > 0 && $height > 0 ? compact('width', 'height') : null;
    }

    public static function determineMediaAspectRatio($mediaRatio): string
    {
        $aspectRatio = '';

        if (isset($mediaRatio) && $mediaRatio['has_ratio']) {
            if ($mediaRatio['ratio'] == "square") {
                $aspectRatio = '1/1';
            } elseif ($mediaRatio['ratio'] == "paysage" || $mediaRatio['ratio'] == "portrait") {
                if ($mediaRatio['aspect_ratio'] == "aspect-16/9") {
                    $aspectRatio = ($mediaRatio['ratio'] == "paysage") ? '16/9' : '9/16';
                } elseif ($mediaRatio['aspect_ratio'] == "aspect-3/2") {
                    $aspectRatio = ($mediaRatio['ratio'] == "paysage") ? '3/2' : '2/3';
                } else {
                    $aspectRatio = ($mediaRatio['ratio'] == "paysage") ? '4/3' : '3/4';
                }
            }
        }

        return $aspectRatio;
    }

    /**
     * Retrieve and manipulate the contents of an SVG file.
     *
     * @param string $path    The path or URL to the SVG file.
     * @param array  $params  Optional parameters to modify the SVG (e.g., attributes or classes).
     *
     * @return string|null    The SVG content as a string or null if an error occurs.
     * @throws Exception      If the file does not exist or is not a valid SVG file.
     */
    public static function getSvg($path, $params = []): ?string
    {
        // Validate the input path
        if (!is_string($path) || empty($path)) {
            throw new Exception("Invalid SVG path provided.");
        }

        // Normalize the path for Bedrock installations
        $imagePath = UrlHelper::url_to_file_system($path);
        if (defined('WP_CONTENT_DIR')) {
            $imagePath = str_replace('/wp', '', $imagePath);
        }

        // Check if the file exists and is an SVG
        $fileInfo = pathinfo($imagePath);
        if (!file_exists($imagePath) || strtolower($fileInfo['extension']) !== 'svg') {
            throw new Exception("SVG file not found.");
        }

        // Get the SVG content of the file
        $svgString = file_get_contents($imagePath);

        // Check if custom attributes or classes need to be added
        if (array_key_exists('attr', $params) || array_key_exists('classes', $params)) {
            // Load the SVG as a SimpleXMLElement with error handling
            libxml_use_internal_errors(true);
            $svg = simplexml_load_string($svgString);
            if ($svg === false) {
                throw new Exception("Error loading SVG.");
            }
            $attrs = $svg->attributes();

            // Add custom attributes
            if (array_key_exists('attr', $params)) {
                foreach ($params['attr'] as $key => $value) {
                    if ($attrs[$key]) {
                        $attrs[$key] = $value;
                    } else {
                        $svg->addAttribute($key, $value);
                    }
                }
            }

            // Add custom classes
            if (array_key_exists('classes', $params)) {
                if ($attrs['class']) {
                    $attrs['class'] .= ' ' . $params['classes'];
                } else {
                    $svg->addAttribute('class', $params['classes']);
                }
            }

            // Remove the XML version added by asXML method
            $svgString = str_replace("<?xml version=\"1.0\"?>\n", '', $svg->asXML());
        }

        return $svgString;
    }
}
