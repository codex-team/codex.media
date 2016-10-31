<?php defined('SYSPATH') or die('No direct script access.');

class Model_Settings extends Model_preDispatch {

    /**
     * model vars
     */
    public $name;
    public $value;
    public $label;


    /**
     * basic model functions
     */
    public function __construct($name = null) {

        if (!$name) return;

        self::get($name);

    }

    private function get($name = null) {

        $parameterRow = Dao_Settings::select()
            ->where('name', '=', $name)
            ->limit(1)
            ->execute();

        return self::fillByRow($parameterRow);

    }

    private function fillByRow($parameterRow) {

        if (!empty($parameterRow)) {

            foreach ($parameterRow as $field => $value) {

                if (property_exists($this, $field)) {

                    $this->$field = $value;

                }

            }

        }

        return $this;

    }

    public function insert() {

        $parameterRow = Dao_Settings::insert();

        $parameterRow->set('name',  $this->name);
        $parameterRow->set('value', $this->value);
        $parameterRow->set('label', $this->label);

        $parameterRow = $parameterRow->execute();

        if ($parameterRow) {

            return $this;

        }

        return false;

    }

    public function update() {

        $parameterRow = Dao_Settings::update()
            ->where('name', '=', $this->name);

        $parameterRow->set('value', $this->value);
        $parameterRow->set('label', $this->label);

        $parameterRow = $parameterRow->execute();

        if ($parameterRow) {

            return $this;

        }

        return false;

    }


    /**
     * other functions
     */
    public static function getListByLabel($label = null) {

        $parameterRows = Dao_Settings::select('name')
            ->where('label', '=', $label)
            ->execute();

        $paramList = array();

        foreach ($parameterRows as $row) {

            $paramList[] = new Model_Settings($row['name']);

        }

        return $paramList;

    }

}
