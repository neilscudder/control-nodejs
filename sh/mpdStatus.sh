#!/bin/sh
MPC="$1"
$MPC --format \
"<div id=\"infotainer\" class=\"info-container\"> \
  <h2>[[%title%]|[%file%]]</h2> \
  <p><strong>Artist:</strong> [%artist%]</p> \
  <p><strong>Album:</strong> [%album%]</p> \
  <div class=\"animated button released\" id=\"insertNextTwo\"> \
    Insert Next Two \
  </div> \
</div>" | head -n1
exit 0
