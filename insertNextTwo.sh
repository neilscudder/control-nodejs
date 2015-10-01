#!/bin/bash

main()
{
  MPC="$1"
  local currentAlbum="$($MPC --format %album% | head -n 1)"
  local currentFname="$($MPC --format %file% | head -n 1)"
  local albumFnames="$($MPC --format %file% search album "$currentAlbum")"
  echo "one"
  local nextFnames="${albumFnames##"$currentFname"}"
#  echo "$nextFnames"
#  IFS=$'\n' read -r -d -a nextArray <<< "$nextFnames"
  readarray -t nextArray <<<"$nextFnames"
  echo "${nextArray["0"]}"
  echo "${nextArray[1]}"
  if [ ${#nextArray[@]} -gt 1 ]; then
    $MPC insert "${nextArray[0]}" "${nextArray[1]}"
    echo "three"
  fi
}

main "$1"

exit 0
