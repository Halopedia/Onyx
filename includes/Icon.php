<?php

namespace Onyx;

require_once 'includes/Html.php';

class Icon {
  
  private static $icons = [];

  private static $iconSources = [
    'edit' => [
      'width' => 28,
      'height' => 28,
      'content' => [
        [
          'type' => 'path',
          'attributes' => [
            'd' => 'M 21 2 L 26 7 L 7 26 L 2 26 L 2 21 Z M 18 6 L 22 10'
          ]
        ]
      ]
    ],
    'talk' => [
      'width' => 28,
      'height' => 28,
      'content' => [
        [
          'type' => 'path',
          'attributes' => [
            'd' => 'M 2 2 L 26 2 L 26 20 L 14 20 L 8 26 L 8 20 L 2 20 Z'
          ]
        ]
      ]
    ],
    'view-source' => [
      'width' => 28,
      'height' => 28,
      'content' => [
        [
          'type' => 'path',
          'attributes' => [
            'd' => 'M 2 14 Q 14 0 26 14 Q 14 28 2 14 Z M 14 10 Q 18 10 18 14 Q 18 18 14 18 Q 10 18 10 14 Q 10 10 14 10 Z'
          ]
        ]
      ]
    ],
    'dropdown' => [
      'width' => 14,
      'height' => 14,
      'content' => [
        [
          'type' => 'path',
          'attributes' => [
            'd' => 'M 3 5 L 11 5 L 7 9 Z'
          ]
        ]
      ]
    ],
    'close' => [
      'width' => 14,
      'height' => 14,
      'content' => [
        [
          'type' => 'line',
          'attributes' => [
            'x1' => 2, 'y1' => 2, 'x2' => 12, 'y2' => 12
          ]
        ],
        [
          'type' => 'line',
          'attributes' => [
            'x1' => 2, 'y1' => 12, 'x2' => 12, 'y2' => 2
          ]
        ],
      ]
    ]
  ];

  public static function getIcon(string $iconName) : ?Icon {

    if (isset(Icon::$icons[$iconName])) {

      // If the requested icon is already part of the icon array, just return
      // it immediately
      return Icon::$icons[$iconName];

    } elseif (isset(Icon::$iconSources[$iconName])) {
      
      // Otherwise, if the requested icon is part of the iconSources array,
      // construct a new Icon object using the iconSources info, add it to the
      // icon array and return it
      $source = Icon::$iconSources[$iconName];
      Icon::$icons[$iconName] = new Icon($source['width'],
          $source['height'], $source['content']);
      return Icon::$icons[$iconName];

    } else {
      
      // Finally, if the requested icon is not part of either array, just
      // return null - no such icon exists
      return null;

    }

  }

  private $defaultWidth;
  private $defaultHeight;
  private $content;

  public function __construct(int $defaultWidth, int $defaultHeight,
      array $content) {
    $this->$defaultWidth = $defaultWidth;
    $this->$defaultHeight = $defaultHeight;
    $this->$content = $content;
  }

  public function makeSvg(int $width = -1, int $height = -1,
      array $attributes = []) : string {

    if ($width < 0) {
      $width = $this->$defaultWidth;
    }

    if ($height < 0) {
      $height = $this->$defaultWidth;
    }
    
    $attributes['width'] = $width;
    $attributes['height'] = $height;
    $attributes['viewBox'] = "0 0 $width $height";
    
    // DEBUG: Why does this break?
    $result = Html::openElement('svg', $attributes);
    $result .= $this->makeInnerSvg($width, $height);
    $result .= Html::closeElement('svg');

    return $result;
  }

  public function makeInnerSvg(int $width = -1, int $height = -1) : string {

    if ($width < 0) {
      $width = $this->$defaultWidth;
    }

    if ($height < 0) {
      $height = $this->$defaultHeight;
    }

    $result = '';

    foreach ($this->$content as $element) {
      $this->makeElement($result, $element, $width, $height);
    }

  }

  protected function makeElement(string &$result, array $element,
      int $width, int $height) : void {

    // TODO: Implement rescaling of element to match the given width and height
    
    $result .= Html::element($element['type'], $element['attributes']);

  }
  
}

?>