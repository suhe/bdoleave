<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class Test extends ActiveRecord {


	public static function tableName(){
		return 'test';
	}
}