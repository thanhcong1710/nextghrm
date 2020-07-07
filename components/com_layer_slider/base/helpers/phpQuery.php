<?php
/*-------------------------------------------------------------------------
# com_layer_slider - com_layer_slider
# -------------------------------------------------------------------------
# @ author    John Gera, George Krupa, Janos Biro
# @ copyright Copyright (C) 2014 Offlajn.com  All Rights Reserved.
# @ license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @ website   http://www.offlajn.com
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
?><?php

class phpQueryDOMDocument {
  var $dom;
  var $node = null;
  var $html = null;

  function __construct($html = null) {
    if ($html) {
      $this->dom = new DOMDocument();
      $this->dom->loadHTML($html);
      $this->node = $this->dom->documentElement->lastChild->firstChild;
    }
  }

  public function children() {
    if ($this->node->lastChild) {
      $doc = new phpQueryDOMDocument();
      $doc->dom = $this->dom;
      $doc->node = $this->node->lastChild;
      $doc->html = &$this->html;
      return $doc;
    }
    else return $this;
  }
  public function addClass($add) {
    $class = $this->node->getAttribute('class');
    $this->node->setAttribute('class', $class? "$class $add" : $add);
    return $this;
  }
  public function attr($attr, $value = null) {
    if ($value === null) return $this->node->getAttribute($attr);
    $this->node->setAttribute($attr, $value);
    return $this;
  }
  public function val($value = null) {
    return $this->attr('value', $value);
  }
  public function html($html) {
    $this->html = $html;
    $this->node->appendChild($this->dom->createTextNode('__HTML__'));
    return $this;
  }
  public function append($html) {
    $xml = preg_replace('/^<(\w+)>$/', '<$1/>', $html);
    $fragment = $this->dom->createDocumentFragment();
    $fragment->appendXML($xml);
    $this->node->appendChild($fragment);
    //echo'<pre>';print_r($this->__toString());exit;
    return $this;
  }
  public function __toString() {
    preg_match('/<body>(.*)<\/body>/', $this->dom->saveHTML(), $match);
    return str_replace('__HTML__', $this->html, $match[1]);
  }
}

class phpQuery {
  static function newDocument($html) {
    return new phpQueryDOMDocument($html);
  }
}