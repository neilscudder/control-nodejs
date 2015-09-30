#!/bin/bash

main()
{
  $MPC="$1"
  local currentAlbum=$($MPC --format %album% | head -n 1)
  local currentFname=$($MPC --format %file% | head -n 1)
  local albumFnames=$($MPC --format %file% search album "$currentAlbum")
  local nextTwoFnames=$($albumFnames | grep -A 2 "$currentFname")
  if [ $(echo "$nextTwoFnames" | wc -l) -gt 2 ]; then
    $MPC insert "$nextTwoFnames"
  fi
}

main("$1")

exit 0
