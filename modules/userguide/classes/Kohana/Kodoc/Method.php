<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class method documentation generator.
 *
 * @package    Kohana/Userguide
 * @category   Base
 *
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Kodoc_Method extends Kodoc
{

    /**
     * @var ReflectionMethod The ReflectionMethod for this class
     */
    public $method;

    /**
     * @var array array of Kodoc_Method_Param
     */
    public $params;

    /**
     * @var array the things this function can return
     */
    public $return = [];

    /**
     * @var string the source code for this function
     */
    public $source;

    public function __construct($class, $method)
    {
        $this->method = new ReflectionMethod($class, $method);

        $this->class = $parent = $this->method->getDeclaringClass();

        if ($modifiers = $this->method->getModifiers()) {
            $this->modifiers = '<small>' . implode(' ', Reflection::getModifierNames($modifiers)) . '</small> ';
        }

        do {
            if ($parent->hasMethod($method) and $comment = $parent->getMethod($method)->getDocComment()) {
                // Found a description for this method
                break;
            }
        } while ($parent = $parent->getParentClass());

        list($this->description, $tags) = Kodoc::parse($comment);

        if ($file = $this->class->getFileName()) {
            $this->source = Kodoc::source($file, $this->method->getStartLine(), $this->method->getEndLine());
        }

        if (isset($tags['param'])) {
            $params = [];

            foreach ($this->method->getParameters() as $i => $param) {
                $param = new Kodoc_Method_Param([$this->method->class, $this->method->name], $i);

                if (isset($tags['param'][$i])) {
                    preg_match('/^(\S+)(?:\s*(?:\$' . $param->name . '\s*)?(.+))?$/s', $tags['param'][$i], $matches);

                    $param->type = $matches[1];

                    if (isset($matches[2])) {
                        $param->description = ucfirst($matches[2]);
                    }
                }
                $params[] = $param;
            }

            $this->params = $params;

            unset($tags['param']);
        }

        if (isset($tags['return'])) {
            foreach ($tags['return'] as $return) {
                if (preg_match('/^(\S*)(?:\s*(.+?))?$/', $return, $matches)) {
                    $this->return[] = [$matches[1], isset($matches[2]) ? $matches[2] : ''];
                }
            }

            unset($tags['return']);
        }

        $this->tags = $tags;
    }

    public function params_short()
    {
        $out = '';
        $required = true;
        $first = true;
        foreach ($this->params as $param) {
            if ($required and $param->default and $first) {
                $out .= '[ ' . $param;
                $required = false;
                $first = false;
            } elseif ($required and $param->default) {
                $out .= '[, ' . $param;
                $required = false;
            } elseif ($first) {
                $out .= $param;
                $first = false;
            } else {
                $out .= ', ' . $param;
            }
        }

        if (! $required) {
            $out .= '] ';
        }

        return $out;
    }
} // End Kodoc_Method
