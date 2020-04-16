<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* core/themes/stable/templates/admin/update-version.html.twig */
class __TwigTemplate_95f43788c20f71c76f779a2bf8f53a3610171df27d68d6ef96a9759a1550544c extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 35];
        $filters = ["escape" => 26, "without" => 26, "date" => 31, "t" => 37];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 'without', 'date', 't'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 26
        echo "<div class=\"";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["attributes"] ?? null), "class", [])), "html", null, true);
        echo " project-update__version\"";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\Core\Template\TwigExtension')->withoutFilter($this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null)), "class"), "html", null, true);
        echo ">
  <div class=\"clearfix\">
    <div class=\"project-update__version-title layout-column layout-column--quarter\">";
        // line 28
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null)), "html", null, true);
        echo "</div>
    <div class=\"project-update__version-details layout-column layout-column--quarter\">
      <a href=\"";
        // line 30
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["version"] ?? null), "release_link", [])), "html", null, true);
        echo "\">";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["version"] ?? null), "version", [])), "html", null, true);
        echo "</a>
      <span class=\"project-update__version-date\">(";
        // line 31
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["version"] ?? null), "date", [])), "Y-M-d"), "html", null, true);
        echo ")</span>
    </div>
    <div class=\"layout-column layout-column--half\">
      <ul class=\"project-update__version-links\">
        ";
        // line 35
        if (( !$this->getAttribute(($context["version"] ?? null), "core_compatible", [], "any", true, true) || $this->getAttribute(($context["version"] ?? null), "core_compatible", []))) {
            // line 36
            echo "          <li class=\"project-update__download-link\">
            <a href=\"";
            // line 37
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["version"] ?? null), "download_link", [])), "html", null, true);
            echo "\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Download"));
            echo "</a>
          </li>
        ";
        }
        // line 40
        echo "        <li class=\"project-update__release-notes-link\">
          <a href=\"";
        // line 41
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["version"] ?? null), "release_link", [])), "html", null, true);
        echo "\">";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Release notes"));
        echo "</a>
        </li>
        ";
        // line 43
        if (($context["core_compatibility_details"] ?? null)) {
            // line 44
            echo "          <li class=\"project-update__compatibility-details\">
            ";
            // line 45
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["core_compatibility_details"] ?? null)), "html", null, true);
            echo "
          </li>
        ";
        }
        // line 48
        echo "      </ul>
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "core/themes/stable/templates/admin/update-version.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  115 => 48,  109 => 45,  106 => 44,  104 => 43,  97 => 41,  94 => 40,  86 => 37,  83 => 36,  81 => 35,  74 => 31,  68 => 30,  63 => 28,  55 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Theme override for the version display of a project.
 *
 * Available variables:
 * - attributes: HTML attributes suitable for a container element.
 * - title: The title of the project.
 * - core_compatibility_details: Render array of core compatibility details.
 * - version:  A list of data about the latest released version, containing:
 *   - version: The version number.
 *   - date: The date of the release.
 *   - download_link: The URL for the downloadable file.
 *   - release_link: The URL for the release notes.
 *   - core_compatible: A flag indicating whether the project is compatible
 *     with the currently installed version of Drupal core. This flag is not
 *     set for the Drupal core project itself.
 *   - core_compatibility_message: A message indicating the versions of Drupal
 *     core with which this project is compatible. This message is also
 *     contained within the 'core_compatibility_details' variable documented
 *     above. This message is not set for the Drupal core project itself.
 *
 * @see template_preprocess_update_version()
 */
#}
<div class=\"{{ attributes.class }} project-update__version\"{{ attributes|without('class') }}>
  <div class=\"clearfix\">
    <div class=\"project-update__version-title layout-column layout-column--quarter\">{{ title }}</div>
    <div class=\"project-update__version-details layout-column layout-column--quarter\">
      <a href=\"{{ version.release_link }}\">{{ version.version }}</a>
      <span class=\"project-update__version-date\">({{ version.date|date('Y-M-d') }})</span>
    </div>
    <div class=\"layout-column layout-column--half\">
      <ul class=\"project-update__version-links\">
        {% if version.core_compatible is not defined or version.core_compatible %}
          <li class=\"project-update__download-link\">
            <a href=\"{{ version.download_link }}\">{{ 'Download'|t }}</a>
          </li>
        {% endif %}
        <li class=\"project-update__release-notes-link\">
          <a href=\"{{ version.release_link }}\">{{ 'Release notes'|t }}</a>
        </li>
        {% if core_compatibility_details %}
          <li class=\"project-update__compatibility-details\">
            {{ core_compatibility_details }}
          </li>
        {% endif %}
      </ul>
    </div>
  </div>
</div>
", "core/themes/stable/templates/admin/update-version.html.twig", "/var/www/html/eln_mlbrepo/web/core/themes/stable/templates/admin/update-version.html.twig");
    }
}
