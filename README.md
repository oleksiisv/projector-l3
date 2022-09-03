# projector-l3

Configure cron: 
35 15 * * * /usr/bin/php {path_to_app}/projector/l3/index.php 

Add logs if necessary: 
35 15 * * * /usr/bin/php {path_to_app}/projector/l3/index.php > {path_to_app}projector/l3/log.log 2>&1
