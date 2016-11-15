<?php

namespace farmacia\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tip_doc".
 *
 * @property string $TI_COD
 * @property string $TI_NOM
 * @property string $TI_CODART
 * @property string $TI_MODIF
 * @property string $TI_CODOP
 */
class TipoDocumento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tip_doc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TI_COD'], 'string', 'max' => 3],
            [['TI_NOM'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TI_COD' => 'Tipo de Documento',
            'TI_NOM' => 'Nombre',
        ];
    }

    public static function primaryKey()
    {
        return ['TI_COD'];
    }



    public static function listaTiposDocumento() 
    {
        return ArrayHelper::map(TipoDocumento::find()->all(), 'TI_COD', 'TI_COD');
    }
}
