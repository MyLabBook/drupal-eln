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

/* core/themes/stable/templates/admin/admin-block.html.twig */
class __TwigTemplate_c80a5fdd2c92e94175e45bf183a2dc279246b587e7ee990a51492dec75c704ac extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 17, "if" => 22];
        $filters = ["escape" => 21];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape'],
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
        // line 17
        $context["classes"] = [0 => "panel"];
        // line 21
        echo "<div";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method")), "html", null, true);
        echo ">
  ";
        // line 22
        if ($this->getAttribute(($context["block"] ?? null), "title", [])) {
            // line 23
            echo "    <h3 class=\"panel__title\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["block"] ?? null), "title", [])), "html", null, true);
            echo "</h3>
  ";
        }
        // line 25
        echo "  ";
        if ($this->getAttribute(($context["block"] ?? null), "content", [])) {
            // line 26
            echo "    <div class=\"panel__content\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["block"] ?? null), "content", [])), "html", null, true);
            echo "</div>
  ";
        } elseif ($this->getAttribute(        // line 27
($context["block"] ?? null), "description", [])) {
            // line 28
            echo "    <div class=\"panel__description\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["block"] ?? null), "description", [])), "html", null, true);
            echo "</div>
  ";
        }
        // line 30
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "core/themes/stable/templates/admin/admin-block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  86 => 30,  80 => 28,  78 => 27,  73 => 26,  70 => 25,  64 => 23,  62 => 22,  57 => 21,  55 => 17,);
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
 * Theme override for an administrative block.
 *
 * Available variables:
 * - block: An array of information about the block, including:
 *   - show: A flag indicating if the block should be displayed.
 *   - title: The block title.
 *   - content: (optional) The content of the block.
 *   - description: (optional) A description of the block.
 *     (Description should only be output if content is not available).
 * - attributes: HTML attributes for the containing div element.
 */
#}
{%
  set classes = [
    'panel',
  ]
%}
<div{{ attributes.addClass(classes) }}>
  {% if block.title %}
    <h3 class=\"panel__title\">{{ block.title }}</h3>
  {% endif %}
  {% if block.content %}
    <div class=\"panel__content\">{{ block.content }}</div>
  {% elseif block.description %}
    <div class=\"panel__description\">{{ block.description }}</div>
  {% endif %}
</div>
", "core/themes/stable/templates/admin/admin-block.html.twig", "/var/www/html/eln_mlbrepo/web/core/themes/stable/templates/admin/admin-block.html.twig");
    }
}
