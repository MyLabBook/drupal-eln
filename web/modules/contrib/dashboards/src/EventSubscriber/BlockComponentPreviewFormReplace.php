<?php

namespace Drupal\dashboards\EventSubscriber;

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\RendererInterface;
use Drupal\layout_builder\Event\SectionComponentBuildRenderArrayEvent;
use Drupal\layout_builder\LayoutBuilderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Replaces form elements with divs in layout builder previews.
 *
 * Because layout builder's preview interface is wrapped in a form, any block
 * component that also has a form creates invalid HTML (a form inside a form)
 * and prevents the outer form from being submitted correctly.
 *
 * This subscriber scans for forms in blocks and changes the HTML element
 * to a div, which allows the outer form to work correctly.
 *
 * Can be remove when patch is in core.
 *
 * @see https://www.drupal.org/node/3045171
 *
 * @internal
 *   Tagged services are internal.
 */
class BlockComponentPreviewFormReplace implements EventSubscriberInterface {

  /**
   * The core renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Creates a BlockComponentRenderArray object.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The core renderer service.
   */
  public function __construct(RendererInterface $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Very low priority - we want this to occur last since we perform early
    // rendering of block components and want the most complete render array
    // before doing that.
    $events[LayoutBuilderEvents::SECTION_COMPONENT_BUILD_RENDER_ARRAY] = ['onBuildRender', -1000];
    return $events;
  }

  /**
   * Change forms to divs if in layout builder's preview mode.
   *
   * @param \Drupal\layout_builder\Event\SectionComponentBuildRenderArrayEvent $event
   *   The section component render event.
   */
  public function onBuildRender(SectionComponentBuildRenderArrayEvent $event) {
    $build = $event->getBuild();
    if ($event->inPreview() && isset($build['content']) && is_array($build['content'])) {
      $build['content'] = $this->convertFormsToDiv($build['content']);
      $event->setBuild($build);
    }
  }

  /**
   * Convert form tags to div when displayed in the Layout Builder UI form.
   *
   * @param array $content
   *   The render array of the block.
   */
  protected function convertFormsToDiv(array $content) {
    // Render the block content early so we can parse the HTML.
    // We keep an original copy around so we can return that if no forms are
    // found. This allows us to only perform the early rendering when
    // necessary.
    $orginal_content = $content;
    $markup = $this->renderer->render($content);
    $html = Html::load((string) $markup);

    $forms = $html->getElementsByTagName('form');
    if ($forms->length) {
      // Iteratve over each form and swap the form element with a div.
      while ($form = $forms->item($forms->length - 1)) {
        $form_children = [];
        foreach ($form->childNodes as $form_child) {
          $form_children[] = $form_child;
        }
        $replacement_div = $html->createElement('div');
        foreach ($form_children as $form_child) {
          $replacement_div->appendChild($form_child);
        }
        foreach ($form->attributes as $form_attr_name => $form_attr) {
          $replacement_div->setAttribute($form_attr_name, $form_attr->nodeValue);
        }
        $form->parentNode->replaceChild($replacement_div, $form);
      }
      $content['#markup'] = $html->saveHTML($html->getElementsByTagName('body')->item(0));
      return $content;
    }
    else {
      return $orginal_content;
    }
  }

}
