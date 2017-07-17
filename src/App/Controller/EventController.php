<?php

namespace App\Controller;

use \Norm\Controller\NormController;
use Norm\Norm;

class EventController extends AppController
{
	public function mapRoute()
    {
        parent::mapRoute();

        $this->map('/:idCat/listEvent', 'listEvent')->via('GET', 'POST');
    }

    public function sortList(){
        // $sort = parent::getSort();
        $sort['date'] = -1;
        return $sort;
    }

	public function search()
    {
        $entries = Norm::factory("Category")->find($this->getCriteria())
            ->match($this->getMatch())
            ->sort($this->getSort())
            ->skip($this->getSkip())
            ->limit($this->getLimit());

        $this->data['entries'] = $entries;
    }

    public function listEvent($idCat)
    {
    	$modelCategory = Norm::factory("Category");

    	$entries = $this->collection->find(array('category' => $idCat))
    		->match($this->getMatch())
            ->sort($this->sortList())
            ->skip($this->getSkip())
            ->limit($this->getLimit());

        // var_dump($this->sortList());exit();
            
        $this->data['entries'] = $entries;
        $this->data['idCat'] = $idCat;
    }

    public function create()
    {
    	$modelCategory = Norm::factory("Category");

    	$get = $this->request->get();

        $entry = $this->collection->newInstance()->set($this->getCriteria());
        $category = $modelCategory->findOne($get['cat']);

        $this->data['entry'] = $entry;
        $this->data['category'] = $category;

        if ($this->request->isPost()) {
            try {
                $result = $entry->set($this->request->getBody())->save();

                h('notification.info', $this->clazz.' created.');

                $this->redirect(\URL::site('event/'.$get['cat'].'/listEvent'));

                h('controller.create.success', array(
                    'model' => $entry
                ));
            } catch (Stop $e) {
                throw $e;
            } catch (Exception $e) {
                // no more set notification.error since notificationmiddleware will
                // write this later
                // h('notification.error', $e);

                h('controller.create.error', array(
                    'model' => $entry,
                    'error' => $e,
                ));

                // rethrow error to make sure notificationmiddleware know what todo
                throw $e;
            }
        }
    }

    public function update($id)
    {
        try {
            $entry = $this->collection->findOne($id);
        } catch (Exception $e) {
            // noop
        }

        if (is_null($entry)) {
            return $this->app->notFound();
        }

        if ($this->request->isPost() || $this->request->isPut()) {
            try {
                $merged = array_merge(
                    isset($entry) ? $entry->dump() : array(),
                    $this->request->getBody() ?: array()
                );

                $entry->set($merged)->save();

                h('notification.info', $this->clazz.' updated');

                $this->redirect(\URL::site('event/'.$entry['category'].'/listEvent'));

                h('controller.update.success', array(
                    'model' => $entry,
                ));
            } catch (Stop $e) {
                throw $e;
            } catch (Exception $e) {
                h('notification.error', $e);

                if (empty($entry)) {
                    $model = null;
                }

                h('controller.update.error', array(
                    'error' => $e,
                    'model' => $entry,
                ));
            }
        }

        $this->data['entry'] = $entry;
    }

    public function delete($id)
    {
        $id = explode(',', $id);


        if ($this->request->isPost() || $this->request->isDelete()) {
            $post = $this->request->post();
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

                    $model->remove();

                    $this->data['entries'][] = $model;
                }

                h('notification.info', $this->clazz.' deleted.');

                $this->redirect($post['redirect']);

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