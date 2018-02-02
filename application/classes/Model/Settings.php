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

    const BRANDING_KEY = 'branding';
    const LOGO_KEY = 'logo';

    public function __construct()
    {
    }

    public static function getAll()
    {
        $parameterRow = Dao_Settings::select()
            ->cached(Date::MINUTE * 30, 'settings')
            ->execute();

        $siteSettings = [];

        foreach ($parameterRow as $item => $info) {
            $key = Arr::get($info, 'name');
            $value = Arr::get($info, 'value');

            if (!$key && !$value) {
                continue;
            }

            $siteSettings[$key] = $value;
        }

        return $siteSettings;
    }

    /**
     * @deprecated
     *
     * @param null|mixed $name
     */
    private function get($name = null)
    {
        $parameterRow = Dao_Settings::select()
            ->where('name', '=', $name)
            ->limit(1)
            ->cached(Date::MINUTE * 30, 'settings:' . $name)
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
        $parameterRow = Dao_Settings::insert()
            ->set('name', $this->name)
            ->set('value', $this->value)
            ->set('label', $this->label)
            ->clearcache('settings')
            ->execute();

        if ($parameterRow) {
            return $this;
        }

        return false;
    }

    public function update()
    {
        $parameterRow = Dao_Settings::update()
            ->where('name', '=', $this->name)
            ->set('value', $this->value)
            ->set('label', $this->label)
            ->clearcache('settings')
            ->execute();

        if ($parameterRow) {
            return $this;
        }

        return false;
    }

    public function delete()
    {
        return Dao_Settings::delete()->where('name', '=', $this->name)->execute();
    }

    /**
     * Saves new branding image
     *
     * @param string $filename file name
     *
     * @return string new brangind image name
     */
    public function newBranding($filename)
    {
        $brandingExists = Dao_Settings::select()
                            ->where('name', '=', self::BRANDING_KEY)
                            ->limit(1)
                            ->execute();

        $this->name = self::BRANDING_KEY;
        $this->value = $filename;

        if ($brandingExists) {
            $this->update();
        } else {
            $this->insert();
        }

        return $this->value;
    }

    /**
     * Saves new logo image
     *
     * @param string $filename file name
     *
     * @return string new logo image name
     */
    public function newLogo($filename)
    {
        $logoExists = Dao_Settings::select()
                            ->where('name', '=', self::LOGO_KEY)
                            ->limit(1)
                            ->execute();

        $this->name = self::LOGO_KEY;
        $this->value = $filename;

        if ($logoExists) {
            $this->update();
        } else {
            $this->insert();
        }

        return $this->value;
    }
}
