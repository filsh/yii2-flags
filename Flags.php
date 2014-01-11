<?php

namespace yii\flags;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

class Flags extends \yii\base\Widget
{
    const FLAT_16 = 'flat/16';
    const FLAT_24 = 'flat/24';
    const FLAT_32 = 'flat/32';
    const FLAT_48 = 'flat/48';
    const FLAT_64 = 'flat/64';
    
    const SHINY_16 = 'shiny/16';
    const SHINY_24 = 'shiny/24';
    const SHINY_32 = 'shiny/32';
    const SHINY_48 = 'shiny/48';
    const SHINY_64 = 'shiny/64';
    
    public $options = [];
    
    public $label;
    
    public $tagName = 'span';
    
    public $encodeLabel = true;
    
    public $useSprite = false;
    
    public $flag;
    
    public $type = self::FLAT_16;
    
    public function init()
    {
        parent::init();
        if(!isset($this->flag)) {
            throw new InvalidConfigException('Flag element is required.');
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        
        $this->flag = strtoupper($this->flag);
        Html::addCssClass($this->options, 'flags');
        Html::addCssClass($this->options, $this->getCssClass());
    }
    
    public function run()
    {
        $this->registerCss();
        echo Html::tag($this->tagName, $this->encodeLabel ? Html::encode($this->label) : $this->label, $this->options);
    }
    
    protected function registerCss()
    {
        $view = $this->getView();
        $bundle = new FlagsAsset();
        $bundle->publish($view->getAssetManager());
        $view->assetBundles[get_called_class()] = $bundle;
        
        if($this->useSprite) {
            switch($this->type) {
                case self::FLAT_16:
                case self::FLAT_24:
                case self::FLAT_32:
                case self::FLAT_48:
                case self::FLAT_64:
                    $bundle->css[] = $this->type . '/style.css';
                    break;
                case self::SHINY_16:
                case self::SHINY_24:
                case self::SHINY_32:
                case self::SHINY_48:
                case self::SHINY_64:
                    $bundle->css[] = $this->type . '/style.css';
                    break;
                default:
                    throw new InvalidConfigException('Unknown flags type "' . $type . '".');
            }
        } else {
            $img = $bundle->baseUrl . '/' . $this->type . '/' . $this->flag . '.png';
            $size = explode('/', $this->type)[1] . 'px';
            $css = [
                'background-image: url("'.$img.'")',
                'display: inline-block',
                'width:' . $size,
                'height:' . $size
            ];
            $view->registerCss('.' . $this->getCssClass() . '{' . implode(';', $css) . ';}');
        }
    }
    
    protected function getCssClass()
    {
        return 'flag-' . $this->flag;
    }
}