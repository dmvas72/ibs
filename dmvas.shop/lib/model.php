<?

namespace Dmvas\Shop;

use \Bitrix\Main\Entity,
    \Bitrix\Main\Type;
    
class ModelTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'model_shop';
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
            )),
            //MANUFACTURER ID
            new Entity\IntegerField('MANUFACTURER_ID', array(
                'required' => true,
            )),
            new Entity\ReferenceField(
                'MANUFACTURER',
                'Dmvas\Shop\ManufacturerTable',
                array('=this.MANUFACTURER_ID' => 'ref.ID'),
            )
        );
    }
}

?>