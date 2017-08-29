<?php

namespace App\Controller;

use \Norm\Controller\NormController;
use Norm\Norm;

class UserController extends AppController
{
    public function getSort(){
        // $sort = parent::getSort();
        $sort['first_name'] = 1;
        return $sort;
    }

    public function search()
    {
        $entries = $this->collection->find($this->getCriteria())
            ->match($this->getMatch())
            ->sort($this->getSort())
            ->skip($this->getSkip())
            ->limit($this->getLimit());

        $this->data['entries'] = $entries;
    }

    public function delete($id)
    {
        $id = explode(',', $id);

        if ($this->request->isPost() || $this->request->isDelete()) {
            $single = false;
            if (count($id) === 1) {
                $single = true;
            }

            try {
                $this->data['entries'] = array();

                foreach ($id as $value) {
                    $model = $this->collection->findOne($value);

                    if (is_null($model)) {
                        if ($single) {
                            $this->app->notFound();
                        }

                        continue;
                    }

                    $dataAttend = \Norm::factory("Attendance")->find(array('user' => $value));
                    foreach ($dataAttend as $key => $attend) {
                        $attend->remove();
                    }

                    $model->remove();

                    $this->data['entries'][] = $model;
                }

                h('notification.info', $this->clazz.' deleted.');

                h('controller.delete.success', array(
                    'models' => $this->data['entries'],
                ));

            } catch (Stop $e) {
                throw $e;
            } catch (Exception $e) {
                h('notification.error', $e);

                if (empty($model)) {
                    $model = null;
                }

                h('controller.delete.error', array(
                    'error' => $e,
                    'model' => $model,
                ));
            }

        }
    }
}