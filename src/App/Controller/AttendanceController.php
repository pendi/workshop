<?php

namespace App\Controller;

use \Norm\Controller\NormController;
use Norm\Norm;

class AttendanceController extends AppController
{
	public function mapRoute()
    {
        parent::mapRoute();

        $this->map('/null/createManual', 'createManual')->via('GET', 'POST');
    }

    public function create()
    {
        $modelRules = Norm::factory("Rules");
        $modelStatuses = Norm::factory("Statuses");

        $entry = $this->collection->newInstance()->set($this->getCriteria());

        $this->data['entry'] = $entry;

        $late = $modelRules->findOne(array("name" => "Late"));

        if ($this->request->isPost()) {
            try {
                $post = $this->request->getBody();
                if (isset($post['result'])) {
                    $post['user'] = $post['result'][0];
                    $post['time'] = $post['result'][1];
                    $post['event'] = $post['result'][2];
                    $post['category'] = $post['result'][3];
                    $post['status'] = 1;
                }

                if ($post['time'] > $late['value']) {
                    $post['status'] = 2;
                }

                if ($post['time'] == "00:00") {
                    $post['status'] = 4;
                }
                unset($post['result']);

                $status = $post['status'];

                $result = $entry->set($post)->save();

                h('notification.info', $this->clazz.' created.');

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


        $dataStatus = $modelStatuses->findOne(array('code' => $status));

        $entryStatus = array(
            "code" => $dataStatus['code'],
            "name" => $dataStatus['name'],
            "color" => $dataStatus['color']
        );

        echo json_encode($entryStatus);
        exit();
    }

    public function createManual()
    {
        $entry = $this->collection->newInstance()->set($this->getCriteria());

        $this->data['entry'] = $entry;

        if ($this->request->isPost()) {
            try {
                $post = $this->request->post();
                var_dump($post);exit();
                $result = $entry->set($this->request->getBody())->save();

                h('notification.info', $this->clazz.' created.');

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



    public function createUpdate($id)
    {
        $modelRules = Norm::factory("Rules");

        $late = $modelRules->findOne(array("name" => "Late"));

        if ($this->request->isPost()) {
            try {
                $post = $this->request->getBody();
                $attendance = $this->collection->findOne($id);
                var_dump(count($attendance));exit();
                if (count($attendance) != 0) {
                    $time = $post['hours'].':'.$post['minutes'];
                    if ($post['status'] == 1 && $time > $late['value']) {
                        $post['status'] = 2;
                    }

                    $post['time'] = $time;
                    if ($post['status'] == 3 || $post['status'] == 4) {
                        $post['time'] = "00:00";
                    }

                    unset($post['hours']);
                    unset($post['minutes']);

                    $merged = array_merge(
                        isset($attendance) ? $attendance->dump() : array(),
                        $post ?: array()
                    );

                    $attendance->set($merged)->save();

                    h('notification.info', 'Participants updated');

                    $this->redirect(\URL::site('event/'.$merged['event'].'/attendance'));
                } else {
                    var_dump($post);exit();
                }
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
}