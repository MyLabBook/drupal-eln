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

/* modules/contrib/geolocation/templates/geolocation-common-map-display.html.twig */
class __TwigTemplate_c7dc5a943663b3c2e47635922c26b2a63dc9d118a6c66576bb2743e49bc3bd4d extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 1, "for" => 3];
        $filters = ["escape" => 1];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
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
        // line 1
        echo "<div id=\"";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["id"] ?? null)), "html", null, true);
        echo "\" class=\"geolocation-common-map\"  ";
        if (((( !twig_test_empty($this->getAttribute(($context["centre"] ?? null), "lat_north_east", [])) &&  !twig_test_empty($this->getAttribute(($context["centre"] ?? null), "lng_north_east", []))) &&  !twig_test_empty($this->getAttribute(($context["centre"] ?? null), "lat_south_west", []))) &&  !twig_test_empty($this->getAttribute(($context["centre"] ?? null), "lng_south_west", [])))) {
            echo " data-centre-lat-north-east=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["centre"] ?? null), "lat_north_east", [])), "html", null, true);
            echo "\" data-centre-lng-north-east=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["centre"] ?? null), "lng_north_east", [])), "html", null, true);
            echo "\" data-centre-lat-south-west=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["centre"] ?? null), "lat_south_west", [])), "html", null, true);
            echo "\" data-centre-lng-south-west=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["centre"] ?? null), "lng_south_west", [])), "html", null, true);
            echo "\" ";
        }
        echo " ";
        if (( !twig_test_empty($this->getAttribute(($context["centre"] ?? null), "lat", [])) &&  !twig_test_empty($this->getAttribute(($context["centre"] ?? null), "lng", [])))) {
            echo " data-centre-lat=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["centre"] ?? null), "lat", [])), "html", null, true);
            echo "\" data-centre-lng=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["centre"] ?? null), "lng", [])), "html", null, true);
            echo "\" ";
        }
        echo " ";
        if ( !twig_test_empty(($context["fitbounds"] ?? null))) {
            echo " data-fitbounds=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["fitbounds"] ?? null)), "html", null, true);
            echo "\" ";
        }
        echo " ";
        if ( !twig_test_empty(($context["clientlocation"] ?? null))) {
            echo " data-clientlocation=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["clientlocation"] ?? null)), "html", null, true);
            echo "\" ";
        }
        echo ">
    <div class=\"geolocation-common-map-locations\">
    ";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["locations"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["location"]) {
            // line 4
            echo "        ";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["location"]), "html", null, true);
            echo "
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['location'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 6
        echo "    </div>
    <div class=\"geolocation-common-map-container\"></div>
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/geolocation/templates/geolocation-common-map-display.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  106 => 6,  97 => 4,  93 => 3,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("<div id=\"{{ id }}\" class=\"geolocation-common-map\"  {% if centre.lat_north_east is not empty and centre.lng_north_east is not empty and centre.lat_south_west is not empty and centre.lng_south_west is not empty %} data-centre-lat-north-east=\"{{ centre.lat_north_east }}\" data-centre-lng-north-east=\"{{ centre.lng_north_east }}\" data-centre-lat-south-west=\"{{ centre.lat_south_west }}\" data-centre-lng-south-west=\"{{ centre.lng_south_west }}\" {% endif %} {% if centre.lat is not empty and centre.lng is not empty %} data-centre-lat=\"{{ centre.lat }}\" data-centre-lng=\"{{ centre.lng }}\" {% endif %} {% if fitbounds is not empty %} data-fitbounds=\"{{ fitbounds }}\" {% endif %} {% if clientlocation is not empty %} data-clientlocation=\"{{ clientlocation }}\" {% endif %}>
    <div class=\"geolocation-common-map-locations\">
    {%  for location in locations %}
        {{ location }}
    {% endfor %}
    </div>
    <div class=\"geolocation-common-map-container\"></div>
</div>
", "modules/contrib/geolocation/templates/geolocation-common-map-display.html.twig", "/var/www/html/eln_mlbrepo/web/modules/contrib/geolocation/templates/geolocation-common-map-display.html.twig");
    }
}
