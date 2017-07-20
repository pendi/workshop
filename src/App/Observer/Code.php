<?php

namespace App\Observer;

use \Norm\Norm;

class Code
{
	public function saving($model)
	{
		// var_dump($model);exit();
		if (is_null($model['$id'])) {
			$modelStatuses = Norm::factory('Statuses');

			$dataCode = $modelStatuses->find();

			$model['code'] = 1;
			if (count($dataCode) > 0) {
				$dataCode = $dataCode->toArray();
				$model['code'] = count($dataCode) + 1;
			}
		}
	}
}
