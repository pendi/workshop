<?php

namespace App\Provider;

use Norm\Norm;

class AppProvider extends \Bono\Provider\Provider
{
    public function initialize()
    {
        $app = $this->app;

        $app->get('/', function () use ($app) {
        	$modelCategory = Norm::factory("Category");
        	$modelAttendance = Norm::factory("Attendance");
        	$modelStatuses = Norm::factory("Statuses");

        	$dataCategories = $modelCategory->find()->sort(array('$created_time' => -1))->limit(2);
        	$dataStatuses = $modelStatuses->find();

        	$statusCode = array();
        	foreach ($dataStatuses as $ds => $status) {
        		$statusCode[] = $status['code'];
        	}

        	$totalAttendance = array();
        	$dataChart = array();
        	foreach ($dataCategories as $dc => $category) {
        		$dataAttendances = $modelAttendance->find(array('category' => $category['$id']));
        		$totalAttendance = count($dataAttendances);


	        	$status_1 = array();
	        	$status_2 = array();
	        	$status_3 = array();
	        	$status_4 = array();
        		foreach ($dataAttendances as $da => $attendance) {
        			if (in_array($attendance['status'], $statusCode)) {
        				if ($attendance['status'] == 1) {
        					$status_1[] = $attendance['status'];
        				}
        				if ($attendance['status'] == 2) {
        					$status_2[] = $attendance['status'];
        				}
        				if ($attendance['status'] == 3) {
        					$status_3[] = $attendance['status'];
        				}
        				if ($attendance['status'] == 4) {
        					$status_4[] = $attendance['status'];
        				}
        			}
        		}

        		$average_status1 = round(0);
        		$average_status2 = round(0);
        		$average_status3 = round(0);
        		$average_status4 = round(0);
        		if ($totalAttendance != 0) {
        			$average_status1 = round((count($status_1)/$totalAttendance)*100, 1);
        			$average_status2 = round((count($status_2)/$totalAttendance)*100, 1);
        			$average_status3 = round((count($status_3)/$totalAttendance)*100, 1);
        			$average_status4 = round((count($status_4)/$totalAttendance)*100, 1);
        		}

        		
        		foreach ($dataStatuses as $dst => $valStatus) {
        			if ($valStatus['code'] == 1) {
        				$average = $average_status1;
        				$color = $valStatus['color'];
        			}
        			if ($valStatus['code'] == 2) {
        				$average = $average_status2;
        				$color = $valStatus['color'];
        			}
        			if ($valStatus['code'] == 3) {
        				$average = $average_status3;
        				$color = $valStatus['color'];
        			}
        			if ($valStatus['code'] == 4) {
        				$average = $average_status4;
        				$color = $valStatus['color'];
        			}
        			$dataChart[$category['name']][] = array('name' => $valStatus['name'], 'y' => $average, 'color' => $color);
        		}
        	}
        			// var_dump($dataChart);
        		// exit();


        	// exit();
        	// $data = array(
        		$data = array(
			        array(
			            "Firefox",
			            45
			        ),
			        array(
			            "IE",
			            26.8
			        ),
			        array(
			            'name' => 'Chrome',
			            'y' => 12.8,
			            'sliced' => true,
			            'selected' => true
			        ),
			        array(
			            "Safari",
			            8.5
			        ),
			        array(
			            "Opera",
			            6.2
			        ),
			        array(
			            "Others",
			            0.7
			        )
			    );
        	// );

        		// var_dump($data);exit();
        	$app->response->data('dataChart', $dataChart);
            $app->response->template('static/index');
        });
        
    }
}
