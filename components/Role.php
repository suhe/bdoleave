<?php
namespace app\components;
use Yii;
use yii\filters\AccessRule;

class Role extends AccessRule {

	/**
	 * @inheritdoc
	 */
	protected function matchRole($user) {
		if (empty($this->roles)) {
			return true;
		}
		
		foreach ($this->roles as $role) {
			if ($role === '?') {
				if (Yii::$app->user->isGuest) {
					return true;
				}
			} elseif ($role === '@') {
				if (!Yii::$app->user->isGuest) {
					return true;
				}
				// Check if the user is logged in, and the roles match
			} elseif (!$user->getIsGuest() && $role === Yii::$app->user->identity->EmployeeTitle) {
				return true;
			}
		}

		return false;
	}
}