<?

namespace Dmvas\Shop;

use \Bitrix\Main\Entity,
    \Bitrix\Main\Type;
    
class ManufacturerTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'manufacturer_shop';
    }
    
    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            //NAME
            new Entity\StringField('NAME', array(
                'required' => true,
                'validation' => function(){
                    return array(
                        new Entity\Validator\Unique,
                    );
                }
            ))
        );
    }
}

?>