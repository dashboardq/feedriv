<?php

namespace app\controllers;

use app\models\SharedFeed;

use DateTime;

class ConsoleController {
    public function example($in, $out) {
        $out->write('This is an example.', 'green');
    }

    public function refresh($in, $out) {
        // How many cycles within an hour.
        $divisor = 60;
        $data = ao()->db->query('
            SELECT DISTINCT f.shared_feed_id, f.last_updated_at
            FROM feeds f, users u, restrictions r
            WHERE r.premium_level > 0
            AND r.user_id = u.id
            AND u.id = f.user_id
            ORDER BY f.last_updated_at ASC
            ');

        if(count($data)) {
            $total = count($data);
            if($total < $divisor) {
                $out->write('There are fewer items than cycles so run 1 per cycle.', 'green');
                $hour_ago = (new DateTime())->modify('-1 hour');
                $found = false;
                for($i = 0; $i < $total; $i++) {
                    $last_updated_at = new DateTime($data[$i]['last_updated_at']);
                    $out->write('Hour Ago: ' . $hour_ago->format('Y-m-d H:i:s'), 'green');
                    $out->write('Last Updated: ' . $last_updated_at->format('Y-m-d H:i:s'), 'green');
                    if(!$found && $hour_ago > $last_updated_at) {
                        // Only need to process 1 a minute since there are less than 60 shared feeds to process.
                        $found = true;
                        $out->write('Refreshing: ' . $data[$i]['shared_feed_id'], 'green');
                        SharedFeed::refresh($data[$i]['shared_feed_id']);
                    } else {
                        $out->write('No more feeds to refresh.');
                        break;
                    }

                }
            } else {
                $per_cycle = ceil($total / $divisor);
                $out->write('There are more items than cycles so run ' . $per_cycle . ' per cycle.', 'green');
                $out->write('Per Cycle: ' . $per_cycle, 'green');
                for($i = 0; $i < $per_cycle; $i++) {
                    $out->write('Refreshing: ' . $data[$i]['shared_feed_id'], 'green');

                    SharedFeed::refresh($data[$i]['shared_feed_id']);
                }
            }

        } else {
            $out->write('There are no feeds to refresh.', 'green');
        }
    }

    public function view($in, $out) {
        $out->view('console/view', ['color' => 'red']);
    }
}
