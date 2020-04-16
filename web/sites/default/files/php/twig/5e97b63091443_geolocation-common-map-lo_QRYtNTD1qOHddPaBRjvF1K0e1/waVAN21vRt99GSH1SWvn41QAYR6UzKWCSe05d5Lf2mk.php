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

/* modules/contrib/geolocation/templates/geolocation-common-map-location.html.twig */
class __TwigTemplate_19ece851ee0e603582bd18166a7f8e671abcfa3d5327cb73cc9514dee7bdaaee extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 15];
        $filters = ["escape" => 12];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
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
        echo "<div
    class=\"geolocation\"
    data-lat=\"";
        // line 12
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["position"] ?? null), "lat", [])), "html", null, true);
        echo "\"
    data-lng=\"";
        // line 13
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["position"] ?? null), "lng", [])), "html", null, true);
        echo "\"
    typeof=\"Place\"
    ";
        // line 15
        if ( !twig_test_empty(($context["location_id"] ?? null))) {
            echo " data-location-id=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["location_id"] ?? null)), "html", null, true);
            echo "\" ";
        }
        // line 16
        echo "    ";
        if (twig_test_empty(($context["disable_marker"] ?? null))) {
            // line 17
            echo "        data-set-marker=\"true\"
        ";
            // line 18
            if ( !twig_test_empty(($context["icon"] ?? null))) {
                echo " data-icon=\"";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null)), "html", null, true);
                echo "\" ";
            }
            // line 19
            echo "        ";
            if ( !twig_test_empty(($context["marker_label"] ?? null))) {
                echo " data-marker-label=\"";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["marker_label"] ?? null)), "html", null, true);
                echo "\" ";
            }
            // line 20
            echo "    ";
        }
        // line 21
        echo ">
    <span property=\"geo\" typeof=\"GeoCoordinates\">
        <meta property=\"latitude\" content=\"";
        // line 23
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["position"] ?? null), "lat", [])), "html", null, true);
        echo "\" />
        <meta property=\"longitude\" content=\"";
        // line 24
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["position"] ?? null), "lng", [])), "html", null, true);
        echo "\" />
    </span>
    <h2 class=\"location-title\" property=\"name\">";
        // line 26
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null)), "html", null, true);
        echo "</h2>
    <div class=\"location-content\">";
        // line 27
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null)), "html", null, true);
        echo "</div>
</div>";
    }

    public function getTemplateName()
    {
        return "modules/contrib/geolocation/templates/geolocation-common-map-location.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  113 => 27,  109 => 26,  104 => 24,  100 => 23,  96 => 21,  93 => 20,  86 => 19,  80 => 18,  77 => 17,  74 => 16,  68 => 15,  63 => 13,  59 => 12,  55 => 10,);
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
  When overriding this template, make sure to preserve
  - CSS-classes
  - parent - children hierarchy
  - data-lng and data-lat attributes
  or the attached Geolocation Javascript will fail.

  Changing the HTML tags, adding classes or adding content around or within the existing structure is no problem.
#}
<div
    class=\"geolocation\"
    data-lat=\"{{ position.lat }}\"
    data-lng=\"{{ position.lng }}\"
    typeof=\"Place\"
    {% if location_id is not empty %} data-location-id=\"{{ location_id }}\" {% endif %}
    {% if disable_marker is empty %}
        data-set-marker=\"true\"
        {% if icon is not empty %} data-icon=\"{{ icon }}\" {% endif %}
        {% if marker_label is not empty %} data-marker-label=\"{{ marker_label }}\" {% endif %}
    {% endif %}
>
    <span property=\"geo\" typeof=\"GeoCoordinates\">
        <meta property=\"latitude\" content=\"{{ position.lat }}\" />
        <meta property=\"longitude\" content=\"{{ position.lng }}\" />
    </span>
    <h2 class=\"location-title\" property=\"name\">{{ title }}</h2>
    <div class=\"location-content\">{{ content }}</div>
</div>", "modules/contrib/geolocation/templates/geolocation-common-map-location.html.twig", "/var/www/html/eln_mlbrepo/web/modules/contrib/geolocation/templates/geolocation-common-map-location.html.twig");
    }
}
