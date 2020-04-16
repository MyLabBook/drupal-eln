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

/* themes/contrib/druadmin_lte_theme/templates/node/node--protocol_step.html.twig */
class __TwigTemplate_ca24a781e5d3638e128b70050580878847f4cd4a21aea1dd1e7d6df6ace208b6 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 16, "if" => 37, "for" => 54, "autoescape" => 410];
        $filters = ["escape" => 34, "raw" => 101, "length" => 332, "replace" => 683];
        $functions = ["devel_breakpoint" => 241, "breakpoint" => 243, "range" => 343, "drupal_view" => 376];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for', 'autoescape'],
                ['escape', 'raw', 'length', 'replace'],
                ['devel_breakpoint', 'breakpoint', 'range', 'drupal_view']
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
        // line 13
        echo "

";
        // line 16
        $context["exp_name"] = $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_parent_experiment", []), "entity", []), "title", []), "value", []);
        // line 17
        $context["exp_nid"] = $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_parent_experiment", []), "entity", []), "nid", []), "value", []);
        // line 18
        $context["exp_status"] = $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_parent_experiment", []), "entity", []), "field_completion_status", []), "value", []);
        // line 19
        echo "
<div class=\"row\"
     style=\"border: 3px solid #0a7bc1; padding-left: 10px; padding-right: 10px; padding-bottom: 20px;
            background-color: #f4f4f4; margin-left: 15px; margin-right: 15px;\">

  <h3>The Experiment </h3>

  <div class=\"row\">

    <div class=\"col-md-12\">
      <div class=\"card h-100\" style=\"border: 4px solid #028ee1; background-color: #fff; \">

        <div class=\"card-body\"
             style=\"text-align: center; margin-top: 20px; margin-bottom: 20px; margin-left: 15px; margin-right: 15px;\">
          <h3 class=\"card-title\" style=\"text-align: center; margin-top: 10px;\">
            <a href=\"/node/";
        // line 34
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["exp_nid"] ?? null)), "html", null, true);
        echo "\"><b> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["exp_name"] ?? null)), "html", null, true);
        echo " </b></a>
          </h3>
          <div style=\"text-align: center\">
            ";
        // line 37
        if ((($context["exp_status"] ?? null) == 0)) {
            // line 38
            echo "              Status: <b> Incomplete </b>
            ";
        } elseif ((        // line 39
($context["exp_status"] ?? null) == 1)) {
            // line 40
            echo "              Status: <b> Complete </b>
            ";
        }
        // line 42
        echo "          </div>
        </div>
      </div>
    </div>

  </div>

  <h3>The Protocol Steps </h3>

  <div class=\"row\">

    ";
        // line 53
        $context["step_num"] = 1;
        // line 54
        echo "    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_parent_experiment", []), "entity", []), "field_protocol_steps", []));
        foreach ($context['_seq'] as $context["_key"] => $context["step"]) {
            // line 55
            echo "
      <div class=\"col-md-4\">
        <div class=\"card h-100\" style=\"border: 2px solid #028ee1; background-color: #fff; margin: 15px; \">

          <div class=\"card-body\"
               style=\"text-align: center; margin-top: 20px; margin-bottom: 20px; margin-left: 15px; margin-right: 15px;\">
            <h4 class=\"card-title\">
              ";
            // line 62
            $context["step_name"] = $this->getAttribute($this->getAttribute($this->getAttribute($context["step"], "entity", []), "title", []), "value", []);
            // line 63
            echo "              <a href=\"/node/";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($this->getAttribute($context["step"], "entity", []), "nid", []), "value", [])), "html", null, true);
            echo "\">
                <b> ";
            // line 64
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["step_num"] ?? null)), "html", null, true);
            echo ". ";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["step_name"] ?? null)), "html", null, true);
            echo " </b>
              </a>
            </h4>
            <div style=\"text-align: center\">
              ";
            // line 68
            if (($this->getAttribute($this->getAttribute($this->getAttribute($context["step"], "entity", []), "field_completion_status", []), "value", []) == 0)) {
                // line 69
                echo "                Status: <b> Incomplete </b>
              ";
            } elseif (($this->getAttribute($this->getAttribute($this->getAttribute(            // line 70
$context["step"], "entity", []), "field_completion_status", []), "value", []) == 1)) {
                // line 71
                echo "                Status: <b> Complete </b>
              ";
            }
            // line 73
            echo "            </div>
          </div>
        </div>
      </div>
      ";
            // line 77
            $context["step_num"] = (($context["step_num"] ?? null) + 1);
            // line 78
            echo "
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['step'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 80
        echo "  </div>

</div>


<p>
  ";
        // line 86
        echo " &nbsp;
</p>


<h2>Protocol Step: &nbsp; &nbsp; &nbsp; <b>  ";
        // line 90
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "title", []), "value", [])), "html", null, true);
        echo " </b></h2>

  ";
        // line 92
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_completion_status", []), "value", []) == 0)) {
            // line 93
            echo "    <h4><em>Overall Step Status: <b> Incomplete </b> </em></h4>
  ";
        } elseif (($this->getAttribute($this->getAttribute(        // line 94
($context["node"] ?? null), "field_completion_status", []), "value", []) == 1)) {
            // line 95
            echo "    <h4><em>Overall Step Status: <b> Complete </b> </em></h4>
  ";
        }
        // line 97
        echo "
  <hr>

  ";
        // line 101
        echo "  ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "body", []), "value", [])));
        echo "

