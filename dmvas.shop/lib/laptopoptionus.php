<?

namespace Dmvas\Shop;

use \Bitrix\Main\Entity,
    \Bitrix\Main\Type;
    
class LaptopOptionUsTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'laptopoptionus_shop';
    }
    
    public static function getMap()
    {
        return array(
            //ID
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            //LAPTOP ID
            new Entity\IntegerField('LAPTOP_ID'),
            new Entity\ReferenceField(
                'LAPTOP',
                'Dmvas\Shop\LaptopTable',
                array('=this.LAPTOP_ID' => 'ref.ID'),
            ),
            //OPTION ID
            new Entity\IntegerField('OPTION_ID'),
            new Entity\ReferenceField(
                'OPTION',
                'Dmvas\Shop\OptionTable',
                array('=this.OPTION_ID' => 'ref.ID'),
            )
        );
    }
}

?>