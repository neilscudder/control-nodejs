#!/bin/bash

main()
{
  MPC="$1"
  local currentAlbum="$($MPC --format %album% | head -n 1)"
  local currentFname="$($MPC --format %file% | head -n 1)"
  local albumFnames="$($MPC --format %file% search album "$currentAlbum")"
  echo "one"
  local nextTwoFnames="$(echo "$albumFnames" | grep -A 2 "$currentFname")"
  echo "two"
  if [ $(echo "$nextTwoFnames" | wc -l) -gt 1 ]; then
    $MPC insert "$nextTwoFnames"
    echo "three"
  fi
}

main "$1"

exit 0
