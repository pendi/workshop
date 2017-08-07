<?php

namespace App\Controller;

use \Norm\Controller\NormController;
use \App\Report\ReportExport;
use Norm\Norm;

class ReportController extends AppController
{
	public function mapRoute()
    {
        parent::mapRoute();

        $this->map('/:idCat/listEvent', 'listEvent')->via('GET', 'POST');
        $this->map('/:id/attendance', 'attendance')->via('GET', 'POST');
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

    public function read($id)
    {
        $modelUser = Norm::factory("User");
        $modelCategory = Norm::factory("Category");
        $modelEvent = Norm::factory("Event");
        $modelAttendance = Norm::factory("Attendance");
        $modelStatuses = Norm::factory("Statuses");

        $get = $this->request->get();

        try {
            $this->data['entry'] = $entry = $modelCategory->findOne($id);
        } catch (Exception $e) {
            // noop
        }

        $dataUsers = $modelUser->find(array('username!ne' => 'admin'))->sort(array('first_name' => 1));
        $eventEntries = $modelEvent->find(array('category' => $id))->sort(array('date' => 1));

        $dataEvents = array();
        foreach ($eventEntries as $key => $event) {

            $users = array();
            foreach ($dataUsers as $du => $user) {
                $dataAttendance = $modelAttendance->findOne(array('user' => $user['$id'], 'event' => $event['$id']));
                $dataStatus = $modelStatuses->findOne(array('code' => $dataAttendance['status']));

                $user['time'] = $dataAttendance['time'];
                $user['tooltip'] = $dataStatus['name'];
                if ($dataAttendance['status'] == 3 || $dataAttendance['status'] == 4) {
                    $user['time'] = $dataStatus['name'];
                    $user['tooltip'] = $dataStatus['name'].' - '.$dataAttendance['description'];
                }
                $user['description'] = $dataAttendance['description'];
                $user['status_color'] = $dataStatus['color'];

                $users[] = $user->toArray();

            }

            $event['attendance'] = $users;
            $dataEvents[] = $event->toArray();
        }

        if (isset($get['export'])) {
            $report = ReportExport::create($this->app);
            $field = array();
            $report->reportWorkshop($dataEvents,$dataUsers,$entry);
        }
        
        $this->data['dataUsers'] = $dataUsers;
        $this->data['dataEvents'] = $dataEvents;
    }
}