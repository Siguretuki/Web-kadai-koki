<?php
$redis = new Redis();
$redis->connect('redis', 6379);
$value = $redis->get('access');
$redis->incr('access');
print("お前は$value 番目のお客さんやで( ・ω・)");
