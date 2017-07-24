<?php

namespace App\Controller;

use \Norm\Controller\NormController;
use Norm\Norm;

class AttendanceController extends AppController
{
	// public function mapRoute()
 //    {
 //        parent::mapRoute();

 //        $this->map('/:idCat/listAttendance', 'listAttendance')->via('GET', 'POST');
 //    }

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
}