<div class=\"tabs\">

  <ul class=\"nav nav-tabs\">

    ";
        // line 107
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_task_list", []), "value", []) == 1)) {
            // line 108
            echo "      ";
            $context["taskListLabel"] = "Task List";
            // line 109
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_task_list", []), "value", []))) {
                // line 110
                echo "        ";
                $context["taskListLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_task_list", []), "value", []);
                // line 111
                echo "      ";
            }
            // line 112
            echo "      <li class=\"nav-item\">
        <a href=\"#tasklist\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 113
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["taskListLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-check\"></i></a>
      </li>
    ";
        }
        // line 117
        echo "
    ";
        // line 118
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_notes_in_rich_text", []), "value", []) == 1)) {
            // line 119
            echo "      ";
            $context["notesLabel"] = "Notes in Rich Text";
            // line 120
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_rich_text_notes", []), "value", []))) {
                // line 121
                echo "        ";
                $context["notesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_rich_text_notes", []), "value", []);
                // line 122
                echo "      ";
            }
            // line 123
            echo "      <li class=\"nav-item\">
        <a href=\"#notes\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 124
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["notesLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-italic\"></i></a>
      </li>
    ";
        }
        // line 128
        echo "
    ";
        // line 129
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_data_tables", []), "value", []) == 1)) {
            // line 130
            echo "      ";
            $context["dataTableLabel"] = "Data Tables";
            // line 131
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_data_tables", []), "value", []))) {
                // line 132
                echo "        ";
                $context["dataTableLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_data_tables", []), "value", []);
                // line 133
                echo "      ";
            }
            // line 134
            echo "      <li class=\"nav-item\">
        <a href=\"#datatables\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 135
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["dataTableLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-database\"></i></a>
      </li>
    ";
        }
        // line 139
        echo "
    ";
        // line 140
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_location", []), "value", []) == 1)) {
            // line 141
            echo "      ";
            $context["locationLabel"] = "Location";
            // line 142
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_location", []), "value", []))) {
                // line 143
                echo "        ";
                $context["locationLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_location", []), "value", []);
                // line 144
                echo "      ";
            }
            // line 145
            echo "      <li class=\"nav-item\">
        <a href=\"#location\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 146
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["locationLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-map-marker\"></i></a>
      </li>
    ";
        }
        // line 150
        echo "
    ";
        // line 151
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_file_uploads", []), "value", []) == 1)) {
            // line 152
            echo "      ";
            $context["fileUploadsLabel"] = "Image & File Uploads";
            // line 153
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_file_uploads", []), "value", []))) {
                // line 154
                echo "        ";
                $context["fileUploadsLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_file_uploads", []), "value", []);
                // line 155
                echo "      ";
            }
            // line 156
            echo "      <li class=\"nav-item\">
        <a href=\"#uploads\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 157
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["fileUploadsLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-file\"></i></a>
      </li>
    ";
        }
        // line 161
        echo "
    ";
        // line 162
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_urls", []), "value", []) == 1)) {
            // line 163
            echo "      ";
            $context["urlsLabel"] = "Web Sites";
            // line 164
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_urls", []), "value", []))) {
                // line 165
                echo "        ";
                $context["urlsLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_urls", []), "value", []);
                // line 166
                echo "      ";
            }
            // line 167
            echo "      <li class=\"nav-item\">
        <a href=\"#urls\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 168
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["urlsLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i class=\"fa fa-cloud\"></i>
        </a>
      </li>
    ";
        }
        // line 172
        echo "
    ";
        // line 173
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_decimal_values", []), "value", []) == 1)) {
            // line 174
            echo "      ";
            $context["decimalValuesLabel"] = "Decimal Values";
            // line 175
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_decimal_values", []), "value", []))) {
                // line 176
                echo "        ";
                $context["decimalValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_decimal_values", []), "value", []);
                // line 177
                echo "      ";
            }
            // line 178
            echo "      <li class=\"nav-item\">
        <a href=\"#decimals\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 179
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["decimalValuesLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-calculator\"></i></a>
      </li>
    ";
        }
        // line 183
        echo "
    ";
        // line 184
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_integer_values", []), "value", []) == 1)) {
            // line 185
            echo "      ";
            $context["integerValuesLabel"] = "Integer Values";
            // line 186
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_integer_values", []), "value", []))) {
                // line 187
                echo "        ";
                $context["integerValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_integer_values", []), "value", []);
                // line 188
                echo "      ";
            }
            // line 189
            echo "      <li class=\"nav-item\">
        <a href=\"#integers\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 190
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["integerValuesLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-list-ol\"></i></a>
      </li>
    ";
        }
        // line 194
        echo "
    ";
        // line 195
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_float_values", []), "value", []) == 1)) {
            // line 196
            echo "      ";
            $context["floatValuesLabel"] = "Float Values";
            // line 197
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_float_values", []), "value", []))) {
                // line 198
                echo "        ";
                $context["floatValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_float_values", []), "value", []);
                // line 199
                echo "      ";
            }
            // line 200
            echo "      <li class=\"nav-item\">
        <a href=\"#floats\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 201
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["floatValuesLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-sliders\"></i></a>
      </li>
    ";
        }
        // line 205
        echo "
    ";
        // line 206
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_text_values", []), "value", []) == 1)) {
            // line 207
            echo "      ";
            $context["textValuesLabel"] = "Text Values";
            // line 208
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_text_values", []), "value", []))) {
                // line 209
                echo "        ";
                $context["textValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_text_values", []), "value", []);
                // line 210
                echo "      ";
            }
            // line 211
            echo "      <li class=\"nav-item\">
        <a href=\"#textvals\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 212
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["textValuesLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-text-height\"></i></a>
      </li>
    ";
        }
        // line 216
        echo "
    ";
        // line 217
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_date_values", []), "value", []) == 1)) {
            // line 218
            echo "      ";
            $context["dateValuesLabel"] = "Date Values";
            // line 219
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_date_values", []), "value", []))) {
                // line 220
                echo "        ";
                $context["dateValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_date_values", []), "value", []);
                // line 221
                echo "      ";
            }
            // line 222
            echo "      <li class=\"nav-item\">
        <a href=\"#datevals\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 223
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["dateValuesLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-calendar\"></i></a>
      </li>
    ";
        }
        // line 227
        echo "
    ";
        // line 228
        if (($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_display_videos", []), "value", []) == 1)) {
            // line 229
            echo "      ";
            $context["videosLabel"] = "Videos";
            // line 230
            echo "      ";
            if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_videos", []), "value", []))) {
                // line 231
                echo "        ";
                $context["videosLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_videos", []), "value", []);
                // line 232
                echo "      ";
            }
            // line 233
            echo "      <li class=\"nav-item\">
        <a href=\"#videos\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; ";
            // line 234
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["videosLabel"] ?? null)), "html", null, true);
            echo " &nbsp; <i
            class=\"fa fa-forward\"></i></a>
      </li>
    ";
        }
        // line 238
        echo "
  </ul>

  ";
        // line 241
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\devel\Twig\Extension\Debug')->breakpoint($this->env, $context), "html", null, true);
        echo "

  ";
        // line 243
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Ajgl\Twig\Extension\BreakpointExtension')->setBreakpoint($this->env, $context), "html", null, true);
        echo "

  ";
        // line 246
        echo "
  <div class=\"tab-content\">


    ";
        // line 251
        echo "    <div id=\"tasklist\" class=\"tab-pane active\">

      ";
        // line 254
        echo "      ";
        $context["taskListLabel"] = "Task List";
        // line 255
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_task_list", []), "value", []))) {
            // line 256
            echo "        ";
            $context["taskListLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_task_list", []), "value", []);
            // line 257
            echo "      ";
        }
        // line 258
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["taskListLabel"] ?? null)), "html", null, true);
        echo " </h3></center>

      ";
        // line 261
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_task_list", []), "value", [])));
        echo "
      <hr>

      ";
        // line 265
        echo "      ";
        $context["itemnum"] = 1;
        // line 266
        echo "      ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_task_checklist", []));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 267
            echo "
        <b>Task ";
            // line 268
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["itemnum"] ?? null)), "html", null, true);
            echo " </b>
        ";
            // line 269
            if (($this->getAttribute($this->getAttribute($context["item"], "value", []), "first", []) == 0)) {
                // line 270
                echo "          ";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar("(Not Complete) ");
                echo "
        ";
            } else {
                // line 272
                echo "          ";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar("<b>(Completed)</b> ");
                echo "
        ";
            }
            // line 274
            echo "        :
        ";
            // line 275
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["item"], "value", []), "second", [])));
            echo " <br>
        <hr>
        ";
            // line 277
            $context["itemnum"] = (($context["itemnum"] ?? null) + 1);
            // line 278
            echo "      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 279
        echo "
    </div>


    ";
        // line 284
        echo "    <div id=\"notes\" class=\"tab-pane\">

      ";
        // line 287
        echo "      ";
        $context["notesListLabel"] = "Notes in Rich Text";
        // line 288
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_rich_text_notes", []), "value", []))) {
            // line 289
            echo "        ";
            $context["notesListLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_rich_text_notes", []), "value", []);
            // line 290
            echo "      ";
        }
        // line 291
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["notesListLabel"] ?? null)), "html", null, true);
        echo " </h3></center>

      ";
        // line 294
        echo "      ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_notes", []));
        foreach ($context['_seq'] as $context["_key"] => $context["note"]) {
            // line 295
            echo "        <p>
          ";
            // line 296
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($context["note"], "value", [])));
            echo "
        </p>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['note'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 299
        echo "
    </div>


    ";
        // line 304
        echo "    <div id=\"datatables\" class=\"tab-pane\">

      ";
        // line 307
        echo "      ";
        $context["tableValuesLabel"] = "Tables of Data";
        // line 308
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_data_tables", []), "value", []))) {
            // line 309
            echo "        ";
            $context["tableValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_data_tables", []), "value", []);
            // line 310
            echo "      ";
        }
        // line 311
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["tableValuesLabel"] ?? null)), "html", null, true);
        echo "</h3></center>

      ";
        // line 314
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_data_tables", []), "value", [])));
        echo "
      <hr>


      ";
        // line 319
        echo "      <style>
        th {padding: 10px; text-align: center; background-color: #e9e9e9; }
        td {padding: 10px;}
      </style>

      <br>

      ";
        // line 327
        echo "      ";
        // line 328
        echo "      ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_data_tables", []), "getValue", [], "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["tableItem"]) {
            // line 329
            echo "
        ";
            // line 331
            echo "
        ";
            // line 332
            $context["rowCount"] = twig_length_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["tableItem"], "value", [], "array")));
            // line 333
            echo "        ";
            // line 334
            echo "
        <table border=\"1\">
          ";
            // line 336
            $context["rowCounter"] = 1;
            // line 337
            echo "
          ";
            // line 338
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["tableItem"], "value", [], "array"));
            foreach ($context['_seq'] as $context["_key"] => $context["rows"]) {
                if ((($context["rowCounter"] ?? null) < ($context["rowCount"] ?? null))) {
                    // line 339
                    echo "
            ";
                    // line 340
                    $context["colCount"] = twig_length_filter($this->env, $this->sandbox->ensureToStringAllowed($context["rows"]));
                    // line 341
                    echo "
            <tr>
              ";
                    // line 343
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(range(0, (($context["colCount"] ?? null) - 1)));
                    foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                        // line 344
                        echo "                <td> ";
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["rows"], $context["i"], [], "array")), "html", null, true);
                        echo " </td>
              ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 346
                    echo "            </tr>

            ";
                    // line 348
                    $context["rowCounter"] = (($context["rowCounter"] ?? null) + 1);
                    // line 349
                    echo "            ";
                    // line 350
                    echo "          ";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['rows'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 351
            echo "
        </table>
        <br>
        ";
            // line 354
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["tableItem"], "caption", [])), "html", null, true);
            echo " <br>

      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tableItem'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 357
        echo "
    </div>


    ";
        // line 362
        echo "    <div id=\"location\" class=\"tab-pane\">

      ";
        // line 365
        echo "      ";
        $context["locationLabel"] = "Location";
        // line 366
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_location", []), "value", []))) {
            // line 367
            echo "        ";
            $context["locationLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_location", []), "value", []);
            // line 368
            echo "      ";
        }
        // line 369
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["locationLabel"] ?? null)), "html", null, true);
        echo " </h3></center>

      ";
        // line 372
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_location", []), "value", [])));
        echo "
      <hr>

      ";
        // line 376
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, views_embed_view("geolocation_demo_common_map", "block_1"), "html", null, true);
        echo "

      <h3>Geolocation Coordinates </h3>

      <b>Lat:</b> ";
        // line 380
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_geolocation_demo_single", []), "lat", [])), "html", null, true);
        echo " <br>
      <b>Long:</b> ";
        // line 381
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_geolocation_demo_single", []), "lng", [])), "html", null, true);
        echo " <br>

    </div>


    ";
        // line 387
        echo "    <div id=\"uploads\" class=\"tab-pane\">

      ";
        // line 390
        echo "      ";
        $context["fileUploadLabel"] = "Image & File Uploads";
        // line 391
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_file_uploads", []), "value", []))) {
            // line 392
            echo "        ";
            $context["fileUploadLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_file_uploads", []), "value", []);
            // line 393
            echo "      ";
        }
        // line 394
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["fileUploadLabel"] ?? null)), "html", null, true);
        echo " </h3></center>

      ";
        // line 397
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_image_file_uploads", []), "value", [])));
        echo "
      <hr>

      ";
        // line 401
        echo "      <p>
        ";
        // line 402
        $context["image_exists"] = false;
        // line 403
        echo "
        ";
        // line 404
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_protocol_images", []));
        foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
            // line 405
            echo "        <table style=\"width:98%;\" class=\"table table-bordered table-condensed\">
          ";
            // line 406
            $context["title"] = $this->getAttribute($context["image"], "title", []);
            // line 407
            echo "          ";
            $context["upload_image"] = $this->getAttribute($this->getAttribute($this->getAttribute($context["image"], "entity", []), "filename", []), "value", []);
            // line 408
            echo "          ";
            $context["image_exists"] = true;
            // line 409
            echo "
          ";
            // line 411
            echo "          <tr>
            <td>
              <center>
                <embed height=\"400\" src=\"/sites/default/files/protocol-images/";
            // line 414
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["upload_image"] ?? null)), "html", null, true);
            echo "\" ></embed>
              </center>
            </td>
          </tr>
          <tr>
            <td>
      <p><b> ";
            // line 420
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null)), "html", null, true);
            echo " </b></p>
      <a href=\"/sites/default/files/protocol-images/";
            // line 421
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["upload_image"] ?? null)), "html", null, true);
            echo "\" target=\"_blank\">Open image in separate
        window</a>
      </td>
      </tr>
      ";
            // line 426
            echo "      </table>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['image'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 428
        echo "
      <hr>
      <br>

      </p>

      ";
        // line 435
        echo "      <p>

        ";
        // line 437
        $context["pdf_exists"] = false;
        // line 438
        echo "
        ";
        // line 439
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_protocol_files", []));
        foreach ($context['_seq'] as $context["_key"] => $context["pdf"]) {
            // line 440
            echo "
        ";
            // line 442
            echo "        ";
            $context["display_embedded"] = $this->getAttribute($context["pdf"], "display", []);
            // line 443
            echo "        ";
            $context["description"] = $this->getAttribute($context["pdf"], "description", []);
            // line 444
            echo "        ";
            $context["upload_filename"] = $this->getAttribute($this->getAttribute($this->getAttribute($context["pdf"], "entity", []), "filename", []), "value", []);
            // line 445
            echo "        ";
            $context["pdf_exists"] = true;
            // line 446
            echo "
        ";
            // line 448
            echo "        <table style=\"width:98%;\" class=\"table table-bordered table-condensed\">
          <tr>
            <td>
      <p>
      <center><b> ";
            // line 452
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["description"] ?? null)), "html", null, true);
            echo " </b></center>
      </p>
      <a href=\"/sites/default/files/protocol-files/";
            // line 454
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["upload_filename"] ?? null)), "html", null, true);
            echo "\" target=\"_blank\">Open file in separate
        window</a>
      </td>
      </tr>

      <tr>
        <td>
          <center>
            ";
            // line 462
            if ((($context["display_embedded"] ?? null) == true)) {
                // line 463
                echo "              <embed height=\"400\" src=\"/sites/default/files/protocol-files/";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["upload_filename"] ?? null)), "html", null, true);
                echo "#page=1&zoom=130\"
                     width=\"1120\"></embed>
            ";
            }
            // line 466
            echo "          </center>
        </td>
      </tr>
      </table>

      ";
            // line 472
            echo "      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pdf'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 473
        echo "      </p>

    </div>


    ";
        // line 479
        echo "    <div id=\"urls\" class=\"tab-pane\">

      ";
        // line 482
        echo "      ";
        $context["urlUploadLabel"] = "Web Sites";
        // line 483
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_urls", []), "value", []))) {
            // line 484
            echo "        ";
            $context["urlUploadLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_urls", []), "value", []);
            // line 485
            echo "      ";
        }
        // line 486
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["urlUploadLabel"] ?? null)), "html", null, true);
        echo " </h3></center>
      <hr>

      ";
        // line 490
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_web_sites", []), "value", [])));
        echo "
      <hr>

      ";
        // line 494
        echo "      ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_urls", []));
        foreach ($context['_seq'] as $context["_key"] => $context["url"]) {
            // line 495
            echo "        <p>
          <a href=\"";
            // line 496
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["url"], "uri", [])), "html", null, true);
            echo "\" target=\"_blank\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["url"], "title", [])), "html", null, true);
            echo "</a>
        </p>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['url'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 499
        echo "    </div>


    ";
        // line 503
        echo "    <div id=\"decimals\" class=\"tab-pane\">

      ";
        // line 506
        echo "      ";
        $context["decimalValuesLabel"] = "Decimal Values";
        // line 507
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_decimal_values", []), "value", []))) {
            // line 508
            echo "        ";
            $context["decimalValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_decimal_values", []), "value", []);
            // line 509
            echo "      ";
        }
        // line 510
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["decimalValuesLabel"] ?? null)), "html", null, true);
        echo "</h3></center>

      ";
        // line 513
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_decimal_values", []), "value", [])));
        echo "
      <hr>

      ";
        // line 517
        echo "      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        ";
        // line 522
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_decimal_values", []));
        foreach ($context['_seq'] as $context["_key"] => $context["decimalval"]) {
            // line 523
            echo "          <tr>
            <td> ";
            // line 524
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["decimalval"], "value", []), "first", [])), "html", null, true);
            echo " </td>
            <td> ";
            // line 525
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["decimalval"], "value", []), "second", [])), "html", null, true);
            echo " </td>
          </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['decimalval'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 528
        echo "      </table>

    </div>


    ";
        // line 534
        echo "    <div id=\"integers\" class=\"tab-pane\">

      ";
        // line 537
        echo "      ";
        $context["integerValuesLabel"] = "Integer Values";
        // line 538
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_integer_values", []), "value", []))) {
            // line 539
            echo "        ";
            $context["integerValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_integer_values", []), "value", []);
            // line 540
            echo "      ";
        }
        // line 541
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["integerValuesLabel"] ?? null)), "html", null, true);
        echo "</h3></center>

      ";
        // line 544
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_integer_values", []), "value", [])));
        echo "
      <hr>

      ";
        // line 548
        echo "      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        ";
        // line 553
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_integer_values", []));
        foreach ($context['_seq'] as $context["_key"] => $context["intval"]) {
            // line 554
            echo "          <tr>
            <td> ";
            // line 555
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["intval"], "value", []), "first", [])), "html", null, true);
            echo " </td>
            <td> ";
            // line 556
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["intval"], "value", []), "second", [])), "html", null, true);
            echo " </td>
          </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['intval'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 559
        echo "      </table>

    </div>


    ";
        // line 565
        echo "    <div id=\"floats\" class=\"tab-pane\">

      ";
        // line 568
        echo "      ";
        $context["floatValuesLabel"] = "Float Values";
        // line 569
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_float_values", []), "value", []))) {
            // line 570
            echo "        ";
            $context["floatValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_float_values", []), "value", []);
            // line 571
            echo "      ";
        }
        // line 572
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["floatValuesLabel"] ?? null)), "html", null, true);
        echo "</h3></center>

      ";
        // line 575
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_float_values", []), "value", [])));
        echo "
      <hr>

      ";
        // line 579
        echo "      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        ";
        // line 584
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_float_values", []));
        foreach ($context['_seq'] as $context["_key"] => $context["floatval"]) {
            // line 585
            echo "          <tr>
            <td> ";
            // line 586
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["floatval"], "value", []), "first", [])), "html", null, true);
            echo " </td>
            <td> ";
            // line 587
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["floatval"], "value", []), "second", [])), "html", null, true);
            echo " </td>
          </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['floatval'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 590
        echo "      </table>

    </div>


    ";
        // line 596
        echo "    <div id=\"textvals\" class=\"tab-pane\">

      ";
        // line 599
        echo "      ";
        $context["textValuesLabel"] = "Text Values";
        // line 600
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_text_values", []), "value", []))) {
            // line 601
            echo "        ";
            $context["textValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_text_values", []), "value", []);
            // line 602
            echo "      ";
        }
        // line 603
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["textValuesLabel"] ?? null)), "html", null, true);
        echo "</h3></center>

      ";
        // line 606
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_text_values", []), "value", [])));
        echo "
      <hr>

      ";
        // line 610
        echo "      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        ";
        // line 615
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_text_values", []));
        foreach ($context['_seq'] as $context["_key"] => $context["textval"]) {
            // line 616
            echo "          <tr>
            <td> ";
            // line 617
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["textval"], "value", []), "first", [])), "html", null, true);
            echo " </td>
            <td> ";
            // line 618
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["textval"], "value", []), "second", [])), "html", null, true);
            echo " </td>
          </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['textval'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 621
        echo "      </table>

    </div>


    ";
        // line 627
        echo "    <div id=\"datevals\" class=\"tab-pane\">

      ";
        // line 630
        echo "      ";
        $context["dateValuesLabel"] = "Date Values";
        // line 631
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_date_values", []), "value", []))) {
            // line 632
            echo "        ";
            $context["dateValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_date_values", []), "value", []);
            // line 633
            echo "      ";
        }
        // line 634
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["dateValuesLabel"] ?? null)), "html", null, true);
        echo "</h3></center>

      ";
        // line 637
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_the_date_values", []), "value", [])));
        echo "
      <hr>

      ";
        // line 641
        echo "      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        ";
        // line 646
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_date_values", []));
        foreach ($context['_seq'] as $context["_key"] => $context["dateval"]) {
            // line 647
            echo "          <tr>
            <td> ";
            // line 648
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["dateval"], "value", []), "first", [])), "html", null, true);
            echo " </td>
            <td> ";
            // line 649
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($context["dateval"], "value", []), "second", [])), "html", null, true);
            echo " </td>
          </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dateval'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 652
        echo "      </table>
    </div>


    ";
        // line 657
        echo "
    <div id=\"videos\" class=\"tab-pane\">

      ";
        // line 661
        echo "      ";
        $context["videoValuesLabel"] = "Media and YouTube Videos";
        // line 662
        echo "      ";
        if ( !twig_test_empty($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_videos", []), "value", []))) {
            // line 663
            echo "        ";
            $context["videoValuesLabel"] = $this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_tab_label_videos", []), "value", []);
            // line 664
            echo "      ";
        }
        // line 665
        echo "      <center><h3> ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["videoValuesLabel"] ?? null)), "html", null, true);
        echo "</h3></center>

      ";
        // line 668
        echo "      ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute(($context["node"] ?? null), "field_notes_videos", []), "value", [])));
        echo "
      <hr>

      <div>

        ";
        // line 674
        echo "        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_media_videos", []));
        foreach ($context['_seq'] as $context["_key"] => $context["video"]) {
            // line 675
            echo "
          <p>

          ";
            // line 678
            $context["vfile"] = $this->getAttribute($context["video"], "entity", []);
            // line 679
            echo "          ";
            $context["uri"] = $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["vfile"] ?? null), "field_media_video_file", []), "entity", []), "uri", []), "value", []);
            // line 680
            echo "
          ";
            // line 682
            echo "            <video width=\"640\" height=\"480\" controls>
              ";
            // line 683
            $context["good_uri"] = twig_replace_filter($this->sandbox->ensureToStringAllowed(($context["uri"] ?? null)), ["public://" => "/sites/default/files/"]);
            // line 684
            echo "              <source src=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["good_uri"] ?? null)), "html", null, true);
            echo "\" type=\"video/mp4\">
            </video>
          ";
            // line 687
            echo "
          <h4> ";
            // line 688
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($this->getAttribute($context["video"], "entity", []), "name", []), "value", [])), "html", null, true);
            echo " </h4>
          </p>

        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['video'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 692
        echo "
      </div>

      <hr>

      ";
        // line 698
        echo "      <div>

        ";
        // line 700
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "field_embedded_videos", []));
        foreach ($context['_seq'] as $context["_key"] => $context["evideo"]) {
            // line 701
            echo "
          <div class=\"field__items\">
            <div class=\"field__item\">

              <div class=\"video-embed-field-provider-youtube video-embed-field-responsive-video\">

                <p>

                ";
            // line 709
            $context["vidsrc"] = twig_replace_filter($this->sandbox->ensureToStringAllowed($this->getAttribute($context["evideo"], "value", [])), ["https://youtu.be/" => "https://www.youtube.com/embed/"]);
            // line 710
            echo "
                <iframe width=\"854\" height=\"480\" src=\"";
            // line 711
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["vidsrc"] ?? null)), "html", null, true);
            echo "\"
                        allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\"
                        allowfullscreen></iframe>

                <h4> ";
            // line 715
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["vidsrc"] ?? null)), "html", null, true);
            echo " </h4>

                </p>

              </div>

            </div>

          </div>

          <br>

        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['evideo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 728
        echo "
      </div>

    </div>

  </div>

</div>

";
    }

    public function getTemplateName()
    {
        return "themes/contrib/druadmin_lte_theme/templates/node/node--protocol_step.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1535 => 728,  1516 => 715,  1509 => 711,  1506 => 710,  1504 => 709,  1494 => 701,  1490 => 700,  1486 => 698,  1479 => 692,  1469 => 688,  1466 => 687,  1460 => 684,  1458 => 683,  1455 => 682,  1452 => 680,  1449 => 679,  1447 => 678,  1442 => 675,  1437 => 674,  1428 => 668,  1422 => 665,  1419 => 664,  1416 => 663,  1413 => 662,  1410 => 661,  1405 => 657,  1399 => 652,  1390 => 649,  1386 => 648,  1383 => 647,  1379 => 646,  1372 => 641,  1365 => 637,  1359 => 634,  1356 => 633,  1353 => 632,  1350 => 631,  1347 => 630,  1343 => 627,  1336 => 621,  1327 => 618,  1323 => 617,  1320 => 616,  1316 => 615,  1309 => 610,  1302 => 606,  1296 => 603,  1293 => 602,  1290 => 601,  1287 => 600,  1284 => 599,  1280 => 596,  1273 => 590,  1264 => 587,  1260 => 586,  1257 => 585,  1253 => 584,  1246 => 579,  1239 => 575,  1233 => 572,  1230 => 571,  1227 => 570,  1224 => 569,  1221 => 568,  1217 => 565,  1210 => 559,  1201 => 556,  1197 => 555,  1194 => 554,  1190 => 553,  1183 => 548,  1176 => 544,  1170 => 541,  1167 => 540,  1164 => 539,  1161 => 538,  1158 => 537,  1154 => 534,  1147 => 528,  1138 => 525,  1134 => 524,  1131 => 523,  1127 => 522,  1120 => 517,  1113 => 513,  1107 => 510,  1104 => 509,  1101 => 508,  1098 => 507,  1095 => 506,  1091 => 503,  1086 => 499,  1075 => 496,  1072 => 495,  1067 => 494,  1060 => 490,  1053 => 486,  1050 => 485,  1047 => 484,  1044 => 483,  1041 => 482,  1037 => 479,  1030 => 473,  1024 => 472,  1017 => 466,  1010 => 463,  1008 => 462,  997 => 454,  992 => 452,  986 => 448,  983 => 446,  980 => 445,  977 => 444,  974 => 443,  971 => 442,  968 => 440,  964 => 439,  961 => 438,  959 => 437,  955 => 435,  947 => 428,  940 => 426,  933 => 421,  929 => 420,  920 => 414,  915 => 411,  912 => 409,  909 => 408,  906 => 407,  904 => 406,  901 => 405,  897 => 404,  894 => 403,  892 => 402,  889 => 401,  882 => 397,  876 => 394,  873 => 393,  870 => 392,  867 => 391,  864 => 390,  860 => 387,  852 => 381,  848 => 380,  840 => 376,  833 => 372,  827 => 369,  824 => 368,  821 => 367,  818 => 366,  815 => 365,  811 => 362,  805 => 357,  796 => 354,  791 => 351,  784 => 350,  782 => 349,  780 => 348,  776 => 346,  767 => 344,  763 => 343,  759 => 341,  757 => 340,  754 => 339,  749 => 338,  746 => 337,  744 => 336,  740 => 334,  738 => 333,  736 => 332,  733 => 331,  730 => 329,  725 => 328,  723 => 327,  714 => 319,  706 => 314,  700 => 311,  697 => 310,  694 => 309,  691 => 308,  688 => 307,  684 => 304,  678 => 299,  669 => 296,  666 => 295,  661 => 294,  655 => 291,  652 => 290,  649 => 289,  646 => 288,  643 => 287,  639 => 284,  633 => 279,  627 => 278,  625 => 277,  620 => 275,  617 => 274,  611 => 272,  605 => 270,  603 => 269,  599 => 268,  596 => 267,  591 => 266,  588 => 265,  581 => 261,  575 => 258,  572 => 257,  569 => 256,  566 => 255,  563 => 254,  559 => 251,  553 => 246,  548 => 243,  543 => 241,  538 => 238,  531 => 234,  528 => 233,  525 => 232,  522 => 231,  519 => 230,  516 => 229,  514 => 228,  511 => 227,  504 => 223,  501 => 222,  498 => 221,  495 => 220,  492 => 219,  489 => 218,  487 => 217,  484 => 216,  477 => 212,  474 => 211,  471 => 210,  468 => 209,  465 => 208,  462 => 207,  460 => 206,  457 => 205,  450 => 201,  447 => 200,  444 => 199,  441 => 198,  438 => 197,  435 => 196,  433 => 195,  430 => 194,  423 => 190,  420 => 189,  417 => 188,  414 => 187,  411 => 186,  408 => 185,  406 => 184,  403 => 183,  396 => 179,  393 => 178,  390 => 177,  387 => 176,  384 => 175,  381 => 174,  379 => 173,  376 => 172,  369 => 168,  366 => 167,  363 => 166,  360 => 165,  357 => 164,  354 => 163,  352 => 162,  349 => 161,  342 => 157,  339 => 156,  336 => 155,  333 => 154,  330 => 153,  327 => 152,  325 => 151,  322 => 150,  315 => 146,  312 => 145,  309 => 144,  306 => 143,  303 => 142,  300 => 141,  298 => 140,  295 => 139,  288 => 135,  285 => 134,  282 => 133,  279 => 132,  276 => 131,  273 => 130,  271 => 129,  268 => 128,  261 => 124,  258 => 123,  255 => 122,  252 => 121,  249 => 120,  246 => 119,  244 => 118,  241 => 117,  234 => 113,  231 => 112,  228 => 111,  225 => 110,  222 => 109,  219 => 108,  217 => 107,  207 => 101,  202 => 97,  198 => 95,  196 => 94,  193 => 93,  191 => 92,  186 => 90,  180 => 86,  172 => 80,  165 => 78,  163 => 77,  157 => 73,  153 => 71,  151 => 70,  148 => 69,  146 => 68,  137 => 64,  132 => 63,  130 => 62,  121 => 55,  116 => 54,  114 => 53,  101 => 42,  97 => 40,  95 => 39,  92 => 38,  90 => 37,  82 => 34,  65 => 19,  63 => 18,  61 => 17,  59 => 16,  55 => 13,);
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
 *
 *
 * Available variables:
 *
 * @see
 *
 * @ingroup themeable
 */
#}


{# Get the reference to the parent experiment #}
{% set exp_name = node.field_parent_experiment.entity.title.value %}
{% set exp_nid = node.field_parent_experiment.entity.nid.value %}
{% set exp_status = node.field_parent_experiment.entity.field_completion_status.value %}

<div class=\"row\"
     style=\"border: 3px solid #0a7bc1; padding-left: 10px; padding-right: 10px; padding-bottom: 20px;
            background-color: #f4f4f4; margin-left: 15px; margin-right: 15px;\">

  <h3>The Experiment </h3>

  <div class=\"row\">

    <div class=\"col-md-12\">
      <div class=\"card h-100\" style=\"border: 4px solid #028ee1; background-color: #fff; \">

        <div class=\"card-body\"
             style=\"text-align: center; margin-top: 20px; margin-bottom: 20px; margin-left: 15px; margin-right: 15px;\">
          <h3 class=\"card-title\" style=\"text-align: center; margin-top: 10px;\">
            <a href=\"/node/{{ exp_nid }}\"><b> {{ exp_name }} </b></a>
          </h3>
          <div style=\"text-align: center\">
            {% if (exp_status == 0) %}
              Status: <b> Incomplete </b>
            {% elseif (exp_status == 1) %}
              Status: <b> Complete </b>
            {% endif %}
          </div>
        </div>
      </div>
    </div>

  </div>

  <h3>The Protocol Steps </h3>

  <div class=\"row\">

    {% set step_num = 1 %}
    {% for step in node.field_parent_experiment.entity.field_protocol_steps %}

      <div class=\"col-md-4\">
        <div class=\"card h-100\" style=\"border: 2px solid #028ee1; background-color: #fff; margin: 15px; \">

          <div class=\"card-body\"
               style=\"text-align: center; margin-top: 20px; margin-bottom: 20px; margin-left: 15px; margin-right: 15px;\">
            <h4 class=\"card-title\">
              {% set step_name = step.entity.title.value %}
              <a href=\"/node/{{ step.entity.nid.value }}\">
                <b> {{ step_num }}. {{ step_name }} </b>
              </a>
            </h4>
            <div style=\"text-align: center\">
              {% if (step.entity.field_completion_status.value == 0) %}
                Status: <b> Incomplete </b>
              {% elseif (step.entity.field_completion_status.value == 1) %}
                Status: <b> Complete </b>
              {% endif %}
            </div>
          </div>
        </div>
      </div>
      {% set step_num = step_num + 1 %}

    {% endfor %}
  </div>

</div>


<p>
  {# content #} &nbsp;
</p>


<h2>Protocol Step: &nbsp; &nbsp; &nbsp; <b>  {{ node.title.value }} </b></h2>

  {% if (node.field_completion_status.value == 0) %}
    <h4><em>Overall Step Status: <b> Incomplete </b> </em></h4>
  {% elseif (node.field_completion_status.value == 1) %}
    <h4><em>Overall Step Status: <b> Complete </b> </em></h4>
  {% endif %}

  <hr>

  {# Output the description/body #}
  {{ node.body.value | raw }}

<div class=\"tabs\">

  <ul class=\"nav nav-tabs\">

    {% if node.field_display_task_list.value == 1 %}
      {% set taskListLabel = \"Task List\" %}
      {% if node.field_tab_label_task_list.value is not empty %}
        {% set taskListLabel = node.field_tab_label_task_list.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#tasklist\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ taskListLabel }} &nbsp; <i
            class=\"fa fa-check\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_notes_in_rich_text.value == 1 %}
      {% set notesLabel = \"Notes in Rich Text\" %}
      {% if node.field_tab_label_rich_text_notes.value is not empty %}
        {% set notesLabel = node.field_tab_label_rich_text_notes.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#notes\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ notesLabel }} &nbsp; <i
            class=\"fa fa-italic\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_data_tables.value == 1 %}
      {% set dataTableLabel = \"Data Tables\" %}
      {% if node.field_tab_label_data_tables.value is not empty %}
        {% set dataTableLabel = node.field_tab_label_data_tables.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#datatables\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ dataTableLabel }} &nbsp; <i
            class=\"fa fa-database\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_location.value == 1 %}
      {% set locationLabel = \"Location\" %}
      {% if node.field_tab_label_location.value is not empty %}
        {% set locationLabel = node.field_tab_label_location.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#location\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ locationLabel }} &nbsp; <i
            class=\"fa fa-map-marker\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_file_uploads.value == 1 %}
      {% set fileUploadsLabel = \"Image & File Uploads\" %}
      {% if node.field_tab_label_file_uploads.value is not empty %}
        {% set fileUploadsLabel = node.field_tab_label_file_uploads.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#uploads\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ fileUploadsLabel }} &nbsp; <i
            class=\"fa fa-file\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_urls.value == 1 %}
      {% set urlsLabel = \"Web Sites\" %}
      {% if node.field_tab_label_urls.value is not empty %}
        {% set urlsLabel = node.field_tab_label_urls.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#urls\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ urlsLabel }} &nbsp; <i class=\"fa fa-cloud\"></i>
        </a>
      </li>
    {% endif %}

    {% if node.field_display_decimal_values.value == 1 %}
      {% set decimalValuesLabel = \"Decimal Values\" %}
      {% if node.field_tab_label_decimal_values.value is not empty %}
        {% set decimalValuesLabel = node.field_tab_label_decimal_values.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#decimals\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ decimalValuesLabel }} &nbsp; <i
            class=\"fa fa-calculator\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_integer_values.value == 1 %}
      {% set integerValuesLabel = \"Integer Values\" %}
      {% if node.field_tab_label_integer_values.value is not empty %}
        {% set integerValuesLabel = node.field_tab_label_integer_values.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#integers\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ integerValuesLabel }} &nbsp; <i
            class=\"fa fa-list-ol\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_float_values.value == 1 %}
      {% set floatValuesLabel = \"Float Values\" %}
      {% if node.field_tab_label_float_values.value is not empty %}
        {% set floatValuesLabel = node.field_tab_label_float_values.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#floats\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ floatValuesLabel }} &nbsp; <i
            class=\"fa fa-sliders\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_text_values.value == 1 %}
      {% set textValuesLabel = \"Text Values\" %}
      {% if node.field_tab_label_text_values.value is not empty %}
        {% set textValuesLabel = node.field_tab_label_text_values.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#textvals\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ textValuesLabel }} &nbsp; <i
            class=\"fa fa-text-height\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_date_values.value == 1 %}
      {% set dateValuesLabel = \"Date Values\" %}
      {% if node.field_tab_label_date_values.value is not empty %}
        {% set dateValuesLabel = node.field_tab_label_date_values.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#datevals\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ dateValuesLabel }} &nbsp; <i
            class=\"fa fa-calendar\"></i></a>
      </li>
    {% endif %}

    {% if node.field_display_videos.value == 1 %}
      {% set videosLabel = \"Videos\" %}
      {% if node.field_tab_label_videos.value is not empty %}
        {% set videosLabel = node.field_tab_label_videos.value %}
      {% endif %}
      <li class=\"nav-item\">
        <a href=\"#videos\" data-toggle=\"tab\"> &nbsp; &nbsp; &nbsp; {{ videosLabel }} &nbsp; <i
            class=\"fa fa-forward\"></i></a>
      </li>
    {% endif %}

  </ul>

  {{ devel_breakpoint() }}

  {{ breakpoint() }}

  {# dump(_context|keys) #}

  <div class=\"tab-content\">


    {# TASK LIST #}
    <div id=\"tasklist\" class=\"tab-pane active\">

      {# Output the heading according to the tab label #}
      {% set taskListLabel = \"Task List\" %}
      {% if node.field_tab_label_task_list.value is not empty %}
        {% set taskListLabel = node.field_tab_label_task_list.value %}
      {% endif %}
      <center><h3> {{ taskListLabel }} </h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_task_list.value | raw }}
      <hr>

      {# Output the tasks one by one #}
      {% set itemnum = 1 %}
      {% for item in node.field_task_checklist %}

        <b>Task {{ itemnum }} </b>
        {% if item.value.first == 0 %}
          {{ \"(Not Complete) \" | raw }}
        {% else %}
          {{ \"<b>(Completed)</b> \" | raw }}
        {% endif %}
        :
        {{ item.value.second | raw }} <br>
        <hr>
        {% set itemnum = itemnum + 1 %}
      {% endfor %}

    </div>


    {# RICH TEXT NOTES #}
    <div id=\"notes\" class=\"tab-pane\">

      {# Output the heading according to the tab label #}
      {% set notesListLabel = \"Notes in Rich Text\" %}
      {% if node.field_tab_label_rich_text_notes.value is not empty %}
        {% set notesListLabel = node.field_tab_label_rich_text_notes.value %}
      {% endif %}
      <center><h3> {{ notesListLabel }} </h3></center>

      {# Since this is all rich text, there is not an extra Notes section #}
      {% for note in node.field_notes %}
        <p>
          {{ note.value | raw }}
        </p>
      {% endfor %}

    </div>


    {# TABLES OF DATA #}
    <div id=\"datatables\" class=\"tab-pane\">

      {# Output the heading according to the tab label #}
      {% set tableValuesLabel = \"Tables of Data\" %}
      {% if node.field_tab_label_data_tables.value is not empty %}
        {% set tableValuesLabel = node.field_tab_label_data_tables.value %}
      {% endif %}
      <center><h3> {{ tableValuesLabel }}</h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_data_tables.value | raw }}
      <hr>


      {# Set the style for the table cells throughout the page #}
      <style>
        th {padding: 10px; text-align: center; background-color: #e9e9e9; }
        td {padding: 10px;}
      </style>

      <br>

      {# The tableItem is the array of table values in the tablefield widget #}
      {# The last row and last column contain non-table values so use row & col counters to exclude the last row & col#}
      {% for tableItem in node.field_data_tables.getValue() %}

        {# dump(tableItem) #}

        {% set rowCount = tableItem['value'] | length %}
        {# rowCount #}

        <table border=\"1\">
          {% set rowCounter = 1 %}

          {% for rows in tableItem['value'] if rowCounter < rowCount %}

            {% set colCount = rows | length %}

            <tr>
              {% for i in 0..colCount-1 %}
                <td> {{ rows[i] }} </td>
              {% endfor %}
            </tr>

            {% set rowCounter = rowCounter + 1 %}
            {# rowCounter #}
          {% endfor %}

        </table>
        <br>
        {{ tableItem.caption }} <br>

      {% endfor %}

    </div>


    {# LOCATION #}
    <div id=\"location\" class=\"tab-pane\">

      {# Output the heading according to the tab label #}
      {% set locationLabel = \"Location\" %}
      {% if node.field_tab_label_location.value is not empty %}
        {% set locationLabel = node.field_tab_label_location.value %}
      {% endif %}
      <center><h3> {{ locationLabel }} </h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_location.value | raw }}
      <hr>

      {# Embed the map as a block using the Twig Tweak module functionality #}
      {{ drupal_view('geolocation_demo_common_map', 'block_1') }}

      <h3>Geolocation Coordinates </h3>

      <b>Lat:</b> {{ node.field_geolocation_demo_single.lat }} <br>
      <b>Long:</b> {{ node.field_geolocation_demo_single.lng }} <br>

    </div>


    {# FILE UPLOADS #}
    <div id=\"uploads\" class=\"tab-pane\">

      {# Output the heading according to the tab label #}
      {% set fileUploadLabel = \"Image & File Uploads\" %}
      {% if node.field_tab_label_file_uploads.value is not empty %}
        {% set fileUploadLabel = node.field_tab_label_file_uploads.value %}
      {% endif %}
      <center><h3> {{ fileUploadLabel }} </h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_image_file_uploads.value | raw }}
      <hr>

      {# Image File Uploads #}
      <p>
        {% set image_exists = false %}

        {% for image in node.field_protocol_images %}
        <table style=\"width:98%;\" class=\"table table-bordered table-condensed\">
          {% set title = image.title %}
          {% set upload_image = image.entity.filename.value %}
          {% set image_exists = true %}

          {% autoescape %}
          <tr>
            <td>
              <center>
                <embed height=\"400\" src=\"/sites/default/files/protocol-images/{{ upload_image }}\" ></embed>
              </center>
            </td>
          </tr>
          <tr>
            <td>
      <p><b> {{ title }} </b></p>
      <a href=\"/sites/default/files/protocol-images/{{ upload_image }}\" target=\"_blank\">Open image in separate
        window</a>
      </td>
      </tr>
      {% endautoescape %}
      </table>
      {% endfor %}

      <hr>
      <br>

      </p>

      {# PDF File Uploads #}
      <p>

        {% set pdf_exists = false %}

        {% for pdf in node.field_protocol_files %}

        {# dump(pdf) #}
        {% set display_embedded = pdf.display %}
        {% set description = pdf.description %}
        {% set upload_filename = pdf.entity.filename.value %}
        {% set pdf_exists = true %}

        {% autoescape %}
        <table style=\"width:98%;\" class=\"table table-bordered table-condensed\">
          <tr>
            <td>
      <p>
      <center><b> {{ description }} </b></center>
      </p>
      <a href=\"/sites/default/files/protocol-files/{{ upload_filename }}\" target=\"_blank\">Open file in separate
        window</a>
      </td>
      </tr>

      <tr>
        <td>
          <center>
            {% if display_embedded == true %}
              <embed height=\"400\" src=\"/sites/default/files/protocol-files/{{ upload_filename }}#page=1&zoom=130\"
                     width=\"1120\"></embed>
            {% endif %}
          </center>
        </td>
      </tr>
      </table>

      {% endautoescape %}
      {% endfor %}
      </p>

    </div>


    {# LIST OF WEBSITES / URLS #}
    <div id=\"urls\" class=\"tab-pane\">

      {# Output the heading according to the tab label #}
      {% set urlUploadLabel = \"Web Sites\" %}
      {% if node.field_tab_label_urls.value is not empty %}
        {% set urlUploadLabel = node.field_tab_label_urls.value %}
      {% endif %}
      <center><h3> {{ urlUploadLabel }} </h3></center>
      <hr>

      {# Output the rich text notes #}
      {{ node.field_notes_web_sites.value | raw }}
      <hr>

      {# Output the list of URLs / Web sites #}
      {% for url in node.field_urls %}
        <p>
          <a href=\"{{ url.uri }}\" target=\"_blank\">{{ url.title }}</a>
        </p>
      {% endfor %}
    </div>


    {# DECIMAL VALUES #}
    <div id=\"decimals\" class=\"tab-pane\">

      {# Set the text for the DECIMAL VALUES tab as appropriate #}
      {% set decimalValuesLabel = \"Decimal Values\" %}
      {% if node.field_tab_label_decimal_values.value is not empty %}
        {% set decimalValuesLabel = node.field_tab_label_decimal_values.value %}
      {% endif %}
      <center><h3> {{ decimalValuesLabel }}</h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_decimal_values.value | raw }}
      <hr>

      {# Output the decimal values in an HTML table #}
      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        {% for decimalval in node.field_decimal_values %}
          <tr>
            <td> {{ decimalval.value.first }} </td>
            <td> {{ decimalval.value.second }} </td>
          </tr>
        {% endfor %}
      </table>

    </div>


    {# INTEGER VALUES #}
    <div id=\"integers\" class=\"tab-pane\">

      {# Set the text for the INTEGER VALUES tab as appropriate #}
      {% set integerValuesLabel = \"Integer Values\" %}
      {% if node.field_tab_label_integer_values.value is not empty %}
        {% set integerValuesLabel = node.field_tab_label_integer_values.value %}
      {% endif %}
      <center><h3> {{ integerValuesLabel }}</h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_integer_values.value | raw }}
      <hr>

      {# Output the integer values in an HTML table #}
      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        {% for intval in node.field_integer_values %}
          <tr>
            <td> {{ intval.value.first }} </td>
            <td> {{ intval.value.second }} </td>
          </tr>
        {% endfor %}
      </table>

    </div>


    {# FLOAT VALUES #}
    <div id=\"floats\" class=\"tab-pane\">

      {# Set the text for the FLOAT VALUES tab as appropriate #}
      {% set floatValuesLabel = \"Float Values\" %}
      {% if node.field_tab_label_float_values.value is not empty %}
        {% set floatValuesLabel = node.field_tab_label_float_values.value %}
      {% endif %}
      <center><h3> {{ floatValuesLabel }}</h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_float_values.value | raw }}
      <hr>

      {# Output the float values in an HTML table #}
      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        {% for floatval in node.field_float_values %}
          <tr>
            <td> {{ floatval.value.first }} </td>
            <td> {{ floatval.value.second }} </td>
          </tr>
        {% endfor %}
      </table>

    </div>


    {# TEXT VALUES #}
    <div id=\"textvals\" class=\"tab-pane\">

      {# Set the text for the TEXT VALUES tab as appropriate #}
      {% set textValuesLabel = \"Text Values\" %}
      {% if node.field_tab_label_text_values.value is not empty %}
        {% set textValuesLabel = node.field_tab_label_text_values.value %}
      {% endif %}
      <center><h3> {{ textValuesLabel }}</h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_text_values.value | raw }}
      <hr>

      {# Output the text values in an HTML table #}
      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        {% for textval in node.field_text_values %}
          <tr>
            <td> {{ textval.value.first }} </td>
            <td> {{ textval.value.second }} </td>
          </tr>
        {% endfor %}
      </table>

    </div>


    {# DATE VALUES #}
    <div id=\"datevals\" class=\"tab-pane\">

      {# Set the text for the DATE VALUES tab as appropriate #}
      {% set dateValuesLabel = \"Date Values\" %}
      {% if node.field_tab_label_date_values.value is not empty %}
        {% set dateValuesLabel = node.field_tab_label_date_values.value %}
      {% endif %}
      <center><h3> {{ dateValuesLabel }}</h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_the_date_values.value | raw }}
      <hr>

      {# Output the date values in an HTML table #}
      <table border=\"1\">
        <tr>
          <th>Label</th>
          <th>Value</th>
        </tr>
        {% for dateval in node.field_date_values %}
          <tr>
            <td> {{ dateval.value.first }} </td>
            <td> {{ dateval.value.second }} </td>
          </tr>
        {% endfor %}
      </table>
    </div>


    {# VIDEOS #}

    <div id=\"videos\" class=\"tab-pane\">

      {# Set the text for the VIDEO VALUES tab as appropriate #}
      {% set videoValuesLabel = \"Media and YouTube Videos\" %}
      {% if node.field_tab_label_videos.value is not empty %}
        {% set videoValuesLabel = node.field_tab_label_videos.value %}
      {% endif %}
      <center><h3> {{ videoValuesLabel }}</h3></center>

      {# Output the rich text notes #}
      {{ node.field_notes_videos.value | raw }}
      <hr>

      <div>

        {# Output all of the videos from the Media library #}
        {% for video in node.field_media_videos %}

          <p>

          {% set vfile = video.entity %}
          {% set uri = vfile.field_media_video_file.entity.uri.value %}

          {% autoescape %}
            <video width=\"640\" height=\"480\" controls>
              {% set good_uri = uri|replace({'public://': '/sites/default/files/'}) %}
              <source src=\"{{ good_uri }}\" type=\"video/mp4\">
            </video>
          {% endautoescape %}

          <h4> {{ video.entity.name.value }} </h4>
          </p>

        {% endfor %}

      </div>

      <hr>

      {# Output all of the YouTube videos #}
      <div>

        {% for evideo in node.field_embedded_videos %}

          <div class=\"field__items\">
            <div class=\"field__item\">

              <div class=\"video-embed-field-provider-youtube video-embed-field-responsive-video\">

                <p>

                {% set vidsrc = evideo.value | replace({'https://youtu.be/': 'https://www.youtube.com/embed/'}) %}

                <iframe width=\"854\" height=\"480\" src=\"{{ vidsrc }}\"
                        allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\"
                        allowfullscreen></iframe>

                <h4> {{ vidsrc }} </h4>

                </p>

              </div>

            </div>

          </div>

          <br>

        {% endfor %}

      </div>

    </div>

  </div>

</div>

", "themes/contrib/druadmin_lte_theme/templates/node/node--protocol_step.html.twig", "/var/www/html/eln_mlbrepo/web/themes/contrib/druadmin_lte_theme/templates/node/node--protocol_step.html.twig");
    }
}
