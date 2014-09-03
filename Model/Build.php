<?php

namespace Model;

final class Build {
    protected static $_instance = null;
    private $_tab = null;

    private function __construct() {
        $this->_tab = \Lib\Options::getInstance()->getTab();
    }

    /**
     * 单例
     *
     * @return \Model\Build
     */
    public static function getInstance() {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /**
     * 创建类名
     *
     * @param string $name
     * @return string
     */
    public function toClass($name) {
        $options = \Lib\Options::getInstance();
        $items   = array('class ' . sprintf($options->getModelType(), ucfirst($name)));

        if (!empty($options->getExtendName())) {
            $items[] = ' extends ' . $options->getExtendName();
        }

        if (!empty($options->getImplements())) {
            $items[] = ' implements ' . $options->getImplements();
        }

        $items[] = " {\n";

        return implode('', $items);
    }

    /**
     * 创建注释
     *
     * @param array|string $comments
     * @return array
     */
    public function toComment($comments) {
        $comments = (is_array($comments)) ? $comments : array($comments);
        $items    = array($this->_tab . '/**');

        foreach ($comments as $comment) {
            $items[] = $this->_tab. ' * ' . $comment;
        }

        $items[] = $this->_tab . ' */';

        return implode("\n", $items);
    }

    /**
     * 创建属性
     *
     * @param string $name
     * @param string $value
     * @return string
     */
    public function toProperty($name, $value, $permissions = 'protected') {
        return $this->_tab . $permissions . ' $' . $name . ' = ' . ($value === null ? 'null' : (!is_numeric($value) ? "'" . $value . "'" : $value)) . ';' . "\n";
    }

    /**
     * 创建set方法
     *
     * @param string $name
     * @param array $code
     * @param string $params
     * @return string
     */
    public function toSetFunc($name, array $code, $params) {
        return $this->toFunc('set' . ucfirst($name), $code, $params, 'public');
    }

    /**
     * 创建get方法
     *
     * @param string $name
     * @param string $code
     * @return string
     */
    public function toGetFunc($name, $code) {
        return $this->toFunc('get' . ucfirst($name), $code, null, 'public');
    }

    /**
     * 创建方法
     *
     * @param string $name
     * @param array $code
     * @param string|array $params
     * @param string $permissions [public|private|protected]
     * @return array
     */
    public function toFunc($name, array $code, $params = null, $permissions = 'public') {
        $params = (is_array($params)) ? $params : array($params);
        $argvs  = (empty($params) || empty($params[0])) ? '' : ('$' . implode(', $', $params));

        $items   = array($this->_tab . $permissions . ' function ' . $name . '(' . $argvs . ') {');
        $items[] = implode("\n", $code);
        $items[] = $this->_tab . "}\n";

        return implode("\n", $items);
    }

    /**
     * 创建toArray方法
     *
     * @param array $sets
     * @return string
     */
    public function toToArray(array $sets) {
        $items = array(str_repeat($this->_tab, 2) . 'return array(');
        $citem = array();

        foreach ($sets as $name) {
            $citem[] = str_repeat($this->_tab, 3) . "'" . $name . "' => \$this->_" . $name;
        }

        $items[] = implode(',' . "\n", $citem);
        unset($citem);

        $items[] = str_repeat($this->_tab, 2) . ");\n";

        return $this->toFunc('toArray', $items);
    }


}
