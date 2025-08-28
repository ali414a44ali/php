#!/bin/bash

# start.sh - يشغل البوت من الرام، ويعيد تشغيله إذا وقع

SRC_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
RAM_DIR="/dev/shm/bot_ram"

rm -rf "$RAM_DIR"
mkdir -p "$RAM_DIR"
cp "$SRC_DIR"/*.php "$RAM_DIR"
cp "$SRC_DIR"/vals.php "$RAM_DIR" 2>/dev/null
cp "$SRC_DIR"/*.txt "$SRC_DIR"/*.json "$RAM_DIR" 2>/dev/null
cd "$RAM_DIR" || exit 1

until php polling_daemon.php; do
  echo "[!] polling_daemon.php exited — restarting in 3s..."
  sleep 3
done
