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

/* themes/contrib/druadmin_lte_theme/templates/navigation/menu.html.twig */
class __TwigTemplate_c670e0c6b361da8c052472e5c6320c49b90b88bb4d7c3b85cf9f830c576d98a5 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["import" => 16, "macro" => 24, "if" => 27, "for" => 31];
        $filters = ["escape" => 37];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['import', 'macro', 'if', 'for'],
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
        // line 16
        $context["menus"] = $this;
        // line 17
        echo "
";
        // line 22
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($context["menus"]->getmenu_links(($context["items"] ?? null), ($context["attributes"] ?? null), 0));
        echo "

";
    }

    // line 24
    public function getmenu_links($__items__ = null, $__attributes__ = null, $__menu_level__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "items" => $__items__,
            "attributes" => $__attributes__,
            "menu_level" => $__menu_level__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 25
            echo "  ";
            $context["menus"] = $this;
            // line 26
            echo "  
  ";
            // line 27
            if (($context["items"] ?? null)) {
                // line 28
                echo "    ";
                if ((($context["menu_level"] ?? null) != 0)) {
                    // line 29
                    echo "      <ul class=\"treeview-menu\">
    ";
                }
                // line 31
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 32
                    echo "      ";
                    if ($this->getAttribute($context["item"], "below", [])) {
                        // line 33
                        echo "        <li class=\"treeview\">
      ";
                    } else {
                        // line 35
                        echo "        <li>
      ";
                    }
                    // line 37
                    echo "          <a href=\"";
                    echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["item"], "url", [])), "html", null, true);
                    echo "\">
            <i class=\"fa ";
                    // line 38
                    if ($this->getAttribute($context["item"], "icon", [])) {
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["item"], "icon", [])), "html", null, true);
                    } else {
                        echo "fa-circle-o";
                    }
                    echo "\"></i>
            <span>";
                    // line 39
                    echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["item"], "title", [])), "html", null, true);
                    echo "</span>
            ";
                    // line 40
                    if ($this->getAttribute($context["item"], "below", [])) {
                        // line 41
                        echo "              <i class=\"fa fa-angle-left pull-right\"></i>
            ";
                    }
                    // line 43
                    echo "          </a>
          ";
                    // line 44
                    if ($this->getAttribute($context["item"], "below", [])) {
                        // line 45
                        echo "            ";
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($context["menus"]->getmenu_links($this->getAttribute($context["item"], "below", []), ($context["attributes"] ?? null), (($context["menu_level"] ?? null) + 1)));
                        echo "
          ";
                    }
                    // line 47
                    echo "        </li>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 49
                echo "    ";
                if ((($context["menu_level"] ?? null) != 0)) {
                    // line 50
                    echo "      </ul>
    ";
                }
                // line 52
                echo "  ";
            }
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "themes/contrib/druadmin_lte_theme/templates/navigation/menu.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  160 => 52,  156 => 50,  153 => 49,  146 => 47,  140 => 45,  138 => 44,  135 => 43,  131 => 41,  129 => 40,  125 => 39,  117 => 38,  112 => 37,  108 => 35,  104 => 33,  101 => 32,  96 => 31,  92 => 29,  89 => 28,  87 => 27,  84 => 26,  81 => 25,  67 => 24,  60 => 22,  57 => 17,  55 => 16,);
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
 * Theme override to display a menu.
 *
 * Available variables:
 * - menu_name: The machine name of the menu.
 * - items: A nested list of menu items. Each menu item contains:
 *   - attributes: HTML attributes for the menu item.
 *   - below: The menu item child items.
 *   - title: The menu link title.
 *   - url: The menu link url, instance of \\Drupal\\Core\\Url
 *   - localized_options: Menu link localized options.
 */
#}
{% import _self as menus %}

{#
  We call a macro which calls itself to render the full tree.
  @see http://twig.sensiolabs.org/doc/tags/macro.html
#}
{{ menus.menu_links(items, attributes, 0) }}

{% macro menu_links(items, attributes, menu_level) %}
  {% import _self as menus %}
  
  {% if items %}
    {% if menu_level != 0 %}
      <ul class=\"treeview-menu\">
    {% endif %}
      {% for item in items %}
      {% if item.below %}
        <li class=\"treeview\">
      {% else %}
        <li>
      {% endif %}
          <a href=\"{{ item.url }}\">
            <i class=\"fa {% if item.icon %}{{ item.icon }}{% else %}fa-circle-o{% endif %}\"></i>
            <span>{{ item.title }}</span>
            {% if item.below %}
              <i class=\"fa fa-angle-left pull-right\"></i>
            {% endif %}
          </a>
          {% if item.below %}
            {{ menus.menu_links(item.below, attributes, menu_level + 1) }}
          {% endif %}
        </li>
      {% endfor %}
    {% if menu_level != 0 %}
      </ul>
    {% endif %}
  {% endif %}
{% endmacro %}
", "themes/contrib/druadmin_lte_theme/templates/navigation/menu.html.twig", "/var/www/html/eln_mlbrepo/web/themes/contrib/druadmin_lte_theme/templates/navigation/menu.html.twig");
    }
}
