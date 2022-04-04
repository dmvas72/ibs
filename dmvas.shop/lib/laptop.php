<?

namespace Dmvas\Shop;

use \Bitrix\Main\Entity,
    \Bitrix\Main\Type;
    
class LaptopTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'laptop_shop';
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
            //YEAR
            new Entity\StringField('YEAR', array(
                'required' => true,
            )),
            //PRICE
            new Entity\FloatField('PRICE', array(
                'required' => true,
            )),
            //MODEL ID
            new Entity\IntegerField('MODEL_ID', array(
                'required' => true,
            )),
            new Entity\ReferenceField(
                'MODEL',
                'Dmvas\Shop\ModelTable',
                array('=this.MODEL_ID' => 'ref.ID'),
            )
        );
    }
}

?>