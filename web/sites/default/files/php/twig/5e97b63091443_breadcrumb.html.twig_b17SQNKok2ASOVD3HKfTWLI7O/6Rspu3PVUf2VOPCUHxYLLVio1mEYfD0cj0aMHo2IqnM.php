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

/* themes/contrib/druadmin_lte_theme/templates/navigation/breadcrumb.html.twig */
class __TwigTemplate_14f8db7fcdc9cf17103bca62addbba7b41eef74a7661f3c36f93116adfc2b8c4 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 10, "set" => 12, "for" => 13];
        $filters = ["escape" => 17];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set', 'for'],
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
        // line 10
        if (($context["breadcrumb"] ?? null)) {
            // line 11
            echo "<ol class=\"breadcrumb\">
  ";
            // line 12
            $context["i"] = 0;
            // line 13
            echo "  ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["breadcrumb"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 14
                echo "      ";
                if ($this->getAttribute($context["item"], "text", [])) {
                    // line 15
                    echo "        ";
                    if ($this->getAttribute($context["item"], "url", [])) {
                        // line 16
                        echo "          <li>
            <a href=\"";
                        // line 17
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["item"], "url", [])), "html", null, true);
                        echo "\">
              ";
                        // line 18
                        if ((($context["i"] ?? null) == 0)) {
                            // line 19
                            echo "                <i class=\"fa fa-home\"></i>
              ";
                        }
                        // line 21
                        echo "              ";
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["item"], "text", [])), "html", null, true);
                        echo "
            </a>
          <li>
        ";
                    } else {
                        // line 25
                        echo "          <li class=\"active\">";
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["item"], "text", [])), "html", null, true);
                        echo "</li>
        ";
                    }
                    // line 27
                    echo "      ";
                }
                // line 28
                echo "      ";
                $context["i"] = (($context["i"] ?? null) + 1);
                // line 29
                echo "  ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 30
            echo "  ";
            if ((($context["title"] ?? null) &&  !($context["is_front"] ?? null))) {
                // line 31
                echo "    <li class=\"active\">";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null)), "html", null, true);
                echo "</li>
  ";
            }
            // line 33
            echo "</ol>
";
        }
    }

    public function getTemplateName()
    {
        return "themes/contrib/druadmin_lte_theme/templates/navigation/breadcrumb.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 33,  115 => 31,  112 => 30,  106 => 29,  103 => 28,  100 => 27,  94 => 25,  86 => 21,  82 => 19,  80 => 18,  76 => 17,  73 => 16,  70 => 15,  67 => 14,  62 => 13,  60 => 12,  57 => 11,  55 => 10,);
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
 * Theme override for a breadcrumb trail.
 *
 * Available variables:
 * - breadcrumb: Breadcrumb trail items.
 */
#}
{% if breadcrumb %}
<ol class=\"breadcrumb\">
  {% set i = 0 %}
  {% for item in breadcrumb %}
      {% if item.text %}
        {% if item.url %}
          <li>
            <a href=\"{{ item.url }}\">
              {% if i == 0 %}
                <i class=\"fa fa-home\"></i>
              {% endif %}
              {{ item.text }}
            </a>
          <li>
        {% else %}
          <li class=\"active\">{{ item.text }}</li>
        {% endif %}
      {% endif %}
      {% set i = i + 1 %}
  {% endfor %}
  {% if title and not is_front %}
    <li class=\"active\">{{ title }}</li>
  {% endif %}
</ol>
{% endif %}
", "themes/contrib/druadmin_lte_theme/templates/navigation/breadcrumb.html.twig", "/var/www/html/eln_mlbrepo/web/themes/contrib/druadmin_lte_theme/templates/navigation/breadcrumb.html.twig");
    }
}
