<?php defined('SYSPATH') or die('No direct script access.');

class Model_Settings extends Model_preDispatch
{
    /**
     * Settings для сохранение глобальных переменных с настройками сайта.
     *
     * @var $name  имя переменной
     * @var $value значение переменной
     * @var $label метка (для объединения переменных в группы)
     */
    public $name;
    public $value;
    public $label;


    public function __construct($name = null)
    {
        if (!$name) return;

        self::get($name);
    }

    private function get($name = null)
    {
        $parameterRow = Dao_Settings::select()
            ->where('name', '=', $name)
            ->limit(1)
            ->execute();

        return self::fillByRow($parameterRow);
    }

    private function fillByRow($parameterRow)
    {
        if (!empty($parameterRow)) {

            foreach ($parameterRow as $field => $value) {

                if (property_exists($this, $field)) {

                    $this->$field = $value;
                }
            }
        }

        return $this;
    }

    public function insert()
    {
        $parameterRow = Dao_Settings::insert();

        $parameterRow->set('name',  $this->name);
        $parameterRow->set('value', $this->value);
        $parameterRow->set('label', $this->label);

        $parameterRow = $parameterRow->execute();

        if ($parameterRow) return $this;

        return false;
    }

    public function update()
    {
        $parameterRow = Dao_Settings::update()
            ->where('name', '=', $this->name);

        $parameterRow->set('value', $this->value);
        $parameterRow->set('label', $this->label);

        $parameterRow = $parameterRow->execute();

        if ($parameterRow) return $this;

        return false;
    }

    public function delete()
    {
        return Dao_Settings::delete()->where('name', '=', $this->name)->execute();
    }


    /**
     * Returns vars array by label
     *
     * @return array[key] = value
     */
    public static function getListByLabel($label = null)
    {
        $parameterRows = Dao_Settings::select('name');

        if ($label) {

            $parameterRows->where('label', '=', $label);
        }

        $parameterRows->execute();

        $paramList = array();

        if ($parameterRows) {

            foreach ($parameterRows as $row) {

                $param = new Model_Settings($row['name']);

                $paramList[$param->name] = $param->value;
            }
        }

        return $paramList;
    }
}
